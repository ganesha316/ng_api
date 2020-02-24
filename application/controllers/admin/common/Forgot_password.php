<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot_password extends CI_Controller {

	public function index()
	{
		$data = [];
		$data['page_name'] = 'forgot_password';
		$data['view'] = 'admin/login/forgot_password_view';
		$this->load->view('template/admin_login', $data);
	}

	public function username_validate()
	{
		if ($this->input->post(NULL,TRUE)) {
			$this->form_validation->set_rules($this->validation_rules('forgot_password'));
			if ($this->form_validation->run()) {
				$user_data = $this->common->select_data('user_master',"email='$_POST[user_id]'", 'email', 1);

				$this->load->helper('string');
				$otp = random_string('numeric',6);
				$this->session->set_flashdata('vendor_fp_otp', ['email'=>$user_data['email'], 'otp' => sha1($otp)]);

				$html_content = '<p>One time password to reset your system password is - <b>'.$otp.'</b></p>';
				$mail = send_mail([
					'to'=> $user_data['email'],
					'from'=> 'admin@raythonesfilms.com',
					'subject'=> 'Raythones - Password recovery email',
					'message'=> $html_content,
				]);
				if ($mail !== TRUE) {
					echo json_encode(['status' => 'error', 'msg' => 'Unable to send Email. Technical error occured']);
				}
				else{
					echo json_encode(['status' => 'success', 'msg' => 'OTP sent! Please check your email']);
				}
			}
			else{
				echo json_encode(['status' => 'error', 'msg' => form_error('user_id',NULL,NULL)]);
			}
		}
	}

	public function strong_password($password)
	{
		return (bool)preg_match(password_pattern(), $password);
	}

	public function otp_validate()
	{
		if ($this->input->post(NULL,TRUE)) {
			$saved_otp = $this->session->flashdata('vendor_fp_otp')['otp'];
			if (sha1($_POST['otp']) == $saved_otp) {	
				echo json_encode(['status' => 'success', 'msg' => 'otp verified']);
			}
			else{
				echo json_encode(['status' => 'error', 'msg' => 'Invalid OTP']);
			}
			$this->session->keep_flashdata('vendor_fp_otp');
		}
	}

	public function reset_password()
	{
		if ($this->input->post(NULL,TRUE)) {
			$this->form_validation->set_rules($this->validation_rules('reset_password'));
			if ($this->form_validation->run()) {
				$email = $this->session->flashdata('vendor_fp_otp')['email'];

				$update = $this->common->update_data(
					'user_master',
					['password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)],
					['email'=>$email]
				);
				if ($update) {
					echo json_encode(['status' => 'success', 'msg' => admin_url('login')]);
				}

				if (!$update) {
					echo json_encode(['status' => 'common_error', 'msg' => 'Technical error occured.Unable to reset password', 'url'=> admin_url('login')]);
				}
			}
			else{
				$error = [];
				foreach ($_POST	 as $key => $value) {
					$error[$key] = form_error($key,NULL,NULL);
				}
				$this->session->keep_flashdata('vendor_fp_otp');
				echo json_encode(['status' => 'error', 'msg' => $error]);
			}
		}
	}

	public function validate_user($user_id)
	{
		$user_data = $this->common->select_data_array_options([
			'table' => 'user_master',
			'where' => ['email' => $user_id], 
			'limit' => 1
		]);
		return !empty($user_data);
	}

	private function validation_rules($type)
	{
		$config = array(
			'forgot_password' => [
				[
					'field' => 'user_id',
					'label' => 'Email',
					'rules' => 'trim|required|valid_email|callback_validate_user',
					'errors'=> [
						'validate_user' => 'This Email does not belongs to our system'
					],
				],
			],
			
			'reset_password' => [
				[
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|callback_strong_password',
					'errors' => [
						'strong_password' => 'A password must be minimum of 8 characters in length must contain at least one uppercase letter, at least one digit, at least one of the following special characters !@#$%^&*-',
					]
				],
				[
					'field' => 'c_password',
					'label' => 'Confirm password',
					'rules' => 'trim|required|matches[password]',
				],
			],
		);

		return isset($config[$type]) ? $config[$type] : [];
	}

}

/* End of file Forgot_password.php */
/* Location: ./application/controllers/admin/Forgot_password.php */