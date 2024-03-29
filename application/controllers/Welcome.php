<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Madmin');
	}
	public function index(){
		// redirect(base_url('login'));
	}
	public function login()
	{		
		$this->load->view('login');
		if ($this->input->post()) {
			$response=$this->Madmin->cek_login();
			if ($response['status']==200) {
				redirect(base_url('dashboard'));
			}else{
				redirect(base_url().'login?status='.$response['status'].'&msg='.base64_encode($response['msg']).'');
			}
		}
	}
	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url('login'));
	}
}
