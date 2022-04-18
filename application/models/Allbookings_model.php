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
			$this->db->where('DATE(bookingTimes.startTime) >=', date('Y-m-d H:i:s',strtotime($this->input->get('start', TRUE))));
			$this->db->where('DATE(bookingTimes.endTime) <=', date('Y-m-d H:i:s',strtotime($this->input->get('end', TRUE))));
			//$this->db->where('endTime >=',   date("Y-m-d H:i:s", strtotime("-150 week")));
			return $this->db->get('bookingTimes');
		}

		function fetch_all_rooms($buildingID){
			$this->db->order_by('roomName');
			$this->db->where('buildingID', $buildingID);
			return $this->db->get('rooms');
		}

		function fetch_all_rooms_for_checkbox($buildingID){
			$this->db->order_by('roomName');
			$this->db->where('buildingID', $buildingID);
			$query =  $this->db->get('rooms');
			return $query->result_array();
		}


		var $table = "bookingTimes";  
		var $select_column = array("created_at", "roomName", "startTime", "endTime","approved","public_info", "workout", "comment","c_name","c_phone","c_email","takes_place","timeID","roomID",);  
		//järgmisel real kirjeldan ära millste lahtritega saab sorteerida
		var $order_column = array("created_at", "roomID",  null, "startTime", null, null,  null,"approved","public_info", "workout", "comment","c_name","c_phone","c_email","takes_place");   
		var $select_column_for_filtering = array("created_at", "roomName", "startTime", "startTime", "startTime","endTime", "endTime" ,"approved","public_info", "workout", "comment","c_name","c_phone","c_email","takes_place","timeID","roomID",);  
		
		function make_query()  
		{  
			 $data=$this->input->post();
			// print_r($data);
			 $this->db->select($this->select_column);  
			
			 $this->db->join('bookings', 'bookingTimes.bookingID = bookings.id' , 'left');
			 $this->db->join('rooms', 'bookingTimes.roomID = rooms.id' , 'left');
			
			 $this->db->join('buildings', 'rooms.buildingID = buildings.id' , 'left');
			 $this->db->where('buildingID',  $this->session->userdata('building'));
			 $this->db->from($this->table);
			 if($data["is_date_search"]==1){
			
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

			 for ($i=0; $i < count($data["columns"]); $i++) {
				$user_input=$data["columns"][$i]["search"]["value"];
			
				if ($user_input != '') {

					if ($i == 2) {
						$this->db->where('WEEKDAY(startTime) =', $user_input);
					}
					if ($i == 4) {
						$user_input=str_replace('.', ':', $user_input);
						if (!preg_match('/:/', $user_input,  $matches)) {
							$this->db->where('HOUR(startTime) =', $user_input);
						} 
					
						else if (preg_match('/:/', $user_input,  $matches)) {
							
							$this->db->where('HOUR(startTime) =', $user_input);
						} 
						
					}
					if ($i == 5) {
						$user_input=str_replace('.', ':', $user_input);
						if (!preg_match('/:/', $user_input,  $matches)) {
							$this->db->where('HOUR(endTime) =', $user_input);
						} 
						//i do not need this because LIKE query is helping here
						// else if (preg_match('/(.*)\b\:\b(.*)/', $user_input,  $matches)) {
						// 	$this->db->where('TIME(endTime) =', date('H:i', strtotime($user_input)));
						// }
						else if (preg_match('/:/', $user_input,  $matches)) {
							
							$this->db->where('HOUR(endTime) =', $user_input);
						} 
					}
					
					 if ($i == 6) {
						$this->db->where('TIMESTAMPDIFF(minute, startTime, endTime) =', $user_input);
					} else {
						$this->db->like("LOWER(" . $this->select_column_for_filtering[$i] . ")", mb_strtolower($user_input));
					}
					
				}
					

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
			
		//	 $this->db->from($this->table); 
			 return $this->db->count_all_results();  
		}  

		function get_sum_over_pages()  
		{  
			$data=$this->input->post();
			$this->db->select('SUM(TIMESTAMPDIFF(minute, startTime, endTime)) AS myTotal');
			$this->make_query(); 
		
		//	 $this->db->from($this->table); 
		//	 return $this->db->count_all_results();  
			 $query =  $this->db->get();
			 return $query->row()->myTotal;
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


		function save_statistics($data)
		{
			$this->db->insert('rooms_statistics', $data);
		}
	
		public function collect_all_room_from_user_session_buildingdata($slug){
			$this->db->select("rooms.id");
			$this->db->join('rooms', ' buildings.id = rooms.buildingID' , 'left');
			$query = $this->db->get_where('buildings', array('buildings.id' => $slug));
			$array = $query->result_array();
			return array_column($array,"id");
		
		}

		function update_event($data, $id)
		{
			$this->db->where('timeID', $id);
			$this->db->update('bookingTimes', $data);
		}

		function insert_version($data)
		{
			$this->db->insert('bookingTimeVersions', $data);
		}
	
		function insert_event($data)
		{
			$this->db->insert('bookingTimes', $data);
		}

	}
