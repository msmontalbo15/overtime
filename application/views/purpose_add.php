<h5>Add Purpose</h5>
<hr />

<form id="myform">
	<div class="form-row">
		<div class="form-group col-md-6">
			<!-- <label for="username">Username</label> -->
			<textarea class="form-control form-control-sm" id="remarks" name="remarks"><?php echo set_value('remarks'); ?></textarea>
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
			url: "<?php echo base_url("purpose/add_process"); ?>",
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