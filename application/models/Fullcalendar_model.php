<?php

class Fullcalendar_model extends CI_Model
{
	function fetch_all_event(){
		$this->db->order_by('bookingTimes.startTime');
		$this->db->join('bookings', 'bookingTimes.bookingID = bookings.id' , 'left');
		$this->db->join('rooms', 'bookingTimes.roomID = rooms.id' , 'left');
		$this->db->join('buildings', 'rooms.buildingID = buildings.id' , 'left');
		// $this->db->where('DATE(bookingTimes.startTime) >=', date('Y-m-d H:i:s',strtotime($this->input->get('start', TRUE))));
		// $this->db->where('DATE(bookingTimes.endTime) <=', date('Y-m-d H:i:s',strtotime($this->input->get('end', TRUE))));
		return $this->db->get('bookingTimes');
	}

	function getAllRooms($id)
    {
		$this->db->where('id', $id);
		$this->db->join('buildings', 'rooms.buildingID = buildings.id' , 'left');
		$this->db->join('regions', 'buildings.id = regions.regionID' , 'left');
		$query = $this->db->get('rooms');
		return $query->result();
      
	}

	public function collect_all_room_from_user_session_buildingdata($slug){
		$this->db->select("rooms.id");
		$this->db->join('rooms', ' buildings.id = rooms.buildingID' , 'left');
		$query = $this->db->get_where('buildings', array('buildings.id' => $slug));
		$array = $query->result_array();
		return array_column($array,"id");
	
	}

	function insert_event($data)
	{
		$this->db->insert('bookingTimes', $data);
	}

	function insert_version($data)
	{
		$this->db->insert('bookingTimeVersions', $data);
	}

	function save_statistics($data)
	{
		$this->db->insert('rooms_statistics', $data);
	}

	function update_event($data, $id)
	{
		$this->db->where('timeID', $id);
		$this->db->update('bookingTimes', $data);
	}

	function delete_event($id)
	{
		$this->db->where('timeID', $id);
		$this->db->delete('bookingTimes');
	}

	function delete_versions($id)
	{
		$this->db->where('timeID', $id);
		$this->db->delete('bookingTimeVersions');
	}

	function deleteTImesAndBooking($id)
	{
		
		$this->db->select("timeID");
		$this->db->where('bookingID',$id);
		
		$versionsToDelete=$this->db->get('bookingTimes');
		$query = $this->db->get_where('bookingTimes', array('bookingID' => $id));
	
		$array = $query->result_array();
		$alltimeIDWhichVersionsToDelete=array_column($array,"timeID");
		$this->db->where_in('timeID', $alltimeIDWhichVersionsToDelete);
		$this->db->delete('bookingTimeVersions');
		$this->db->delete('bookingTimes', array('bookingID' => $id));
		$this->db->delete('bookings', array('id' => $id));

	}

	function count_bookingTimes($bookingID)
	{
		$this->db->where('bookingID',$bookingID);
		return $this->db->get('bookingTimes')->num_rows();
		
	}

	

	function fetch_versions($timeID)
    {
        $this->db->where('timeID', $timeID);
		$this->db->order_by('versionID', 'DESC');
		$this->db->limit(20);
		$query = $this->db->get('bookingTimeVersions');
		return $query->result();
      	
	
    }

	function fetch_city($country_id)
    {
        $this->db->where('regionID', $country_id);
		$this->db->order_by('id', 'ASC');
		$this->db->select("name, buildings.id");
		$this->db->distinct();
		$this->db->join('rooms', 'buildings.id  = rooms.buildingID' , 'left');
		$this->db->where('roomActive','1');
        $query = $this->db->get('buildings');
        $output = '<option value="">Select Asutus</option>';
        foreach ($query->result() as $row) {
            $output .= '<option  data-value="' . $row->id . '" value="' . $row->name . '">'.$row->name.'</option>';
        }
        return $output;
    }



	function fetch_building_from_activity_or_region($activity_id = NULL, $country_id = NULL)
    {
      	$this->db->select("buildings.name, buildings.id");
		$this->db->distinct();
		$this->db->join('rooms', 'buildings.id  = rooms.buildingID', 'left');
		$this->db->join('room_activity', 'rooms.id  = room_activity.room_id' , 'left');
	
		if($country_id){
			$this->db->where('regions.regionID', $country_id);
			$this->db->join('regions', 'buildings.regionID  = regions.regionID', 'left');
		}
		if($activity_id){
			$this->db->where('activities.activityID', $activity_id);
			$this->db->join('activities', 'activities.activityID  = room_activity.activity_id' , 'left');
		}
		if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID')==='4'){
			$this->db->where('roomActive','1');
			}
        $query = $this->db->get('buildings');
		$output = '<option  data-value="0" value="---"></option>';
        foreach ($query->result() as $row) {
            $output .= '<option  data-value="' . $row->id . '" value="' . $row->name . '">'.$row->name.'</option>';
        }
        return $output;
    }

	function fetch_rooms_from_region_activity_building($activity_id = NULL, $country_id=NULL, $buildingID = NULL)
	{
		$this->db->order_by('rooms.id', 'ASC');
		if($buildingID){
			$this->db->where('rooms.buildingID', $buildingID);
		}
		$this->db->select("rooms.id, rooms.roomName, rooms.roomActive");
		$this->db->distinct();
		$this->db->join('room_activity', 'rooms.id  = room_activity.room_id', 'left');
		$this->db->join('buildings', 'buildings.id  = rooms.buildingID', 'left');
		
		if($country_id){
			$this->db->where('regions.regionID', $country_id);
			$this->db->join('regions', 'buildings.regionID  = regions.regionID', 'left');
		}
		if($activity_id){
			$this->db->where('activities.activityID', $activity_id);
			$this->db->join('activities', 'activities.activityID  = room_activity.activity_id', 'left');
		}
		if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID') === '4') {
			$this->db->where('roomActive', '1');
		}
		if ($this->session->userdata('roleID')==='2'  || $this->session->userdata('roleID')==='3'){
			$this->db->where('buildings.id',$this->session->userdata('building'));
			}
		$query = $this->db->get('rooms');
		$output = '';
		foreach ($query->result() as $row) {

			if ($row->roomActive == 0) {
				$output .= '<option  data-value="' . $row->id . '" value="' . $row->roomName . ' (peidetud)">' . $row->roomName . ' (peidetud)</option>';
			} else {
				$output .= '<option  data-value="' . $row->id . '" value="' . $row->roomName . '">' . $row->roomName . '</option>';
			}
		}

		return $output;
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

?>
