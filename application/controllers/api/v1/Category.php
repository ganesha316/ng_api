<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends API_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get()
	{
		$results = $this->common->select_data_array_options([
			'table'    => 'category',
			'select'   => 'id,name',
		]);

		$this->response($results);	
	}

	public function save_category()
	{
		if ($this->input->post(NULL,TRUE)) {
			$this->form_validation->set_rules($this->validation_rules());
			$form_validate = $this->form_validation->run();
			
			if ($form_validate) {
				$post_data = array(
					'name' => $_POST['name'],
					'created_at' => date('Y-m-d H:i:s'),
				);

				$this->common->insert_data('category',$post_data);
				$this->response([],HTTP_OK,TRUE,'Category Added Successfully');
				
			}
			else{
				$error = get_validation_errors();
				$this->response(['errors' => $error],HTTP_OK,FALSE,'Validation Errors');
			}
		}
		else{
			$this->response([],HTTP_BAD_REQUEST,FALSE,'Invalid request');
		}

	}

	public function edit()
	{
		if (!$this->input->post('category_id')) {
			$this->response(['category_id' => 'please provide category id'], HTTP_OK, FALSE, 'validation error');
		}
		$data = [];
		$category_id = $this->input->post('category_id');
		$category = $this->common->select_data_array_options([
			'table' => 'category',
			'where' => ['id' => $category_id],
			'limit' => 1
		]);
		$this->response($category);
	}

	public function update_category()
	{
		if ($this->input->post(NULL,TRUE)) {
			$category_id = $_POST['id'];
			$this->form_validation->set_rules($this->validation_rules());
			$form_validate = $this->form_validation->run();

			if ($form_validate) {
				$post_data = array(
					'name' => $_POST['name'],
				);

				$this->common->update_data('category',$post_data,['id' => $category_id]);

				$this->session->set_flashdata('swal', [
					'type'=> 'success',
					'title'=> 'Category updated successfully',
				]);
				echo json_encode(['status' => 'success', 'msg' => admin_url('category')]);
			}
			else{
				$error = get_validation_errors();	
				echo json_encode(['status' => 'error', 'msg' => $error]);
			}
		}
		else{
			$this->response([],HTTP_BAD_REQUEST,FALSE,'Invalid request');
		}
	}

	public function delete()
	{
		if (!empty($this->input->post('ids'))) {
			$ids = (array)$this->input->post('ids');
			$where = "id in(".implode(',', $ids).")";

			$deleted_count = $this->common->delete_data('category',$where);

			if ($deleted_count > 0) {
				echo json_encode(['status' => 'success', 'msg' => 'Deleted successfully']);
			}
			else{
				echo json_encode(['status' => 'error', 'msg' => 'Unable to delete. Technical error occured']);	
			}
		}
	}

	private function validation_rules()
	{
		$config = [];
		$config = [
			[
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'trim|required',
			],
		];

		return $config;
	}

	public function brand()
	{
		$results = $this->common->select_data_array_options([
			'table'    => 'brand',
			'select'   => 'id,name',
		]);

		$this->response($results);	
	}

	public function product()
	{
		$results = $this->common->select_data_array_options([
			'table'    => 'products p',
			'select'   => 'p.id,p.name,p.price,p.category_id,p.brand_id,p.img_path,b.name as brand_name, c.name as category_name',
			'join' => [
				['table' => 'brand b', 'condition' => 'b.id = p.brand_id', 'type' => 'left'],
				['table' => 'category c', 'condition' => 'c.id = p.category_id', 'type' => 'left'],
			],

		]);

		$this->response($results);	
	}

	public function brandFilter()
	{
		$post = get_json_post();
		$results = $this->common->select_data_array_options([
			'table'    => 'products p',
			'select'   => 'p.id,p.name,p.price,p.category_id,p.brand_id,p.img_path,b.name as brand_name, c.name as category_name',
			'join' => [
				['table' => 'brand b', 'condition' => 'b.id = p.brand_id', 'type' => 'left'],
				['table' => 'category c', 'condition' => 'c.id = p.category_id', 'type' => 'left'],
			],
			'where' => ['p.brand_id' => $post['id']]
		]);

		$this->response($results);	
	}

	public function categoryFilter()
	{
		$post = get_json_post();
		$results = $this->common->select_data_array_options([
			'table'    => 'products p',
			'select'   => 'p.id,p.name,p.price,p.category_id,p.brand_id,p.img_path,b.name as brand_name, c.name as category_name',
			'join' => [
				['table' => 'brand b', 'condition' => 'b.id = p.brand_id', 'type' => 'left'],
				['table' => 'category c', 'condition' => 'c.id = p.category_id', 'type' => 'left'],
			],
			'where' => ['p.category_id' => $post['cat_id']]
		]);

		$this->response($results);	
	}
}

/* End of file Category.php */
/* Location: ./application/controllers/api/v1/Category.php */