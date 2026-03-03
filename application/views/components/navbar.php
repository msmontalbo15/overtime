<?php
if (!isset($_navbar_color)) $_navbar_color = 'navbar-dark navbar-light';
?>
<!-- Navbar -->
  <nav class="main-header navbar navbar-expand <?php echo htmlspecialchars($_navbar_color); ?>">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="user-dropdwn" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="far fa-user"></i>
          <?php
            if($this->session->has_userdata('overtime_name')){
              echo ucwords($this->session->userdata('overtime_name'));
            }
          ?>
          <?php if($this->session->userdata('overtime_type') == 3): ?>
            <span class="badge badge-danger ml-1" style="font-size:9px;">SUPER ADMIN</span>
          <?php endif; ?>
        </a>

        <div class="dropdown-menu" aria-labelledby="user-dropdwn" style="left: inherit; right: 0px;">
          <a class="dropdown-item" href="<?php echo base_url('logout'); ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </li>
    </ul>

  </nav>

  <!-- /.navbar -->