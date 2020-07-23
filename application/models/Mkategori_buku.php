<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mkategori_buku extends CI_Model {


    public function data_kategori_buku($params,$custom_select='',$count=false,$additional_where='', $order_by="id_kategori_buku DESC")
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

        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'kategori_buku';
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
    function validasi(){
        $status=200;
        $msg="";
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama_kategori_buku', 'Nama kategori_buku', 'trim|required|min_length[2]|max_length[200]',
             array(
                'required'      => '%s masih kosong',                
            )
        );      
        if ($this->form_validation->run() == TRUE) {
            $status=200;
            $msg="Berhasil";
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    function simpan(){
        $post=$this->input->post();
        $this->db->insert('kategori_buku', $post);      
    }
    function hapus($id_kategori_buku){
        $this->db->where('id_kategori_buku', $id_kategori_buku);
        $this->db->delete('kategori_buku');
    }
    function edit($id_kategori_buku){
        $this->db->where('id_kategori_buku', $id_kategori_buku);
        $post=$this->input->post();
        $this->db->update('kategori_buku', $post);
    }      
}

/* End of file Mkategori_buku.php */
/* Location: ./application/models/Mkategori_buku.php */