<h5>Add User</h5>
<hr />

<form id="myform">
	<div class="form-row">
		<div class="form-group col-md-6">
			<label for="username">Username</label>
			<input type="text" class="form-control form-control-sm" id="username" name="username" autocomplete="off">
		</div>
		<div class="form-group col-md-6">
			<label for="password">Password</label>
			<input type="password" class="form-control form-control-sm" id="password" name="password" autocomplete="off">
		</div>
	</div>
	<hr />

	<div class="form-row">
		<div class="form-group col-md-5">
			<label for="fname">First Name</label>
			<input type="text" class="form-control form-control-sm" id="fname" name="fname" autocomplete="off">
		</div>
		<div class="form-group col-md-5">
			<label for="lname">Last Name</label>
			<input type="text" class="form-control form-control-sm" id="lname" name="lname" autocomplete="off">
		</div>
		<div class="form-group col-md-2">
			<label for="mname">M.I</label>
			<input type="text" class="form-control form-control-sm" id="mname" name="mname" autocomplete="off">
		</div>
	</div>
	<div class="form-row">
		<div class="form-group col-md-12">
			<label for="position">Position</label>
			<input type="text" class="form-control form-control-sm" id="position" name="position" autocomplete="off">
		</div>
	</div>
	<div class="form-row">
		<div class="form-group col-md-12">
			<label for="grade">Salary Grade</label>
			<input type="number" maxlength="2" class="form-control form-control-sm" id="grade" name="grade" autocomplete="off">
		</div>
	</div>
	<hr />
	<div class="form-group row">
		<div class="col-md-12 text-right">
			<button type="submit" class="btn btn-success btn-sm">Submit</button>
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
		$('button[type=submit]').prop('disabled', true);
		formData = $('#myform').serialize();
		$.ajax({
			type: 'post',
			url: "<?php echo base_url("user/add_process"); ?>",
			data: formData,
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					toastr.success(response.msg);
					$("#myform")[0].reset();
					$('#form_modal').modal('toggle');
					table.ajax.reload();
				} else {
					toastr.error(response.msg);
				}
				$('button[type=submit]').prop('disabled', false);
			}
		});
	});
</script>