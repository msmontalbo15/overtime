<h5>Edit User</h5>
<hr />

<form id="myform">
	<input type='hidden' name="user_id" id="user_id" value="<?php echo $details->id; ?>">
	<div class="form-row">
		<div class="form-group col-md-5">
			<label for="username">Username</label>
			<input type="text" class="form-control form-control-sm" id="username" name="username" autocomplete="off" disabled="" value="<?php echo $details->username; ?>">
		</div>
		<div class="form-group col-md-5">
			<label for="password">Password</label>
			<input type="password" class="form-control form-control-sm" id="password" name="password" autocomplete="off">
		</div>
		<div class="form-group col-md-2">
			<div class="form-check">
				<input class="form-check-input" type="checkbox" name="status" id="status" <?php echo ($details->status) ? 'checked' : ''; ?>>
				<label class="form-check-label" for="status">
					Active
				</label>
			</div>
		</div>

	</div>
	<hr />

	<div class="form-row">
		<div class="form-group col-md-5">
			<label for="fname">First Name</label>
			<input type="text" class="form-control form-control-sm" id="fname" name="fname" autocomplete="off" value="<?php echo $details->first_name; ?>">
		</div>
		<div class="form-group col-md-5">
			<label for="lname">Last Name</label>
			<input type="text" class="form-control form-control-sm" id="lname" name="lname" autocomplete="off" value="<?php echo  $details->last_name; ?>">
		</div>
		<div class="form-group col-md-2">
			<label for="mname">M.I</label>
			<input type="text" class="form-control form-control-sm" id="mname" name="mname" autocomplete="off" value="<?php echo  $details->middle; ?>">
		</div>
	</div>
	<div class="form-row">
		<div class="form-group col-md-12">
			<label for="position">Position</label>
			<input type="text" class="form-control form-control-sm" id="position" name="position" autocomplete="off" value="<?php echo  $details->possition; ?>">
		</div>
	</div>
	<div class="form-row">
		<div class="form-group col-md-12">
			<label for="grade">Salary Grade</label>
			<input type="number" maxlength="2" class="form-control form-control-sm" id="grade" name="grade" autocomplete="off" value="<?php echo  $details->grade; ?>">
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

	$('#myform').submit(function(e) {
		e.preventDefault();
		formData = $('#myform').serialize();
		$.ajax({
			type: 'post',
			url: "<?php echo base_url("user/update_process"); ?>",
			data: formData,
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					table.ajax.reload();
					$('#form_modal').modal('toggle');
					toastr.info(response.msg);
				} else {
					toastr.error(response.msg);
				}
			}
		});
	});
</script>