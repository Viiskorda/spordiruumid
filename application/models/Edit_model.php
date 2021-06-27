<?php

class Edit_model extends CI_Model
{

   function fetch_all_event(){
	$this->db->select("*");
	$this->db->select("DATE_FORMAT(bookingTimes.startTime, '%Y-%m-%dT%H:%i') AS startTime", FALSE);
	$this->db->select("DATE_FORMAT(bookingTimes.endTime, '%Y-%m-%dT%H:%i') AS endTime", FALSE);
		$this->db->order_by('bookingTimes.startTime');
		$this->db->join('bookings', 'bookingTimes.bookingID = bookings.id' , 'left');
		$this->db->join('rooms', 'bookingTimes.roomID = rooms.id' , 'left');
		$this->db->join('buildings', 'rooms.buildingID = buildings.id' , 'left');
	//	$this->db->join('bookingTypes', 'bookings.typeID = bookingTypes.id' , 'left');
		return $this->db->get('bookingTimes');
	}

	public function getBookingformData(){
		$this->db->where('buildingID', $this->session->userdata('building'));
		$query = $this->db->get('bookingFormSettings');
		return $query->row_array();
	}
	

	function fetch_all_Booking_times(){
		$this->db->order_by('bookingTimes.timeID');
		$this->db->join('bookings', 'bookingTimes.bookingID = bookings.id' , 'left');
		return $this->db->get('bookingTimes');
		}


	function update_booking($data1, $id){


		$this->db->where('id', $id);
		$this->db->update('bookings', $data1);
	
	}
	
	function update_bookingTimes($insert_data, $id){
			
		//	$this->db->insert('bookingTimes', $data2);
		$this->db->where('timeID', $id);
		$this->db->update('bookingTimes', $insert_data);
	//	var_dump($insert_data);
		}


	function insert($insert_data, $id){
		
		$this->db->where('bookingID', $id);
		$this->db->where($insert_data);
		$q = $this->db->get('bookingTimes');

		if ( $q->num_rows() > 0 ) 
		{
		   $this->db->where('user_id',$id);
		   $this->db->update('bookingTimes',$insert_data);
		   return $this->db->insert_id();
		} else {
			$this->db->where('bookingID', $id);
			$this->db->insert('bookingTimes', $insert_data);
			return $this->db->insert_id();
		}
		
		}

		public function get_room($insert_data){
		
			$this->db->select("roomID");  
			$this->db->order_by('bookingTimes.startTime');
			$this->db->where('bookingID', $insert_data);
			$query=$this->db->get('bookingTimes');
			return $query->row_array();
		
		}

		public function get_allbookingtimes($bookingID, $timeID){
			
			$this->db->where('bookingID', $bookingID);
			$this->db->where('timeID', $timeID);
			$this->db->join('bookings', 'bookingTimes.bookingID = bookings.id' , 'left');
			$this->db->order_by('bookingTimes.startTime','ASC');
			$query=$this->db->get('bookingTimes');
			return $query->result_array();
		
		}

		public function get_conflictsDates($insert_data, $bookingID ){
		
			$this->db->select("timeID, created_at, startTime, endTime, public_info, workout");  
			$this->db->order_by('bookingTimes.startTime');
			$this->db->join('bookings', 'bookingTimes.bookingID = bookings.id' , 'left');
			$this->db->join('rooms', 'bookingTimes.roomID = rooms.id' , 'left');
			$this->db->join('buildings', 'rooms.buildingID = buildings.id' , 'left');
		//	$this->db->where('buildingID', $insert_data);
			$this->db->where('roomID',  $insert_data);
			$this->db->where('bookingID != ', $bookingID); 
			$this->db->where('DATE(startTime) >=', date('Y-m-d H:i:s',strtotime("-1 day")));
			$query=$this->db->get('bookingTimes');
			return  $query->result();
		
		}


		public function get_conflictsDates2($insert_data, $inserted_room,$bookingID){
		
			$this->db->select("created_at, startTime, endTime, public_info, workout");  
			$this->db->order_by('bookingTimes.startTime');
			$this->db->join('bookings', 'bookingTimes.bookingID = bookings.id' , 'left');
			$this->db->join('rooms', 'bookingTimes.roomID = rooms.id' , 'left');
			$this->db->join('buildings', 'rooms.buildingID = buildings.id' , 'left');
			$this->db->where('bookingID != ', $bookingID); 
			$this->db->where('buildingID', $insert_data);
			$this->db->where('roomID',  $inserted_room);
			$this->db->where('DATE(startTime) >=', date('Y-m-d H:i:s',strtotime("-1 day")));
			$query=$this->db->get('bookingTimes');
			return  $query->result();
		
	
		}

		

	public function can_update_or_not($sessionBuilding,$timeID)
    {
		$this->db->where('timeID',  $timeID);
		$this->db->join('rooms', 'bookingTimes.roomID = rooms.id' , 'left');
		$this->db->where('buildingID',  $sessionBuilding);
		$query = $this->db->get('bookingTimes');
		if ($query->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
      
	}


	public function get_lastDate( $whichWeekDaynumberToSearch,$bookingID,$startingDate)
    {
		$this->db->select("timeID");  
		$this->db->order_by('bookingTimes.startTime');
		 $this->db->where('bookingID',  $bookingID);
		 $this->db->where('startTime>=', $startingDate);
		 $this->db->where('WEEKDAY(startTime)+1=',  $whichWeekDaynumberToSearch);
		$query=$this->db->get('bookingTimes');
		return  $query->result_array();
      
	}

	public function get_info_for_version( $timID)
    {
		$this->db->select("startTime, endTime, bookingTimeColor, roomID, roomName");  
		$this->db->order_by('bookingTimes.startTime');
		 $this->db->where('timeID',  $timID);
		 $this->db->join('rooms', 'bookingTimes.roomID = rooms.id' , 'left');
		$query=$this->db->get('bookingTimes');
		return  $query->result_array();
      
	}
	
	function insert_version($data)
	{
		$this->db->insert('bookingTimeVersions', $data);
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



	function getAllRooms()
	{
		if($this->session->userdata('roleID')=='2' || $this->session->userdata('roleID')=='3'){
			$this->db->select("id, roomName");
			$this->db->order_by('id');
			$this->db->where('rooms.buildingID', $this->session->userdata('building'));
			$query = $this->db->get('rooms');
			return $query->result_array();
	
		}
	}
	

	
	function getSelectedRoom($timeID){
		$this->db->select("id, roomName");
		$this->db->join('bookingTimes', 'bookingTimes.roomID = rooms.id' , 'left');
		$this->db->where('bookingTimes.timeID', $timeID);
		$query = $this->db->get('rooms');
		return $query->result_array();
	}

}

?>
