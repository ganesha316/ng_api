	public function index()
	{
		$data = [];
		$data['view'] = 'admin/<?= $view ?>/<?= $module ?>_view';
		$data['js'] = 'dist/js/pages/<?= $controller ?>.js';
		$data['title'] = $data['form_title'] = '<?= $module_lable ?>';
		$data['breadcrumb'] = [
			[ 'name' => 'Dashboard', 'href' => admin_url('dashboard') ],
			[ 'name' => '<?= $module_lable ?>', 'href' => '#' ],
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
			'table'    => '<?= $table ?>',
			'select'   => 'id,name,sort_order',
			'limit'    => $per_page,
			'order_by' => ['column' => $sort, 'type' => $order],
			'offset'   => $offset,
			'like'     => ['column' => 'name', 'match' => $search],
		]);

		$this->load->library('pagination');
		$config                         = pagination_config();
		$config['base_url']             = admin_url('<?= $controller ?>');
		$config['page_query_string']    = TRUE;
		$config['total_rows']           = $this->common->select_data_array_options([
			'table' => '<?= $table ?>',
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
			'name' => create_sort_link($query,'name',$sort,$order,admin_url('<?= $controller ?>')),
			'sort_order' => create_sort_link($query,'sort_order',$sort,$order,admin_url('<?= $controller ?>')),
		];

		$this->load->view('template/admin', $data);
	}