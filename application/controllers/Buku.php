<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buku extends CI_Controller {

	public function __construct()
	{
		parent::__construct();        
        $this->load->model('Mbuku');        
		if (empty($this->session->userdata('admin'))) {
			redirect('login');			
		}
	}
	public function index()
	{
        $data['survei'] = $this->function_lib->findAll('status!="hapus"','survei','nama_survei ASC');
		$this->load->view('buku/index',$data,FALSE);
	}
    function getKategori(){
        header("Content-type: application/json");
        $id_survei = $this->input->get('id_survei',TRUE);
        $kategori = $this->function_lib->findAll('status!="hapus" AND id_survei="'.$id_survei.'"','kategori_buku','nama_kategori_buku ASC');
        echo json_encode(array("status"=>200,"msg"=>"","data"=>$kategori));
    }
	function get_data() {        
        $params = isset($_POST) ? $_POST : array();
        $params['table'] = 'buku';

        $judul_buku=$this->input->get('judul_buku',true);
        $deskripsi=$this->input->get('deskripsi',true);
        $id_survei=$this->input->get('survei',true);
        $id_kategori_buku=$this->input->get('kategori_buku',true);
                
        $params['select'] = "
            *
        ";
        $params['join'] = "
        ";
        $params['where'] = "1";
      
        if(trim($judul_buku)!='')
        {
            $params['where'].=' AND judul_buku LIKE "%'.$judul_buku.'%"';
        }        
        if(trim($deskripsi)!='')
        {
            $params['where'].=' AND deskripsi_buku LIKE "%'.$deskripsi.'%"';
        }
        if(trim($id_survei)!='')
        {
            $params['where'].=' AND id_kategori_buku IN (SELECT id_kategori_buku from kategori_buku WHERE id_survei IN (SELECT id_survei FROM survei WHERE id_survei="'.$id_survei.'"))';
        }
        if(trim($id_kategori_buku)!='')
        {
            $params['where'].=' AND id_kategori_buku = "'.$id_kategori_buku.'"';
        }


		
        $params['order_by'] = "
            id_buku DESC
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
                      
            $actions='<a class="btn btn-xs btn-primary" href="'.base_url().'buku/edit/'.$id_buku.'" title="Edit"><i class="fa fa-pencil"></i></a>'.' '.'<a class="btn btn-xs btn-danger" onclick="return confirm(\'Yakin hapus?\');" href="'.base_url().'buku/hapus/'.$id_buku.'" title="Hapus"><i class="fa fa-trash"></i></a>';            
            $gambar_buku_img = '<img class="img-responsive" style="height=\'200px\';width=\'200px;\'" src="'.base_url('assets/image_buku/').$gambar_buku.'">';
            $jumlah_favorit=$this->function_lib->get_one('count(id_favorit)','buku_favorit','id_buku="'.$id_buku.'"');
            $kategori_buku = $this->function_lib->get_one('nama_kategori_buku','kategori_buku','id_kategori_buku="'.$id_kategori_buku.'"');
            $entry = array('id' => $id_buku,
                'cell' => array(
                    'actions' =>  $actions,
                    'no' =>  $no,                    
                    'judul_buku' =>(trim($judul_buku)!="")?$judul_buku:"",
                    'deskripsi_buku' =>(trim($deskripsi_buku)!="")?substr(strip_tags($deskripsi_buku), 0,100):"",
                    // 'rating_buku' =>(trim($rating_buku)!="")?$rating_buku:"",                                        
                    'jumlah_view' =>(trim($jumlah_view)!="")?$jumlah_view:"",                                        
                    'jumlah_favorit' =>(trim($jumlah_favorit)!="")?$jumlah_favorit:"",                                        
                    'file_buku' =>(trim($file_buku)!="")?$file_buku:"",                                        
                    'gambar_buku' =>(trim($gambar_buku)!="")?$gambar_buku_img:"",                                       
                    'kategori_buku' =>(trim($kategori_buku)!="")?$kategori_buku:"",                    
                ),
            );
            
            $json_data['rows'][] = $entry;            
            
            

        }

        echo json_encode($json_data);
    }

    public function tambah(){        
    	if ($this->input->post()) {
    		$validasi=$this->Mbuku->validasi();

    		if ($validasi['status']==200) {
    			$response = $this->Mbuku->simpan();
    			redirect(base_url('buku/index?status='.$response['status'].'&msg='.base64_encode($response['msg']).''));
    		}else{    			
    			redirect(base_url('buku/tambah?status='.$validasi['status'].'&msg='.base64_encode($validasi['msg']).''));
    		}
    	}   
        $data['kategori_buku'] = $this->function_lib->findAllCustom('1','kategori_buku','id_kategori_buku, nama_kategori_buku','nama_kategori_buku ASC');
    	$this->load->view('buku/tambah', $data, FALSE);
    }
    public function hapus($id_buku){
    	$cek_id=$this->function_lib->get_one('id_buku','buku','id_buku="'.$id_buku.'"');
    	if (trim($cek_id)!="") {
    		$this->Mbuku->hapus($id_buku);
    		redirect(base_url('buku?status=200&msg='.base64_encode("Berhasil menghapus").''));
    	}else{
    		redirect(base_url('buku?status=500&msg='.base64_encode("Gagal menghapus").''));
    	}
    }
    public function edit($id_buku){
    	$data_buku=$this->function_lib->get_row('buku','id_buku="'.$id_buku.'"');
    	if (empty($data_buku)) {
    		redirect(base_url('buku?status=500&msg='.base64_encode("buku tidak ditemukan").''));
    	}
    	if ($this->input->post()) {
    		$validasi=$this->Mbuku->validasi();
    		if ($validasi['status']=="200") {
    			$response=$this->Mbuku->edit($id_buku);
    			redirect(base_url('buku?status='.$response['status'].'&msg='.base64_encode($response['msg']).''));
    		}else{
    			redirect(base_url('buku?status=500&msg='.base64_encode($validasi['msg']).''));
    		}

    	}     
        $data['kategori_buku'] = $this->function_lib->findAllCustom('1','kategori_buku','id_kategori_buku, nama_kategori_buku','nama_kategori_buku ASC');   
    	$data['buku']=$data_buku;
    	$this->load->view('buku/edit', $data, FALSE);
    }

}

/* End of file buku.php */
/* Location: ./application/controllers/buku.php */