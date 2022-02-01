<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Fullcalendar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('fullcalendar_model');
	}

	function getUnapprovedBookings(){
	
		$data= $this->fullcalendar_model->getUnapprovedBookings($this->session->userdata('building'));
		echo json_encode($data);
	}


	function load($roomId)
	{
		$this->input->get('saal', TRUE);
		$event_data = $this->fullcalendar_model->fetch_all_event();
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
		foreach($event_data->result_array() as $row)
			if(	$row['roomID']==$roomId &&$row['buildingID'] ==$this->session->userdata('building')){
				
				{
					$data[] = array(
						'id'	=>	$row['timeID'],
						'roomID'	=>	$row['roomID'],
						'timeID'=>	$row['timeID'],
						'title'	=>	$row['public_info'],
						'description'	=>	$row['workout'],
						'comment' => $row['comment'],
						'start'	=>	$row['startTime'],
						'end'	=>	$row['endTime'],
						'event_in'	=>	$row['event_in'],
						'event_out'	=>	$row['event_out'],
						'clubname'	=>	$row['c_name'],
						'phone'	=>	$row['c_phone'],
						'email'	=>	$row['c_email'],
						'workout'	=>	$row['workout'],
						'created_at'	=>	$row['created_at'],
						'selectedroom'	=>	$row['name'],
						'building'	=>	$row['name'],
						'roomName'	=>	$row['roomName'],
						'bookingID'	=>	$row['bookingID'],
						'takesPlace'	=>	$row['takes_place'],
						'approved'	=>	$row['approved'],
						'organizer'	=>	$row['organizer'],
						'typeID'	=>	$row['typeID'],
						'color'	=>	$row['bookingTimeColor'],
						'hasChanged'	=>	$row['hasChanged'],
						'timeComment'	=>	$row['timecomment'],
						'showComment'	=>	$row['showcomment'],
					);

					
				}
			}
		} else {
			foreach($event_data->result_array() as $row)
				if(	$row['roomID']==$roomId){
					
				{
					$data[] = array(
					
						'roomID'	=>	$row['roomID'],
						'title'	=>	$row['public_info'],
						'description'	=>	$row['workout'],
						'start'	=>	$row['startTime'],
						'end'	=>	$row['endTime'],
						'building'	=>	$row['name'],
						'takesPlace'	=>	$row['takes_place'],
						'approved'	=>	$row['approved'],
						'typeID'	=>	$row['typeID'],
						'color'	=>	$row['bookingTimeColor'],
				
					);
				}
			}

		}
		if(!isset($data)){
			//$data="Sorry, no data for you";
		}

		// Remove emptyes from array
		$data = array_map('array_filter', $data);
		$data = array_filter($data);
		echo json_encode($data);
	}


	function countBookindTimes(){
		
		
		print_r($this->fullcalendar_model->count_bookingTimes($this->input->post('bookingID')));

	}

	function insert()
	{
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
			$arrayOfRoomIDWhereCanMakeChanges=$this->fullcalendar_model->collect_all_room_from_user_session_buildingdata($this->session->userdata('building'));
			if (in_array($this->input->post('selectedRoomID'), $arrayOfRoomIDWhereCanMakeChanges)) {
			$data = array(
				'roomID'			=>	$this->input->post('roomID'),
				'startTime'	=>	$this->input->post('start'),
				'endTime'		=>	$this->input->post('end'),
				'bookingID'			=>	$this->input->post('bookingID'),
				'bookingTimeColor'	=>	$this->input->post('color'),
				'takes_place'		=>	$this->input->post('takesPlace'),
				'approved'			=>	$this->input->post('approved')
			);
			$this->fullcalendar_model->insert_event($data);
		
		}
	}
	}


	function delete()
	{
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
			$arrayOfRoomIDWhereCanMakeChanges=$this->fullcalendar_model->collect_all_room_from_user_session_buildingdata($this->session->userdata('building'));
			if (in_array($this->input->post('selectedRoomID'), $arrayOfRoomIDWhereCanMakeChanges)) {
				if($this->input->post('timeID'))
				{
					$this->fullcalendar_model->delete_versions($this->input->post('timeID'));
					$this->fullcalendar_model->delete_event($this->input->post('timeID'));
					
				}
			}
			

		
		}
		
	}

	function deleteAllConnectedBookings()
	{
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
			$arrayOfRoomIDWhereCanMakeChanges=$this->fullcalendar_model->collect_all_room_from_user_session_buildingdata($this->session->userdata('building'));
			if (in_array($this->input->post('selectedRoomID'), $arrayOfRoomIDWhereCanMakeChanges)) {
				if($this->input->post('bookingID'))
				{
					$this->fullcalendar_model->deleteTImesAndBooking($this->input->post('bookingID'));
				}
			}
		}
	}

	function updateEvent()
	{
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
			$arrayOfRoomIDWhereCanMakeChanges=$this->fullcalendar_model->collect_all_room_from_user_session_buildingdata($this->session->userdata('building'));
			if (in_array($this->input->post('selectedRoomID'), $arrayOfRoomIDWhereCanMakeChanges)) {
				if($this->input->post('timeID'))
				{
					$data = array(
						'startTime'	=>	$this->input->post('start'),
						'endTime'		=>	$this->input->post('end'),
						'hasChanged'	=>	1,
						);

					$this->fullcalendar_model->update_event($data, $this->input->post('timeID'));

					$dataForVersioning = array(
						'timeID'	=>	$this->input->post('timeID'),
						'startTime'		=>	$this->input->post('versionStart'),
						'endTime'	=>	$this->input->post('versionEnd'),
						'nameWhoChanged'		=> $this->session->userdata('userName'),
						'reason'	=>	$this->input->post('reason'),
					
						);

					$this->fullcalendar_model->insert_version($dataForVersioning);
				}	
			}	
		}
	}


	function approveEvents()
	{
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
			$arrayOfRoomIDWhereCanMakeChanges=$this->fullcalendar_model->collect_all_room_from_user_session_buildingdata($this->session->userdata('building'));
			if (in_array($this->input->post('selectedRoomID'), $arrayOfRoomIDWhereCanMakeChanges)) {
				if($this->input->post('timeID'))
				{
					$data = array(
						'approved'			=>	$this->input->post('approved'),
						'hasChanged'	=>	1,
						);

					$this->fullcalendar_model->update_event($data, $this->input->post('timeID'));

					$dataForVersioning = array(
						'timeID'	=>	$this->input->post('timeID'),
						'nameWhoChanged'		=> $this->session->userdata('userName'),
						'reason'	=> ($this->input->post('approved')=='1')?'Kinnitatud':'Kinnitus maha',
					
						);

					$this->fullcalendar_model->insert_version($dataForVersioning);
				}	
			}	
		}
	}


	function takesPlace()
	{
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
			$arrayOfRoomIDWhereCanMakeChanges=$this->fullcalendar_model->collect_all_room_from_user_session_buildingdata($this->session->userdata('building'));
			if (in_array($this->input->post('selectedRoomID'), $arrayOfRoomIDWhereCanMakeChanges)) {
				if($this->input->post('timeID'))
				{
					$data = array(
						'takes_place'			=>	$this->input->post('takesPlace'),
						'hasChanged'	=>	1,
						);

					$this->fullcalendar_model->update_event($data, $this->input->post('timeID'));

					$dataForVersioning = array(
						'timeID'	=>	$this->input->post('timeID'),
						'nameWhoChanged'		=> $this->session->userdata('userName'),
						'reason'	=> ($this->input->post('takesPlace')=='1')?'Toimub':'Ei toimu',
					
						);

					$this->fullcalendar_model->insert_version($dataForVersioning);
				}
			}
		}
	}


	
	function fetch_versions()
	{
	 if($this->input->post('timeID'))
	 {
		echo json_encode($this->fullcalendar_model->fetch_versions($this->input->post('timeID'), JSON_UNESCAPED_UNICODE));
	 }
	}


	function fetch_city()
	{
	 if($this->input->post('country_id'))
	 {
	  echo $this->home_model->fetch_city($this->input->post('country_id'));
	 }
	}


	function fetch_building()
	{
	 if($this->input->post('state_id'))
	 {
	  echo $this->home_model->fetch_building($this->input->post('state_id'));
	 }
	}


	function showComment()
	{
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
				if($this->input->post('timeID'))
				{
					$data = array(
						'showcomment'			=>	$this->input->post('showComment')
						);

					$this->fullcalendar_model->update_event($data, $this->input->post('timeID'));
				
					print_r($data);
			}
		}
	}
	function updateComment()
	{
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
				if($this->input->post('timeID'))
				{
					$data = array(
						'timecomment'			=>	$this->input->post('comment')
						);

					$this->fullcalendar_model->update_event($data, $this->input->post('timeID'));
				
			}
			
		}
	}


	

}

?>
