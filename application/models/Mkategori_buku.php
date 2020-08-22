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
        if (trim($order_by)!="") {
        	$params['order_by'] = $order_by;
        }
        $id_survei=$this->input->post('id_survei');
        if (trim($id_survei)!="") {
            $where .= ' AND id_survei ="'.$id_survei.'"';
        }
        $pencarian = $this->input->post('pencarian',TRUE);
        if (trim($pencarian)!="") {
            $where .= ' AND upper(nama_kategori_buku) = "'.strtoupper($this->db->escape_str($pencarian)).'"';
        }
        $nama_survei = $this->input->post('nama_survei');
        if (trim($nama_survei)!="") {
            $where .= ' AND id_survei IN (SELECT id_survei FROM survei WHERE UPPER(nama_survei)="'.$nama_survei.'" )';
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
        $this->form_validation->set_rules('id_survei', 'Survei', 'trim|required',
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
        $unique_time = time();
        $config['upload_path']          = 'assets/kategori_buku';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 1000;        
        $config['overwrite'] = FALSE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('gambar_kategori_buku', FALSE)) {
            $status = 500;
            $msg = $this->upload->display_errors();
        }
        else
        {
            $config['image_library'] = 'gd2';
            $config['source_image'] = 'assets/kategori_buku/'.$this->upload->data('file_name');
            $config['create_thumb'] = TRUE;            
            $config['thumb_marker'] = '-' . $unique_time;        
            $config['maintain_ratio'] = TRUE;            
            $config['width'] = 500;
            $config['heigt'] = 500;
            $this->upload->overwrite = true;
            $this->load->library('image_lib');            
            $this->image_lib->clear();
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $nama_kategori_buku=$this->input->post('nama_kategori_buku',TRUE);            
            $id_survei=$this->input->post('id_survei',TRUE);            

            $data = $this->upload->data(); 
            $thumbnail = $data['raw_name'].'-'.$unique_time.$data['file_ext']; 

            $insertData = array(
                "nama_kategori_buku" => $nama_kategori_buku,
                "gambar_kategori_buku" => $thumbnail,
                "id_survei" => $id_survei,
            );

            $status = 200;
            $msg = "Berhasil menambahkan Kategori";
            $post=$this->input->post();
            $this->db->insert('kategori_buku', $insertData);      
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    function hapus($id_kategori_buku){
        // $gambar_kategori_buku=$this->function_lib->get_one('gambar_kategori_buku','kategori_buku','id_kategori_buku="'.$id_kategori_buku.'"');
        
        // if (file_exists(FCPATH.'assets/kategori_buku/'.$gambar_kategori_buku) && $gambar_kategori_buku != "") {
        //     unlink(FCPATH.'assets/kategori_buku/'.$gambar_kategori_buku);
        // }        
        // echo "</pre>";
        $status = $this->input->get('s',TRUE);
        $status = trim($status)!="" ? $status : "non_aktif";
        $columnUpdate = array(
            "status" => $status
        );
        $this->db->where('id_kategori_buku', $id_kategori_buku);
        $this->db->update('kategori_buku', $columnUpdate);
    }
    function edit($id_kategori_buku){
        $nama_kategori_buku=$this->input->post('nama_kategori_buku',TRUE);            
        $id_survei=$this->input->post('id_survei',TRUE);            
        $unique_time = time();
        $config['upload_path']          = 'assets/kategori_buku';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 1000;        
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $upload = $this->upload->do_upload('gambar_kategori_buku');
        if ($upload) {        
                $gambar_kategori_buku=$this->function_lib->get_one('gambar_kategori_buku','kategori_buku','id_kategori_buku="'.$id_kategori_buku.'"');
                if (file_exists(FCPATH.'assets/kategori_buku/'.$gambar_kategori_buku) && $gambar_kategori_buku != "") {
                    unlink(FCPATH.'assets/kategori_buku/'.$gambar_kategori_buku);                    
                }       
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/kategori_buku/'.$this->upload->data('file_name');
                $config['create_thumb'] = TRUE;            
                $config['thumb_marker'] = '-' . $unique_time;        
                $config['maintain_ratio'] = TRUE;            
                $config['width'] = 500;
                $config['heigt'] = 500;
                $config['overwrite'] = TRUE;
                $this->load->library('image_lib');            
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                

                $data = $this->upload->data(); 
                $thumbnail = $data['raw_name'].'-'.$unique_time.$data['file_ext']; 

                $updateData = array(
                    "nama_kategori_buku" => $nama_kategori_buku,
                    "gambar_kategori_buku" => $thumbnail,
                    "id_survei" => $id_survei,
                );
                
                $status = 200;
                $msg = "Berhasil mengubah Kategori";
                $this->db->where('id_kategori_buku', $id_kategori_buku);                
                $this->db->update('kategori_buku', $updateData); 
            
        }else{
           
                $status = 200;
                $msg = "Berhasil mengubah Kategori";
                $updateData = array(
                    "nama_kategori_buku" => $nama_kategori_buku,   
                    "id_survei" => $id_survei,             
                );
                $this->db->where('id_kategori_buku', $id_kategori_buku);
                $post=$this->input->post();
                $this->db->update('kategori_buku', $updateData);            
        }
       return array("status"=>$status,"msg"=>$msg); 
    }      
}

/* End of file Mkategori_buku.php */
/* Location: ./application/models/Mkategori_buku.php */