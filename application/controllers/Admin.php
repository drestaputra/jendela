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
		$data['jml_buku'] = (int) $this->function_lib->get_one('count(id_buku)','buku','1');
		$data['jml_kategori'] = (int) $this->function_lib->get_one('count(id_kategori_buku)','kategori_buku','1');
		$data['jml_favorit'] = (int) $this->function_lib->get_one('count(id_favorit)','buku_favorit','1');
		$this->load->view('dashboard',$data,FALSE);
	}

}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */