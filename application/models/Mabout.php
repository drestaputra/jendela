<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mabout extends CI_Model {

	function get_about(){
		// SELECT * FROM `site_configuration` where configuration_index in ("app_dev","app_dev_web")
		$where='configuration_index in ("app_name","app_description","app_dev","app_dev_web")';
		$this->db->where($where);
		$this->db->order_by('configuration_index', 'ASC');
		$query=$this->db->get("site_configuration");
		return($query->result_array());
	}

}

/* End of file Mabout.php */
/* Location: ./application/models/Mabout.php */