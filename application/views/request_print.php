<?php
// Load dynamic print template settings
$CI =& get_instance();
$CI->load->model('superadmin_model');
$_s = $CI->superadmin_model->get_all_settings();
$_to_lines       = explode("\r\n", $_s['template_request_header']  ?? "Ms. FLOCERFIDA D. VILLAMAR\r\nOfficer-in-Charge, Human Resources Management Office");
$_through_lines  = explode("\r\n", $_s['template_request_through'] ?? "Ms. FELIZA SALAZAR\r\nHead, Payroll Unit");
$_sig_name       = $_s['template_signatory_name']   ?? 'Frances Marion Salazar';
$_sig_title      = $_s['template_signatory_title']  ?? 'Officer-In-Charge';
$_sig_office     = $_s['template_signatory_office'] ?? 'Digital Communications Office';
$_app_title      = $_s['app_title'] ?? 'DIGITAL COMMUNICATIONS OFFICE';
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
      <td style="width: 151px;"><span style="font-size:14px;"><strong>TO</strong></span><br />
       </td>
      <td style="width: 49px;"><span style="font-size:14px;"><strong>:</strong></span><br />
       </td>
      <td style="width: 888px;">
      <div style="line-height:1;"><span style="font-size:14px;"><?php echo htmlspecialchars($_to_lines[0] ?? ''); ?></strong><br />
      <?php echo htmlspecialchars($_to_lines[1] ?? ''); ?></span><br />
       </div>
      </td>
    </tr>
    <tr>
      <td style="width: 151px;"><span style="font-size:14px;"><strong>THROUGH</strong></span><br />
       </td>
      <td style="width: 49px;"><span style="font-size:14px;"><strong>:</strong></span><br />
       </td>
      <td style="width: 888px;">
      <div style="line-height:1;"><span style="font-size:14px;"><?php echo htmlspecialchars($_through_lines[0] ?? ''); ?></strong><br />
      <?php echo htmlspecialchars($_through_lines[1] ?? ''); ?></span><br />
       </div>
      </td>
    </tr>
    <tr>
      <?php
		if ($wfh) {
			$subject = 'Accomplishment Report for Services rendered during Skeletal Period';
		} else {
			$subject = 'Accomplishment Report for Overtime Services rendered for ' . date('F Y', strtotime($info['month']));
		}
		?>
      <td style="width: 151px;"><span style="font-size:14px;"><strong>SUBJECT</strong></span><br />
       </td>
      <td style="width: 49px;"><span style="font-size:14px;"><strong>:</strong></span><br />
       </td>
      <td style="width: 888px;"><span style="font-size:14px;"><strong><?php echo $subject; ?></strong></span><br />
       </td>
    </tr>
    <tr>
      <td style="width: 151px;"><span style="font-size:14px;"><strong>DATE</strong></span></td>
      <td style="width: 49px;"><span style="font-size:14px;"><strong>:</strong></span></td>
      <td style="width: 888px;"><span style="font-size:14px;"><strong><?php echo date('d F Y', strtotime($info['date'])); ?></strong></span></td>
    </tr>
  </tbody>
</table>

<hr />

      <?php
		if ($wfh) {
			$caption = 'work from home';
		} else {
			$caption = 'overtime';
		}
		?>

<div style="letter-spacing:1px;"><span style="font-size:12px;">This is to respectfully submit the accomplishment report of the <?php echo $caption; ?> services rendered by the undersigned. <br />
Below are the details of the events and activities attended with corresponding time coverage for your review.</span></div>

<div style="letter-spacing:1px;"> </div>

<p><span style="font-size:14px;"><strong>DATE, TIME AND DETAILS:</strong></span></p>

<table border="0" cellpadding="1" cellspacing="1" style="width: 100%;">
  <tbody>

      <?php
		$output = "";
		$count  = 1;
		if ($lists) {
			foreach ($lists as $key => $value) {
				$date       = date('F d, Y', strtotime($value->overtime_date));
				$time_in    = date('h:iA', strtotime($value->time_in));
				$time_out   = date('h:iA', strtotime($value->time_out));
				$remarks    = $value->remarks;
				$other_day  = ucwords($value->other_day);

				$time_in_array = explode(':', $time_in);
				$hr_in       = $time_in_array[0];
				$mins_in     = $time_in_array[1];

				$time_out_array = explode(':', $time_out);
				$hr_out       = $time_out_array[0];
				$mins_out     = $time_out_array[1];

				if ($wfh) {
					$time_in = '08:00AM';
				} else {
					if ($other_day == 'Regular') {
						$time_in = '05:00PM';
						$other_day = date('l', strtotime($value->overtime_date));
					} else {
						if ($mins_in >= 30) {
							$time_in = date('h:00A', strtotime('+1 hour', strtotime($value->time_in)));
						} else {
							$time_in = date('h:00A', strtotime($value->time_in));
						}
					}
				}

				if ($mins_out >= 30) {
					$time_out_round = date('h:00A', strtotime('+1 hour', strtotime($value->time_out)));
				} else {
					$time_out_round = date('h:00A', strtotime($value->time_out));
				}

				if ($hr_out == '11' && $mins_out >= 30) {
					$time_out_round = '11:' . $mins_out;
				} else if ($hr_out == '11') {
					$time_out_round = '11:00PM';
				}

				if ($time_in == '12') {
					$time_in = '1:00PM';
				}


				$output .= "<tr>
            <td style='width: 225px;'><span style='font-size:14px;'>$count. $date</span></td>
            <td style='width: 208px;'><span style='font-size:14px;'>$time_in to $time_out_round</span></td>
            <td style='width: 555px;'><span style='font-size:14px;'>$remarks</span></td>
            <td style='width: 100px;'><span style='font-size:14px;'>$other_day</span></td>
          </tr>";
				$count = $count + 1;
			}
		}
		echo $output;
		?>

  </tbody>
</table>

<p> </p>

<p><span style="font-size:12px;">For your consideration and approval,</span></p>

<p> </p>
<?php
$mname = ($details->middle) ? "$details->middle." : '';
?>
<div style="line-height:1.1;"><span style="font-size:14px;"><strong><?php echo strtoupper($details->first_name . " " . $mname . " " . $details->last_name); ?></strong></span></div>

<div style="line-height:1.1;"><span style="font-size:12px;"><?php echo ucwords($details->possition); ?></span></div>

<p> </p>

<p><span style="font-size:12px;">Noted by:</span></p>

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