<?php

function redirect_ssl() {

    $CI =& get_instance();

    // Railway terminates SSL externally via X-Forwarded-Proto
    $proto    = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '');
    $is_https = $proto === 'https' || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    // Always update base_url to https on Railway
    $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);

    // If already HTTPS, do nothing — avoids redirect loop
    if ($is_https) return;

    // Not HTTPS yet — redirect to HTTPS version of current URL
    $https_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $https_url, true, 301);
    exit;
}