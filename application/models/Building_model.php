<?php
	class Building_model extends CI_Model{

		public function __construct(){
			$this->load->database();
		}

		public function get_building($slug = FALSE){
			if($slug === FALSE){
			$this->db->order_by('buildings.id');
			//$this->db->join('rooms', ' buildings.id = rooms.buildingID' , 'left');
			$this->db->join('regions', ' buildings.regionID = regions.regionID' , 'left');
			$query = $this->db->get('buildings');
			return $query->result_array();
			}
			$this->db->join('rooms', ' buildings.id = rooms.buildingID' , 'left');
			$this->db->join('regions', ' buildings.regionID = regions.regionID' , 'left');
			$query = $this->db->get_where('buildings', array('buildings.id' => $slug));
			return $query->result_array();
		
		}

		
		public function getBookingformData($buildingID){
			$this->db->where('buildingID', $buildingID);
			$query = $this->db->get('bookingFormSettings');
			return $query->row_array();
		}

		public function getBookingformDataDetailsOnce($buildingID){
			$this->db->where('buildingID', $buildingID);
			$this->db->where('typeID', '1');
			$query = $this->db->get('bookingFormSettingsDetails');
			return $query->row_array();
		}
		public function getBookingformDataDetailsPeriod($buildingID){
			$this->db->where('buildingID', $buildingID);
			$this->db->where('typeID', '2');
			$query = $this->db->get('bookingFormSettingsDetails');
			return $query->row_array();
		}
		public function getBookingformDataDetailsEvent($buildingID){
			$this->db->where('buildingID', $buildingID);
			$this->db->where('typeID', '3');
			$query = $this->db->get('bookingFormSettingsDetails');
			return $query->row_array();
		}


		public function get_rooms(){
			$this->db->order_by('buildingID');
			$query = $this->db->get('rooms');
			return $query->result_array();
		}

		public function get_room($roomID){
			$this->db->where('id', $roomID);
			$query = $this->db->get('rooms');
			return $query->row_array();
		}

		public function get_all_roomsID_from_one_building($buildingID){
			$this->db->select('id');
			$this->db->where('buildingID', $buildingID);
			$query = $this->db->get('rooms');
			return $query->result();
		}

		public function does_building_have_admin($buildingID){
			$this->db->where('buildingID', $buildingID);
			$query = $this->db->get('users');
			return $query->row_array();
		}


		public function delete_building($id){
			$this->db->where('id', $id);
			$this->db->delete('buildings');
			return true;
		}
		public function delete_building_settings($id){
			$this->db->where('buildingID', $id);
			$this->db->delete('bookingFormSettings');
			return true;
		}
		

		public function check_if_room_has_reservations_only_in_past($id){
			$query1 = $this->db->get_where('bookingTimes', array('roomID' => $id));
			foreach ($query1->result() as $row)
				{
					$lastYear = strtotime("-1 year");
					if (strtotime($row->endTime)>$lastYear ){
						return false;
					}
				}
			return true;
		}

		public function checkIfRoomIsBookable($roomID)
		{
				$this->db->where('rooms.buildingID', $this->session->userdata('building'));
				$this->db->where('rooms.id',$roomID);
				$query = $this->db->get('rooms');
				return $query->result();
		}
		public function get_rooms_buildingID($roomID){
			$this->db->select("buildingID");
			$this->db->where('rooms.id',$roomID);
			$query = $this->db->get('rooms');
			$result = $query->row();
			return $result->buildingID;  
		}


		public function delete_room($id){
			$query1 = $this->db->get_where('bookingTimes', array('roomID' => $id));
			foreach ($query1->result() as $row)
				{
					
					$today = strtotime('today UTC');
					if (strtotime($row->endTime)>$today ){
						return false;
						echo $row->endTime;
					}
				}
		
			$this->db->where('id', $id);
			$this->db->delete('rooms');
			return true;
		}


		public function create_category(){
			$data = array(
				'name' => $this->input->post('name'),
				'building_id' => $this->session->buildingdata('building_id')
			);
			return $this->db->insert('categories', $data);
		}



		public function registerBuilding($data){
		
			$this->db->insert('buildings', $data);
			return $this->db->insert_id();
		}

		public function registerBuildingSettings($id){
			return $this->db->insert('bookingFormSettings', array('buildingID' => $id));
		}


		public function registerBuildingSettingsDetails($id, $typeID){
			return $this->db->insert('bookingFormSettingsDetails', array('buildingID' => $id, 'typeID' => $typeID));
		}



		public function update_booking_settings($data){
			$this->db->where('buildingID', $this->session->userdata('building'));
			return $this->db->update('bookingFormSettings', $data);
		}

		
		public function update_booking_settings_details($data, $typeID){
			$this->db->where('buildingID', $this->session->userdata('building'));
			$this->db->where('typeID', $typeID);
			return $this->db->update('bookingFormSettingsDetails', $data);
		}

	
		public function update_building($data){
			
		
			$this->db->where('id', $this->input->post('id'));

			return $this->db->update('buildings', $data);
		}

		public function update_room($data, $roomID){
			
		
			$this->db->where('id',$roomID);

			return $this->db->update('rooms', $data);
		}



		public function createNewRoom($data){
			
			// Insert room
			$this->db->insert('rooms', $data);
			return $this->db->insert_id();
		
		}

		public function get_room_activities($roomID){
			$this->db->order_by('activityName', 'ASC');
			$this->db->where('room_id',$roomID);
			$this->db->join('activities', ' room_activity.activity_id = activities.activityID' , 'left');
			$query = $this->db->get('room_activity');
			return $query->result_array();
		}

		public function createNewActivity($data){
			
			// Insert activity if not exists

			$g = $this->db->get_where('activities',$data);

			if ($g->num_rows() > 0) {
				return $g->row()->activityID;
			} else {
				$this->db->insert('activities', $data);
				return $this->db->insert_id();
			}
		
		
		}

		public function createRoomActivity($data){
			
			// Insert activity if not exists

			$g = $this->db->get_where('room_activity',$data);

			if ($g->num_rows() < 1) {
				$this->db->insert('room_activity', $data);
				return true;
			} 
		
		
		}

		public function delete_RoomActivity($array){
			$this->db->where($array);
			$this->db->delete('room_activity');
			return true;
		}

		public function delete_ActivityIfIsNotInUse(){

			$sql = "DELETE activities FROM  activities LEFT JOIN room_activity ON activities.activityID=room_activity.activity_id WHERE room_activity.activity_id IS NULL";
			$this->db->query($sql);
		}

		function getAllRegions()
		{
		  
			$this->db->order_by('regionID');
			$query = $this->db->get('regions');
			return $query->result_array();
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

	}
