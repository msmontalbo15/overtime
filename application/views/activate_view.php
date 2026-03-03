<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>License Manager</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
    --bg:    #080c10;
    --card:  #0d1117;
    --blue:  #0ea5e9;
    --green: #22c55e;
    --red:   #ef4444;
    --gold:  #f59e0b;
    --line:  rgba(255,255,255,0.06);
    --text:  #e2e8f0;
    --muted: #475569;
}
* { margin:0; padding:0; box-sizing:border-box; }
html, body { height:100%; }
body {
    font-family: 'Syne', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}
body::before {
    content:'';
    position:fixed; inset:0;
    background-image:
        linear-gradient(var(--line) 1px, transparent 1px),
        linear-gradient(90deg, var(--line) 1px, transparent 1px);
    background-size: 48px 48px;
    animation: drift 25s linear infinite;
    z-index:0;
}
@keyframes drift { to { background-position: 0 48px; } }

.glow-tl {
    position:fixed; top:-250px; left:-250px;
    width:600px; height:600px;
    background:radial-gradient(circle, rgba(14,165,233,0.08) 0%, transparent 65%);
    pointer-events:none; z-index:0;
    animation: breathe 6s ease-in-out infinite alternate;
}
.glow-br {
    position:fixed; bottom:-250px; right:-250px;
    width:600px; height:600px;
    background:radial-gradient(circle, rgba(34,197,94,0.06) 0%, transparent 65%);
    pointer-events:none; z-index:0;
    animation: breathe 6s ease-in-out infinite alternate-reverse;
}
@keyframes breathe { from{opacity:.4} to{opacity:1} }

.wrap {
    position:relative; z-index:1;
    width:100%; max-width:500px;
    padding:20px;
    animation: arrive .5s cubic-bezier(.22,1,.36,1) both;
}
@keyframes arrive {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}

.card {
    background: var(--card);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 3px;
    overflow: hidden;
    box-shadow: 0 40px 80px rgba(0,0,0,0.7), 0 0 0 1px rgba(255,255,255,0.03);
}

.top-bar {
    height: 3px;
    background: linear-gradient(90deg, var(--blue), #6366f1, var(--blue));
    background-size: 200%;
    animation: shimmer 2.5s linear infinite;
}
.top-bar.green {
    background: linear-gradient(90deg, var(--green), #10b981, var(--green));
    background-size: 200%;
}
@keyframes shimmer { to { background-position: 200% 0; } }

.body { padding: 44px 40px 36px; }

/* ── Status badge ── */
.status {
    display:inline-flex; align-items:center; gap:8px;
    border-radius:2px;
    font-family:'DM Mono',monospace;
    font-size:10px; letter-spacing:2.5px; text-transform:uppercase;
    padding:5px 12px; margin-bottom:28px;
}
.status.active  { background:rgba(34,197,94,.1);  border:1px solid rgba(34,197,94,.25); color:var(--green); }
.status.inactive{ background:rgba(239,68,68,.1);  border:1px solid rgba(239,68,68,.25); color:var(--red); }
.status-dot { width:6px; height:6px; border-radius:50%; background:currentColor; animation:blink 1.2s step-start infinite; }
@keyframes blink { 50%{opacity:0} }

h1 { font-size:28px; font-weight:800; color:#fff; letter-spacing:-.5px; margin-bottom:6px; line-height:1.2; }
h1 em { font-style:normal; color:var(--blue); }
.sub { font-size:13px; color:var(--muted); margin-bottom:28px; line-height:1.6; }

/* ── Owner block ── */
.owner {
    display:flex; align-items:center; gap:16px;
    background:rgba(255,255,255,.03);
    border:1px solid rgba(255,255,255,.06);
    border-radius:3px;
    padding:14px 18px;
    margin-bottom:24px;
}
.ava {
    width:44px; height:44px; border-radius:3px; flex-shrink:0;
    background:linear-gradient(135deg,#0ea5e9,#6366f1);
    display:flex; align-items:center; justify-content:center; font-size:18px;
}
.owner-meta .lbl { font-family:'DM Mono',monospace; font-size:9px; letter-spacing:2px; text-transform:uppercase; color:var(--muted); margin-bottom:3px; }
.owner-meta .name { font-size:15px; font-weight:700; color:#f1f5f9; margin-bottom:2px; }
.owner-meta a { font-family:'DM Mono',monospace; font-size:11px; color:var(--blue); text-decoration:none; opacity:.75; }
.owner-meta a:hover { opacity:1; text-decoration:underline; }

/* ── License info card ── */
.lic-info {
    background:rgba(34,197,94,.05);
    border:1px solid rgba(34,197,94,.15);
    border-radius:3px; padding:16px 18px;
    margin-bottom:24px;
}
.lic-info .row { display:flex; justify-content:space-between; align-items:center; padding:4px 0; font-size:12px; }
.lic-info .row .k { color:var(--muted); font-family:'DM Mono',monospace; font-size:10px; letter-spacing:1px; text-transform:uppercase; }
.lic-info .row .v { color:#a3e635; font-family:'DM Mono',monospace; font-size:11px; }
.lic-key-display {
    background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08);
    border-radius:3px; padding:10px 14px;
    font-family:'DM Mono',monospace; font-size:13px; color:#38bdf8;
    letter-spacing:1px; text-align:center; margin-bottom:8px; word-break:break-all;
}

/* ── Alerts ── */
.alert { border-radius:3px; padding:12px 16px; font-size:13px; margin-bottom:20px; line-height:1.5; }
.alert.err  { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.25); color:#fca5a5; }
.alert.ok   { background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.25); color:#86efac; }

/* ── Form ── */
.field-wrap {
    position:relative; margin-bottom:14px;
}
.field-label {
    display:block; font-family:'DM Mono',monospace; font-size:10px;
    letter-spacing:2px; text-transform:uppercase; color:var(--muted);
    margin-bottom:8px;
}
input[type=text], input[type=password] {
    width:100%;
    background:rgba(255,255,255,.05);
    border:1px solid rgba(255,255,255,.1);
    border-radius:3px;
    padding:14px 16px;
    font-family:'DM Mono',monospace; font-size:14px;
    color:#fff; outline:none;
    letter-spacing:2px;
    transition:border-color .2s;
}
input:focus { border-color:var(--blue); }
input::placeholder { color:var(--muted); letter-spacing:.5px; }

.btn {
    display:flex; align-items:center; justify-content:center; gap:10px;
    width:100%; padding:15px 24px;
    font-family:'Syne',sans-serif; font-size:13px; font-weight:700;
    letter-spacing:2px; text-transform:uppercase;
    border:none; cursor:pointer;
    transition:all .2s; position:relative; overflow:hidden;
    text-decoration:none;
}
.btn::after { content:''; position:absolute; inset:0; background:linear-gradient(135deg,rgba(255,255,255,.1),transparent); opacity:0; transition:opacity .2s; }
.btn:hover::after { opacity:1; }
.btn:hover { transform:translateY(-1px); }

.btn-activate {
    background:linear-gradient(135deg,#0ea5e9,#6366f1);
    color:#fff; border-radius:3px; margin-top:4px;
}
.btn-activate:hover { box-shadow:0 8px 24px rgba(14,165,233,.4); }

.btn-home {
    background:linear-gradient(135deg,var(--green),#10b981);
    color:#fff; border-radius:3px; margin-bottom:16px;
}
.btn-home:hover { box-shadow:0 8px 24px rgba(34,197,94,.35); }

.btn-deactivate {
    background:rgba(239,68,68,.1); color:var(--red);
    border:1px solid rgba(239,68,68,.25); border-radius:3px; margin-top:4px;
}
.btn-deactivate:hover { background:rgba(239,68,68,.2); box-shadow:none; }

.divider { display:flex; align-items:center; gap:12px; margin:24px 0; color:var(--muted); }
.divider::before,.divider::after { content:''; flex:1; height:1px; background:var(--line); }
.divider span { font-family:'DM Mono',monospace; font-size:10px; letter-spacing:2px; text-transform:uppercase; }

.footer {
    text-align:center; margin-top:18px;
    font-family:'DM Mono',monospace; font-size:10px; color:#1e293b; letter-spacing:1px;
}
</style>
</head>
<body>
<div class="glow-tl"></div>
<div class="glow-br"></div>

<div class="wrap">
<div class="card">
    <div class="top-bar <?php echo $is_active ? 'green' : ''; ?>"></div>
    <div class="body">

        <?php if ($is_active): ?>
            <div class="status active"><span class="status-dot"></span> Licensed & Active</div>
        <?php else: ?>
            <div class="status inactive"><span class="status-dot"></span> Not Activated</div>
        <?php endif; ?>

        <h1>License <em><?php echo $is_active ? 'Active' : 'Manager'; ?></em></h1>
        <p class="sub"><?php echo $is_active
            ? 'This installation is authenticated and running under a valid license.'
            : 'Enter your license key below to activate this installation.'; ?></p>

        <div class="owner">
            <div class="ava">👤</div>
            <div class="owner-meta">
                <div class="lbl">Rightful Owner</div>
                <div class="name">Mark Spencer Montalbo</div>
                <a href="https://github.com/msmontalbo15/overtime" target="_blank">github.com/msmontalbo15/overtime</a>
            </div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert err">⚠ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert ok">✓ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if ($is_active): ?>

            <?php if (!empty($license_info)): ?>
            <div class="lic-info">
                <div class="lic-key-display"><?php echo htmlspecialchars(file_exists(FCPATH.'license.key') ? trim(file_get_contents(FCPATH.'license.key')) : ''); ?></div>
                <div class="row">
                    <span class="k">Label</span>
                    <span class="v"><?php echo htmlspecialchars($license_info['label'] ?? '—'); ?></span>
                </div>
                <div class="row">
                    <span class="k">Domain</span>
                    <span class="v"><?php echo htmlspecialchars($license_info['domain'] ?? 'Any'); ?></span>
                </div>
                <div class="row">
                    <span class="k">Expires</span>
                    <span class="v"><?php echo !empty($license_info['expires_at']) ? date('M d, Y', strtotime($license_info['expires_at'])) : 'Never'; ?></span>
                </div>
            </div>
            <?php endif; ?>

            <a href="<?php echo base_url(); ?>" class="btn btn-home">← Back to Application</a>

            <div class="divider"><span>Deactivate</span></div>
            <p class="sub" style="margin-bottom:14px;font-size:12px;">
                To deactivate, enter your current license key to confirm ownership.
            </p>
            <?php echo form_open('activate/deactivate'); ?>
                <div class="field-wrap">
                    <label class="field-label">Confirm License Key</label>
                    <input type="password" name="license_key" placeholder="MSM-XXXX-XXXX-XXXX-XXXX" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-deactivate"
                    onclick="return confirm('Are you sure you want to deactivate this installation?')">
                    🔒 &nbsp; Deactivate This Installation
                </button>
            </form>

        <?php else: ?>

            <?php echo form_open('activate'); ?>
                <div class="field-wrap">
                    <label class="field-label">License Key</label>
                    <input type="text" name="license_key" placeholder="MSM-XXXX-XXXX-XXXX-XXXX"
                           autocomplete="off" autofocus spellcheck="false"
                           oninput="this.value=this.value.toUpperCase()">
                </div>
                <button type="submit" class="btn btn-activate">🔑 &nbsp; Activate Application</button>
            </form>

        <?php endif; ?>

    </div>
</div>
<div class="footer">&copy; <?php echo date('Y'); ?> Mark Spencer Montalbo — All rights reserved</div>
</div>
</body>
</html>