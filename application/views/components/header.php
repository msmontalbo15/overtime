<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<?php
	// Load dynamic settings from DB
	$_logo_path    = 'assets/images/dco.png';
	$_icon_path    = 'assets/images/dco.ico';
	$_app_title    = 'Digital Communications Office';
	$_theme_color  = 'sidebar-light-primary';
	$_navbar_color = 'navbar-dark navbar-light';
	$CI =& get_instance();
	try {
		$CI->load->model('superadmin_model');
		$_settings     = $CI->superadmin_model->get_all_settings();
		$_logo_path    = !empty($_settings['logo_path'])    ? $_settings['logo_path']    : $_logo_path;
		$_icon_path    = !empty($_settings['icon_path'])    ? $_settings['icon_path']    : $_icon_path;
		$_app_title    = !empty($_settings['app_title'])    ? $_settings['app_title']    : $_app_title;
		$_theme_color  = !empty($_settings['theme_color'])  ? $_settings['theme_color']  : $_theme_color;
		$_navbar_color = !empty($_settings['navbar_color']) ? $_settings['navbar_color'] : $_navbar_color;
	} catch (Exception $e) {
		// system_settings table may not exist yet — use defaults
	}
	// MUST be outside try so it always runs even if DB fails
	$CI->load->vars([
		'_logo_path'    => $_logo_path,
		'_icon_path'    => $_icon_path,
		'_app_title'    => $_app_title,
		'_theme_color'  => $_theme_color,
		'_navbar_color' => $_navbar_color,
	]);
	?>
	<link rel="icon" type="image/png" href="<?php echo base_url(htmlspecialchars($_icon_path)); ?>">

	<title><?php echo htmlspecialchars($_app_title); ?> | <?php echo $title; ?></title>

	<!-- Font Awesome Icons -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/fontawesome-free/css/all.min.css'); ?>">
	<!-- Theme style -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/adminlte/adminlte.min.css'); ?>">

	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/font.css'); ?>">
	<!-- Style -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>

<body class="hold-transition sidebar-mini">
	<div class="wrapper">