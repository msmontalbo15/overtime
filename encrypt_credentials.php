<?php
/**
 * encrypt_credentials.php
 * ============================================================
 * ONE-TIME SETUP SCRIPT — run from CLI only, then DELETE IT.
 *
 * Usage:
 *   php encrypt_credentials.php
 *
 * What it does:
 *   1. Asks for your 3 credentials interactively
 *   2. Generates a random AES-256 master key
 *   3. Saves master key to ONE LEVEL ABOVE the webroot (not accessible via HTTP)
 *   4. Encrypts credentials with AES-256-GCM and saves to .env.license
 *   5. You add .env.license path to index.php — no plaintext ever in code
 *
 * Owner: Mark Spencer Montalbo
 * ============================================================
 */

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    die("This script must be run from the command line only.\n");
}

echo "\n";
echo "╔══════════════════════════════════════════════════╗\n";
echo "║   License Credential Encryptor — MSM Overtime   ║\n";
echo "╚══════════════════════════════════════════════════╝\n\n";

// ── Check requirements ────────────────────────────────────────────────────────
if (!extension_loaded('openssl')) {
    die("ERROR: OpenSSL extension is required. Enable it in php.ini.\n");
}
if (!in_array('aes-256-gcm', openssl_get_cipher_methods())) {
    die("ERROR: AES-256-GCM not supported by your OpenSSL version.\n");
}

// ── Collect credentials ───────────────────────────────────────────────────────
echo "Enter your credentials (input is hidden where possible):\n\n";

function prompt($label, $secret = false) {
    echo "$label: ";
    if ($secret && PHP_OS_FAMILY !== 'Windows') {
        system('stty -echo');
        $val = trim(fgets(STDIN));
        system('stty echo');
        echo "\n";
    } else {
        $val = trim(fgets(STDIN));
    }
    return $val;
}

$supabase_url    = prompt('SUPABASE_URL    (e.g. https://xxxx.supabase.co)');
$supabase_key    = prompt('SUPABASE_ANON_KEY', true);
$hmac_secret     = prompt('LICENSE_HMAC_SECRET (any random string you choose)', true);

if (empty($supabase_url) || empty($supabase_key) || empty($hmac_secret)) {
    die("\nERROR: All three values are required.\n");
}

// ── Generate master key ───────────────────────────────────────────────────────
$master_key = bin2hex(random_bytes(32)); // 64-char hex = 256-bit key

// ── Encrypt function (AES-256-GCM) ───────────────────────────────────────────
function encrypt_value($plaintext, $key_hex) {
    $key    = hex2bin($key_hex);
    $iv     = random_bytes(12);           // 96-bit IV for GCM
    $tag    = '';
    $cipher = openssl_encrypt($plaintext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag, '', 16);
    if ($cipher === false) die("Encryption failed.\n");
    // Store as: base64(iv + tag + ciphertext)
    return base64_encode($iv . $tag . $cipher);
}

$enc_url    = encrypt_value($supabase_url, $master_key);
$enc_key    = encrypt_value($supabase_key, $master_key);
$enc_secret = encrypt_value($hmac_secret,  $master_key);

// ── Determine paths ───────────────────────────────────────────────────────────
$webroot     = __DIR__;                          // e.g. C:/xampp/htdocs/overtime
$above_root  = dirname($webroot);                // e.g. C:/xampp/htdocs
$master_file = $above_root . DIRECTORY_SEPARATOR . '.license_master_key';
$env_file    = $webroot    . DIRECTORY_SEPARATOR . '.env.license';

// ── Write master key ABOVE webroot ────────────────────────────────────────────
file_put_contents($master_file, $master_key);
chmod($master_file, 0600); // owner read-only (Linux/Mac)

// ── Write encrypted credentials inside project ────────────────────────────────
$env_contents = "# Encrypted license credentials — AES-256-GCM\n";
$env_contents .= "# Generated: " . date('Y-m-d H:i:s') . "\n";
$env_contents .= "# DO NOT edit manually. Re-run encrypt_credentials.php to update.\n";
$env_contents .= "SUPABASE_URL="      . $enc_url    . "\n";
$env_contents .= "SUPABASE_ANON_KEY=" . $enc_key    . "\n";
$env_contents .= "LICENSE_HMAC_SECRET=" . $enc_secret . "\n";
$env_contents .= "MASTER_KEY_PATH="   . $master_file . "\n";

file_put_contents($env_file, $env_contents);

// ── Done ──────────────────────────────────────────────────────────────────────
echo "\n✓ Encryption complete!\n\n";
echo "Files created:\n";
echo "  Master key : $master_file\n";
echo "  Encrypted  : $env_file\n\n";
echo "⚠ IMPORTANT — do these now:\n";
echo "  1. Add '.env.license' to your .gitignore\n";
echo "  2. DELETE this script: php encrypt_credentials.php won't be needed again\n";
echo "     (or at least keep it out of the webroot)\n";
echo "  3. The master key file is ABOVE your webroot — it cannot be accessed via HTTP\n";
echo "  4. Back up '$master_file' somewhere safe — without it credentials can't be decrypted\n\n";
echo "The .env.license encrypted values are safe to commit if needed,\n";
echo "but the master key file must never be committed.\n\n";