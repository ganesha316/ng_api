	public function delete()
	{
		if (!empty($this->input->post('ids'))) {
			$ids = (array)$this->input->post('ids');
			$where = "id in(".implode(',', $ids).")";

			$deleted_count = $this->common->delete_data('<?= $table ?>',$where);

			if ($deleted_count > 0) {
				echo json_encode(['status' => 'success', 'msg' => 'Deleted successfully']);
			}
			else{
				echo json_encode(['status' => 'error', 'msg' => 'Unable to delete. Technical error occured']);	
			}
		}
	}