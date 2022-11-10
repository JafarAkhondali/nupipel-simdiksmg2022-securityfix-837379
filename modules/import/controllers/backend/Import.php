<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 *| --------------------------------------------------------------------------
 *| Dashboard Controller
 *| --------------------------------------------------------------------------
 *| For see your board
 *|
 */
class Import extends Admin
{

	public function __construct()
	{
		parent::__construct();
	}

	public function pd()
	{
		if (!$this->aauth->is_allowed('dashboard')) {
			redirect('/', 'refresh');
		}

		$data = [];
		$this->render('backend/standart/upload', $data);
	}
}

/* End of file Dashboard.php */
/* Location: ./application/controllers/administrator/Dashboard.php */