<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Phpqrcode{

	var $CI;

	public function __construct(){
		$this->CI =& get_instance();
		require_once APPPATH.'third_party/Phpqrcode/qrlib.php';
	}

	public function generate_qr($value){
		//QRcode::png($value, 'images/qr/' . $value.'.png');
	
			QRcode::png(
			    $value,
			    $outfile = 'images/qr/' . $value.'.png',
			    $level = QR_ECLEVEL_L,
			    $size = 20,
			    $margin = 2,
			    $saveandprint = false 
			);

	}


}