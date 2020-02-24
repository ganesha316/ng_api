	public function add()
	{
		$data = [];
		$data['title'] = $data['form_title'] = 'Add <?= $module_lable ?>';
		$data['view'] = 'admin/<?= $view ?>/add_<?= $module ?>_view';
		$data['js'] = 'dist/js/pages/<?= $controller ?>.js';
		$data['form_type'] = 'add';
		$data['<?= $module ?>'] = $this->common->get_table_columns('<?= $table ?>');
		$data['<?= $module ?>'] = array_fill_keys($data['<?= $module ?>'], '');
		$data['breadcrumb'] = [
			[ 'name' => 'Dashboard', 'href' => admin_url('dashboard') ],
			[ 'name' => '<?= $module_lable ?>', 'href' => admin_url('<?= $module ?>') ],
			[ 'name' => 'Add <?= $module_lable ?>', 'href' => '#' ],
		];
		$this->load->view('template/admin', $data);
	}