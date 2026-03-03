<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_file{

	var $CI;

	public function __construct(){

		$this->CI =& get_instance();

	}

	/*		
		type, location, data
		$this->upload->validate('pdf', '', $filename);

	*/

	public function validate($source = array() ){

		$fileImage = $source['file'];
		$type 	   = $source['type'];
		$path 	   = $source['path'];
		$prefix    = $source['prefix'];

		if($source['type'] == 'docs'){
			$file_type 		= 'pdf';
			$max_size 		= 20480; //20MB 
		}else{
			$file_type 		= 'jpg|png|jpeg';
			$max_size 		= 6144; //1024KB = 1MB
		}


		$file_extension = pathinfo($fileImage, PATHINFO_EXTENSION);

		$file_name =  $prefix .'_'.  date('Ymdhis') .'_'. uniqid() .'.'. $file_extension;

		$config = array(
			'file_name'   	=> $file_name,
			'upload_path' 	=> $path,
			'allowed_types' => $file_type,
			'max_size' 		=> $max_size,
			'overwrite'   	=> false,
		);


		if($prefix = 'user'){
			$config['maintain_ratio'] = true;
			$config['width'] = 192;
			$config['height'] = 192;
		}

		$this->CI->load->library('image_lib', $config);
		$this->CI->image_lib->initialize($config);
		//load upload libraries
		$this->CI->load->library('upload', $config);
		//execute
		$this->CI->upload->initialize($config);
		if ($this->CI->upload->do_upload('file')) {
			$success =  true;
		}else{
			$success =  false;
		}

		return array(
			'success'  => $success,
			'filename' => $file_name
		);

	}

	public function delete_file($source = array()){
		$file 	= $source['file'];
		$path   = $source['path'];

		$file_path =  "$path/$file";

		if (file_exists($file_path)){
	            @chmod( $file_path, 0777 );
                @unlink( $file_path );
		}

	}




}