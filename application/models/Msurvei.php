<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Msurvei extends CI_Model {


    public function data_survei($params,$custom_select='',$count=false,$additional_where='', $order_by="id_survei DESC")
    {
        
        $where_detail=' ';
        $where=" AND status='aktif'";        
        if($count==false)
        {
            $params['order_by'] =$order_by;
        }
        $order_by=$this->input->post('order_by');
        if (trim($order_by)!="") {
        	$params['order_by'] = $order_by;
        }
        $pencarian = $this->input->post('pencarian',TRUE);
        if (trim($pencarian)!="") {
            $where .= ' AND nama_survei LIKE "%'.$this->db->escape_str($pencarian).'%"';
        }

        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'survei';
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
        $this->form_validation->set_rules('nama_survei', 'Nama survei', 'trim|required|min_length[2]|max_length[200]',
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
        $config['upload_path']          = 'assets/survei';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 1000;        
        $config['overwrite'] = FALSE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('gambar_survei', FALSE)) {
            $status = 500;
            $msg = $this->upload->display_errors();
        }
        else
        {
            $config['image_library'] = 'gd2';
            $config['source_image'] = 'assets/survei/'.$this->upload->data('file_name');
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
            $nama_survei=$this->input->post('nama_survei',TRUE);            

            $data = $this->upload->data(); 
            $thumbnail = $data['raw_name'].'-'.$unique_time.$data['file_ext']; 

            $insertData = array(
                "nama_survei" => $nama_survei,
                "gambar_survei" => $thumbnail,
            );

            $status = 200;
            $msg = "Berhasil menambahkan Kategori";
            $post=$this->input->post();
            $this->db->insert('survei', $insertData);      
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    function hapus($id_survei){
        // $gambar_survei=$this->function_lib->get_one('gambar_survei','survei','id_survei="'.$id_survei.'"');
        
        // if (file_exists(FCPATH.'assets/survei/'.$gambar_survei) && $gambar_survei != "") {
        //     unlink(FCPATH.'assets/survei/'.$gambar_survei);
        // }           
        $status = $this->input->get('s',TRUE);
        $status = trim($status)!="" ? $status : "non_aktif";
        $columnUpdate = array(
            "status" => $status
        );
        $this->db->where('id_survei', $id_survei);
        $this->db->update('survei', $columnUpdate);
    }
    function edit($id_survei){
        $nama_survei=$this->input->post('nama_survei',TRUE);            
        $unique_time = time();
        $config['upload_path']          = 'assets/survei';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 1000;        
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $upload = $this->upload->do_upload('gambar_survei');
        if ($upload) {        
                $gambar_survei=$this->function_lib->get_one('gambar_survei','survei','id_survei="'.$id_survei.'"');
                if (file_exists(FCPATH.'assets/survei/'.$gambar_survei) && $gambar_survei != "") {
                    unlink(FCPATH.'assets/survei/'.$gambar_survei);                    
                }       
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/survei/'.$this->upload->data('file_name');
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
                    "nama_survei" => $nama_survei,
                    "gambar_survei" => $thumbnail,
                );
                
                $status = 200;
                $msg = "Berhasil mengubah Kategori";
                $this->db->where('id_survei', $id_survei);                
                $this->db->update('survei', $updateData); 
            
        }else{
           
                $status = 200;
                $msg = "Berhasil mengubah Kategori";
                $updateData = array(
                    "nama_survei" => $nama_survei,                
                );
                $this->db->where('id_survei', $id_survei);
                $post=$this->input->post();
                $this->db->update('survei', $updateData);            
        }
       return array("status"=>$status,"msg"=>$msg); 
    }      
}

/* End of file Msurvei.php */
/* Location: ./application/models/Msurvei.php */