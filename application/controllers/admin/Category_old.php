<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Admin_Controller {

	public function __construct()
	{
		parent::__construct('admin');
		$this->banner_upload_path = './uploads/category/banner/';
		$this->banner_image_config = [
			'upload_path'   => $this->banner_upload_path,
			'allowed_types' => 'jpg|png|jpeg',
		];
		$this->banner_image_width = 1600;
	}

	public function index()
	{
		$data = [];
		$data['view']  = 'admin/category/category_view';
		$data['js']    = ['dist/js/pages/category.js'];
		$data['title'] = $data['form_title'] = 'Category';
		$data['breadcrumb'] = [
			[ 'name' => 'Dashboard', 'href' => admin_url('dashboard') ],
			[ 'name' => 'Category', 'href' => '#' ],
		];

		$page = ($this->input->get('page')) ? (int)$this->input->get('page') : 1;
		$per_page = 10;
		$offset = get_page_offset($page,$per_page);

		$search = $this->input->get('search');

		$sort_arr = ['name','sort_order'];
		$sort = $this->input->get('sort');
		$sort = (in_array($sort, $sort_arr)) ? $sort : 'name';

		$order = (strtolower($this->input->get('order')) == 'desc') ? 'desc' : 'asc';

		$data['results'] = $this->common->select_data_array_options([
			'table'    => 'category',
			'select'   => 'id,name,sort_order',
			'limit'    => $per_page,
			'order_by' => ['column' => $sort, 'type' => $order],
			'offset'   => $offset,
			'like'     => ['column' => 'name', 'match' => $search],
		]);

		$this->load->library('pagination');
		$config                         = pagination_config();
		$config['base_url']             = admin_url('category');
		$config['page_query_string']    = TRUE;
		$config['total_rows']           = $this->common->select_data_array_options([
			'table' => 'category',
			'select' => 'count(*) as count',
			'limit' => 1,
			'like' => ['column'=>'name','match' => $search]
		]);
		$config['total_rows'] = $config['total_rows']['count'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$query = $this->input->get();

		$data['header_link'] = [
			'name' => create_sort_link($query,'name',$sort,$order,admin_url('category')),
			'sort_order' => create_sort_link($query,'sort_order',$sort,$order,admin_url('category')),
		];

		$this->load->view('template/admin', $data);
	}

	public function add()
	{
		$data = [];
		$data['title']     = $data['form_title'] = 'Add Category';
		$data['view']      = 'admin/category/add_category_view';
		$data['js']        = ['dist/js/pages/category.js','plugins/jscolor/jscolor.js'];
		$data['form_type'] = 'add';
		$data['category']  = $this->common->get_table_columns('category');
		$data['category']  = array_fill_keys($data['category'], '');
		$data['breadcrumb'] = [
			[ 'name' => 'Dashboard', 'href' => admin_url('dashboard') ],
			[ 'name' => 'Category', 'href' => admin_url('category') ],
			[ 'name' => 'Add Category', 'href' => '#' ],
		];
		$this->load->view('template/admin', $data);
	}

	public function save_category()
	{
		if ($this->input->post(NULL,TRUE)) {
			$this->form_validation->set_rules($this->validation_rules('add'));
			$form_validate = $this->form_validation->run();
			
			make_upload_directory($this->banner_upload_path,true);
            $file_validate = validate_file('banner_image',$this->banner_image_config);
            if ($form_validate && ($file_validate === TRUE)) {

            	$file_upload = upload_file('banner_image',$this->banner_image_config);
                if ($file_upload['image_width'] > $this->banner_image_width) {
                    resize_images($file_upload,$this->banner_image_width,true);
                }

				$post_data = array(
					'name'         => $_POST['name'],
					'slug'         => $_POST['slug'],
					'bg_color'     => $_POST['bg_color'],
					'banner_name'  => $_POST['banner_name'],
					'banner_image' => $file_upload['file_name'],
					'content'      => $_POST['content'],
					'sort_order'   => $_POST['sort_order'],
					'status'       => $_POST['status'],
					'created_at'   => date('Y-m-d H:i:s'),
				);

				$this->common->insert_data('category', $post_data);

				$this->session->set_flashdata('swal', [
					'type'=> 'success',
					'title'=> 'Category added successfully',
				]);
				echo json_encode(['status' => 'success', 'msg' => admin_url('category')]);
			}
			else{
				$error = get_validation_errors();
				if($file_validate !== TRUE){
                    $error['banner_image'] = $file_validate;
                }
				echo json_encode(['status' => 'error', 'msg' => $error]);
			}
		}
	}

	public function edit()
	{
		if (!$this->input->get('category_id')) {
			redirect(admin_url('category'),'refresh');
		}
		$data = [];
		$category_id = $this->input->get('category_id');
		$data['category'] = $this->common->select_data_array_options([
			'table' => 'category',
			'where' => ['id' => $category_id],
			'limit' => 1
		]);
		if (empty($data['category'])) {
			redirect(admin_url('category'),'refresh');
		}
		$data['title']      = $data['form_title'] = 'Edit Category';
		$data['view']       = 'admin/category/add_category_view';
		$data['js']         = ['dist/js/pages/category.js','plugins/jscolor/jscolor.js'];
		$data['form_type']  = 'edit';
		$data['breadcrumb'] = [
			[ 'name' => 'Dashboard', 'href' => admin_url('dashboard') ],
			[ 'name' => 'Category', 'href' => admin_url('category') ],
			[ 'name' => 'Edit Category', 'href' => '#' ],
		];
		
		$this->load->view('template/admin', $data);
	}

	public function update_category()
	{
		if ($this->input->post(NULL,TRUE)) {
			$category_id = $_POST['id'];
			$this->form_validation->set_rules($this->validation_rules('edit'));
			$form_validate = $this->form_validation->run();

			$file_validate = TRUE;
			if (!empty($_FILES['banner_image']['name'])) {
				$file_validate = validate_file('banner_image',$this->banner_image_config);
			}
			
			if ($form_validate && $file_validate === TRUE) {

				$file_name = NULL;
				if (!empty($_FILES['banner_image']['name'])) {
					$file_upload = upload_file('banner_image',$this->banner_image_config);
					if ($file_upload['image_width'] > $this->banner_image_width) {
						resize_images($file_upload,$this->banner_image_width,true);
					}
					$file_name = $file_upload['file_name'];
				}

				$post_data = array(
					'name' => $_POST['name'],
					'slug' => $_POST['slug'],
					'bg_color' => $_POST['bg_color'],
					'banner_name'  => $_POST['banner_name'],
                    'content'      => $_POST['content'],
					'sort_order' => $_POST['sort_order'],
					'status' => $_POST['status'],
				);

				if ($file_name != NULL) {
					$post_data['banner_image'] = $file_name;
					$old_file = $this->common->select_data_array_options([
						'table' => 'category',
						'where' => ['id' => $category_id],
						'select' => 'banner_image',
						'limit' => 1
					])['banner_image'];
					if (!empty($old_file)) {
						unlink($this->banner_upload_path.$old_file);
					}
				}

				$this->common->update_data('category',$post_data,['id' => $category_id]);

				$this->session->set_flashdata('swal', [
					'type'=> 'success',
					'title'=> 'Category updated successfully',
				]);
				echo json_encode(['status' => 'success', 'msg' => admin_url('category')]);
			}
			else{
				$error = get_validation_errors();
				if($file_validate !== TRUE){
					$error['banner_image'] = $file_validate;
				}
				echo json_encode(['status' => 'error', 'msg' => $error]);
			}
		}
	}

	public function validation_rules($type)
	{
		$config = [
			[
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'trim|required',
			],
			[
				'field' => 'sort_order',
				'label' => 'Sort Order',
				'rules' => 'trim|required|numeric',
			],
			[
				'field' => 'bg_color',
				'label' => 'Background Color',
				'rules' => 'trim|required|alpha_numeric',
			],
			[
                'field' => 'banner_name',
                'label' => 'Banner Name',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'content',
                'label' => 'Content',
                'rules' => 'trim|required|max_length[1000]',
            ],
			[
				'field' => 'status',
				'label' => 'Status',
				'rules' => 'trim|required',
			],
		];

		if ($type == 'add') {
			$config[] = [
				'field' => 'slug',
				'label' => 'Slug',
				'rules' => 'trim|strtolower|required|is_valid_slug|is_unique[category.slug]',
				'errors'=> [
					'is_unique' => 'This slug is already in use. Please enter different slug',
					'is_valid_slug' => 'The Slug field may only contain alpha-numeric characters,-,_ without spaces',
				],
			];
		}

		if ($type == 'edit') {
			$config[] = [
				'field' => 'slug',
				'label' => 'Slug',
				'rules' => 'trim|strtolower|required|is_valid_slug|callback_unique_slug_except_current',
				'errors'=> [
					'unique_slug_except_current' => 'This slug is already in use. Please enter different slug',
					'is_valid_slug' => 'The Slug field may only contain alpha-numeric characters,-,_ without spaces',
				],
			];
		}
		return $config;
	}

	public function delete()
	{
		if (!empty($this->input->post('ids'))) {
			$ids = (array)$this->input->post('ids');
			$where = "in(".implode(',', $ids).")";

			$banner_images = $this->common->select_data_array_options(['table'=>'category','select'=>'banner_image','where'=>'id '.$where]);

			$home_gallery_images = $this->common->select_data_array_options(['table'=>'home_gallery','select'=>'image','where'=>'cat_id '.$where]);

			$deleted_count = $this->common->delete_data('category','id '.$where);

			if ($deleted_count > 0) {

				$this->common->delete_data('home_gallery','cat_id '.$where);

				foreach ($banner_images as $image) {
					@unlink($this->banner_upload_path.$image['banner_image']);
				}

				foreach ($home_gallery_images as $image) {
					@unlink('./uploads/category/home_gallery/'.$image['image']);
				}

				echo json_encode(['status' => 'success', 'msg' => 'Deleted successfully']);
			}
			else{
				echo json_encode(['status' => 'error', 'msg' => 'Unable to delete. Technical error occured']);	
			}
		}
	}

	public function unique_slug_except_current($slug)
	{
		$cat_data = $this->common->select_data('category',['slug' => $_POST['slug']],'id',1);
		return (empty($cat_data) || (!empty($cat_data) && $cat_data['id'] == $_POST['id']));
	}

}

/* End of file Category.php */
/* Location: ./application/controllers/admin/Category.php */