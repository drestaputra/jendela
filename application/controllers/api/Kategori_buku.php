<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Kategori_buku extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mkategori_buku');
        if (!AUTHORIZATION::verify_request()) {         
            $response = ['status' => 401, 'msg' => 'Unauthorized Access! '];
            echo json_encode($response);
            exit();
        }
    }

    public function data_kategori_buku_post()
    {
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= "";
        
        $query_arr= $this->Mkategori_buku->data_kategori_buku($params,$custom_select='',$count=false,$additional_where);        
        $query = $query_arr['query'];
        $total = $query_arr['total'];
        $status = $query_arr['status'];
        $msg = $query_arr['msg'];
        $response=$query->result_array();        
        $perPage=((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total;
        if ($total!=0) {            
        $totalPage=ceil($total/$perPage)-1;
        }else{$totalPage=0;}       
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
    
}

/* End of file Kategori_buku.php */
/* Location: ./application/controllers/android/Kategori_buku.php */