|?php
defined('BASEPATH') OR exit('No direct script access allowed');

class <?= ucfirst($controller) ?> extends Admin_Controller {

	public function __construct()
	{
		parent::__construct('super_admin');
	}
	
<?php $this->load->view('test/index_view'); ?>


<?php $this->load->view('test/add_view'); ?>


<?php $this->load->view('test/save_view'); ?>


<?php $this->load->view('test/edit_view'); ?>


<?php $this->load->view('test/update_view'); ?>


<?php $this->load->view('test/delete_view'); ?>


<?php $this->load->view('test/validation_view'); ?>

}