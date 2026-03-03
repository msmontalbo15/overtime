<?php
// Variables set by index.php before including this file:
// $reason       — human-readable message (may contain safe HTML like <strong>)
// $reason_code  — 'no_key' | 'network' | 'denied' | 'invalid'
// $activate_url — full URL to /activate

$_reason_code = isset($reason_code) ? $reason_code : 'invalid';
$_reason      = isset($reason)      ? $reason      : 'License verification failed.';
$_activate    = isset($activate_url) ? $activate_url : '#';

// Icon and color per reason code
$_icons  = ['no_key' => '🔑', 'network' => '📡', 'denied' => '🚫', 'invalid' => '🔒'];
$_icon   = $_icons[$_reason_code] ?? '🔒';
$_titles = [
    'no_key'  => 'Activation Required',
    'network' => 'License Server Unreachable',
    'denied'  => 'License Denied',
    'invalid' => 'License Invalid',
];
$_title  = $_titles[$_reason_code] ?? 'Access Denied';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo $_title; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root{--red:#ff3b3b;--blue:#00c2ff;--bg:#080c10;--card:#0d1117;--line:rgba(255,255,255,0.06)}
*{margin:0;padding:0;box-sizing:border-box}html,body{height:100%}
body{font-family:'Syne',sans-serif;background:var(--bg);color:#e2e8f0;
min-height:100vh;display:flex;align-items:center;justify-content:center;
overflow:hidden;position:relative}
body::before{content:'';position:fixed;inset:0;
background-image:linear-gradient(var(--line) 1px,transparent 1px),
linear-gradient(90deg,var(--line) 1px,transparent 1px);
background-size:48px 48px;animation:drift 25s linear infinite;z-index:0}
@keyframes drift{to{background-position:0 48px}}
.glow{position:fixed;border-radius:50%;pointer-events:none;z-index:0;
animation:breathe 5s ease-in-out infinite alternate}
.glow-tl{top:-200px;left:-200px;width:500px;height:500px;
background:radial-gradient(circle,rgba(255,59,59,.1) 0%,transparent 70%)}
.glow-br{bottom:-200px;right:-200px;width:500px;height:500px;
background:radial-gradient(circle,rgba(0,194,255,.07) 0%,transparent 70%);
animation-direction:alternate-reverse}
@keyframes breathe{from{opacity:.4}to{opacity:1}}
.wrap{position:relative;z-index:1;width:100%;max-width:520px;padding:20px;
animation:arrive .5s cubic-bezier(.22,1,.36,1) both}
@keyframes arrive{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.card{background:var(--card);border:1px solid rgba(255,255,255,.07);border-radius:3px;
overflow:hidden;box-shadow:0 40px 80px rgba(0,0,0,.7)}
.bar{height:3px;background:linear-gradient(90deg,var(--red),#ff6b35,var(--red));
background-size:200%;animation:bar 2s linear infinite}
@keyframes bar{to{background-position:200% 0}}
.inner{padding:44px 40px 36px}
.badge{display:inline-flex;align-items:center;gap:8px;
background:rgba(255,59,59,.1);border:1px solid rgba(255,59,59,.25);color:var(--red);
font-family:'DM Mono',monospace;font-size:10px;letter-spacing:3px;text-transform:uppercase;
padding:5px 12px;border-radius:2px;margin-bottom:24px}
.dot{width:6px;height:6px;background:currentColor;border-radius:50%;
animation:blink 1s step-start infinite}
@keyframes blink{50%{opacity:0}}
.icon-big{font-size:48px;display:block;margin-bottom:16px}
h1{font-size:28px;font-weight:800;color:#fff;letter-spacing:-.5px;margin-bottom:10px}
h1 em{font-style:normal;color:var(--red)}
.reason-box{background:rgba(255,59,59,.07);border:1px solid rgba(255,59,59,.2);
border-left:3px solid var(--red);border-radius:2px;padding:12px 16px;
font-family:'DM Mono',monospace;font-size:12px;color:#ff9090;line-height:1.6;
margin-bottom:24px}
.owner{display:flex;align-items:center;gap:14px;
background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);
border-radius:3px;padding:14px 16px;margin-bottom:24px}
.ava{width:40px;height:40px;background:linear-gradient(135deg,#0ea5e9,#6366f1);
border-radius:2px;display:flex;align-items:center;justify-content:center;
font-size:16px;flex-shrink:0}
.owner-meta .lbl{font-family:'DM Mono',monospace;font-size:9px;letter-spacing:2px;
text-transform:uppercase;color:#475569;margin-bottom:3px}
.owner-meta .name{font-size:14px;font-weight:700;color:#f1f5f9;margin-bottom:2px}
.owner-meta a{font-family:'DM Mono',monospace;font-size:11px;color:#38bdf8;
text-decoration:none;opacity:.75}
.owner-meta a:hover{opacity:1;text-decoration:underline}
.warn{background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.18);
border-radius:2px;padding:11px 14px;font-size:12px;color:#d4a800;
line-height:1.6;margin-bottom:24px}
.btn{display:flex;align-items:center;justify-content:center;gap:10px;
width:100%;padding:14px 20px;background:linear-gradient(135deg,#0ea5e9,#6366f1);
color:#fff;text-decoration:none;border-radius:3px;
font-family:'Syne',sans-serif;font-size:13px;font-weight:700;
letter-spacing:1.5px;text-transform:uppercase;border:none;cursor:pointer;
transition:all .2s;position:relative;overflow:hidden}
.btn::after{content:'';position:absolute;inset:0;
background:linear-gradient(135deg,rgba(255,255,255,.1),transparent);
opacity:0;transition:opacity .2s}
.btn:hover::after{opacity:1}
.btn:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(14,165,233,.4)}
/* network-specific style */
.btn-retry{background:linear-gradient(135deg,#f59e0b,#d97706)}
.btn-retry:hover{box-shadow:0 8px 24px rgba(245,158,11,.4)}
.footer{text-align:center;margin-top:18px;
font-family:'DM Mono',monospace;font-size:10px;color:#1e293b;letter-spacing:1px}
</style>
</head>
<body>
<div class="glow glow-tl"></div>
<div class="glow glow-br"></div>
<div class="wrap">
<div class="card">
  <div class="bar"></div>
  <div class="inner">

    <div class="badge"><span class="dot"></span>
    <?php echo $_reason_code === 'network' ? 'Offline' : 'Unauthorized'; ?>
    </div>

    <span class="icon-big"><?php echo $_icon; ?></span>
    <h1><?php echo $_title; ?></h1>

    <div class="reason-box"><?php echo $_reason; ?></div>

    <div class="owner">
      <div class="ava">👤</div>
      <div class="owner-meta">
        <div class="lbl">Rightful Owner</div>
        <div class="name">Mark Spencer Montalbo</div>
        <a href="https://github.com/msmontalbo15/overtime" target="_blank">github.com/msmontalbo15/overtime</a>
      </div>
    </div>

    <div class="warn">
      ⚠ Unauthorized use, redistribution, or sharing of this software
      is strictly prohibited and may result in legal action.
    </div>

    <?php if ($_reason_code === 'network'): ?>
      <a href="javascript:location.reload()" class="btn btn-retry">↻ &nbsp; Retry Connection</a>
    <?php else: ?>
      <a href="<?php echo htmlspecialchars($_activate); ?>" class="btn">🔑 &nbsp; Enter License Key</a>
    <?php endif; ?>

  </div>
</div>
<div class="footer">&copy; <?php echo date('Y'); ?> Mark Spencer Montalbo — All rights reserved</div>
</div>
</body>
</html>