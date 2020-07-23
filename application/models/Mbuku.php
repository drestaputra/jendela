<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mbuku extends CI_Model {


    public function data_buku($params,$custom_select='',$count=false,$additional_where='', $order_by="id_buku DESC")
    {
        
        $where_detail=' ';
        $where=" ";        
        if($count==false)
        {
            $params['order_by'] =$order_by;
        }
        $order_by=$this->input->post('order_by');
        if (trim($order_by)) {
        	$params['order_by'] = $order_by;
        }
        $pencarian=$this->input->post('pencarian');
        if (trim($pencarian)!="") {
            $where.=' AND (judul_buku like "%'.$pencarian.'%" OR deskripsi_buku like "%'.$pencarian.'%")';
        }
        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'buku';
        $params['select'] = '*';
        
        if(trim($custom_select)!='')
        {
            $params['select'] = $custom_select;
        }
        $params['where_detail'] =" 1
        ".$where_detail.' '.$where;
        
        return array(
            'status'=>200,
            'msg'=>"sukses",
            'query'=>$this->function_lib->db_query_execution($params,false),
            'total'=>$this->function_lib->db_query_execution($params, true),
        );
    }        
    public function setFavorit(){
    	$status=500;
    	$msg="";
    	$device_id=$this->input->post('device_id');
    	$id_buku=$this->input->post('id_buku');
    	$cek=$this->function_lib->get_one('id_favorit','buku_favorit','device_id="'.$device_id.'" AND id_buku="'.$id_buku.'"');
    	if (trim($cek)) {
    		$this->db->where('id_favorit', $cek);
    		$this->db->delete('buku_favorit');
    		$status=200;
    		$msg="hapus";
    		
    	}else{
    		$insertColumn=array(
    			"device_id"=>$device_id,
    			"id_buku"=>$id_buku
    		);
    		$this->db->insert('buku_favorit', $insertColumn);
    		$status=200;
    		$msg="tambah";
    	}
    	return array("status"=>$status,"msg"=>$msg);
    }

}

/* End of file Mbuku.php */
/* Location: ./application/models/Mbuku.php */