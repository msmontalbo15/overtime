<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Activate Controller — Supabase Machine+Domain Locked
 * Owner: Mark Spencer Montalbo
 * https://github.com/msmontalbo15/overtime
 */
class Activate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // ── GET /activate ─────────────────────────────────────────────────────
    public function index()
    {
        $key_file   = FCPATH . 'license.key';
        $cache_file = FCPATH . 'license.cache';
        $current_key = file_exists($key_file) ? strtoupper(trim(file_get_contents($key_file))) : '';

        // Check cache first (fast) — only hit Supabase if cache is stale/missing
        $is_active = !empty($current_key) && $this->_is_valid($current_key, $cache_file);

        // Already licensed — redirect straight to app on plain GET
        if ($is_active && !$this->input->post()) {
            redirect(base_url());
            return;
        }

        $this->data['title']          = 'License Manager';
        $this->data['is_active']      = $is_active;
        $this->data['current_key']    = $is_active ? $current_key : '';
        $this->data['current_domain'] = $this->_bare_domain();
        $this->data['error']          = '';
        $this->data['success']        = '';

        if ($this->input->post('license_key')) {
            $this->_handle_activate($key_file, $cache_file);
            return;
        }

        $this->load->view('activate_view', $this->data);
    }

    // ── POST /activate/deactivate ─────────────────────────────────────────
    public function deactivate()
    {
        if (!$this->input->post()) { redirect('activate'); return; }

        $key_file    = FCPATH . 'license.key';
        $cache_file  = FCPATH . 'license.cache';
        $current_key = file_exists($key_file) ? strtoupper(trim(file_get_contents($key_file))) : '';
        $entered_key = strtoupper(trim($this->input->post('license_key', true)));

        $this->data['title']          = 'License Manager';
        $this->data['current_domain'] = $this->_bare_domain();
        $this->data['error']          = '';
        $this->data['success']        = '';

        if (empty($entered_key) || !hash_equals($current_key, $entered_key)) {
            $this->data['is_active']   = true;
            $this->data['current_key'] = $current_key;
            $this->data['error']       = 'Key does not match. Deactivation denied.';
            log_message('error', '[License] Failed deactivation from ' . $this->input->ip_address());
            $this->load->view('activate_view', $this->data);
            return;
        }

        @unlink($key_file);
        @unlink($cache_file);
        $this->data['is_active']   = false;
        $this->data['current_key'] = '';
        $this->data['success']     = 'Installation deactivated. License key and cache removed.';
        $this->load->view('activate_view', $this->data);
    }

    // ── Private: handle activation POST ──────────────────────────────────
    private function _handle_activate($key_file, $cache_file)
    {
        $entered_key = strtoupper(trim($this->input->post('license_key', true)));
        $domain      = $this->_bare_domain();

        if (empty($entered_key)) {
            $this->data['is_active']   = false;
            $this->data['current_key'] = '';
            $this->data['error']       = 'Please enter a license key.';
            $this->load->view('activate_view', $this->data);
            return;
        }

        $valid = $this->_rpc_verify($entered_key);

        if ($valid === null) {
            $this->data['is_active']   = false;
            $this->data['current_key'] = '';
            $this->data['error']       = 'Cannot reach the license server. Check your internet connection.';
            $this->load->view('activate_view', $this->data);
            return;
        }

        if (!$valid) {
            $this->data['is_active']   = false;
            $this->data['current_key'] = '';
            $this->data['error']       = 'License key is invalid, revoked, expired, not registered for domain <strong>'
                                       . htmlspecialchars($domain) . '</strong>, or this machine is not authorized.';
            log_message('error', '[License] Failed activation "' . $entered_key . '" domain=' . $domain . ' from ' . $this->input->ip_address());
            $this->load->view('activate_view', $this->data);
            return;
        }

        file_put_contents($key_file, $entered_key);
        $this->_write_cache($cache_file, $entered_key, $domain);

        // Redirect straight into the application
        redirect(base_url());
    }

    // ── Private: call Supabase RPC verify_license_machine ────────────────
    // Returns: true = valid | false = denied | null = network error
    private function _rpc_verify($key)
    {
        if (!defined('SUPABASE_URL') || !defined('SUPABASE_ANON_KEY')) return null;
        if (!function_exists('curl_init')) return null;

        $domain     = $this->_bare_domain();
        $machine_id = $this->_machine_id();
        $payload    = json_encode([
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
        $response  = curl_exec($ch);
        $err       = curl_errno($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err || $response === false) {
            log_message('error', '[License] cURL error ' . $err);
            return null;
        }
        $decoded = json_decode(trim($response), true);
        if ($decoded !== true && $decoded !== false) {
            log_message('error', '[License] Unexpected RPC response HTTP=' . $http_code . ' body=' . $response);
        }
        return $decoded === true;
    }

    // ── Private: SHA256(hostname|MAC) fingerprint ─────────────────────────
    private function _machine_id()
    {
        $hostname = strtolower(trim(function_exists('gethostname') ? gethostname() : (getenv('COMPUTERNAME') ?: '')));
        $mac      = '';

        if (PHP_OS_FAMILY === 'Windows' || strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $out = shell_exec('getmac /fo csv /nh 2>nul');
            if ($out && preg_match('/([0-9A-Fa-f]{2}[:\-]){5}[0-9A-Fa-f]{2}/', $out, $m)) {
                $mac = strtolower(str_replace('-', ':', $m[0]));
            }
        } else {
            $out = shell_exec('ip link show 2>/dev/null || ifconfig 2>/dev/null');
            if ($out && preg_match('/([0-9a-f]{2}:){5}[0-9a-f]{2}/i', $out, $m)) {
                $mac = strtolower($m[0]);
            }
        }

        return hash('sha256', $hostname . '|' . ($mac ?: 'no-mac'));
    }

    // ── Private: get raw hostname and MAC for display ─────────────────────
    private function _machine_raw()
    {
        $hostname = function_exists('gethostname') ? gethostname() : (getenv('COMPUTERNAME') ?: 'unknown');
        $mac      = 'not found';

        if (PHP_OS_FAMILY === 'Windows' || strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $out = shell_exec('getmac /fo csv /nh 2>nul');
            if ($out && preg_match('/([0-9A-Fa-f]{2}[:\-]){5}[0-9A-Fa-f]{2}/', $out, $m)) {
                $mac = strtolower(str_replace('-', ':', $m[0]));
            }
        } else {
            $out = shell_exec('ip link show 2>/dev/null || ifconfig 2>/dev/null');
            if ($out && preg_match('/([0-9a-f]{2}:){5}[0-9a-f]{2}/i', $out, $m)) {
                $mac = strtolower($m[0]);
            }
        }
        return ['hostname' => $hostname, 'mac' => $mac];
    }

    // ── Private: write HMAC cache ─────────────────────────────────────────
    private function _write_cache($cache_file, $key, $domain)
    {
        if (!defined('LICENSE_HMAC_SECRET')) return;
        $machine_id = $this->_machine_id();
        $ts         = time();
        $token      = hash_hmac('sha256', $key.'|'.$domain.'|'.$machine_id.'|'.$ts, LICENSE_HMAC_SECRET);
        file_put_contents($cache_file, json_encode([
            'key'     => $key,
            'domain'  => $domain,
            'machine' => $machine_id,
            'ts'      => $ts,
            'token'   => $token,
        ]));
    }

    // ── Private: bare domain ──────────────────────────────────────────────
    private function _bare_domain()
    {
        $raw = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return strtolower(preg_replace(['/:\d+$/', '/^www\./'], ['', ''], $raw));
    }

    // ── GET /activate/debug ───────────────────────────────────────────────
    public function debug()
    {
        header('Content-Type: text/plain; charset=utf-8');

        $domain     = $this->_bare_domain();
        $machine_id = $this->_machine_id();
        $raw        = $this->_machine_raw();
        $key_file   = FCPATH . 'license.key';
        $stored_key = file_exists($key_file) ? strtoupper(trim(file_get_contents($key_file))) : '';

        echo "=== License Debug ===\n\n";
        echo "-- This machine --\n";
        echo "Hostname         : " . $raw['hostname'] . "\n";
        echo "MAC address      : " . $raw['mac'] . "\n";
        echo "Machine ID (hash): " . $machine_id . "\n";
        echo "Domain           : " . $domain . "\n";
        echo "Stored key       : " . ($stored_key ?: '(none)') . "\n\n";

        if (!function_exists('curl_init')) {
            echo "ERROR: cURL not enabled. Open php.ini, uncomment extension=curl, restart Apache.\n";
            exit;
        }

        // cURL helpers
        $post = function($url, $body) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $body, CURLOPT_TIMEOUT => 8,
                CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_HTTPHEADER => [
                    'apikey: ' . SUPABASE_ANON_KEY,
                    'Authorization: Bearer ' . SUPABASE_ANON_KEY,
                    'Content-Type: application/json', 'Accept: application/json',
                ],
            ]);
            $r = curl_exec($ch); $c = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
            return [$c, $r];
        };
        $get = function($url) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 8,
                CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_HTTPHEADER => [
                    'apikey: ' . SUPABASE_ANON_KEY,
                    'Authorization: Bearer ' . SUPABASE_ANON_KEY,
                    'Accept: application/json',
                ],
            ]);
            $r = curl_exec($ch); $c = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
            return [$c, $r];
        };

        // ── Test RPC with stored key ──────────────────────────────────────
        if ($stored_key) {
            echo "-- RPC test (stored key + this machine) --\n";
            list($code, $resp) = $post(
                SUPABASE_URL . '/rest/v1/rpc/verify_license_machine',
                json_encode(['p_key' => $stored_key, 'p_domain' => $domain, 'p_machine_id' => $machine_id])
            );
            echo "HTTP     : $code\n";
            echo "Response : $resp\n";
            echo "Result   : " . ($resp === 'true' ? "VALID ✓ — activation should work" : "INVALID ✗ — see fix below") . "\n\n";

            if ($resp === 'true') {
                echo "Everything looks good. If activate page still shows an error,\n";
                echo "delete license.key and license.cache from the project root and try again.\n";
                exit;
            }
        }

        // ── Read licenses table ───────────────────────────────────────────
        echo "-- Licenses table --\n";
        list($code2, $resp2) = $get(
            SUPABASE_URL . '/rest/v1/licenses?select=license_key,domain,machine_id,is_active,revoked_at,expires_at&order=created_at.desc'
        );

        $rows = json_decode($resp2, true);
        $fix_key = $stored_key ?: 'MSM-XXXX-XXXX-XXXX-XXXX';

        if ($code2 !== 200 || !is_array($rows)) {
            echo "Cannot read (RLS blocks anon — normal). Raw: $resp2\n\n";
        } elseif (count($rows) === 0) {
            echo "Table is EMPTY — no keys exist yet.\n\n";
        } else {
            foreach ($rows as $row) {
                $dm = ($row['domain'] ?? '') === $domain ? 'MATCH ✓' : 'MISMATCH ✗';
                $mm = ($row['machine_id'] ?? '') === $machine_id ? 'MATCH ✓' : (empty($row['machine_id']) ? 'NULL (will bind on first use)' : 'MISMATCH ✗');
                echo "  Key        : " . $row['license_key'] . "\n";
                echo "  Domain     : '" . ($row['domain'] ?? '') . "'  =>  $dm\n";
                echo "  Machine ID : '" . ($row['machine_id'] ?? 'NULL') . "'\n";
                echo "  This mac   : '$machine_id'  =>  $mm\n";
                echo "  Active     : " . ($row['is_active'] ? 'yes' : 'NO') . ($row['revoked_at'] ? '  REVOKED' : '') . "\n";
                echo "  Expires    : " . ($row['expires_at'] ?: 'never') . "\n\n";
                $fix_key = $row['license_key'];
            }
        }

        // ── Generate exact fix SQL ────────────────────────────────────────
        echo "-- Fix: run this SQL in Supabase SQL Editor --\n\n";
        echo "-- Step 1: Make sure the machine_id column exists\n";
        echo "ALTER TABLE public.licenses ADD COLUMN IF NOT EXISTS machine_id varchar(64) DEFAULT NULL;\n\n";
        echo "-- Step 2: Insert or update key for THIS machine + domain\n";
        echo "INSERT INTO public.licenses (license_key, label, domain, machine_id, is_active)\n";
        echo "VALUES (\n";
        echo "  '$fix_key',\n";
        echo "  'Mark Spencer Montalbo - Local Desktop',\n";
        echo "  '$domain',\n";
        echo "  '$machine_id',\n";
        echo "  true\n";
        echo ")\n";
        echo "ON CONFLICT (license_key) DO UPDATE\n";
        echo "  SET domain     = '$domain',\n";
        echo "      machine_id = '$machine_id',\n";
        echo "      is_active  = true,\n";
        echo "      revoked_at = NULL;\n\n";
        echo "-- Step 3: Make sure the new RPC function exists (re-run supabase_setup.sql)\n";
        echo "-- The function must be named verify_license_machine(p_key, p_domain, p_machine_id)\n";
        exit;
    }
}