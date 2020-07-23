<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Dresta Twas Ardha Putra
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Login extends REST_Controller  {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();        
        if (!AUTHORIZATION::verify_request()) {        	
        	$response = ['status' => 401, 'msg' => 'Unauthorized Access! '];
        	echo json_encode($response);
        	exit();
        }
    }

	public function kolektor_post(){
		$this->load->library('form_validation');
		$response=array("status"=>500,"msg"=>"Gagal Login");
		$post=$this->input->post();
		if ($post) {
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			if ($this->form_validation->run() == TRUE) {
				$this->load->model('Mandroid');
				$hasil=$this->Mandroid->cek_login_kolektor();
				if ($hasil['status']==200) {
					$response["status"]=200;
					$response["msg"]=$hasil['msg'];
					$response["data"]=$hasil["data"];
				}else{
					$response["status"]=500;
					$response["msg"]=$hasil['msg'];
				}				
			}else{
				$response["status"]=500;
				$response["msg"]=validation_errors('','');
			}
		} else {
			$response["status"]=500;
			$response["msg"]="Terjadi kesalahan jaringan silahkan coba lagi";
		}
		$this->response($response);
		
	}	

	public function lupass(){
		$this->load->model('Mandroid');
		$post=$this->input->post();
		$this->load->library('form_validation');					
		$data['status']=500;									
		$data['msg']="Gagal silahkan coba lagi";

		if ($post) {			
			$this->form_validation->set_rules('username', 'username', 'trim|required');
			if ($this->form_validation->run() == TRUE) {				
				$username=$this->input->post('username',TRUE);
				$email=$this->Mandroid->get_email($username);
				if ($email['status']==200) {
					$this->load->model('Mmail');
					$data_email['token']=$email['token'];
					$message=$this->load->view('template_mail_lupass', $data_email, TRUE);
					$this->Mmail->kirim_email($email['msg'],"Biro Umroh Cilacap","Permintaan Perubahaan Password",$message);
					$data['status']=200;									
					$data['msg']="Email telah dikirim, silahkan cek email untuk mengubah password";	
				}else{
					$data['status']=500;									
					$data['msg']=$email['msg'];	
				}
			} else {
				$data['status']=500;									
				$data['msg']="Gagal silahkan coba lagi";
			}			
			echo("[".json_encode($data)."]");
		}		
		// $this->load->view('android/daftar_agen', $data, FALSE);
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/android/Login.php */