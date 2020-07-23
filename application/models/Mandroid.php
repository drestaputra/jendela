<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mandroid extends CI_Model {

	function cek_login_kolektor(){		
		$username=$this->input->post('username',TRUE);
		$password=$this->input->post('password',TRUE);
		$password=isset($password)?sha1($password):"";
		
		$where="username='".$username."' AND password='".$password."' AND status='aktif'";
		$this->db->where($where);
		$query=$this->db->get('kolektor');		
		$jumlah_cocok=$query->num_rows();
		$data_kolektor=array();
		if ($jumlah_cocok!=0) {
			$status=200;
			$msg="Berhasil Login";
			$data_kolektor=$query->row_array();
		}else{
			$status=500;
			$msg="Username atau password salah";
		}
		return array("status"=>$status,"msg"=>$msg,"data"=> $data_kolektor);
		
	}
	
	function get_akun($id){		
		$this->db->where('id_agen', $id);
		$query_agen=$this->db->get('agen');
		$this->db->where('id_jamaah', $id);
		$query_jamaah=$this->db->get('jamaah');
		$cocok_agen=$query_agen->num_rows();
		$cocok_jamaah=$query_jamaah->num_rows();
		if ($cocok_agen!=0) {
			$output=$query_agen->row_array();
			$output['nama_lengkap']=$output['nama_agen'];
			$output['id_user']=$output['id_agen'];
			$output['jenis_user']="agen";
			$output['foto']=$output['foto_agen'];
			$output['foto_ktp']=$output['foto_ktp_agen'];
		} elseif($cocok_jamaah!=0) {
			$output=$query_jamaah->row_array();
			$output['nama_lengkap']=$output['nama_jamaah'];
			$output['id_user']=$output['id_jamaah'];
			$output['foto']=$output['foto_jamaah'];
			$output['foto_ktp']=$output['foto_ktp_jamaah'];
			$output['jenis_user']="jamaah";
		}else{
			$output=null;
		}
		return $output;
	}
	function get_email($username){
		$this->db->where('username', $username);
		$query_agen=$this->db->get('agen');
		$this->db->where('username', $username);
		$query_jamaah=$this->db->get('jamaah');
		$cocok_agen=$query_agen->num_rows();
		$cocok_jamaah=$query_jamaah->num_rows();
		if ($cocok_agen!=0) {
			$output=$query_agen->row_array();
			
			$data_insert=array(
				"email"=>$output['email'],
				"jenis_user"=>"agen",
				"token"=>sha1(rand()),
				"username"=>$username
			);
			$this->db->insert('lupass', $data_insert);
			$this->db->insert('lupass', $data_insert);
			$this->db->where('username', $username);
			$this->db->order_by('id_lupass', 'desc');
			$query_token_agen=$this->db->get('lupass', 1, 0);
			$token_agen=$query_token_agen->row_array();
			$token=$token_agen['token'];
			$output['status']="200";
			$output['msg']=$output['email'];
			$output['token']=$token;
		} elseif($cocok_jamaah!=0) {
			$output=$query_jamaah->row_array();			
			$data_insert=array(
				"email"=>$output['email'],
				"jenis_user"=>"jamaah",
				"token"=>sha1(rand()),
				"username"=>$username
			);
			$this->db->insert('lupass', $data_insert);
			$this->db->where('username', $username);
			$this->db->order_by('id_lupass', 'desc');
			$query_token_jamaah=$this->db->get('lupass', 1, 0);
			$token_jamaah=$query_token_jamaah->row_array();
			$output['status']="200";
			$output['msg']=$output['email'];
			$output['token']=$token_jamaah['token'];

		}else{
			$output['status']=500;
			$output['msg']="Email tidak ditemukan";
		}
		return $output;
	}
	function lupass($kode){
		$password=$this->input->post('pwd',true);
		$where="token='".$kode."' AND is_active='1'";
		$this->db->where($where);
		$query=$this->db->get('lupass');
		$data_lupass=$query->row_array();

		if ($data_lupass['jenis_user']=="jamaah") {
			$data_update_jamaah=array(
				"password"=>sha1($password)
			);
			$this->db->where('username', $data_lupass['username']);
			$this->db->update('jamaah',$data_update_jamaah );
		}
		else if($data_lupass['jenis_user']=="agen") {
			$data_update_agen=array(
				"password"=>sha1($pwd)
			);
			$this->db->where('username', $data_lupass['username']);
			$this->db->update('agen',$data_update_agen );
		}		
		$this->db->where($where);
		$data_update_lupass=array(
			"is_active"=>"0"
		);
		$this->db->update('lupass', $data_update_lupass);
		return array("status"=>200,"msg"=>"Berhasil mengubah password, silahkan login");
	}
	function validasi_daftar(){
		$status=200;
		$msg="";
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required|min_length[3]|max_length[100]',
			 array(
                'required'      => '%s masih kosong',
                'max_length'	=> '%s maksimal 100 karakter',
                'min_length'	=> '%s minimal 3 karakter'

        	)
		);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim|required|min_length[1]|max_length[300]',
			 array(
                'required'      => '%s masih kosong',    
                'max_length'	=> '%s maksimal 300 karakter',
                'min_length'	=> '%s minimal 1 karakter'            
        	)
		);
		$this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique[kolektor.email]',
			array(
                'required'      => '%s masih kosong',
                'is_unique'     => '%s sudah terdaftar.'
        	)
		);
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[20]|is_unique[kolektor.username]',
			array(
                'required'      => '%s masih kosong',        
                'max_length'	=> '%s maksimal 20 karakter',
                'min_length'	=> '%s minimal 3 karakter',
                'is_unique'     => '%s sudah terdaftar.'
        	)
		);
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[40]',
			array(
                'required'      => '%s masih kosong',    
                'max_length'	=> '%s maksimal 40 karakter',
                'min_length'	=> '%s minimal 6 karakter'            
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
	function daftar(){
		$data_kolektor=null;
		 $validasi=$this->validasi_daftar();
		 if ($validasi['status']==200) {
		 	$post=$this->input->post();
		 	$post['password']=sha1($post['password']);
		 	$this->db->insert('kolektor', $post);
		 	$id_kolektor=$this->db->insert_id();
		 	if (trim($id_kolektor)) {		 		
		 		$data_kolektor=$this->function_lib->get_row('kolektor','id_kolektor="'.$id_kolektor.'"');
		 	}
		 }
		 return array("status"=>$validasi['status'],"msg"=>$validasi['msg'],"data"=>$data_kolektor);
	}

}

/* End of file Mandroid.php */
/* Location: ./application/models/Mandroid.php */