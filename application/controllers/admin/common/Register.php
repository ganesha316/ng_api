<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->user_table = 'user_master';
	}

	public function index()
	{
		$data = [];
		$data['page_name'] = 'register';
		$data['view'] = 'admin/login/admin_register_view';
		$this->load->view('template/admin_login', $data);
	}

	public function register_action()
	{
		if ($this->input->post(NULL,TRUE)) {
			if (!isset($_POST['accept_terms'])) {
				$_POST['accept_terms'] = '';
			}

			if ($this->form_validation->run('vendor_register')) {
				$this->load->helper('string');
				$otp = random_string('numeric',6);
				$this->session->set_flashdata('vendor_register_otp', ['mobile'=>$_POST['mobile'],'otp' => sha1($otp)]);
				$sms = send_sms([
					[
						'message' => "$otp is your one time password(OTP) for login with Tryplore",
						'to' => [$_POST['mobile']]
					]
				]);
				if ($sms['type'] == 'error') {
					echo json_encode(['status' => 'otp_error', 'msg' => $sms['message']]);
				}
				else{
					echo json_encode(['status' => 'success', 'msg' => 'OTP sent!']);
				}
			} 
			else {
				$error = [];
				foreach ($_POST	 as $key => $value) {
					$error[$key] = form_error($key,NULL,NULL);
				}
				echo json_encode(['status' => 'error', 'msg' => $error]);
			}
		}
	}

	public function verify_otp()
	{
		if ($this->input->post(NULL,TRUE)) {
			$saved_otp = $this->session->flashdata('vendor_register_otp')['otp'];
			$mobile = $this->session->flashdata('vendor_register_otp')['mobile'];
			if (sha1($_POST['otp']) == $saved_otp) {
				$insert_id = $this->common->insert_data($this->user_table,[
					'email'           => $this->input->post('email'),
					'mobile'          => $mobile,
					'password'        => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
					'mobile_verified' => 'yes',
					'user_type'       => 'vendor',
					'status'          => 'active',
					'created_at'      => date('Y-m-d H:i:s'),
				]);

				if ($insert_id) {
					set_vendor_session($insert_id,'default_login');
					$html_content = '<p>Please click the link below to verify your email adress</p>';
					$link = admin_url('register/verify_email?v=').encrypt_array(['id' => $insert_id]);
					$html_content .= "<a href='$link'>$link</a>";
					send_mail([
						'to'=> $this->input->post('email'),
						'from'=> $this->config->item('mailer_id'),
						'subject'=> 'Tryplore email verification',
						'message'=> $html_content,
					]);
					echo json_encode(['status' => 'success', 'msg' => user_home_page()]);
				}
			}
			else{
				$this->session->keep_flashdata('vendor_register_otp');
				echo json_encode(['status' => 'error', 'msg' => 'Invalid OTP']);
			}
		}
	}

	public function resend_otp()
	{
		if ($this->input->post(NULL,TRUE)) {
			$this->load->helper('string');
			$otp = random_string('numeric',6);
			$mobile = $this->session->flashdata('vendor_register_otp')['mobile'];
			$this->session->set_flashdata('vendor_register_otp', ['mobile'=> $mobile,'otp' => sha1($otp)]);
			$sms = send_sms([
				[
					'message' => "$otp is your one time password(OTP) for login with Tryplore",
					'to' => [$mobile]
				]
			]);
			$this->session->keep_flashdata('vendor_register_otp');

			if ($sms['type'] == 'error') {
				echo json_encode(['status' => 'error', 'msg' => $sms['message']]);
			}
			else{
				echo json_encode(['status' => 'success', 'msg' => 'OTP sent!']);
			}
		}
	}

	public function verify_email()
	{
		if( !$get = encrypt_validation('v')){
			redirect(base_url(),'refresh');
		}

		$update = $this->common->update_data($this->user_table,['email_verified'=>'yes'],['id'=>$get['id']]);
		if ($this->session->userdata('email_verified')) {
			$this->session->set_userdata('email_verified','yes');
		}
		$this->session->set_flashdata('swal',[
			'type'=> 'success',
			'title'=> 'Email verified successfully',
		]);
		redirect(user_home_page(),'refresh');
	}

	public function strong_password($password)
	{
		return (bool)preg_match(password_pattern(), $password);
	}
}

/* End of file Register.php */
/* Location: ./application/controllers/admin/Register.php */