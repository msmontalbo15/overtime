<?php
$class  = $this->router->fetch_class();
$method = $this->router->fetch_method();
// These vars are injected by header.php via $CI->load->vars()
// They are available as $this->load->get_vars() or directly as PHP vars in view scope
if (!isset($_logo_path))    $_logo_path   = 'assets/images/dco.png';
if (!isset($_theme_color))  $_theme_color = 'sidebar-light-primary';
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar <?php echo htmlspecialchars($_theme_color); ?> elevation-3">
	<!-- Brand Logo -->
		<a href="<?php echo base_url(); ?>">
		<img src="<?php echo base_url(htmlspecialchars($_logo_path)); ?>" alt="Logo" class="img-fluid" style="max-width:100%;"  width:33px; height:33px; object-fit:cover;">
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

				<?php if ($this->session->userdata('overtime_type') >= 2): ?>
				<li class="nav-item">
					<a href="<?php echo base_url('summary'); ?>" class="nav-link <?php echo ($class == 'summary') ? 'active' : ''; ?>">
						<i class="nav-icon fas fa-clock"></i>
						<p>Monthly Summary</p>
					</a>
				</li>
				<?php endif; ?>

				<li class="nav-item">
					<a href="<?php echo base_url('accomplishment'); ?>" class="nav-link <?php echo ($class == 'accomplishment') ? 'active' : ''; ?>">
						<i class="nav-icon fas fa-user-clock"></i>
						<p>Accomplishment</p>
					</a>
				</li>

				<li class="nav-item">
					<a href="<?php echo base_url('request'); ?>" class="nav-link <?php echo ($class == 'request' || $class == 'item') ? 'active' : ''; ?>">
						<i class="nav-icon fas fa-print"></i>
						<p>Individual Request</p>
					</a>
				</li>

				<?php if ($this->session->userdata('overtime_type') >= 2): ?>
				<li class="nav-item">
					<a href="<?php echo base_url('user'); ?>" class="nav-link <?php echo ($class == 'user') ? 'active' : ''; ?>">
						<i class="nav-icon fas fa-user"></i>
						<p>User</p>
					</a>
				</li>
				<?php endif; ?>

				<?php if ($this->session->userdata('overtime_type') == 3): ?>
				<li class="nav-item">
					<a href="<?php echo base_url('superadmin'); ?>" class="nav-link <?php echo ($class == 'superadmin') ? 'active' : ''; ?>">
						<i class="nav-icon fas fa-shield-alt text-danger"></i>
						<p>Super Admin</p>
					</a>
				</li>
				<?php endif; ?>

			</ul>
		</nav>
	</div>
</aside>