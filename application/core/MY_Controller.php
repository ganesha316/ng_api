<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Controller extends CI_Controller {

	public function __construct($user_type = NULL) {
		parent::__construct();
		if(!is_logged_in($user_type)){
			if (!is_logged_in()) {
				redirect(admin_url(),'refresh');
			}
			redirect(user_home_page(),'refresh');
		}
	}
}

class API_Controller extends CI_Controller
{
	
	
	public function __construct()
	{
		parent::__construct();

		header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST,GET,PUT,DELETE,OPTIONS');
        header('Access-Control-Allow-Headers:Content-Type');
        header('Content-Type:application/json; charset=UTF-8');
	}

	public function response($data=[], $status_code = HTTP_OK, $success=TRUE, $info='')
	{
		echo json_encode([
			'data' => $data,
			'status' => $status_code,
			'success' => (bool)$success,
			'info' => $info,
		]);
		die;
	}
}
/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */