<h5>Edit Summary</h5>
<hr />

<form id="myform">
	<input type="hidden" name="summary_id" value="<?php echo $details->id; ?>" />
	<div class="form-row">
		<div class="form-group col-md-12">
			<label for="month">Month</label>
			<input type="month" class="form-control form-control-sm" id="month" name="month" autocomplete="off" value="<?php echo date('Y-m', strtotime($details->request_date)); ?>">
		</div>
	</div>

	<div class="form-row">
		<div class="form-group col-md-12">
			<label for="tags">Activities</label>
			<input type="text" class="form-control form-control-sm" id="tags" name="tags" autocomplete="off" value="<?php echo $details->activities; ?>">
		</div>
	</div>

	<div class="form-row">
		<div class="form-group col-md-12">
			<div class="table-responsive" style="max-height: 200px;">
				<?php
				$output = "";
				$list_users = json_decode($details->list_users);

				if ($users) {
					foreach ($users as $key => $value) {
						$id    = $value->id;
						$fname = strtoupper($value->first_name);
						$lname = strtoupper($value->last_name);
						$name  = $lname . ', ' . $fname;

						$ischecked = '';
						if (in_array($id, $list_users)) {
							$ischecked = 'checked';
						}
						$output .= "<div class='form-check'>
                            <input type='checkbox' class='form-check-input' id='$id' value='$id' name='users[]' $ischecked>
                            <label class='form-check-label' for='$id'>$name</label>
                          </div>";
					}
				}

				echo $output;
				?>

			</div>
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
	});

	$('#tags').tagsInput({
		'width': '100%',
		'height': 'auto',
		'delimiter': ['|'],
	});

	$('#myform').submit(function(e) {
		e.preventDefault();
		$(".loading-overlay").css("display", "block");
		formData = $('#myform').serialize();
		$.ajax({
			type: 'post',
			url: "<?php echo base_url("summary/update_process"); ?>",
			data: formData,
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					$('#form_modal').modal('toggle');
					toastr.info(response.msg);
					loadLists();
				} else {
					toastr.error(response.msg);
				}
				$(".loading-overlay").css("display", "none");
			}
		});
	});
</script>