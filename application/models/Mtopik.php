<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mtopik extends CI_Model {

	function validasi(){
		$status=200;
		$msg="";
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama_topik', 'Nama topik', 'trim|required|min_length[2]|max_length[200]',
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
		$this->db->insert('topik', $post);		
	}
	function hapus($id_topik){
		$this->db->where('id_topik', $id_topik);
		$this->db->delete('topik');
	}
	function edit($id_topik){
		$this->db->where('id_topik', $id_topik);
		$post=$this->input->post();
		$this->db->update('topik', $post);
	}
}

/* End of file Mtopik.php */
/* Location: ./application/models/Mtopik.php */