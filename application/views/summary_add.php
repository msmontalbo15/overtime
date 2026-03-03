<h5>Add Summary</h5>
<hr />

<form id="myform">
	<div class="form-row">
		<div class="form-group col-md-12">
			<label for="month">Month</label>
			<input type="month" class="form-control form-control-sm" id="month" name="month" autocomplete="off">
		</div>
	</div>

	<div class="form-row">
		<div class="form-group col-md-12">
			<label for="tags">Activities</label>
			<input type="text" class="form-control form-control-sm" id="tags" name="tags" autocomplete="off">
		</div>
	</div>

	<div class="form-row">
		<div class="form-group col-md-12">
			<div class="table-responsive" style="max-height: 200px;">
				<?php
				error_reporting(0);
				$list_users = array_column($details, 'list_users');
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
			<button type="submit" class="btn btn-success btn-sm">Submit</button>
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
		$('button[type=submit]').prop('disabled', true);
		formData = $('#myform').serialize();
		$.ajax({
			type: 'post',
			url: "<?php echo base_url("summary/add_process"); ?>",
			data: formData,
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					$("#myform")[0].reset();
					$("#tags").removeTag();
					$('#form_modal').modal('toggle');
					toastr.success(response.msg);
					loadLists();
				} else {
					toastr.error(response.msg);
				}
				$('button[type=submit]').prop('disabled', false);
			}
		});
	});


	function onload() {
		const select_month = document.querySelector("#month");
		select_month.addEventListener("change", () => {

			alert("Selected Date: " + select_month.value);

		});
	}
	//onload();
</script>
