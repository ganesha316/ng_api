<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->user_table = 'user_master';
		if(is_logged_in() && !in_array($this->router->fetch_method(), ['logout'])){
			redirect(user_home_page(),'refresh');
		}
	}

	public function index()
	{
		$data = [];
		$data['page_name'] = 'login';
		$data['view'] = 'admin/login/admin_login_view';
		$this->load->view('template/admin_login', $data);
	}

	public function login_action()
	{
		if ($this->input->post(NULL,TRUE)) {
			$this->form_validation->set_rules($this->validation_rules());
			if ($this->form_validation->run()) {
				$user_id = $this->input->post('user_id');
				$user_data = $this->common->select_data($this->user_table,['email' => $user_id], NULL, 1);
				if (empty($user_data)) {
					$error['user_id'] = 'This Email id is not registered';
					echo json_encode(['status' => 'error', 'msg' => $error]);
				}

				if (!empty($user_data)) {
					if(!password_verify($this->input->post('password'), $user_data['password'])){
						$error['password'] = 'Invalid password';
						echo json_encode(['status' => 'error', 'msg' => $error]);
					}
					else {
						set_admin_session($user_data);
						echo json_encode(['status' => 'success', 'msg' => user_home_page()]);
					}
				}
			}
			if ($this->form_validation->run('vendor_login') == FALSE) {
				$error = [];
				foreach ($_POST	 as $key => $value) {
					$error[$key] = form_error($key,NULL,NULL);
				}
				echo json_encode(['status' => 'error', 'msg' => $error]);
			}
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(admin_url('login'),'refresh');
	}

	private function validation_rules()
	{
		$config = [];
		$config = [
			[
				'field' => 'user_id',
				'label' => 'Email/Mobile',
				'rules' => 'trim|required|valid_email',
			],
			[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'trim|required',
			],
		];

		return $config;
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/vendor/Login.php */