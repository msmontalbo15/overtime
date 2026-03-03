<?php
$this->load->view('components/header');
$this->load->view('components/navbar');
$this->load->view('components/sidebar');
?>

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/toastr/toastr.min.css'); ?>">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<h1 class="m-0 text-dark float-left">Individual Request</h1>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-4">
					<div class="card">
						<div class="card-body">
							<form id="requestForm" onsubmit="printRequest(event)">

								<div class="form-row">
									<div class="form-group col-lg-12">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="wfh" name="wfh">
											<label class="form-check-label" for="wfh">Work From Home</label>
										</div>
									</div>
								</div>

								<?php
								if ($this->session->userdata('overtime_type') == 2) {
								?>
									<div class="form-row">
										<div class="form-group col-md-12">
											<label for="month">User</label>
											<select class="form-control form-control-sm" name="employee">
												<?php
												$output = '';
												if ($users) {
													foreach ($users as $key => $value) {
														$id    = $value->id;
														$fname = strtoupper($value->first_name);
														$lname = strtoupper($value->last_name);
														$output .= "<option value='$id'>$lname, $fname</option>";
													}
												}
												echo $output;
												?>
											</select>
										</div>
									</div>
								<?php
								}
								?>


								<div class="form-row">
									<div class="form-group col-md-12">
										<label for="month">Month & Year</label>
										<input type="month" class="form-control form-control-sm" id="month" name="month" autocomplete="off" value="<?php echo set_value('month'); ?>">
									</div>
								</div>

								<div class="form-row">
									<div class="form-group col-md-12">
										<label for="date">Date</label>
										<input type="date" class="form-control form-control-sm" id="date" name="date" autocomplete="off" value="<?php echo (set_value('date')) ? set_value('date') : date('Y-m-d'); ?>">
									</div>
								</div>

								<div class="form-row">
									<div class="form-group col-sm-12">
										<button type="submit" class="btn btn-secondary btn-sm"><i class="nav-icon fas fa-print"></i> Print</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
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
<script src="<?php echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>

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

	function printRequest(e) {
		e.preventDefault();
		formData = $("#requestForm").serialize();
		$.ajax({
			type: 'get',
			url: "<?php echo base_url("request/print_process"); ?>",
			data: formData,
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					print_link = "<?php echo base_url('request/'); ?>" + response.params;
					$('#load_print').load(print_link);
					$('#print_modal').modal('show');
				} else {
					toastr.error(response.msg);
				}

			}
		});
	}
</script>


</body>

</html>
