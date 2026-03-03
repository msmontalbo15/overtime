<?php
// Load dynamic print template settings
$CI =& get_instance();
$CI->load->model('superadmin_model');
$_s = $CI->superadmin_model->get_all_settings();
$_sig_name    = $_s['template_signatory_name']   ?? 'Frances Marion Salazar';
$_sig_title   = $_s['template_signatory_title']  ?? 'Officer-In-Charge';
$_sig_office  = $_s['template_signatory_office'] ?? 'Digital Communications Office';
$_for_name    = $_s['template_summary_for_name']  ?? 'HON. WES GATCHALIAN';
$_for_title   = $_s['template_summary_for_title'] ?? 'City Mayor';
$_app_title   = $_s['app_title'] ?? 'DIGITAL COMMUNICATIONS OFFICE';
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/printStyle.css'); ?>">

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <textarea name="editor1" id="editor1"><p> </p>

<table border="0" cellpadding="1" cellspacing="1" style="width: 100%;">
  <tbody>
    <tr>
      <td style="text-align: center;">
      <div style="letter-spacing:5px;"><span style="font-size:20px;"><strong><?php echo strtoupper(htmlspecialchars($_app_title)); ?></strong></span></div>
      </td>
    </tr>
  </tbody>
</table>

<p> </p>

<table border="0" cellpadding="1" cellspacing="1" style="width: 100%;">
  <tbody>
    <tr>
      <td style="width: 151px;"><span style="font-size:14px;"><strong>FOR</strong></span>
       </td>
      <td style="width: 49px;"><span style="font-size:14px;"><strong>:</strong></span></td>
      <td style="width: 888px;">
      <div style="line-height:1;"><span style="font-size:14px;"><strong><?php echo htmlspecialchars($_for_name); ?></strong><br />
      <?php echo htmlspecialchars($_for_title); ?></span>
       </div>
      </td>
    </tr>
    <tr>
      <td style="width: 151px;"><span style="font-size:14px;"><strong>DATE</strong></span></td>
      <td style="width: 49px;"><span style="font-size:14px;"><strong>:</strong></span></td>
      <td style="width: 888px;"><span style="font-size:14px;"><strong><?php echo date('F d, Y', strtotime($date)); ?></strong></span></td>
    </tr>
    <tr>
      <td style="width: 151px;"><span style="font-size:14px;"><strong>SUBJECT</strong></span>
       </td>
      <td style="width: 49px;"><span style="font-size:14px;"><strong>:</strong></span>
       </td>
      <td style="width: 888px;"><span style="font-size:14px;"><strong>AUTHORITY TO RENDER OVERTIME SERVICES<br/ > FOR THE MONTH OF <?php echo strtoupper(date('F Y', strtotime($details->request_date))); ?></strong></span></td>
    </tr>
  </tbody>
</table>

<hr />
<p style="letter-spacing:1px;"><span style="font-size:12px;">This is to request authority to render the following Digital Communications Unit Staff overtime for the month of <?php echo date('F, Y', strtotime($details->request_date)); ?> including Saturdays and Sundays for the following Series of citywide events and other activities</span></p>

  <?php
	if ($details->activities) {

	?>
     <ol type="A"> 
      <?php
		$result = explode('|', $details->activities);

		foreach ($result as $key => $value) {
		?>
      <li><?php echo $value; ?></li> 
      <?php
		}
		?>
    </ol>             
  <?php
	} else {
		echo '<br>';
	}
	?>
<p><span style="font-size:12px;">Details of the overtime work per each staff as follows</span></p>

<table border="0" cellpadding="1" cellspacing="1" style="width: 100%;">
  <tbody>


            <?php

			if ($results) {
				$count  = 0;
				$output = "";
				foreach ($results as $key => $value) {
					$count = $count + 1;
					$name = $value['name'];
					$logs = $value['logs'];

					$output .= "<tr>
                                <td colspan='5'><span style='font-size:14px;'><strong>$count. $name</strong></span></td>
                              </tr>";


					if ($logs) {
						foreach ($logs as $key => $value) {
							$date       = date('F d, Y', strtotime($value['date']));
							$time_in    =  date('h:iA', strtotime($value['time_in']));
							$time_out   =  date('h:iA', strtotime($value['time_out']));
							$other_day  = ucwords($value['otherday']);
							$remarks    = $value['remarks'];


							$time_in_array = explode(':', $time_in);
							$hr_in       = $time_in_array[0];
							$mins_in     = $time_in_array[1];

							$time_out_array = explode(':', $time_out);
							$hr_out       = $time_out_array[0];
							$mins_out     = $time_out_array[1];



							if ($other_day == 'Regular') {
								//$time_in = '05:00PM';
								$other_day = date('l', strtotime($value['date']));
							} else {
								if ($mins_in >= 30) {
									$time_in = date('h:00A', strtotime('+1 hour', strtotime($value['time_in'])));
								} else {
									$time_in = date('h:00A', strtotime($value['time_in']));
								}
							}
							//$time_out_round =  date('h:00 A', strtotime('+1 hour', strtotime($value['time_out'])));

							if ($mins_out >= 30) {
								$time_out_round = date('h:00A', strtotime('+1 hour', strtotime($value['time_out'])));
							} else {
								$time_out_round = date('h:00A', strtotime($value['time_out']));
							}

							if ($hr_out == '11' && $mins_out >= 30) {
								$time_out_round = '11:' . $mins_out;
							} else if ($hr_out == '11') {
								$time_out_round = '11:00PM';
							}

							if ($time_in == '12:00PM') {
								$time_in = '1:00PM';
							}


							$output .= "<tr>
                            <td style='padding-left:20px;width:237px;'><span style='font-size:14px;'>• $date</span></td>
                            <td style='width: 18px;'><span style='font-size:14px;'>-</span></td>
                            <td style='width:476px;'><span style='font-size:14px;'>$remarks</span></td>
                            <td style='width:260px;'><span style='font-size:14px;'>$time_in to $time_out_round</span></td>
                            <td style='width:82px;'><span style='font-size:14px;'>$other_day</span></td>
                          </tr>";
						}/*logs loop*/
					}/*end if logs*/
				}/*end result logs*/
				echo $output;
			}

			?>

  </tbody>
</table>

<p> </p>

<p><span style="font-size:12px;">For your consideration and approval,</span></p>


<p> </p>

<div style="line-height:1.1;"><span style="font-size:14px;"><strong><?php echo htmlspecialchars($_sig_name); ?></strong></span></div>

<div style="line-height:1.1;"><span style="font-size:12px;"><?php echo htmlspecialchars($_sig_title); ?></span></div>

<div style="line-height:1.1;"><span style="font-size:12px;"><?php echo htmlspecialchars($_sig_office); ?></span></div>
</textarea>
        </div><!-- end col -->
    </div><!-- end row -->
</div><!-- end container -->

<script src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js'); ?>"></script>
<?php $this->load->view('components/ckeditor_script'); ?>