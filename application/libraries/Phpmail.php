<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Phpmail{

	var $CI;

	public function __construct(){
		$this->CI =& get_instance();
	}

	public function validate($data = array()){

		$this->CI->load->library('email');


        //get basic details
        $email      = $data['email'];
        $subject    = $data['subject'];
        $template   = $data['template'];

		$this->CI->email->set_newline("\r\n");

		if($template == 'contactus'){
			$this->CI->email->from($email,'Contact Us');
			$this->CI->email->to('info@skmalanday.com'); 
		}else{
			$this->CI->email->from('noreply@skmalanday.com','SK Malanday');
			$this->CI->email->to("$email"); 
		}


		$this->CI->email->subject("$subject");

        $this->CI->data['data'] = $data;  //all data


        if($template == 'activation'){
            $template_view = 'email_activation_view';

        }else if($template == 'forgotpassword'){

            $template_view = 'email_forgot_view';

        }else if($template == 'contactus'){

            $template_view = 'email_contact_view';

        }else if($template == 'registration'){

            $template_view = 'email_registration_view';

        }else if($template == 'thankyou'){

            $template_view = 'email_thankyou_view';

        }else{
            $template_view = 'email_approval_view';
        }

		$content_email = $this->CI->load->view($template_view, $this->CI->data, true);

		$this->CI->email->message($content_email);	
	
		if($this->CI->email->send()){
			return true;
		}else{
			show_error($this->CI->email->print_debugger());
			return false;
		}	

	}
	
}