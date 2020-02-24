	public function update_<?= $module ?>()
	{
		if ($this->input->post(NULL,TRUE)) {
			$<?= $module ?>_id = $_POST['id'];
			$this->form_validation->set_rules($this->validation_rules('edit'));
			$form_validate = $this->form_validation->run();

			if ($form_validate) {
				$post_data = array(
				<?php foreach ($inputs as $key => $value) { ?>
				<space/>
					'<?= $key ?>' => $_POST['<?= $key ?>'],
				<?php } ?>
				<space/>
				);

				$this->common->update_data('<?= $table ?>',$post_data,['id' => $<?= $module ?>_id]);

				$this->session->set_flashdata('swal', [
					'type'=> 'success',
					'title'=> '<?= $module_lable ?> updated successfully',
				]);
				echo json_encode(['status' => 'success', 'msg' => admin_url('<?= $controller ?>')]);
			}
			else{
				$error = get_validation_errors();	
				echo json_encode(['status' => 'error', 'msg' => $error]);
			}
		}
	}