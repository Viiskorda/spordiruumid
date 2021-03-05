<?php
	class Profile_model extends CI_Model{

		public function __construct(){
			$this->load->database();
        }

		public function get_profile($slug = FALSE){
			
			$this->db->join('buildings', ' buildings.id = users.buildingID' , 'left');
			$query = $this->db->get_where('users', array('userID' => $slug));
			return $query->result_array();
		
		}


		public function update_profile($data){
		
			
			$this->db->where('userID', $this->session->userdata['userID']);

			//siia tuleb parooliga seonduv

			//kõige pealt peab tegema validation, kas password==password2

			$data2 = array(
				//'pw_hash' => $this->input->post('password'),
				
			);

			return $this->db->update('users', $data);
		}

		
		function getRoomID($buildingID){
			$this->db->select('id');  
			$this->db->where('buildingID',$buildingID);
			$result = $this->db->get('rooms');
			return $result->row_array();
		  }
		public function get_my_buildingID(){

			$this->db->select('buildingID');  
			$this->db->where('userID',$this->session->userdata['userID']);
			$query = $this->db->get('users');
			return $query->row_array();
		}

	
		function get_hash($email){
			$this->db->select('pw_hash');  
			$this->db->where('email',$email);
			$result = $this->db->get('users');
			return $result->row_array();

		  }

		  function getUnapprovedBookings($buildingID )
		  {
			  $this->db->select("roomID, approved, startTime, id, buildingID");
			  $this->db->where('DATE(startTime) >=', date('Y-m-d'));
			  $this->db->join('rooms', 'bookingTimes.roomID = rooms.id' , 'left');
			  $this->db->where('rooms.buildingID', $buildingID);
			  $this->db->where('approved !=', 1);
			  $query = $this->db->count_all_results('bookingTimes');
	  
			  return $query;
		  }

		  function getBuildingName($buildingID){
			$this->db->select('name');  
			$this->db->where('id', $buildingID);
			$query = $this->db->get('buildings');
			return $query->row_array();
		}
    }
