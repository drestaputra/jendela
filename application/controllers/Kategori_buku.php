<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_buku extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->load->model('Mkategori_buku');        
		if (empty($this->session->userdata('admin'))) {
			redirect('login');			
		}
	}
	public function index()
	{
		$this->load->view('kategori_buku/index');
	}

	function get_data() {        
        $params = isset($_POST) ? $_POST : array();
        $params['table'] = 'kategori_buku';

        $nama_kategori_buku=$this->input->get('nama_kategori_buku',true);
                
        $params['select'] = "
            *
        ";
        $params['join'] = "
        ";
        $params['where'] = "1";
      
        if(trim($nama_kategori_buku)!='')
        {
            $params['where'].=' AND nama_kategori_buku LIKE "%'.$nama_kategori_buku.'%"';
        }        
		
        $params['order_by'] = "
            id_kategori_buku DESC
        ";
   
        
        $query = $this->function_lib->db_query_execution($params);
        $total = $this->function_lib->db_query_execution($params, true);

        header("Content-type: application/json");
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $json_data = array('page' => $page, 'total' => $total, 'rows' => array());
        $prev_trx = '';
        $no = 0 + ($_POST['rp'] * ($page - 1));
        foreach ($query->result() as $row) {
            
            foreach($row AS $variable=>$value)
            {
                ${$variable}=$value;
            }
          $no++;
                      
            $actions='<a class="btn btn-xs btn-primary" href="'.base_url().'kategori_buku/edit/'.$id_kategori_buku.'" title="Edit"><i class="fa fa-pencil"></i></a>'.' '.'<a class="btn btn-xs btn-danger" onclick="return confirm(\'Yakin hapus?\');" href="'.base_url().'kategori_buku/hapus/'.$id_kategori_buku.'" title="Hapus"><i class="fa fa-trash"></i></a>';            
            $entry = array('id' => $id_kategori_buku,
                'cell' => array(
                    'actions' =>  $actions,
                    'no' =>  $no,                    
                    'nama_kategori_buku' =>(trim($nama_kategori_buku)!="")?$nama_kategori_buku:"",
                    'gambar_kategori_buku' =>(trim($gambar_kategori_buku)!="")?$gambar_kategori_buku:"",                    
                ),
            );
            $json_data['rows'][] = $entry;
            

        }

        echo json_encode($json_data);
    }

    public function tambah(){        
    	if ($this->input->post()) {
    		$validasi=$this->Mkategori_buku->validasi();
    		if ($validasi['status']==200) {
    			$this->Mkategori_buku->simpan();
    			redirect(base_url('kategori_buku'));
    		}else{    			
    			redirect(base_url('kategori_buku/tambah?status='.$validasi['status'].'&msg='.base64_encode($validasi['msg']).''));
    		}
    	}        
    	$this->load->view('kategori_buku/tambah',$data);
    }
    public function hapus($id_kategori_buku){
    	$cek_id=$this->function_lib->get_one('id_kategori_buku','kategori_buku','id_kategori_buku="'.$id_kategori_buku.'"');
    	if (trim($cek_id)!="") {
    		$this->Mkategori_buku->hapus($id_kategori_buku);
    		redirect(base_url('kategori_buku?status=200&msg='.base64_encode("Berhasil menghapus").''));
    	}else{
    		redirect(base_url('kategori_buku?status=500&msg='.base64_encode("Gagal menghapus").''));
    	}
    }
    public function edit($id_kategori_buku){
    	$data_kategori_buku=$this->function_lib->get_row('*','kategori_buku','id_kategori_buku="'.$id_kategori_buku.'"');
    	if (empty($data_kategori_buku)) {
    		redirect(base_url('kategori_buku?status=500&msg='.base64_encode("kategori_buku tidak ditemukan").''));
    	}
    	if ($this->input->post()) {
    		$validasi=$this->Mkategori_buku->validasi();
    		if ($validasi['status']=="200") {
    			$this->Mkategori_buku->edit($id_kategori_buku);
    			redirect(base_url('kategori_buku?status=200&msg='.base64_encode("Berhasil edit").''));
    		}else{
    			redirect(base_url('kategori_buku?status=500&msg='.base64_encode($validasi['msg']).''));
    		}

    	}        
    	$data['kategori_buku']=$data_kategori_buku;
    	$this->load->view('kategori_buku/edit', $data, FALSE);
    }

}

/* End of file kategori_buku.php */
/* Location: ./application/controllers/kategori_buku.php */