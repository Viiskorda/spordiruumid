<?php
	class Region_model extends CI_Model{

		public function __construct(){
			$this->load->database();
		}


		public function get_region($slug ){
			
			$query = $this->db->get_where('regions', array('regions.regionID' => $slug));
			return $query->result_array();
		
		}

		function getAllRegions()
		{
		  
			$this->db->order_by('regionID');
			$query = $this->db->get('regions');
			return $query->result_array();
		}

		public function registerRegion(){
		
			$data = array(
				'regionName' => $this->input->post('region')
			);
		
			return $this->db->insert('regions', $data);
		}

	
		public function update_region($data){
			
		
			$this->db->where('regionID', $this->input->post('regionID'));

			return $this->db->update('regions', $data);
		}

		
		public function delete_region($id){
			$this->db->where('regionID', $id);
			$this->db->delete('regions');
			return true;
		}



	

	}