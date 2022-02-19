<?php


class Home_model extends CI_Model
{

    function getAllRegions()
    {
        // ennem tegin nagu allpool (allikas: https://stackoverflow.com/questions/19922143/display-data-from-database-to-dropdown-codeigniter)
        // $query = $this->db->query('SELECT name FROM regions');
        // return $query->result();
		$this->db->select("regions.regionID,regions.regionName");
		$this->db->distinct();
		$this->db->join('buildings', 'buildings.regionID  = regions.regionID' , 'left');
		$this->db->join('rooms', 'buildings.id  = rooms.buildingID' , 'left');

		if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID')==='4'){
			$this->db->where('roomActive','1');
			}
        $this->db->order_by('regions.regionID');
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

    function getAllBuildings()
    {
		$this->db->select("name, buildings.id");
		$this->db->distinct();
		$this->db->join('rooms', 'buildings.id  = rooms.buildingID' , 'left');
		$this->db->where('roomActive','1');
		$query = $this->db->get('buildings');
	    return $query->result();
    }

  

	function fetch_region($activity_id)
    {
		
        $this->db->select("regions.regionID,regions.regionName");
		$this->db->distinct();
		$this->db->join('buildings', 'buildings.regionID  = regions.regionID' , 'left');
		$this->db->join('rooms', 'buildings.id  = rooms.buildingID' , 'left');
		if($activity_id){

			$this->db->join('room_activity', 'rooms.id  = room_activity.room_id' , 'left');
			$this->db->where('activities.activityID', $activity_id);
			$this->db->join('activities', 'activities.activityID  = room_activity.activity_id' , 'left');
		}
		
		if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID')==='4'){
			$this->db->where('roomActive','1');
			}
        $query = $this->db->get('regions');
		
        $output = '<option  data-value="0" value="---"></option>';
        foreach ($query->result() as $row) {
            $output .= '<option  data-value="' . $row->regionID . '" value="' . $row->regionName . '">'.$row->regionName.'</option>';
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

    function getAllRooms()
    {
		if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID')==='4'){
		$this->db->where('roomActive','1');
		}
		$query = $this->db->get('rooms');
        return $query->result();
	}
	
	function getRoomsFromRegion($country_id)
    {
		$this->db->select("rooms.id, rooms.roomName,roomActive");
        $this->db->where('buildings.regionID', $country_id);
	//	$this->db->order_by('id', 'ASC');
		$this->db->join('buildings', 'rooms.buildingID = buildings.id' , 'left');
		$this->db->join('regions', 'buildings.id = regions.regionID' , 'left');
		if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID')==='4'){
			$this->db->where('roomActive','1');
			}
        $query = $this->db->get('rooms');
        $output = '<option value="">Select Asutus</option>';
        foreach ($query->result() as $row) {
			if($row->roomActive==0){
				$output .= '<option  data-value="' . $row->id . '" value="' . $row->roomName . ' (peidetud)">'.$row->roomName.' (peidetud)</option>';
			}
			else{
				$output .= '<option  data-value="' . $row->id . '" value="' . $row->roomName . '">'.$row->roomName.'</option>';
			}
		}
	    return $output;
    }


    function fetch_building($state_id)
    {
		if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID')==='4'){
			$this->db->where('roomActive','1');
			}
        $this->db->where('buildingID', $state_id);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('rooms');
        $output = '<option value="">Select room</option>';
        foreach ($query->result() as $row) {
			if($row->roomActive==0){
				$output .= '<option  data-value="' . $row->id . '" value="'. $row->roomName .' (peidetud)">'.$row->roomName.' (peidetud)</option>';
			}
			else{
				$output .= '<option  data-value="' . $row->id . '" value="'. $row->roomName .'">'.$row->roomName.'</option>';
			}
		}
        return $output;
	}
	
	

	function chech_if_has_request($email){
		$this->db->select("requestFromBuilding");
		$this->db->where('email',$email );
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

}

