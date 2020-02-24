<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Code_generator extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->data['module']       = 'change_password';
		$this->data['module_lable'] = 'Change Password';
		$this->data['controller']   = 'change_password';
		$this->data['table']        = 'change_password';
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

	public function index(){
		$data = $this->data;
		$this->load->view('test/default_view', $data);
	}

	public function controller(){
		$data = $this->data;
		$view = $this->load->view('test/controller_view', $data,true);
		$view = $this->sanitize_view($view);
		// echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
		$fp = fopen("./uploads/".ucfirst($this->data['controller']).".php","w");
		var_dump(fwrite($fp,$view));
		fclose($fp);
	}

	public function js(){
		$data = $this->data;
		$view = $this->load->view('test/js_file_view', $data,true);
		$view = $this->sanitize_view($view);
		// echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
		$fp = fopen("./uploads/".$this->data['controller'].".js","w");
		var_dump(fwrite($fp,$view));
		fclose($fp);
	}

	public function list_view()
	{
		$data = $this->data;
		$view = $this->load->view('test/list_view', $data,true);
		$view = $this->sanitize_view($view);
		// echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
		$fp = fopen("./uploads/".$this->data['module']."_list_view.php","w");
		var_dump(fwrite($fp,$view));
		fclose($fp);
	}

	public function add_form()
	{
		$data = $this->data;
		$view = $this->load->view('test/add_form_view', $data,true);
		$view = $this->sanitize_view($view);
		// echo '<pre>';print_r(htmlspecialchars($view));echo '</pre>';die;
		$fp = fopen("./uploads/".$this->data['module']."_view.php","w");
		var_dump(fwrite($fp,$view));
		fclose($fp);
	}

	private function sanitize_view($view)
	{
		$view = str_replace(['|?php','|?='], ['<?php','<?='], $view);
		$view = preg_replace('/\s*<space\/>/', '', $view);
		return $view;
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