<?php
class Statistics_model extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}

	function fetch_all_statistics(){
		
		$this->db->order_by("rooms_statistics_id ", "desc");
		$query= $this->db->get('rooms_statistics');
		return $query->result();
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
