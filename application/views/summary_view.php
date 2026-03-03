<?php
$this->load->view('components/header');
$this->load->view('components/navbar');
$this->load->view('components/sidebar');
?>

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/confirm/jquery-confirm.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/toastr/toastr.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/jquery.tagsinput.min.css'); ?>">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<h1 class="m-0 text-dark float-left">Summary</h1>
					<button class="btn btn-success btn-sm float-right" onclick="loadForm('<?php echo base_url("summary/add"); ?>')"><i class="fas fa-plus"></i> Summary</button>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="content">
		<div class="container-fluid" id="load_data">


		</div><!-- /.container-fluid -->
		<div class="container-fluid">
			<div class='row'>
				<div class='col-sm-12'>
					<div id='paging'></div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal" role="dialog" tabindex="2" id="form_modal">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div id="load_form"></div>
			</div>
		</div>
	</div>
</div>


<div class="modal" role="dialog" tabindex="2" id="print_modal">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div id="load_print"></div>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('components/footer'); ?>
<?php $this->load->view('components/table_script'); ?>
<script src="<?php echo base_url('assets/plugins/confirm/jquery-confirm.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.tagsinput.min.js'); ?>"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$(".loading-overlay").css("display", "none");
	});

	toastr.options = {
		"newestOnTop": true,
		"showDuration": "300",
		"hideDuration": "1000",
		"showEasing": "swing",
		"hideEasing": "swing",
		"showMethod": "slideDown",
		"hideMethod": "slideUp"
	}
	page = '';

	function loadLists() {
		url = "<?php echo base_url('summary/list/'); ?>";
		$.ajax({
			type: "GET",
			url: url,
			dataType: 'json',
			data: {
				page: page
			},
			success: function(response) {
				$('#load_data').hide().html(response.data).fadeIn();
				$('#paging').hide().html(response.paging).fadeIn();
			}
		});
	}

	loadLists();

	function printSummary(e, id) {
		e.preventDefault();
		formData = $("#printSummary-" + id).serialize();
		$.ajax({
			type: 'get',
			url: "<?php echo base_url("summary/print_process"); ?>",
			data: formData,
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					print_link = "<?php echo base_url('summary/'); ?>" + response.params;
					$('#load_print').load(print_link);
					$('#print_modal').modal('show');
				} else {
					toastr.error(response.msg);
				}

			}
		});
	}

	$('#paging').on('click', 'a', function(e) {
		e.preventDefault();
		page = $(this).attr('data-ci-pagination-page');
		loadLists()
	});

	function loadForm(url) {
		$('#load_form').load(url);
		$('#form_modal').modal('show');
	}


	function deleteItem(id) {
		$.confirm({
			theme: 'bootstrap',
			title: 'Delete Permanent',
			content: 'Are you sure you want to delete?',
			buttons: {
				confirm: {
					btnClass: 'btn-danger',
					action: function() {
						$(".loading-overlay").css("display", "block");
						url = "<?php echo base_url("summary/delete"); ?>";
						dataString = {
							id: id
						}
						$.ajax({
							type: "GET",
							url: url,
							dataType: 'json',
							data: dataString,
							success: function(response) {
								toastr.info(response.msg);
								$('#div-' + id).remove();
								$(".loading-overlay").css("display", "none");
							}
						});
					}
				},
				cancel: {
					btnClass: 'btn-default'
				},
			}
		});
	}
</script>


</body>

</html>