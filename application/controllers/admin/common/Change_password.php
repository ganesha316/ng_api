<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Change_password extends Admin_Controller {

	public function index()
	{
		$data = [];
		$data['title'] = $data['form_title'] = 'Change Password';
		$data['view'] = 'admin/common/change_password_view';
		$data['js'] = 'dist/js/pages/change_password.js';
		
		$data['breadcrumb'] = [
			[ 'name' => 'Dashboard', 'href' => admin_url('dashboard') ],
			[ 'name' => 'Change Password', 'href' => admin_url('change_password') ],
		];
		$this->load->view('template/admin', $data);
	}

	public function save_change_password()
	{
		if ($this->input->post(NULL,TRUE)) {
			$this->form_validation->set_rules($this->validation_rules('change_password'));
			$form_validate = $this->form_validation->run();
			
			if ($form_validate) {
				$update_data = array(
					'password' => password_hash($_POST['new_password'],PASSWORD_DEFAULT),
				);

				$this->common->update_data('user_master',$update_data,['email'=>$this->session->userdata('email')]);

				$this->session->set_flashdata('swal', [
					'type'=> 'success',
					'title'=> 'Password updated successfully',
				]);
				echo json_encode(['status' => 'success', 'msg' => admin_url('change_password')]);
			}
			else{
				$error = get_validation_errors();
				$this->session->keep_flashdata('vendor_fp_otp');
				echo json_encode(['status' => 'error', 'msg' => $error]);
			}
		}
	}

	public function verify_old_password()
	{
		if ($this->input->post(NULL,TRUE)) {
			$this->form_validation->set_rules($this->validation_rules('verify_old_password'));
			$form_validate = $this->form_validation->run();
			
			if ($form_validate) {
				$email = $this->session->userdata('email');
				$this->load->helper('string');
				$otp = random_string('numeric',6);
				$this->session->set_flashdata('vendor_fp_otp', ['email'=>$email, 'otp' => sha1($otp)]);

				$html_content = '<p>One time password to reset your system password is - <b>'.$otp.'</b></p>';
				$mail = send_mail([
					'to'=> $email,
					'from'=> $this->config->item('mailer_id'),
					'subject'=> $this->config->item('project_name').' - Password reset OTP',
					'message'=> $html_content,
				]);
				if ($mail !== TRUE) {
					echo json_encode(['status' => 'mail_error', 'msg' => 'Unable to send Email. Technical error occured']);
				}
				else{
					echo json_encode(['status' => 'success', 'msg' => 'OTP sent! Please check your email']);
				}
			}
			else{
				$error = get_validation_errors();
				echo json_encode(['status' => 'error', 'msg' => $error]);
			}
		}
	}

	public function strong_password($password)
	{
		return (bool)preg_match(password_pattern(), $password);
	}

	public function validate_old_password($old_password)
	{
		$sys_pass = $this->common->select_data_array_options([
			'table'=>'user_master',
			'where'=>['email'=>$this->session->userdata('email')],
			'select'=>'password',
			'limit'=>1
		]);
		return password_verify($old_password, $sys_pass['password']);
	}

	public function validate_otp($otp)
	{
		$saved_otp = $this->session->flashdata('vendor_fp_otp')['otp'];
		return (sha1($otp) === $saved_otp);
	}

	private function validation_rules($type)
	{
		$config = [];
		$config = [
			'change_password' =>[
				[
					'field' => 'old_password',
					'label' => 'Old Password',
					'rules' => 'trim|required|callback_validate_old_password',
					'errors'=>[
						'validate_old_password' => 'Old password is incorrect'
					]
				],
				[
					'field' => 'new_password',
					'label' => 'New Password',
					'rules' => 'trim|required|callback_strong_password',
					'errors' => [
						'strong_password' => 'A password must be minimum of 8 characters in length must contain at least one uppercase letter, at least one digit, at least one of the following special characters !@#$%^&*-',
					]
				],
				[
					'field' => 'c_new_password',
					'label' => 'Confirm Password',
					'rules' => 'trim|required|matches[new_password]',
				],
				[
					'field' => 'otp',
					'label' => 'OTP',
					'rules' => 'trim|required|callback_validate_otp',
					'errors' => [
						'validate_otp' => 'Invalid OTP',
					]
				],
			],
			'verify_old_password' =>[
				[
					'field' => 'old_password',
					'label' => 'Old Password',
					'rules' => 'trim|required|callback_validate_old_password',
					'errors'=>[
						'validate_old_password' => 'Old password is incorrect'
					]
				],
			]
		];

		return isset($config[$type]) ? $config[$type] : [];
	}
}

/* End of file Change_password.php */
/* Location: ./application/controllers/admin/Change_password.php */