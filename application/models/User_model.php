<?php
	class User_model extends CI_Model{

		public function __construct(){
			$this->load->database();
		}




	


		public function registerSelfDB($enc_password){
			// User data array
			$data = array(
				'userName' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'userPhone' => $this->input->post('phone'),
		       'pw_hash' => $enc_password,
			);
			// Insert user
			return $this->db->insert('users', $data);
		}

		public function update_user_himself($enc_password){
			// $slug = url_title($this->input->post('title'));
			$data = array(
				'userName' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'userPhone' => $this->input->post('phone'),
		       'pw_hash' => $enc_password,
			);
			$this->db->where('email', $this->input->post('email'));
			return $this->db->update('users', $data);
		}

		public function getAllBuildings()
		{
			$this->db->select('name, id');  
			$query = $this->db->get('buildings');
			return $query->result_array();
		}

		public function getRoleName($roleID)
		{
			$this->db->select('role');  
			$this->db->where('id', $roleID);
			$query = $this->db->get('userRoles');
			return $query->row()->role;
		}

		// public function getAllBuildingsICanGiveAccessTo($user_id)
		// {
		// 	$this->db->distinct();
		// 	$this->db->select('name, buildings.id');  
		// 	$this->db->join('userrights', 'buildings.id=userrights.buildingID ' , 'left');
		// 	$this->db->join('users', 'buildings.id = users.buildingID' , 'left');
		// 	$this->db->where('userrights.userID',$user_id);
		// 	$query = $this->db->get('buildings');
		// 	return $query->result_array();
		// }
		

		public function get_one_building_data($buildingID)
		{
			$this->db->select('name, id');  
			$this->db->where('id',$buildingID);
			$query = $this->db->get('buildings');
			return $query->result_array();
		
		}



		public function register($enc_password){
			// User data array
			$data = array(
				'userName' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'status' => $this->input->post('status'),
				'userPhone' => $this->input->post('phone'),
				'roleID' => $this->input->post('role'),
               'pw_hash' => $enc_password,
         
			);
			// Insert user
			return $this->db->insert('users', $data);
		}
		// Log user in
		function validate($email){
			$this->db->where('email',$email);
		//	$this->db->join('rooms', 'users.buildingID = rooms.buildingID' , 'left');
			$result = $this->db->get('users',1);
			return $result;
		  }

		  function getRoomID($buildingID){
			$this->db->select('id');  
			$this->db->where('buildingID',$buildingID);
			$result = $this->db->get('rooms');
			return $result->row_array();
		  }

		  function get_hash($email){
			$this->db->select('pw_hash');  
			$this->db->where('email',$email);
			$result = $this->db->get('users');
			return $result->row_array();

		  }
		  
		// Check email exists
		public function check_email_exists($email){
			$this->db->select('roleID, userID');  
			$query = $this->db->get_where('users', array('email' => $email));
			if(empty($query->row_array())){
				return false;
			} else {
				return $query->row_array();
			}
		}
		
		public function check_email_and_password_exists($email){
			$this->db->select('roleID, userID');  
			$this->db->where('pw_hash !=','');
			$query = $this->db->get_where('users', array('email' => $email));
			if(empty($query->row_array())){
				return false;
			} else {
				return $query->row_array();
			}
		}

		public function user_is_in_db($email){
			$this->db->select('userID');  
			$this->db->where('pw_hash','');
			$this->db->where('email',$email);
			$query = $this->db->get('users');
			print_r($email);
			if(empty($query->result_array())){
				return false;
			} else {
				return true;
			}
		}


		public function insert_user_in_DB_and_give_rights($data){
			return $this->db->insert('users', $data);
		}

		public function get_users($slug = FALSE){
			if($slug === FALSE){
				$this->db->order_by('users.roleID');
				$this->db->order_by('users.userName');
			//	$this->db->join('userrights', 'users.userID = userrights.userID' , 'left');
				$this->db->join('buildings', 'users.buildingID = buildings.id' , 'left');
				$this->db->join('userRoles', 'users.roleID = userRoles.id' , 'left');
				$query = $this->db->get('users');
				return $query->result_array();
			}
			$this->db->join('buildings', 'users.buildingID = buildings.id' , 'left');
			$this->db->join('userRoles', 'users.roleID = userRoles.id' , 'left');
			$query = $this->db->get_where('users', array('userID' => $slug));
			return $query->row_array();
		
		
		}


		public function delete_user($id){
			$this->db->where('userID', $id);
			$this->db->delete('users');
			return true;
		}

		public function delete_userrights($userID, $buildingID){
			$this->db->where('userID', $userID);
			$this->db->where('buildingID', $buildingID);
			$this->db->delete('userrights');
			return true;
		}


		public function create_category(){
			$data = array(
				'name' => $this->input->post('name'),
				'user_id' => $this->session->userdata('user_id')
			);
			return $this->db->insert('categories', $data);
		}


	
		public function update_user($data, $userID){
			// $slug = url_title($this->input->post('title'));
		
			$this->db->where('userID',$userID);
			return $this->db->update('users', $data);
		}



		var $table = "bookingTimes";  
		var $select_column = array("public_info","c_name","c_phone","c_email");  
		//järgmisel real kirjeldan ära millste lahtritega saab sorteerida
		var $order_column = array( "public_info", "c_name","c_phone","c_email");   

		function make_query()  
		{    $this->db->distinct();
			$data=$this->input->post();
			 $this->db->select($this->select_column);  
			 $this->db->join('bookings', 'bookingTimes.bookingID = bookings.id' , 'left');
			 $this->db->join('rooms', 'bookingTimes.roomID = rooms.id' , 'left');
			 $this->db->join('buildings', 'rooms.buildingID = buildings.id' , 'left');
			 $this->db->where('buildingID', ''. $this->session->userdata('building').'');
			 $this->db->where_not_in('public_info', "Suletud");

			 $this->db->from($this->table);
	
		
			
			 if(isset($data["search"]["value"]))  
			 {  
			
				 $this->db->group_start();
				 $this->db->like("startTime", $data["search"]["value"]);  
				  $this->db->or_like("LOWER(roomName)", mb_strtolower($data["search"]["value"]));  
				  $this->db->or_like("LOWER(workout)", mb_strtolower($data["search"]["value"]));  
			
				  $this->db->or_like("LOWER(public_info)", mb_strtolower($data["search"]["value"]));  
				  $this->db->or_like("LOWER(comment)", mb_strtolower($data["search"]["value"]));  
				  $this->db->or_like("LOWER(c_name)", mb_strtolower($data["search"]["value"]));  
				  $this->db->or_like("LOWER(c_phone)", mb_strtolower($data["search"]["value"]));  
				  $this->db->or_like("LOWER(c_email)", mb_strtolower($data["search"]["value"]));  
				  $this->db->group_end();
				//  $this->db->order_by('startTime', 'ASC');  
				
			 }  
			
			 
			 if(isset($data["order"]))  
			 {  
				  $this->db->order_by($this->order_column[$data['order']['0']['column']], $data['order']['0']['dir']);  
			 }  
			 else  
			 {  
				
				  $this->db->order_by('public_info', 'ASC'); 
				 }
				  
		
		
			
		}  
		function make_datatables(){  
			 $this->make_query();  
			 $data=$this->input->post();
			 if($data["length"] != -1)  
			 {  
				  $this->db->limit($data['length'], $data['start']);  
			 }  
			 
			 $query = $this->db->get();  
			 return $query->result();  
		}  
		function get_filtered_data(){  
			
			 $this->make_query(); 
			  
			 $query = $this->db->get();  
			 return $query->num_rows();  
		}       
		function get_all_data()  
		{  
		

			 $this->make_query(); 
			 $this->db->distinct();
			 $data=$this->input->post();
			 if(isset($data["is_date_search"])){
			 
				 $this->db->where('DATE(startTime) >=', date('Y-m-d H:i:s',strtotime($data["start_date"])));
				 $this->db->where('DATE(startTime) <=', date('Y-m-d H:i:s',strtotime($data["end_date"])));
			 
			  }
			 
			  return $this->db->count_all_results();  
		}  


		function check_if_user_has_already_rights_in_building($userID){

			$this->db->select('buildingID, userID, roleID');  
			$this->db->where('userID',$userID);
			$query = $this->db->get('users');
			return $query->row_array();
		
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

		function update_last_login($email){
			$data = array(
				'last_login' => date('Y-m-d H:i:s')
			);
			$this->db->where('email',$email);
			return $this->db->update('users', $data);
		}

		function getAllBuildingIdsWhereIAmAdmin($email){
			$this->db->select('userrights.buildingID, name');  
			$this->db->where('email',$email);
			$this->db->join('userrights', 'users.userID = userrights.userID' , 'left');
			$this->db->join('buildings', 'userrights.buildingID = buildings.id' , 'left');
			$query = $this->db->get('users');
			return $query->result_array();
		}
		
		function getBuildingName($buildingID){
			$this->db->select('name');  
			$this->db->where('id', $buildingID);
			$query = $this->db->get('buildings');
			return $query->row_array();
		}

		function get_user_buildingids_and_roleids($email, $buildingID){
			$this->db->select('userrights.buildingID, userrights.roleID');  
			$this->db->where('email',$email);
			$this->db->where('userrights.buildingID', $buildingID);
			$this->db->join('userrights', 'users.userID = userrights.userID' , 'left');
			$query = $this->db->get('users');
			if(empty($query->result_array())){
				return false;
			} else {
				return $query->result_array();
			}
		}
		function this_user_has_rights($user_id){
	
			$this->db->where('userID', $user_id);
			$query = $this->db->get('userrights');
			if(empty($query->result_array())){
				return false;
			} else {
				return $query->result_array();
			}
		}

		function this_user_has_rights_and_get_building_names($user_id){
			$this->db->select('role, name');  
			$this->db->where('userID', $user_id);
			$this->db->join('buildings', 'userrights.buildingID = buildings.id' , 'left');
			$this->db->join('userRoles', 'userrights.roleID = userRoles.id' , 'left');
			$query = $this->db->get('userrights');
			if(empty($query->result_array())){
				return false;
			} else {
				return $query->result_array();
			}
		}
		
		public function insert_old_rights($getOldRightsData, $roomID){

			$data = array(
				'userID' => $getOldRightsData['userID'],
				'buildingID' => $getOldRightsData['buildingID'],
				'roleID' => $getOldRightsData['roleID'],
		       'default_room_id' => $roomID,
			);

			return $this->db->insert('userrights', $data);
		}

		public function insert_new_rights($userID, $buildingID, $roleID, $roomID){

			$this->db->where('userID', $userID);
			$this->db->where('buildingID', $buildingID);
			$this->db->where('roleID', $roleID);
			$this->db->where('default_room_id', $roomID);
			$query = $this->db->get('userrights');
			if(empty($query->result_array())){
				$data = array(
					'userID' => $userID,
					'buildingID' => $buildingID,
					'roleID' => $roleID,
					'default_room_id' => $roomID,
				);
	
				return $this->db->insert('userrights', $data);
			} else {
				return false;
			}

			
		}

		
		public function get_room($buildingID){
			$this->db->select('id');  
			$this->db->where('buildingID', $buildingID);
			$query = $this->db->get('rooms');
			if(empty($query->result_array())){
				return false;
			} else {
				return $query->row()->id;
			}
			
		}

	}
