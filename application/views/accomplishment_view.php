<?php
error_reporting(0);
$this->load->view('components/header');
$this->load->view('components/navbar');
$this->load->view('components/sidebar');
//$this->accomplishment->selectAccomplishment($this->_user_id);
?>


<?php $this->load->view('components/table_style'); ?>
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/confirm/jquery-confirm.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/toastr/toastr.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/datepicker/css/daterangepicker.css'); ?>">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<h1 class="m-0 text-dark float-left">Accomplishment</h1>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-9">
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table id="tbl_data" class="table table-striped">
									<thead>
										<tr>
											<th>ID</th>
											<th style="width: 15%">Date</th>
											<th style="width: 20%">Time In/Out</th>
											<th style="width: 5%">Rendered</th>
											<th>Remarks</th>
											<th style="width: 10%">Day</th>
											<th style="width: 10%">Action</th>
										</tr>
									</thead>

								</table>
							</div>
						</div>
					</div>


				</div>

				<!-- /.col-md-9 -->
				<div class="col-sm-3">
					<div class="card">
						<div class="card-body">
							<form id="addForm" onsubmit="submitEntry(event)">
								<div class="form-row">
									<div class="form-group col-lg-12">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="wfh" name="wfh">
											<label class="form-check-label" for="wfh">Work From Home</label>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-lg-12">
										<label for="date">Date</label>
										<input type="date" class="form-control form-control-sm" id="date" name="date" autocomplete="off" value="<?php echo set_value('date'); ?>">
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-lg-12">
										<label for="time">Time In/Out</label>
										<input type="text" class="form-control form-control-sm time" id="time" name="time" autocomplete="off" value="05:00 PM - 07:00 PM">
									</div>
								</div>

								<div class="form-row">
									<div class="form-group col-md-12">
										<label for="weekend">Day</label>
										<select class="form-control form-control-sm" id="weekend" name="weekend">
											<?php

											$option = '';
											$array_data = array(
												'regular',
												'saturday',
												'sunday',
												'holiday',
												'work suspension'
											);

											foreach ($array_data as $key => $value) {
												if (set_value('weekend')) {
													if (set_value('weekend') == $value) {
														$option .= "<option value='$value' selected>" . ucwords($value) . "</option>";
													} else {
														$option .= "<option value='$value'>" . ucwords($value) . "</option>";
													}
												} else {
													$option .= "<option value='$value'>" . ucwords($value) . "</option>";
												}
											}

											echo $option;
											?>
										</select>
									</div>
								</div>

								<div class="form-row">
									<div class="form-group col-md-12">
										<label for="remarks">Remarks</label>
										<textarea class="form-control form-control-sm" id="remarks" name="remarks"><?php echo set_value('remarks'); ?></textarea>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-sm-12">
										<button type="submit" class="btn btn-success btn-block btn-sm">Add</button>
									</div>
								</div>
							</form>
						</div>

					</div>
					<div class="card">
						<div class="col-sm-12">
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="remarks">Purpose</label>
									<p>
										<?php
										echo "<p>: " . $data['purpose'] . "</p>";
										?>
									</p>
								</div>
							</div><!-- /.row -->
						</div><!-- /.container-fluid -->
						<!-- <div class="card-body">
							<button class="btn btn-primary btn-sm float-right" onclick="loadForm('<?php echo base_url("purpose/add"); ?>')"><i class="fas fa-plus"></i> Edit Purpose</button>
						</div> -->
					</div>

					<!-- Computation -->
					<div class="card" display="none">
						<div class="card-body">
							<label class="form-check-label font-weight-bold mb-2" for="wfh">OT Rendered</label>
							<div class="container-fluid" id="render_details">
								<p id="render_month">Month: <?php echo $data['month']; ?></p>
								<p id="render_hours">Total hours: <?php echo $data['hours_render']; ?></p>
								<p id="render_pay">Total pay: ₱<?php echo number_format($data['pay'], 2, '.', ','); ?></p>
							</div>
						</div>
					</div>
					<!-- Computation -->
				</div>

				<!-- Total Hours -->
				<form id='renderForm' action="" method="post">
					<div class="form-row">
						<div class="form-group col-md-12">
							<select class="form-control form-control-sm" id="month" name="month">
								<?php
								$curr_month = date('F', mktime(0, 0, 0, date('n')));
								$months = array();
								for ($i = 1; $i <= 5; $i++) {
									$months[] = date('F', mktime(0, 0, 0, date('n') - $i, 1));
								}
								//$months = array_reverse($months, true);
								echo "<option value='$curr_month' selected='selected'>$curr_month</option>";
								foreach ($months as $key => $value) {
									echo "<option value='$value'>$value</option>\n";
								}
								?>
							</select>
						</div>
					</div>
				</form>
				<!-- 

        </div>
        <!-- /.row -->
			</div><!-- /.container-fluid -->
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
</div>
<?php $this->load->view('components/footer'); ?>

<?php $this->load->view('components/table_script'); ?>
<script src="<?php echo base_url('assets/plugins/confirm/jquery-confirm.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datepicker/js/moment.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datepicker/js/daterangepicker.min.js'); ?>"></script>
<script type="text/javascript">
	//$('#render_details').load(<?php //echo base_url("/rendered_detail") 
								?>);

	$(document).ready(function(e) {

		$("#tbl_data").append('<tfoot><tr><th colspan="2">Total Hours: </th><th id="total_hours"><?php echo $data['hours_render']; ?></th></tr></tfoot>');

		var table = $('#tbl_data').DataTable();

		$(".loading-overlay").css("display", "none");

		//data picker
		$('.mydate').daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			locale: {
				format: 'Y-MM-DD'
			}
		});

		$('.time').daterangepicker({
			timePicker: true,
			timePicker24Hour: false,
			locale: {
				format: 'hh:mm A'
			},
			formatTime: "h:i a",
		}).on('show.daterangepicker', function(ev, picker) {
			picker.container.find(".calendar-table").hide();
		});
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

	/*DATA TABLE*/
	url = "<?php echo base_url('accomplishment/data'); ?>"
	config = {
		"order": [
			[0, "desc"]
		],
		"ajax": {
			"url": url,
		},
		"columns": [{
				"data": "id"
			},
			{
				"data": "date"
			},
			{
				"data": "time"
			},
			{
				"data": "render"
			},
			{
				"data": "remarks"
			},
			{
				"data": "day"
			},
			{
				"data": "button"
			},
		],
		"columnDefs": [{
				"targets": [0],
				"visible": false,
				"searchable": false
			},
			{
				"targets": [4],
				"searchable": false
			},
		],
		"responsive": true,
		"autoWidth": false,
		"searchBuilder": true,
	}

	table = $("#tbl_data").DataTable(config);

	function loadForm(url) {
		$('#load_form').load(url);
		$('#form_modal').modal('show');
	}

	/*
	 * Shared helper — fetches OT totals for the currently selected month
	 * and updates the OT Rendered card in real time.
	 * Called after add, delete, update, and month-change.
	 */
	function refreshRenderCard() {
		var month = $('#month').val();
		$.ajax({
			type: 'post',
			url: "<?php echo base_url('accomplishment/selectrender'); ?>",
			data: { month: month },
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					$('#render_month').text('Month: ' + response.month);
					$('#render_hours').text('Total hours: ' + response.hours_render);
					$('#render_pay').text('Total pay: ₱' + response.pay);
					$('#total_hours').text(response.hours_render);
				}
			}
		});
	}

	$(document).ready(function() {

		const table_header = document.querySelector("#tbl_data_length");
		const select_month = document.querySelector("#month");
		const search = document.querySelector("#tbl_data_filter input[type='search']");
		var curr_year = new Date().getFullYear();

		select_month.addEventListener("change", () => {
			search.value = select_month.value + ' ' + curr_year;
			$(search).keyup();
		});
		search.value = select_month.value + ' ' + curr_year;
		$(search).keyup();

		let html_month =
			"<label id='month_label' for='month' style='margin: 0rem 0.5rem 0.5rem 2rem;font-weight: bold;'>Month: </label>";
		table_header.insertAdjacentHTML("beforeend", html_month);

		$(select_month).appendTo(month_label);
	});

	function submitEntry(e) {
		e.preventDefault();
		$(".loading-overlay").css("display", "block");
		$.ajax({
			type: 'post',
			url: "<?php echo base_url("accomplishment/add"); ?>",
			dataType: 'json',
			data: $('#addForm').serialize(),
			success: function(response) {
				if (response.success) {
					toastr.success(response.msg);
					table.ajax.reload();
					refreshRenderCard(); // <-- update OT Rendered card after add
				} else {
					toastr.error(response.msg);
				}
				$(".loading-overlay").css("display", "none");
			}
		});
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
						url = "<?php echo base_url("accomplishment/delete"); ?>";
						dataString = { id: id }
						$.ajax({
							type: "GET",
							url: url,
							dataType: 'json',
							data: dataString,
							success: function(response) {
								toastr.info(response.msg);
								table.ajax.reload();
								refreshRenderCard(); // <-- update OT Rendered card after delete
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

	var render_form = $('#renderForm');
	$("[id='month']").on('change', function(e) {
		$(render_form).submit();
	});

	$(render_form).submit(function(e) {
		e.preventDefault();
		var month = $('#month').val();
		var curr_year = new Date().getFullYear();
		var search = document.querySelector("#tbl_data_filter input[type='search']");
		// Filter the table by the selected month
		search.value = month + ' ' + curr_year;
		$(search).keyup();
		// Refresh the OT Rendered card
		refreshRenderCard(); // <-- update OT Rendered card on month change
	});
</script>
</body>

</html>