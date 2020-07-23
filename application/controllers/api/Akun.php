<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class Akun extends REST_Controller {
	
	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        if (!AUTHORIZATION::verify_request()) {        	
        	$response = ['status' => 401, 'msg' => 'Unauthorized Access! '];
        	echo json_encode($response);
        	exit();
        }
    }
    
	public function detail_post(){		
		$this->load->model('Mandroid');
		$post=$this->input->post();
		if ($post) {			
		$akun=$this->Mandroid->get_akun($post['id_user']);
		}
		echo("[".json_encode($akun)."]");		
	}
}

/* End of file Akun.php */
/* Location: ./application/controllers/android/Akun.php */