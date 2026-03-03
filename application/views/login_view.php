<?php
// Load dynamic settings for login page
$this->load->view('components/header');
$_logo_path = 'assets/images/dco.png';
$_icon_path = 'assets/images/dco.ico';
$_app_title = 'Digital Communications Office';
try {
    $CI =& get_instance();
    $CI->load->model('superadmin_model');
    $_settings  = $CI->superadmin_model->get_all_settings();
    $_logo_path = !empty($_settings['logo_path']) ? $_settings['logo_path'] : $_logo_path;
    $_icon_path = !empty($_settings['icon_path']) ? $_settings['icon_path'] : $_icon_path;
    $_app_title = !empty($_settings['app_title']) ? $_settings['app_title'] : $_app_title;
} catch (Exception $e) {
    // system_settings table may not exist yet
}
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body { background: #f4f6f9; font-family: 'Helvetica', sans-serif; }
        .login-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-box { width: 360px; }
        .login-logo img { max-width: 120px; max-height: 120px; object-fit: contain; }
        .login-logo h2 { font-size: 18px; font-weight: 600; color: #333; margin-top: 10px; line-height: 1.3; }
        .login-card { border-radius: 8px; box-shadow: 0 2px 16px rgba(0,0,0,0.10); }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-box">
        <div class="login-logo text-center mb-3">
            <img src="<?php echo base_url(htmlspecialchars($_logo_path)); ?>" alt="<?php echo htmlspecialchars($_app_title); ?>">
            <h2><?php echo htmlspecialchars($_app_title); ?></h2>
        </div>
        <div class="card login-card">
            <div class="card-body">
                <p class="text-muted text-center mb-3">Sign in to continue</p>
                <?php echo form_open("login"); ?>
                <div class="input-group mb-3">
                    <select class="form-control<?php echo (form_error('username')) ? ' is-invalid' : ''; ?>" name="username">
                        <option value="">-- Select User --</option>
                        <?php if ($data): foreach ($data as $value): ?>
                            <option value="<?php echo $value->username; ?>"><?php echo strtoupper($value->first_name); ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                    </div>
                    <?php if (form_error('username')): ?>
                        <small class="form-text text-danger w-100"><?php echo form_error('username'); ?></small>
                    <?php endif; ?>
                </div>
                <div class="input-group mb-3">
                    <input type="password"
                           class="form-control<?php echo (form_error('password')) ? ' is-invalid' : ''; ?>"
                           name="password" autocomplete="off" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                    </div>
                    <?php if (form_error('password')): ?>
                        <small class="form-text text-danger w-100"><?php echo form_error('password'); ?></small>
                    <?php endif; ?>
                </div>
                <?php if ($this->session->flashdata('error_')): ?>
                    <div class="alert alert-danger py-2 px-3">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?php echo $this->session->flashdata('error_'); ?>
                    </div>
                <?php endif; ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <p class="text-center text-muted mt-3" style="font-size:12px;">
            &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($_app_title); ?>
        </p>
    </div>
</div>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/adminlte/adminlte.min.js'); ?>"></script>
</body>
</html>