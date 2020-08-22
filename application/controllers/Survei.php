<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survei extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->load->model('Msurvei');        
		if (empty($this->session->userdata('admin'))) {
			redirect('login');			
		}
	}
	public function index()
	{
		$this->load->view('survei/index');
	}

	function get_data() {        
        $params = isset($_POST) ? $_POST : array();
        $params['table'] = 'survei';

        $nama_survei=$this->input->get('nama_survei',true);
        $status_survei=$this->input->get('status_survei',true);
                
        $params['select'] = "
            *
        ";
        $params['join'] = "
        ";
        $params['where'] = " status!='hapus'";
      
        if(trim($nama_survei)!='')
        {
            $params['where'].=' AND nama_survei LIKE "%'.$nama_survei.'%"';
        }        
        if(trim($status_survei)!='')
        {
            $params['where'].=' AND status = "'.$status_survei.'"';
        }        
		
        $params['order_by'] = "
            id_survei DESC
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
                      
            $actions='<a class="btn btn-xs btn-primary" href="'.base_url().'survei/edit/'.$id_survei.'" title="Edit"><i class="fa fa-pencil"></i></a>';            
            if ($status == "aktif") {
                $actions .=' <a class="btn btn-xs btn-danger" onclick="return confirm(\'Yakin hapus?\');" href="'.base_url().'survei/hapus/'.$id_survei.'?s=hapus" title="Hapus"><i class="fa fa-trash"></i></a>';            
                $actions .= ' <a class="btn btn-xs btn-warning" onclick="return confirm(\'Yakin me non-aktifkan?\');" href="'.base_url().'survei/hapus/'.$id_survei.'?s=non_aktif" title="Non Aktif"><i class="fa fa-power-off"></i></a>';            
            }else if($status == "non_aktif"){
                $actions .= ' <a class="btn btn-xs btn-danger" onclick="return confirm(\'Yakin hapus?\');" href="'.base_url().'survei/hapus/'.$id_survei.'?s=hapus" title="Hapus"><i class="fa fa-trash"></i></a>';            
                $actions .= ' <a class="btn btn-xs btn-success" onclick="return confirm(\'Yakin aktifkan kembali?\');" href="'.base_url().'survei/hapus/'.$id_survei.'?s=aktif" title="Aktif"><i class="fa fa-check-circle"></i></a>';
            }
            $gambar_survei_img = '<img class="img-responsive" style="height=\'200px\';width=\'200px;\'" src="'.base_url('assets/survei/').$gambar_survei.'">';
            $entry = array('id' => $id_survei,
                'cell' => array(
                    'actions' =>  $actions,
                    'no' =>  $no,                    
                    'nama_survei' =>(trim($nama_survei)!="")?$nama_survei:"",
                    'gambar_survei' =>(trim($gambar_survei)!="")?$gambar_survei_img:"",                    
                ),
            );
            $json_data['rows'][] = $entry;
            

        }

        echo json_encode($json_data);
    }

    public function tambah(){        
    	if ($this->input->post()) {
    		$validasi=$this->Msurvei->validasi();
    		if ($validasi['status']==200) {
    			$response = $this->Msurvei->simpan();
    			redirect(base_url('survei/index?status='.$response['status'].'&msg='.base64_encode($response['msg']).''));
    		}else{    			
    			redirect(base_url('survei/tambah?status='.$validasi['status'].'&msg='.base64_encode($validasi['msg']).''));
    		}
    	}        
    	$this->load->view('survei/tambah',null);
    }
    public function hapus($id_survei){
    	$cek_id=$this->function_lib->get_one('id_survei','survei','id_survei="'.$id_survei.'"');
    	if (trim($cek_id)!="") {
    		$this->Msurvei->hapus($id_survei);
    		redirect(base_url('survei?status=200&msg='.base64_encode("Berhasil mengubah status").''));
    	}else{
    		redirect(base_url('survei?status=500&msg='.base64_encode("Gagal mengubah status").''));
    	}
    }
    public function edit($id_survei){
    	$data_survei=$this->function_lib->get_row('survei','id_survei="'.$id_survei.'"');
    	if (empty($data_survei)) {
    		redirect(base_url('survei?status=500&msg='.base64_encode("survei tidak ditemukan").''));
    	}
    	if ($this->input->post()) {
    		$validasi=$this->Msurvei->validasi();
    		if ($validasi['status']=="200") {
    			$response=$this->Msurvei->edit($id_survei);
    			redirect(base_url('survei?status='.$response['status'].'&msg='.base64_encode($response['msg']).''));
    		}else{
    			redirect(base_url('survei?status=500&msg='.base64_encode($validasi['msg']).''));
    		}

    	}        
    	$data['survei']=$data_survei;
    	$this->load->view('survei/edit', $data, FALSE);
    }

}

/* End of file survei.php */
/* Location: ./application/controllers/survei.php */