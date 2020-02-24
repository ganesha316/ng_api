<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->data['module']       = 'change_password';
		$this->data['module_lable'] = 'Change Password';
		$this->data['controller']   = 'change_password';
		$this->data['view']         = 'common';
		$this->data['table']        = 'home_banner';
		$this->data['inputs'] = [
			'old_password' => [
				'label' => 'Old Password',
				'type' => 'text'
			],
			'new_password' => [
				'label' => 'New Password',
				'type' => 'text'
			],
			'c_old_password' => [
				'label' => 'Confirm Password',
				'type' => 'text'
			],
		];
	}

	public function add_form()
	{
		$data = $this->data;
		$view = $this->load->view('test/add_form_view', $data,true);
		$view = $this->sanitize_view($view);
		echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
	}

	public function validation()
	{
		$data = $this->data;
		$view = $this->load->view('test/validation_view', $data,true);
		$view = $this->sanitize_view($view);
		echo '<pre>';print_r($view);echo '</pre>';die;
	}

	public function list_view()
	{
		$data = $this->data;
		$view = $this->load->view('test/list_view', $data,true);
		$view = $this->sanitize_view($view);
		echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
	}

	public function js_file()
	{
		$data = $this->data;
		$view = $this->load->view('test/js_file_view', $data,true);
		$view = $this->sanitize_view($view);
		echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
	}

	private function sanitize_view($view)
	{
		$view = str_replace(['|?php','|?='], ['<?php','<?='], $view);
		$view = preg_replace('/\s*<space\/>/', '', $view);
		return $view;
	}

	// controller
	public function add()
	{
		$data = $this->data;
		$view = $this->load->view('test/add_view', $data,true);
		$view = $this->sanitize_view($view);
		echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
	}

	public function edit()
	{
		$data = $this->data;
		$view = $this->load->view('test/edit_view', $data,true);
		$view = $this->sanitize_view($view);
		echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
	}

	public function update()
	{
		$data = $this->data;
		$view = $this->load->view('test/update_view', $data,true);
		$view = $this->sanitize_view($view);
		echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
	}

	public function save()
	{
		$data = $this->data;
		$view = $this->load->view('test/save_view', $data,true);
		$view = $this->sanitize_view($view);
		echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
	}

	public function delete()
	{
		$data = $this->data;
		$view = $this->load->view('test/delete_view', $data,true);
		$view = $this->sanitize_view($view);
		echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
	}

	public function index()
	{
		$data = $this->data;
		$view = $this->load->view('test/index_view', $data,true);
		$view = $this->sanitize_view($view);
		echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
	}

	public function controller()
	{
		$data = $this->data;
		$view = $this->load->view('test/controller_view', $data,true);
		$view = $this->sanitize_view($view);
		echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
	}


	public function sample_input()
	{
		return $this->inputs = [
			'name' => [
				'label' => 'Name',
				'type' => 'text'
			],
			'image' => [
				'label' => 'Image',
				'type' => 'file'
			],
			'parent_id' => [
				'label' => 'Parent Category',
				'type' => 'select'
			],
			'description' => [
				'label' => 'Description',
				'type' => 'textarea'
			],
			'slug' => [
				'label' => 'Slug',
				'type' => 'text'
			],
			'meta_title' => [
				'label' => 'Meta Title',
				'type' => 'text'
			],
			'meta_tag_description' => [
				'label' => 'Meta Tag Description',
				'type' => 'textarea'
			],
			'meta_keywords' => [
				'label' => 'Meta Tag Keywords',
				'type' => 'text'
			],
			'top_menu' => [
				'label' => 'In top menu',
				'type' => 'radio',
				'option' => [
					'yes' => 'Yes',
					'no' => 'No',
				]
			],
			'sort_order' => [
				'label' => 'Sort Order',
				'type' => 'text'
			],
			'status' => [
				'label' => 'Status',
				'type' => 'select',
				'option' => [
					'active' => 'Active',
					'inactive' => 'In Active',
				]
			],
		];
	}

}

/* End of file Test.php */
/* Location: ./application/controllers/Test.php */