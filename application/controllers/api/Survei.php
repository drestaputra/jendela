<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Survei extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Msurvei');
        if (!AUTHORIZATION::verify_request()) {         
            $response = ['status' => 401, 'msg' => 'Unauthorized Access! '];
            echo json_encode($response);
            exit();
        }
    }

    public function data_survei_post()
    {
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= "";
        
        $query_arr= $this->Msurvei->data_survei($params,$custom_select='',$count=false,$additional_where);        
        $query = $query_arr['query'];
        $total = $query_arr['total'];
        $status = $query_arr['status'];
        $msg = $query_arr['msg'];
        $response=$query->result_array(); 
        foreach ($response as $key => $value) {
       		$response[$key]['gambar_survei'] = base_url('assets/survei/').$value['gambar_survei'];
       	}       
        $perPage=((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total;
        if ($total!=0) {            
        $totalPage=ceil($total/$perPage)-1;
        }else{$totalPage=0;}       
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
    
}

/* End of file Survei.php */
/* Location: ./application/controllers/android/Survei.php */