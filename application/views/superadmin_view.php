<?php $this->load->view('components/header'); ?>
<?php $this->load->view('components/navbar'); ?>
<?php $this->load->view('components/sidebar'); ?>

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/toastr/toastr.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/confirm/jquery-confirm.min.css'); ?>">

<!-- Content Wrapper -->
<div class="content-wrapper">

  <!-- Page Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <h1 class="m-0 text-dark"><i class="fas fa-shield-alt mr-2 text-danger"></i>Super Admin</h1>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

      <!-- Nav Tabs -->
      <ul class="nav nav-tabs mb-3" id="superadminTabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="templates-tab" data-toggle="tab" href="#templates" role="tab">
            <i class="fas fa-file-alt mr-1"></i> Print Templates
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="admins-tab" data-toggle="tab" href="#admins" role="tab">
            <i class="fas fa-user-shield mr-1"></i> Admin Roles
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="logo-tab" data-toggle="tab" href="#logo" role="tab">
            <i class="fas fa-image mr-1"></i> Logo
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="theme-tab" data-toggle="tab" href="#theme" role="tab">
            <i class="fas fa-palette mr-1"></i> Theme
          </a>
        </li>
      </ul>

      <div class="tab-content" id="superadminTabsContent">

        <!-- ══════════════════════════════════════════════ -->
        <!-- TAB 1: PRINT TEMPLATES                        -->
        <!-- ══════════════════════════════════════════════ -->
        <div class="tab-pane fade show active" id="templates" role="tabpanel">
          <form id="templateForm">
            <div class="row">

              <!-- App Title -->
              <div class="col-md-12 mb-3">
                <div class="card card-outline card-info">
                  <div class="card-header"><strong><i class="fas fa-building mr-1"></i> Office / App Title</strong></div>
                  <div class="card-body">
                    <div class="form-group mb-0">
                      <label>Office Name (shown in document headers)</label>
                      <input type="text" class="form-control" name="app_title"
                             value="<?php echo htmlspecialchars($settings['app_title'] ?? 'DIGITAL COMMUNICATIONS OFFICE'); ?>">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Request / Individual print -->
              <div class="col-md-6 mb-3">
                <div class="card card-outline card-primary h-100">
                  <div class="card-header"><strong><i class="fas fa-envelope mr-1"></i> Individual Request (TO / THROUGH)</strong></div>
                  <div class="card-body">
                    <div class="form-group">
                      <label>TO <small class="text-muted">(name + title, separate lines)</small></label>
                      <textarea class="form-control" name="template_request_header" rows="3"><?php echo htmlspecialchars($settings['template_request_header'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group mb-0">
                      <label>THROUGH <small class="text-muted">(name + title, separate lines)</small></label>
                      <textarea class="form-control" name="template_request_through" rows="3"><?php echo htmlspecialchars($settings['template_request_through'] ?? ''); ?></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Summary print -->
              <div class="col-md-6 mb-3">
                <div class="card card-outline card-success h-100">
                  <div class="card-header"><strong><i class="fas fa-list-alt mr-1"></i> Summary Print (FOR field)</strong></div>
                  <div class="card-body">
                    <div class="form-group">
                      <label>FOR — Name</label>
                      <input type="text" class="form-control" name="template_summary_for_name"
                             value="<?php echo htmlspecialchars($settings['template_summary_for_name'] ?? ''); ?>">
                    </div>
                    <div class="form-group mb-0">
                      <label>FOR — Title / Position</label>
                      <input type="text" class="form-control" name="template_summary_for_title"
                             value="<?php echo htmlspecialchars($settings['template_summary_for_title'] ?? ''); ?>">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Signatory (used across all printouts) -->
              <div class="col-md-12 mb-3">
                <div class="card card-outline card-warning">
                  <div class="card-header"><strong><i class="fas fa-signature mr-1"></i> Signatory (appears on all printouts)</strong></div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group mb-0">
                          <label>Full Name</label>
                          <input type="text" class="form-control" name="template_signatory_name"
                                 value="<?php echo htmlspecialchars($settings['template_signatory_name'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group mb-0">
                          <label>Title / Designation</label>
                          <input type="text" class="form-control" name="template_signatory_title"
                                 value="<?php echo htmlspecialchars($settings['template_signatory_title'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group mb-0">
                          <label>Office / Department</label>
                          <input type="text" class="form-control" name="template_signatory_office"
                                 value="<?php echo htmlspecialchars($settings['template_signatory_office'] ?? ''); ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div><!-- /.row -->

            <div class="text-right">
              <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save mr-1"></i> Save Templates
              </button>
            </div>
          </form>
        </div>

        <!-- ══════════════════════════════════════════════ -->
        <!-- TAB 2: ADMIN ROLES                            -->
        <!-- ══════════════════════════════════════════════ -->
        <div class="tab-pane fade" id="admins" role="tabpanel">
          <div class="row">

            <!-- Current Admins card -->
            <div class="col-md-7">
              <div class="card card-outline card-primary">
                <div class="card-header"><strong><i class="fas fa-users-cog mr-1"></i> Current Admins & Super Admins</strong></div>
                <div class="card-body p-0">
                  <table class="table table-hover mb-0">
                    <thead class="thead-light">
                      <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($admins): foreach ($admins as $a): ?>
                      <tr>
                        <td><?php echo ucwords(strtolower($a->last_name . ', ' . $a->first_name)); ?></td>
                        <td><code><?php echo htmlspecialchars($a->username); ?></code></td>
                        <td>
                          <?php if ($a->user_type == 3): ?>
                            <span class="badge badge-danger"><i class="fas fa-shield-alt mr-1"></i>Super Admin</span>
                          <?php else: ?>
                            <span class="badge badge-info"><i class="fas fa-user-shield mr-1"></i>Admin</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php if ($a->user_type == 3): ?>
                            <button class="btn btn-xs btn-outline-warning"
                              onclick="setRole(<?php echo $a->id; ?>, 2, '<?php echo ucwords(strtolower($a->first_name)); ?>')">
                              <i class="fas fa-arrow-down mr-1"></i> Demote to Admin
                            </button>
                            <button class="btn btn-xs btn-outline-secondary ml-1"
                              onclick="setRole(<?php echo $a->id; ?>, 1, '<?php echo ucwords(strtolower($a->first_name)); ?>')">
                              <i class="fas fa-arrow-down mr-1"></i> Demote to User
                            </button>
                          <?php else: ?>
                            <button class="btn btn-xs btn-outline-danger"
                              onclick="setRole(<?php echo $a->id; ?>, 3, '<?php echo ucwords(strtolower($a->first_name)); ?>')">
                              <i class="fas fa-shield-alt mr-1"></i> Make Super Admin
                            </button>
                            <button class="btn btn-xs btn-outline-secondary ml-1"
                              onclick="setRole(<?php echo $a->id; ?>, 1, '<?php echo ucwords(strtolower($a->first_name)); ?>')">
                              <i class="fas fa-arrow-down mr-1"></i> Demote to User
                            </button>
                          <?php endif; ?>
                        </td>
                      </tr>
                      <?php endforeach; else: ?>
                      <tr><td colspan="4" class="text-center text-muted py-3">No admins found.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Promote a regular user -->
            <div class="col-md-5">
              <div class="card card-outline card-success">
                <div class="card-header"><strong><i class="fas fa-user-plus mr-1"></i> Promote a User</strong></div>
                <div class="card-body">
                  <p class="text-muted small">Select a regular user and assign them Admin or Super Admin role.</p>
                  <div class="form-group">
                    <label>Select User</label>
                    <select class="form-control form-control-sm" id="promoteUserId">
                      <option value="">-- Choose user --</option>
                      <?php if ($all_users): foreach ($all_users as $u): ?>
                        <?php if ($u->user_type == 1): // only show regular users ?>
                        <option value="<?php echo $u->id; ?>">
                          <?php echo ucwords(strtolower($u->last_name . ', ' . $u->first_name)); ?>
                        </option>
                        <?php endif; ?>
                      <?php endforeach; endif; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Assign Role</label>
                    <select class="form-control form-control-sm" id="promoteRoleId">
                      <option value="2">Admin</option>
                      <option value="3">Super Admin</option>
                    </select>
                  </div>
                  <button class="btn btn-success btn-sm btn-block" onclick="promoteUser()">
                    <i class="fas fa-arrow-up mr-1"></i> Promote
                  </button>
                </div>
              </div>

              <div class="card card-outline card-secondary mt-3">
                <div class="card-header"><strong><i class="fas fa-info-circle mr-1"></i> Role Legend</strong></div>
                <div class="card-body small">
                  <p class="mb-1"><span class="badge badge-secondary">User (1)</span> — Can only manage their own overtime</p>
                  <p class="mb-1"><span class="badge badge-info">Admin (2)</span> — Can view all users, generate reports</p>
                  <p class="mb-0"><span class="badge badge-danger">Super Admin (3)</span> — Full access + this panel</p>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- ══════════════════════════════════════════════ -->
        <!-- TAB 3: LOGO                                   -->
        <!-- ══════════════════════════════════════════════ -->
        <div class="tab-pane fade" id="logo" role="tabpanel">
          <div class="row justify-content-center">

            <!-- Logo upload -->
            <div class="col-md-5">
              <div class="card card-outline card-info">
                <div class="card-header"><strong><i class="fas fa-image mr-1"></i> Sidebar & Login Logo</strong></div>
                <div class="card-body text-center">
                  <div class="mb-3">
                    <p class="text-muted small mb-2">Current logo:</p>
                    <img id="currentLogo"
                         src="<?php echo base_url($settings['logo_path'] ?? 'assets/images/dco.png'); ?>"
                         alt="Current Logo"
                         style="max-height:100px; max-width:220px; border:1px solid #dee2e6; border-radius:6px; padding:8px; background:#f8f9fa; object-fit:contain;">
                  </div>
                  <hr>
                  <form id="logoForm" enctype="multipart/form-data">
                    <div class="form-group">
                      <label class="font-weight-bold">Upload New Logo</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="logoFile" name="logo"
                               accept=".jpg,.jpeg,.png,.gif,.svg" onchange="previewFile(event,'logoPreview','logoPreviewWrap','logoFileLabel')">
                        <label class="custom-file-label" id="logoFileLabel" for="logoFile">Choose file...</label>
                      </div>
                      <small class="form-text text-muted">JPG, PNG, GIF, SVG — Max 2MB</small>
                    </div>
                    <div id="logoPreviewWrap" class="mb-3" style="display:none;">
                      <img id="logoPreview" src="#" alt="Preview"
                           style="max-height:80px; max-width:220px; border:1px dashed #6c757d; border-radius:6px; padding:6px; object-fit:contain;">
                    </div>
                    <button type="submit" class="btn btn-info btn-block">
                      <i class="fas fa-upload mr-1"></i> Upload Logo
                    </button>
                  </form>
                </div>
              </div>
            </div>

            <!-- Icon / Favicon upload -->
            <div class="col-md-5">
              <div class="card card-outline card-warning">
                <div class="card-header"><strong><i class="fas fa-star mr-1"></i> Browser Tab Icon (Favicon)</strong></div>
                <div class="card-body text-center">
                  <div class="mb-3">
                    <p class="text-muted small mb-2">Current icon:</p>
                    <img id="currentIcon"
                         src="<?php echo base_url($settings['icon_path'] ?? 'assets/images/dco.ico'); ?>"
                         alt="Current Icon"
                         style="width:64px; height:64px; border:1px solid #dee2e6; border-radius:6px; padding:6px; background:#f8f9fa; object-fit:contain;">
                  </div>
                  <hr>
                  <form id="iconForm" enctype="multipart/form-data">
                    <div class="form-group">
                      <label class="font-weight-bold">Upload New Icon</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="iconFile" name="icon"
                               accept=".ico,.png,.jpg,.jpeg,.gif,.svg" onchange="previewFile(event,'iconPreview','iconPreviewWrap','iconFileLabel')">
                        <label class="custom-file-label" id="iconFileLabel" for="iconFile">Choose file...</label>
                      </div>
                      <small class="form-text text-muted">ICO, PNG — Max 512KB. Use 32×32 or 64×64 px.</small>
                    </div>
                    <div id="iconPreviewWrap" class="mb-3" style="display:none;">
                      <img id="iconPreview" src="#" alt="Preview"
                           style="width:64px; height:64px; border:1px dashed #6c757d; border-radius:6px; padding:6px; object-fit:contain;">
                    </div>
                    <button type="submit" class="btn btn-warning btn-block">
                      <i class="fas fa-upload mr-1"></i> Upload Icon
                    </button>
                  </form>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- ══════════════════════════════════════════════ -->
        <!-- TAB 4: THEME                                  -->
        <!-- ══════════════════════════════════════════════ -->
        <div class="tab-pane fade" id="theme" role="tabpanel">
          <form id="themeForm">
            <div class="row">

              <!-- Sidebar color -->
              <div class="col-md-6">
                <div class="card card-outline card-purple">
                  <div class="card-header"><strong><i class="fas fa-sidebar mr-1"></i> Sidebar Color</strong></div>
                  <div class="card-body">
                    <p class="text-muted small">Current: <code id="currentSidebarLabel"><?php echo htmlspecialchars($settings['theme_color'] ?? 'sidebar-light-primary'); ?></code></p>
                    <div class="row" id="sidebarSwatches">
                      <?php
                      $sidebar_themes = [
                        ['sidebar-light-primary',  '#007bff', 'Primary (Light)'],
                        ['sidebar-dark-primary',   '#0056b3', 'Primary (Dark)'],
                        ['sidebar-light-success',  '#28a745', 'Success (Light)'],
                        ['sidebar-dark-success',   '#155724', 'Success (Dark)'],
                        ['sidebar-light-info',     '#17a2b8', 'Info (Light)'],
                        ['sidebar-dark-info',      '#0c525d', 'Info (Dark)'],
                        ['sidebar-light-warning',  '#ffc107', 'Warning (Light)'],
                        ['sidebar-dark-warning',   '#856404', 'Warning (Dark)'],
                        ['sidebar-light-danger',   '#dc3545', 'Danger (Light)'],
                        ['sidebar-dark-danger',    '#721c24', 'Danger (Dark)'],
                        ['sidebar-light-indigo',   '#6610f2', 'Indigo (Light)'],
                        ['sidebar-dark-indigo',    '#3d0a91', 'Indigo (Dark)'],
                        ['sidebar-light-navy',     '#001f3f', 'Navy (Light)'],
                        ['sidebar-dark-navy',      '#00111a', 'Navy (Dark)'],
                        ['sidebar-light-purple',   '#6f42c1', 'Purple (Light)'],
                        ['sidebar-dark-purple',    '#3d1f7a', 'Purple (Dark)'],
                        ['sidebar-light-teal',     '#20c997', 'Teal (Light)'],
                        ['sidebar-dark-teal',      '#0d6e52', 'Teal (Dark)'],
                        ['sidebar-light-cyan',     '#17a2b8', 'Cyan (Light)'],
                        ['sidebar-dark-cyan',      '#0a5561', 'Cyan (Dark)'],
                      ];
                      $current_theme = $settings['theme_color'] ?? 'sidebar-light-primary';
                      foreach ($sidebar_themes as $t):
                      ?>
                      <div class="col-3 mb-2 text-center">
                        <div class="theme-swatch sidebar-swatch <?php echo ($current_theme === $t[0]) ? 'swatch-active' : ''; ?>"
                             data-value="<?php echo $t[0]; ?>"
                             style="background:<?php echo $t[1]; ?>;"
                             title="<?php echo $t[2]; ?>"
                             onclick="selectSwatch('sidebar', this)">
                          <?php if ($current_theme === $t[0]): ?>
                            <i class="fas fa-check text-white" style="font-size:18px;"></i>
                          <?php endif; ?>
                        </div>
                        <small style="font-size:10px;"><?php echo $t[2]; ?></small>
                      </div>
                      <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="theme_color" id="theme_color_input"
                           value="<?php echo htmlspecialchars($current_theme); ?>">
                  </div>
                </div>
              </div>

              <!-- Navbar color -->
              <div class="col-md-6">
                <div class="card card-outline card-indigo">
                  <div class="card-header"><strong><i class="fas fa-bars mr-1"></i> Navbar Color</strong></div>
                  <div class="card-body">
                    <?php
                    $navbar_themes = [
                      ['navbar-dark navbar-primary',   '#007bff', 'Primary'],
                      ['navbar-dark navbar-secondary',  '#6c757d', 'Secondary'],
                      ['navbar-dark navbar-info',       '#17a2b8', 'Info'],
                      ['navbar-dark navbar-success',    '#28a745', 'Success'],
                      ['navbar-dark navbar-danger',     '#dc3545', 'Danger'],
                      ['navbar-dark navbar-warning',    '#ffc107', 'Warning'],
                      ['navbar-dark navbar-dark',       '#343a40', 'Dark'],
                      ['navbar-light navbar-light',     '#f8f9fa', 'Light'],
                      ['navbar-dark navbar-navy',       '#001f3f', 'Navy'],
                      ['navbar-dark navbar-teal',       '#20c997', 'Teal'],
                      ['navbar-dark navbar-cyan',       '#17a2b8', 'Cyan'],
                      ['navbar-dark navbar-indigo',     '#6610f2', 'Indigo'],
                    ];
                    $current_navbar = $settings['navbar_color'] ?? 'navbar-dark navbar-light';
                    ?>
                    <p class="text-muted small">Current: <code id="currentNavbarLabel"><?php echo htmlspecialchars($current_navbar); ?></code></p>
                    <div class="row" id="navbarSwatches">
                      <?php foreach ($navbar_themes as $n): ?>
                      <div class="col-3 mb-2 text-center">
                        <div class="theme-swatch navbar-swatch <?php echo ($current_navbar === $n[0]) ? 'swatch-active' : ''; ?>"
                             data-value="<?php echo $n[0]; ?>"
                             style="background:<?php echo $n[1]; ?>;"
                             title="<?php echo $n[2]; ?>"
                             onclick="selectSwatch('navbar', this)">
                          <?php if ($current_navbar === $n[0]): ?>
                            <i class="fas fa-check text-white" style="font-size:18px;"></i>
                          <?php endif; ?>
                        </div>
                        <small style="font-size:10px;"><?php echo $n[2]; ?></small>
                      </div>
                      <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="navbar_color" id="navbar_color_input"
                           value="<?php echo htmlspecialchars($current_navbar); ?>">
                  </div>
                </div>
              </div>

            </div><!-- /.row -->
            <div class="text-right">
              <button type="submit" class="btn btn-purple px-4">
                <i class="fas fa-paint-brush mr-1"></i> Apply Theme
              </button>
            </div>
          </form>
        </div>

      </div><!-- /.tab-content -->
    </div><!-- /.container-fluid -->
  </div><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- Swatch styles -->
<style>
  .theme-swatch {
    width: 100%;
    padding-top: 60%;
    border-radius: 6px;
    cursor: pointer;
    border: 3px solid transparent;
    position: relative;
    transition: transform .15s, border-color .15s;
    display: flex; align-items: center; justify-content: center;
  }
  .theme-swatch:hover { transform: scale(1.07); }
  .theme-swatch.swatch-active { border-color: #000 !important; box-shadow: 0 0 0 2px #fff inset; }
  .theme-swatch i { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); }
  .btn-purple { background-color: #6f42c1; color: #fff; border-color: #6f42c1; }
  .btn-purple:hover { background-color: #5a32a3; color: #fff; }
  .card-outline.card-indigo { border-top-color: #6610f2 !important; }
  .card-outline.card-purple { border-top-color: #6f42c1 !important; }
</style>

<?php $this->load->view('components/footer'); ?>
<script src="<?php echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/confirm/jquery-confirm.min.js'); ?>"></script>

<script>
toastr.options = {
  newestOnTop: true, showDuration: 300, hideDuration: 1000,
  showEasing: 'swing', hideEasing: 'swing',
  showMethod: 'slideDown', hideMethod: 'slideUp'
};

/* ── Templates ──────────────────────────────────────────────── */
$('#templateForm').submit(function(e) {
  e.preventDefault();
  $.ajax({
    type: 'post',
    url: '<?php echo base_url("superadmin/save_templates"); ?>',
    data: $(this).serialize(),
    dataType: 'json',
    success: function(r) {
      r.success ? toastr.success(r.msg) : toastr.error(r.msg);
    }
  });
});

/* ── Admin roles ─────────────────────────────────────────────── */
function doSetRole(userId, newType) {
  $.ajax({
    type: 'post',
    url: '<?php echo base_url("superadmin/set_admin"); ?>',
    data: { user_id: userId, new_type: newType },
    dataType: 'json',
    success: function(r) {
      if (r.success) {
        toastr.success(r.msg);
        setTimeout(function(){ location.reload(); }, 1200);
      } else {
        toastr.error(r.msg);
      }
    },
    error: function() {
      toastr.error('Request failed. Please try again.');
    }
  });
}

function setRole(userId, newType, name) {
  var label = newType === 3 ? 'Super Admin' : (newType === 2 ? 'Admin' : 'Regular User');
  $.confirm({
    theme: 'bootstrap',
    title: 'Change Role',
    content: 'Set <strong>' + name + '</strong> as <strong>' + label + '</strong>?',
    buttons: {
      confirm: {
        btnClass: 'btn-primary',
        action: function() { doSetRole(userId, newType); }
      },
      cancel: { btnClass: 'btn-default' }
    }
  });
}

function promoteUser() {
  var userId = parseInt($('#promoteUserId').val());
  var roleId = parseInt($('#promoteRoleId').val());
  var name   = $('#promoteUserId option:selected').text().trim();
  if (!userId) { toastr.warning('Please select a user.'); return; }
  setRole(userId, roleId, name);
}

/* ── Logo & Icon ─────────────────────────────────────────────── */
function previewFile(e, previewId, wrapId, labelId) {
  var file = e.target.files[0];
  if (!file) return;
  $('#' + wrapId).show();
  $('#' + previewId).attr('src', URL.createObjectURL(file));
  $('#' + labelId).text(file.name);
}

$('#logoForm').submit(function(e) {
  e.preventDefault();
  var formData = new FormData(this);
  $.ajax({
    type: 'post',
    url: '<?php echo base_url("superadmin/upload_logo"); ?>',
    data: formData,
    contentType: false,
    processData: false,
    dataType: 'json',
    success: function(r) {
      if (r.success) {
        toastr.success(r.msg);
        var newSrc = '<?php echo base_url(); ?>' + r.path + '?t=' + Date.now();
        // Update preview card
        $('#currentLogo').attr('src', newSrc);
        // Update sidebar brand image in real-time
        $('img.brand-image').attr('src', newSrc);
        // Reset form
        $('#logoForm')[0].reset();
        $('#logoPreviewWrap').hide();
        $('#logoFileLabel').text('Choose file...');
      } else {
        toastr.error(r.msg);
      }
    },
    error: function() { toastr.error('Upload failed. Please try again.'); }
  });
});

$('#iconForm').submit(function(e) {
  e.preventDefault();
  var formData = new FormData(this);
  $.ajax({
    type: 'post',
    url: '<?php echo base_url("superadmin/upload_icon"); ?>',
    data: formData,
    contentType: false,
    processData: false,
    dataType: 'json',
    success: function(r) {
      if (r.success) {
        toastr.success(r.msg);
        var newSrc = '<?php echo base_url(); ?>' + r.path + '?t=' + Date.now();
        // Update preview card
        $('#currentIcon').attr('src', newSrc);
        // Update browser tab favicon in real-time
        $('link[rel="icon"]').remove();
        $('<link rel="icon" type="image/png">').attr('href', newSrc).appendTo('head');
        // Reset form
        $('#iconForm')[0].reset();
        $('#iconPreviewWrap').hide();
        $('#iconFileLabel').text('Choose file...');
      } else {
        toastr.error(r.msg);
      }
    },
    error: function() { toastr.error('Upload failed. Please try again.'); }
  });
});

/* ── Theme swatches ──────────────────────────────────────────── */
function selectSwatch(type, el) {
  var val = $(el).data('value');
  if (type === 'sidebar') {
    $('.sidebar-swatch').removeClass('swatch-active').find('i').remove();
    $(el).addClass('swatch-active').append('<i class="fas fa-check text-white" style="font-size:18px;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none;"></i>');
    $('#theme_color_input').val(val);
    $('#currentSidebarLabel').text(val);
  } else {
    $('.navbar-swatch').removeClass('swatch-active').find('i').remove();
    $(el).addClass('swatch-active').append('<i class="fas fa-check text-white" style="font-size:18px;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none;"></i>');
    $('#navbar_color_input').val(val);
    $('#currentNavbarLabel').text(val);
  }
}

$('#themeForm').submit(function(e) {
  e.preventDefault();
  $.ajax({
    type: 'post',
    url: '<?php echo base_url("superadmin/save_theme"); ?>',
    data: $(this).serialize(),
    dataType: 'json',
    success: function(r) {
      if (r.success) {
        toastr.success(r.msg + ' — Applying...');
        // Apply sidebar theme live
        var sidebarVal = $('#theme_color_input').val();
        var navbarVal  = $('#navbar_color_input').val();
        var $aside = $('aside.main-sidebar');
        // Remove all sidebar-* classes and add the new one
        $aside.removeClass(function(i, cls) {
          return (cls.match(/sidebar-(light|dark)-\S+/g) || []).join(' ');
        }).addClass(sidebarVal);
        // Apply navbar color live
        var $nav = $('nav.main-header');
        $nav.removeClass(function(i, cls) {
          return (cls.match(/navbar-(dark|light|primary|secondary|info|success|danger|warning|navy|teal|cyan|indigo)\S*/g) || []).join(' ');
        }).addClass(navbarVal);
        setTimeout(function(){ location.reload(); }, 1000);
      } else {
        toastr.error(r.msg);
      }
    }
  });
});
</script>

</body>
</html>