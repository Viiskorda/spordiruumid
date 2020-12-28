<?php
	class Allbookings_model extends CI_Model{

		public function __construct(){
			$this->load->database();
		}

		public function get_bookings($slug = FALSE){
		
			$this->db->distinct();
			$query = $this->db->get('bookings');
			return $query->result_array();
		}

		function fetch_all_event($buildingID){
			$this->db->order_by('bookingTimes.startTime');
			$this->db->join('bookings', 'bookingTimes.bookingID = bookings.id' , 'left');
			$this->db->join('rooms', 'bookingTimes.roomID = rooms.id' , 'left');
			$this->db->join('buildings', 'rooms.buildingID = buildings.id' , 'left');
			$this->db->where('buildingID', $buildingID);
			$this->db->where('endTime >=',   date("Y-m-d H:i:s", strtotime("-4 week")));
			return $this->db->get('bookingTimes');
		}

		function fetch_all_rooms($buildingID){
			$this->db->order_by('roomName');
			$this->db->where('buildingID', $buildingID);
			return $this->db->get('rooms');
		}

		function fetch_all_rooms_for_checkbox($buildingID){
			$this->db->order_by('id');
			$this->db->where('buildingID', $buildingID);
			$query =  $this->db->get('rooms');
			return $query->result_array();
		}


		var $table = "bookingTimes";  
		var $select_column = array("created_at", "roomName", "startTime", "endTime","approved","public_info", "workout", "comment","c_name","c_phone","c_email","takes_place","timeID","roomID",);  
		//järgmisel real kirjeldan ära millste lahtritega saab sorteerida
		var $order_column = array("created_at", "roomID",  null, "startTime", null, null,  null,"approved","public_info", "workout", "comment","c_name","c_phone","c_email","takes_place");   
		function make_query()  
		{  
			$data=$this->input->post();
			 $this->db->select($this->select_column);  
			
			 $this->db->join('bookings', 'bookingTimes.bookingID = bookings.id' , 'left');
			 $this->db->join('rooms', 'bookingTimes.roomID = rooms.id' , 'left');
			
			 $this->db->join('buildings', 'rooms.buildingID = buildings.id' , 'left');
			 if($this->session->userdata('roleID')=='2' || $this->session->userdata('roleID')=='3'){
				$this->db->where('buildingID',  $this->session->userdata('building'));
			 }
			 $this->db->from($this->table);
			 if(isset($data["is_date_search"])){
			
				$this->db->where('DATE(startTime) >=', date('Y-m-d H:i:s',strtotime($data["start_date"])));
				$this->db->where('DATE(startTime) <=', date('Y-m-d H:i:s',strtotime($data["end_date"])));
				
			//	print_r( $this->db->get("bookingTimes"));
			 }
			
			 if(isset($data["search"]["value"]))  
			 {  
				
				 $this->db->group_start();
				 $this->db->like("startTime", $data["search"]["value"]);  
				  $this->db->or_like("LOWER(roomName)", mb_strtolower($data["search"]["value"]));  
				  $this->db->or_like("LOWER(public_info)", mb_strtolower($data["search"]["value"]));  
				  $this->db->or_like("LOWER(workout)", mb_strtolower($data["search"]["value"]));  
				  $this->db->or_like("created_at", $data["search"]["value"]);  
				  $this->db->or_like("LOWER(comment)", mb_strtolower($data["search"]["value"]));  
				  $this->db->or_like("LOWER(c_name)", mb_strtolower($data["search"]["value"]));  
				  $this->db->or_like("LOWER(c_phone)", $data["search"]["value"]);  
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
				if(isset($data["orderBy"]))  
				{  
				   $this->db->order_by('timeID', 'DESC');    
				} 
				else{
				  $this->db->order_by('startTime', 'ASC');  }
				  
			 }  
		
			//print_r($this->db->last_query());
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
			$data=$this->input->post();
			if(isset($data["is_date_search"])){
			
				$this->db->where('DATE(startTime) >=', date('Y-m-d H:i:s',strtotime($data["start_date"])));
				$this->db->where('DATE(startTime) <=', date('Y-m-d H:i:s',strtotime($data["end_date"])));
				
			
			 }
		//	 $this->db->from($this->table); 
			
			 return $this->db->count_all_results();  
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
