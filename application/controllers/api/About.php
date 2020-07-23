<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class About extends REST_Controller {
	
	function __construct($config = 'rest') {
        parent::__construct($config);        
        $this->load->database();
        $this->load->model('Mabout');
        if (!AUTHORIZATION::verify_request()) {        	
        	$response = ['status' => 401, 'msg' => 'Unauthorized Access! '];
        	echo json_encode($response);
        	exit();
        }
    }
    public function index_get()
    {         
     	$data=$this->Mabout->get_about();
     	$status=200;
     	$msg="Berhasil";
     	$this->response(array("status"=>$status,"msg"=>$msg,"data"=>$data));     
    }
}

/* End of file About.php */
/* Location: ./application/controllers/android/About.php */