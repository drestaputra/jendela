<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Madmin extends CI_Model {
	function cek_login(){
		// username:adminu
		// pass:admin123
		$password=hash('sha512',$this->input->post('pwd') . config_item('encryption_key'));		
		$this->db->where('username', $this->input->post('username',TRUE));
		$this->db->where('password', $password);
		$query=$this->db->get('admin');
		if ($query->num_rows()!=null) {			
		$data=$query->row_array();					
			$this->session->set_userdata("admin",$data);		
			$this->session->set_userdata('file_manager',true);
			return true;
		}else{
			return false;
		}
	}
	

}

/* End of file Madmin.php */
/* Location: ./application/models/Madmin.php */