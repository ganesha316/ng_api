	public function edit()
	{
		if (!$this->input->get('<?= $module ?>_id')) {
			redirect(admin_url('<?= $controller ?>'),'refresh');
		}
		$data = [];
		$<?= $module ?>_id = $this->input->get('<?= $module ?>_id');
		$data['<?= $module ?>'] = $this->common->select_data('<?= $table ?>',['id' => $<?= $module ?>_id],NULL,1);
		if (empty($data['<?= $module ?>'])) {
			redirect(admin_url('<?= $controller ?>'),'refresh');
		}
		$data['title'] = $data['form_title'] = 'Edit <?= $module_lable ?>';
		$data['view'] = 'admin/<?= $view ?>/add_<?= $module ?>_view';
		$data['js'] = 'dist/js/pages/<?= $controller ?>.js';
		$data['form_type'] = 'edit';
		$data['breadcrumb'] = [
			[ 'name' => 'Dashboard', 'href' => admin_url('dashboard') ],
			[ 'name' => '<?= $module_lable ?>', 'href' => admin_url('<?= $controller ?>') ],
			[ 'name' => 'Edit <?= $module_lable ?>', 'href' => '#' ],
		];
		
		$this->load->view('template/admin', $data);
	}