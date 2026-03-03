<h5><?php echo $title; ?></h5>
<hr />

<form id="myform">
	<input type='hidden' name="user_id" id="user_id" value="<?php echo $details->id; ?>">
	<div class="form-row">
		<div class="form-group col-lg-12">
			<div class="form-check">
				<input type="checkbox" class="form-check-input" id="wfh" name="wfh" <?php echo ($details->is_wfh) ? 'checked' : '';  ?>>
				<label class="form-check-label" for="wfh">Work From Home</label>
			</div>
		</div>
	</div>
	<div class="form-row">
		<div class="form-group col-md-6">
			<label for="date">Date</label>
			<input type="date" class="form-control form-control-sm" id="date" name="date" autocomplete="off" value="<?php echo (set_value('date')) ? set_value('date') : $details->overtime_date; ?>">
		</div>
		<div class="form-group col-md-6">
			<label for="time">Time In/Out</label>
			<input type="text" class="form-control form-control-sm time" id="time" name="time" autocomplete="off" value="<?php echo (set_value('time')) ? set_value('time') : "$details->time_in - $details->time_out"; ?>">
		</div>
	</div>

	<div class="form-row">
		<div class="form-group col-md-12">
			<label for="weekend">Day</label>
			<select class="form-control form-control-sm" id="weekend" name="weekend">

				<?php

				$option = '';
				$array_data = array(
					'regular', 'saturday', 'sunday', 'holiday', 'work suspension'
				);

				foreach ($array_data as $key => $value) {
					if (set_value('weekend')) {
						if (set_value('weekend') == $value) {
							$option .= "<option value='$value' selected>" . ucwords($value) . "</option>";
						} else {
							$option .= "<option value='$value'>" . ucwords($value) . "</option>";
						}
					} else {

						if ($details->other_day == $value) {
							$option .= "<option value='$value' selected>" . ucwords($value) . "</option>";
						} else {
							$option .= "<option value='$value'>" . ucwords($value) . "</option>";
						}
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
			<textarea class="form-control form-control-sm" id="remarks" name="remarks"><?php echo (set_value('remarks')) ? set_value('remarks') : $details->remarks; ?></textarea>
		</div>
	</div>

	<hr />
	<div class="form-group row">
		<div class="col-md-12 text-right">
			<button type="submit" class="btn btn-info btn-sm">Update</button>
			<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
		</div>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
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

	$('#myform').submit(function(e) {
		e.preventDefault();
		formData = $('#myform').serialize();
		$.ajax({
			type: 'post',
			url: "<?php echo base_url("accomplishment/update_process"); ?>",
			data: formData,
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					table.ajax.reload();
					$('#form_modal').modal('toggle');
					toastr.info(response.msg);
					refreshRenderCard(); // <-- update OT Rendered card after update
				} else {
					toastr.error(response.msg);
				}
			}
		});
	});
</script>