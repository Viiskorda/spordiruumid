<?php


class Pages_model extends CI_Model
{

    function fetch_city($country_id)
    {
        $this->db->where('regionID', $country_id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('buildings');
        $output = '<option value="">Select Asutus</option>';
        foreach ($query->result() as $row) {
            $output .= '<option  data-value="' . $row->id . '" value="' . $row->name . '">'.$row->name.'</option>';
        }
        return $output;
    }
	function getAllRooms($roomid)
    {
		if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID')==='4'){
			$this->db->where('roomActive','1');
			}
        $this->db->where('rooms.id', $roomid);
		$this->db->join('buildings', 'rooms.buildingID = buildings.id' , 'left');
		$this->db->join('regions', 'buildings.regionID = regions.regionID' , 'left');
		$query = $this->db->get('rooms');
		return $query->row_array();
      
    }
    function getAllBuildingRooms()
    {
		$this->db->select("id, buildingID, roomName, roomActive");
		if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID')==='4'){
			$this->db->where('roomActive','1');
		}
        $query = $this->db->get('rooms');
        return $query->result();
    }

    function getAllBuildings()
    {
		$this->db->select("name, buildings.id, regionID");
		$this->db->distinct();
		$this->db->join('rooms', 'buildings.id  = rooms.buildingID' , 'left');
		if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID')==='4'){
			$this->db->where('roomActive','1');
		}
        $query = $this->db->get('buildings');
        return $query->result();
    }

    function getAllRegions()
    {
      
        $this->db->order_by('regionID');
        $query = $this->db->get('regions');
        return $query->result();
    }

	function getAllActivities()
    {
		$this->db->select("activityID, activityName");
		$this->db->distinct();
        $this->db->order_by('activityName');
		$this->db->join('room_activity', 'activities.activityID  = room_activity.activity_id', 'left');
		$this->db->join('rooms', 'rooms.id  = room_activity.room_id', 'left');
		$this->db->join('buildings', 'buildings.id  = rooms.buildingID', 'left');

		if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID') === '4') {
			$this->db->where('roomActive', '1');
		}
		if ($this->session->userdata('roleID')==='2'  || $this->session->userdata('roleID')==='3'){
			$this->db->where('buildings.id',$this->session->userdata('building'));
			}
        $query = $this->db->get('activities');
        return $query->result();
    }

    function fetch_building($state_id)
    {
        $this->db->where('buildingID', $state_id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('rooms');
        $output = '<option value="">Select room</option>';
        foreach ($query->result() as $row) {
            $output .= '<option  data-value="' . $row->id . '" value="' . $row->roomName . '">'.$row->roomName.'</option>';
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

