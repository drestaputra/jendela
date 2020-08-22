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
        $pencarian=$this->input->post('pencarian',TRUE);        
        if (trim($pencarian)!="") {
            $where.=' AND (judul_buku like "%'.$this->db->escape_str($pencarian).'%" OR deskripsi_buku like "%'.$this->db->escape_str($pencarian).'%")';
        }
        $nama_survei = $this->input->post('nama_survei',TRUE);
        if (trim($nama_survei)!="") {
            $where .= ' AND id_kategori_buku IN (SELECT id_kategori_buku FROM kategori_buku WHERE id_survei IN (SELECT id_survei FROM survei WHERE UPPER(nama_survei) LIKE "%'.strtoupper($this->db->escape_str($nama_survei)).'%"))';
        }
        $id_kategori_buku = $this->input->post('id_kategori_buku',TRUE);
        $nama_kategori_buku = $this->input->post('nama_kategori_buku');
        if (trim($nama_kategori_buku) !="") {
            $where .= ' AND id_kategori_buku IN (SELECT id_kategori_buku FROM kategori_buku WHERE UPPER(nama_kategori_buku)="'.strtoupper($nama_kategori_buku).'")';            
        }else if(trim($id_kategori_buku) !=""){
            $where .= ' AND id_kategori_buku="'.$id_kategori_buku.'"';            
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

    function validasi(){
        $status=200;
        $msg="";
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_kategori_buku', 'Kategori Buku', 'trim|required',
             array(
                'required'      => '%s masih kosong',                
            )
        );   
        $this->form_validation->set_rules('judul_buku', 'Judul Buku', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );      
        $this->form_validation->set_rules('deskripsi_buku', 'Deskripsi Buku', 'trim|required',
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
        $config['upload_path']          = 'assets/image_buku';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 1000;        
        $config['overwrite'] = FALSE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('gambar_buku', FALSE)) {
            $status = 500;
            $msg = $this->upload->display_errors();
        }
        else
        {
            $config['image_library'] = 'gd2';
            $config['source_image'] = 'assets/image_buku/'.$this->upload->data('file_name');
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
            $id_kategori_buku=$this->input->post('id_kategori_buku');            
            $judul_buku=$this->input->post('judul_buku');            
            $deskripsi_buku=$this->input->post('deskripsi_buku');            

            $data = $this->upload->data(); 
            $thumbnail = $data['raw_name'].'-'.$unique_time.$data['file_ext']; 

            $insertData = array(
                "id_kategori_buku" => $id_kategori_buku,
                "judul_buku" => $judul_buku,
                "deskripsi_buku" => $deskripsi_buku,                
                "gambar_buku" => $thumbnail,
            );

            $status = 200;
            $msg = "Berhasil menambahkan buku";
            $post=$this->input->post();
            $this->db->insert('buku', $insertData);      
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    function edit($id_buku){
        $id_kategori_buku=$this->input->post('id_kategori_buku');            
        $judul_buku=$this->input->post('judul_buku',TRUE);            
        $deskripsi_buku=$this->input->post('deskripsi_buku');         
        $unique_time = time();
        $config['upload_path']          = 'assets/image_buku';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 1000;        
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $upload = $this->upload->do_upload('gambar_buku');        
        if ($upload) {        
                $gambar_buku=$this->function_lib->get_one('gambar_buku','buku','id_buku="'.$id_buku.'"');
                if (file_exists(FCPATH.'assets/image_buku/'.$gambar_buku) && $gambar_buku != "") {
                    unlink(FCPATH.'assets/image_buku/'.$gambar_buku);                    
                }       
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/image_buku/'.$this->upload->data('file_name');
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
                    "id_kategori_buku" => $id_kategori_buku,
                    "judul_buku" => $judul_buku,
                    "deskripsi_buku" => $deskripsi_buku,                
                    "gambar_buku" => $thumbnail,
                );
                
                $status = 200;
                $msg = "Berhasil mengubah Kategori";
                $this->db->where('id_buku', $id_buku);                
                $this->db->update('buku', $updateData); 
            
        }else{
            // tidak ada perubahan upload file            
            $status = 200;
            $msg = "Berhasil mengubah Buku";
            $updateData = array(
               "id_kategori_buku" => $id_kategori_buku,
                "judul_buku" => $judul_buku,
                "deskripsi_buku" => $deskripsi_buku,   
            );
            $this->db->where('id_buku', $id_buku);
            $post=$this->input->post();
            $this->db->update('buku', $updateData);             
        }
       return array("status"=>$status,"msg"=>$msg); 
    }      
    function hapus($id_buku){
        $gambar_buku=$this->function_lib->get_one('gambar_buku','buku','id_buku="'.$id_buku.'"');
        
        if (file_exists(FCPATH.'assets/image_buku/'.$gambar_buku) && $gambar_buku != "") {
            unlink(FCPATH.'assets/image_buku/'.$gambar_kategori_buku);
        }        
        echo "</pre>";
        $this->db->where('id_buku', $id_buku);
        $this->db->delete('buku');
    }

}

/* End of file Mbuku.php */
/* Location: ./application/models/Mbuku.php */