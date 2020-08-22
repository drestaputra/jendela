<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Buku extends Rest_Controller {

	function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Mbuku');
        if (!AUTHORIZATION::verify_request()) {         
            $response = ['status' => 401, 'msg' => 'Unauthorized Access! '];
            echo json_encode($response);
            exit();
        }
    }

    public function data_buku_post()
    {
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        
        $additional_where= "";
        
        $query_arr= $this->Mbuku->data_buku($params,$custom_select='',$count=false,$additional_where);        
        $query = $query_arr['query'];
        $total = $query_arr['total'];
        $status = $query_arr['status'];
        $msg = $query_arr['msg'];
        $response=$query->result_array();        
        $perPage=((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total;
        if ($total!=0) {            
        $totalPage=ceil($total/$perPage)-1;
        }else{$totalPage=0;}
        foreach ($response as $key => $value) {
            $response[$key]['kategori']=$this->function_lib->get_row('kategori_buku','id_kategori_buku="'.$value['id_kategori_buku'].'"');
            $response[$key]['gambar_buku'] = isset($response[$key]['gambar_buku']) ? $response[$key]['gambar_buku'] : "";
            $response[$key]['gambar_buku'] = base_url('assets/image_buku/').$response[$key]['gambar_buku'];
        }
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
     public function detail_buku_post()
    {
        $id_buku=$this->input->post('id_buku');
        $dataBuku=$this->function_lib->get_row('buku','id_buku="'.$id_buku.'"');
        if (!empty($dataBuku)) {
            $dataBuku['deskripsi_buku'] = str_replace('/assets/kcfinder/', base_url().'/assets/kcfinder/', $dataBuku['deskripsi_buku']);
            $dataBuku['gambar_buku'] = base_url('assets/image_buku/').$dataBuku['gambar_buku'];
        }
        $this->response($dataBuku);    
    }
    public function data_buku_favorit_post()
    {
        $params = isset($_POST) ? $_POST : array();
        $start = (int)$this->input->post('page');
        $device_id=$this->input->post('device_id');
        $additional_where= 'AND id_buku in (SELECT id_buku from buku_favorit where device_id="'.$device_id.'")';
        
        $query_arr= $this->Mbuku->data_buku($params,$custom_select='',$count=false,$additional_where);        
        $query = $query_arr['query'];
        $total = $query_arr['total'];
        $status = $query_arr['status'];
        $msg = $query_arr['msg'];
        $response=$query->result_array();        
        $perPage=((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total;
        if ($total!=0) {            
        $totalPage=ceil($total/$perPage)-1;
        }else{$totalPage=0;}
        foreach ($response as $key => $value) {
            $response[$key]['kategori']=$this->function_lib->get_row('kategori_buku','id_kategori_buku="'.$value['id_kategori_buku'].'"');
            $response[$key]['gambar_buku'] = isset($response[$key]['gambar_buku']) ? $response[$key]['gambar_buku'] : "";
            $response[$key]['gambar_buku'] = base_url('assets/image_buku/').$response[$key]['gambar_buku'];
        }
        $json_data = array('status'=>$status,'msg'=>$msg,'page' => $start,'totalPage'=>$totalPage, 'recordsFiltered' => ((int)$this->input->post('perPage')>0)?$this->input->post('perPage'):$total, 'totalRecords' => $total, 'data' => $response);
       

        $this->response($json_data);    
    }
    public function favorit_post(){
        $device_id=$this->input->post('device_id');
        if (trim($device_id)) {
            $response=$this->Mbuku->setFavorit();
            $this->response($response);
        }
    }
}

/* End of file Buku.php */
/* Location: ./application/controllers/android/Buku.php */