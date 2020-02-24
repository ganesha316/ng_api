	public function save_<?= $module ?>()
	{
		if ($this->input->post(NULL,TRUE)) {
			$this->form_validation->set_rules($this->validation_rules('add'));
			$form_validate = $this->form_validation->run();
			
			if ($form_validate) {
				$post_data = array(
				<?php foreach ($inputs as $key => $value) { ?>
				<space/>
					'<?= $key ?>' => $_POST['<?= $key ?>'],
				<?php } ?>
				<space/>
					'created_at' => date('Y-m-d H:i:s'),
				);

				$this->common->insert_data('<?= $table ?>',$post_data);

				$this->session->set_flashdata('swal', [
					'type'=> 'success',
					'title'=> '<?= $module_lable ?> added successfully',
				]);
				echo json_encode(['status' => 'success', 'msg' => admin_url('<?= $controller ?>')]);
			}
			else{
				$error = get_validation_errors();
				echo json_encode(['status' => 'error', 'msg' => $error]);
			}
		}
	}