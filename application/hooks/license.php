<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * License Verification Hook
 * 
 * Protects the application from unauthorized use.
 * A valid license.key file must be present in the root directory.
 * 
 * Owner: Mark Spencer Montalbo
 * Repository: https://github.com/msmontalbo15/overtime
 */

function verify_license()
{
    $CI =& get_instance();

    // Skip check on the activation route itself
    $class = $CI->router->fetch_class();
    if ($class === 'activate') {
        return;
    }

    $key_file   = FCPATH . 'license.key';
    $valid      = false;
    $owner_hash = _get_owner_hash();

    if (file_exists($key_file)) {
        $contents = trim(file_get_contents($key_file));
        $valid    = _validate_key($contents, $owner_hash);
    }

    if (!$valid) {
        // Clear any output and show the lock screen
        if (ob_get_level()) ob_end_clean();
        _show_lock_screen();
        exit;
    }
}

/**
 * The owner fingerprint — derived from owner details.
 * This is NOT a secret; it's just an identifier.
 */
function _get_owner_hash()
{
    $owner = 'Mark Spencer Montalbo|msmontalbo15|https://github.com/msmontalbo15/overtime';
    return hash('sha256', $owner);
}

/**
 * Validates the license key file contents.
 * Key format: BASE64( sha256(owner_hash + salt) + "|" + salt )
 */
function _validate_key($key_contents, $owner_hash)
{
    if (empty($key_contents)) return false;

    $decoded = base64_decode($key_contents, true);
    if ($decoded === false) return false;

    $parts = explode('|', $decoded, 2);
    if (count($parts) !== 2) return false;

    list($stored_sig, $salt) = $parts;

    $expected_sig = hash('sha256', $owner_hash . $salt);

    return hash_equals($expected_sig, $stored_sig);
}

/**
 * Shows the unauthorized / activation required screen.
 */
function _show_lock_screen()
{
    $activation_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
        . '://' . $_SERVER['HTTP_HOST']
        . str_replace('index.php', '', $_SERVER['SCRIPT_NAME'])
        . 'activate';
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Activation Required</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
            }
            .lock-box {
                background: rgba(255,255,255,0.05);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 16px;
                padding: 48px 40px;
                max-width: 480px;
                width: 90%;
                text-align: center;
                box-shadow: 0 25px 50px rgba(0,0,0,0.4);
            }
            .lock-icon {
                font-size: 64px;
                margin-bottom: 16px;
                display: block;
            }
            h1 {
                font-size: 24px;
                font-weight: 700;
                margin-bottom: 8px;
                color: #e2e8f0;
            }
            .subtitle {
                font-size: 14px;
                color: #94a3b8;
                margin-bottom: 32px;
                line-height: 1.6;
            }
            .owner-card {
                background: rgba(255,255,255,0.08);
                border: 1px solid rgba(255,255,255,0.12);
                border-radius: 10px;
                padding: 16px 20px;
                margin-bottom: 28px;
                text-align: left;
            }
            .owner-label {
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 1.5px;
                color: #64748b;
                margin-bottom: 4px;
            }
            .owner-name {
                font-size: 16px;
                font-weight: 600;
                color: #38bdf8;
                margin-bottom: 6px;
            }
            .owner-link {
                font-size: 12px;
                color: #7dd3fc;
                text-decoration: none;
                word-break: break-all;
            }
            .owner-link:hover { text-decoration: underline; }
            .warning {
                background: rgba(239,68,68,0.15);
                border: 1px solid rgba(239,68,68,0.3);
                border-radius: 8px;
                padding: 12px 16px;
                font-size: 13px;
                color: #fca5a5;
                margin-bottom: 28px;
                line-height: 1.5;
            }
            .activate-btn {
                display: inline-block;
                background: linear-gradient(135deg, #0ea5e9, #3b82f6);
                color: #fff;
                text-decoration: none;
                padding: 13px 32px;
                border-radius: 8px;
                font-size: 15px;
                font-weight: 600;
                letter-spacing: 0.3px;
                transition: opacity .2s;
            }
            .activate-btn:hover { opacity: .85; }
            .footer-note {
                margin-top: 24px;
                font-size: 11px;
                color: #475569;
            }
        </style>
    </head>
    <body>
        <div class="lock-box">
            <span class="lock-icon">🔒</span>
            <h1>Activation Required</h1>
            <p class="subtitle">
                This application requires a valid license key to run.<br>
                No <code>license.key</code> file was found or it is invalid.
            </p>

            <div class="owner-card">
                <div class="owner-label">Rightful Owner</div>
                <div class="owner-name">Mark Spencer Montalbo</div>
                <a class="owner-link" href="https://github.com/msmontalbo15/overtime" target="_blank">
                    github.com/msmontalbo15/overtime
                </a>
            </div>

            <div class="warning">
                ⚠️ Unauthorized use, reproduction, or distribution of this software
                without the owner's written permission is strictly prohibited.
            </div>

            <a href="<?php echo $activation_url; ?>" class="activate-btn">
                🔑 &nbsp;Enter Activation Key
            </a>

            <p class="footer-note">
                &copy; <?php echo date('Y'); ?> Mark Spencer Montalbo. All rights reserved.
            </p>
        </div>
    </body>
    </html>
    <?php
}