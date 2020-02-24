<?php
	$this->load->view('admin/common/header');
	if ($this->session->userdata('user_type') == 'admin') {
		$this->load->view('admin/common/admin_menu');
	}
	$this->load->view($view);
	$this->load->view('admin/common/footer');
?>