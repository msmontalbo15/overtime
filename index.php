<?php

date_default_timezone_set("Asia/Manila");

// ============================================================
// LICENSE VERIFICATION — Supabase RPC, Machine + Domain Locked
//
// Security model:
//   1. Checks Supabase on EVERY request — no cache
//   2. Revoke or expire in Supabase = locked on the very next page load
//   3. Key + domain + machine fingerprint must all match
//   4. Wrong machine, wrong domain, revoked, or expired = locked instantly
//
// Owner: Mark Spencer Montalbo
// https://github.com/msmontalbo15/overtime
// ============================================================
(function() {

    // ── Load + decrypt credentials from .env.license ─────────────────────
    // Plaintext credentials are NEVER stored in this file.
    // Run: php encrypt_credentials.php  (once, from CLI, then delete the script)
    (function() {
        $env_file = __DIR__ . '/.env.license';

        if (!file_exists($env_file)) {
            die('<h1 style="font-family:sans-serif;color:#c00;padding:40px">License config missing. Run <code>php encrypt_credentials.php</code>.</h1>');
        }

        // Parse key=value lines, skip comments
        $env = [];
        foreach (file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if ($line[0] === '#' || strpos($line, '=') === false) continue;
            [$k, $v] = explode('=', $line, 2);
            $env[trim($k)] = trim($v);
        }

        // Load master key from ABOVE the webroot (cannot be fetched via HTTP)
        $master_file = $env['MASTER_KEY_PATH'] ?? '';
        if (!$master_file || !file_exists($master_file)) {
            die('<h1 style="font-family:sans-serif;color:#c00;padding:40px">License master key not found.<br><small>Path: ' . htmlspecialchars((string)$master_file) . '</small></h1>');
        }

        $master_key = trim(file_get_contents($master_file));
        if (strlen($master_key) !== 64) {
            die('<h1 style="font-family:sans-serif;color:#c00;padding:40px">License master key malformed. Re-run encrypt_credentials.php.</h1>');
        }

        // AES-256-GCM decrypt: base64(12-byte IV + 16-byte tag + ciphertext)
        $dec = function($encoded, $key_hex) {
            $raw = base64_decode($encoded, true);
            if (!$raw || strlen($raw) < 29) return false;
            return openssl_decrypt(
                substr($raw, 28), 'aes-256-gcm', hex2bin($key_hex),
                OPENSSL_RAW_DATA, substr($raw, 0, 12), substr($raw, 12, 16)
            );
        };

        $url    = $dec($env['SUPABASE_URL']          ?? '', $master_key);
        $anon   = $dec($env['SUPABASE_ANON_KEY']     ?? '', $master_key);
        $secret = $dec($env['LICENSE_HMAC_SECRET']   ?? '', $master_key);

        if (!$url || !$anon || !$secret) {
            die('<h1 style="font-family:sans-serif;color:#c00;padding:40px">Credential decryption failed. Re-run encrypt_credentials.php.</h1>');
        }

        define('SUPABASE_URL',        $url);
        define('SUPABASE_ANON_KEY',   $anon);
        define('LICENSE_HMAC_SECRET', $secret);
    })();
    // ─────────────────────────────────────────────────────────────────────

    $key_file = __DIR__ . '/license.key';

    // Normalize domain: lowercase, strip port and www
    $raw_host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $current_bare = strtolower(preg_replace(['/:\d+$/', '/^www\./'], ['', ''], $raw_host));

    // Allow activate route through
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    if (strpos($uri, 'activate') !== false) return;

    // ── Helper: show lock screen ──────────────────────────────────────────
    $show_lock = function($reason = '', $reason_code = 'invalid') {
        $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
        $base   = rtrim(dirname($script), '/') . '/';
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $activate_url = $scheme . '://' . $host . $base . 'index.php/activate';
        http_response_code(403);
        header('Content-Type: text/html; charset=utf-8');
        include __DIR__ . '/application/views/lock_screen.php';
        exit;
    };

    // ── Helper: machine fingerprint (SHA256 of hostname + MAC) ───────────
    $get_machine_id = function() {
        $hostname = strtolower(trim(function_exists('gethostname') ? gethostname() : (getenv('COMPUTERNAME') ?: '')));
        $mac      = '';
        if (PHP_OS_FAMILY === 'Windows' || strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $out = @shell_exec('getmac /fo csv /nh 2>nul');
            if ($out && preg_match('/([0-9A-Fa-f]{2}[:\-]){5}[0-9A-Fa-f]{2}/', $out, $m)) {
                $mac = strtolower(str_replace('-', ':', $m[0]));
            }
        } else {
            $out = @shell_exec('ip link show 2>/dev/null || ifconfig 2>/dev/null');
            if ($out && preg_match('/([0-9a-f]{2}:){5}[0-9a-f]{2}/i', $out, $m)) {
                $mac = strtolower($m[0]);
            }
        }
        return hash('sha256', $hostname . '|' . ($mac ?: 'no-mac'));
    };

    // ── Helper: call Supabase RPC — returns true | false | null ──────────
    $call_rpc = function($key, $domain, $machine_id) {
        if (!function_exists('curl_init')) return null;
        $payload = json_encode([
            'p_key'        => $key,
            'p_domain'     => $domain,
            'p_machine_id' => $machine_id,
        ]);
        $ch = curl_init(SUPABASE_URL . '/rest/v1/rpc/verify_license_machine');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 8,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTPHEADER     => [
                'apikey: '               . SUPABASE_ANON_KEY,
                'Authorization: Bearer ' . SUPABASE_ANON_KEY,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);
        $response = curl_exec($ch);
        $err      = curl_errno($ch);
        curl_close($ch);
        if ($err || $response === false) return null;
        return json_decode(trim($response), true) === true;
    };

    // ── Step 1: Must have a license.key file ─────────────────────────────
    if (!file_exists($key_file)) {
        $show_lock('No license key installed.', 'no_key');
        return;
    }
    $license_key = strtoupper(trim(file_get_contents($key_file)));
    if (empty($license_key)) {
        $show_lock('License key file is empty.', 'no_key');
        return;
    }

    // ── Step 2: Compute machine fingerprint ──────────────────────────────
    $machine_id = $get_machine_id();

    // ── Step 3: Verify against Supabase on every request — no cache ───────
    // This means revoke or expire takes effect on the very next page load.
    $result = $call_rpc($license_key, $current_bare, $machine_id);

    if ($result === null) {
        // cURL unavailable or network error — lock immediately
        $show_lock(
            'Cannot reach the license server. An active internet connection is required.',
            'network'
        );
        return;
    }

    if ($result !== true) {
        // Revoked, expired, wrong domain, wrong machine, or invalid key
        // Delete local key file too — forces re-activation after fix
        @unlink($key_file);
        @unlink(__DIR__ . '/license.cache');
        $show_lock(
            'License is revoked, expired, or not authorized for this machine/domain. '
            . 'Contact the owner to renew or re-activate.',
            'denied'
        );
        return;
    }

    // ── Step 4: Valid — continue loading the application ─────────────────

})();
// ============================================================

header_remove("X-Powered-By");

	$client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    $myIP = '';
   /* if($myIP != $ip){
    	header('Location: http://valenzuela.gov.ph/maintenance');
    }*/
    
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 */
	define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);
	break;

	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>='))
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}
		else
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
	break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1); // EXIT_ERROR
}

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" directory.
 * Set the path if it is not in the same directory as this file.
 */
	$system_path = 'system';

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * directory than the default one you can set its name here. The directory
 * can also be renamed or relocated anywhere on your server. If you do,
 * use an absolute (full) server path.
 * For more info please see the user guide:
 *
 * https://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 */
	$application_folder = 'application';

/*
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want to move the view directory out of the application
 * directory, set the path to it here. The directory can be renamed
 * and relocated anywhere on your server. If blank, it will default
 * to the standard location inside your application directory.
 * If you do move this, use an absolute (full) server path.
 *
 * NO TRAILING SLASH!
 */
	$view_folder = '';


/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here. For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT: If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller. Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 */
	// The directory name, relative to the "controllers" directory.  Leave blank
	// if your controller is not in a sub-directory within the "controllers" one
	// $routing['directory'] = '';

	// The controller class file name.  Example:  mycontroller
	// $routing['controller'] = '';

	// The controller function you wish to be called.
	// $routing['function']	= '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 */
	// $assign_to_config['name_of_config_item'] = 'value of config item';



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

	// Set the current directory correctly for CLI requests
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}

	if (($_temp = realpath($system_path)) !== FALSE)
	{
		$system_path = $_temp.DIRECTORY_SEPARATOR;
	}
	else
	{
		// Ensure there's a trailing slash
		$system_path = strtr(
			rtrim($system_path, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		).DIRECTORY_SEPARATOR;
	}

	// Is the system path correct?
	if ( ! is_dir($system_path))
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
		exit(3); // EXIT_CONFIG
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// Path to the system directory
	define('BASEPATH', $system_path);

	// Path to the front controller (this file) directory
	define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

	// Name of the "system" directory
	define('SYSDIR', basename(BASEPATH));

	// The path to the "application" directory
	if (is_dir($application_folder))
	{
		if (($_temp = realpath($application_folder)) !== FALSE)
		{
			$application_folder = $_temp;
		}
		else
		{
			$application_folder = strtr(
				rtrim($application_folder, '/\\'),
				'/\\',
				DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
			);
		}
	}
	elseif (is_dir(BASEPATH.$application_folder.DIRECTORY_SEPARATOR))
	{
		$application_folder = BASEPATH.strtr(
			trim($application_folder, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		);
	}
	else
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
		exit(3); // EXIT_CONFIG
	}

	define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);

	// The path to the "views" directory
	if ( ! isset($view_folder[0]) && is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR))
	{
		$view_folder = APPPATH.'views';
	}
	elseif (is_dir($view_folder))
	{
		if (($_temp = realpath($view_folder)) !== FALSE)
		{
			$view_folder = $_temp;
		}
		else
		{
			$view_folder = strtr(
				rtrim($view_folder, '/\\'),
				'/\\',
				DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
			);
		}
	}
	elseif (is_dir(APPPATH.$view_folder.DIRECTORY_SEPARATOR))
	{
		$view_folder = APPPATH.strtr(
			trim($view_folder, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		);
	}
	else
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
		exit(3); // EXIT_CONFIG
	}

	define('VIEWPATH', $view_folder.DIRECTORY_SEPARATOR);

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 */
require_once BASEPATH.'core/CodeIgniter.php';