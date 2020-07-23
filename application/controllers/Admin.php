<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('admin'))) {
			redirect(base_url('login'));
		}
	}
	public function dashboard()
	{
		$this->load->view('dashboard');
	}

}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */