<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends CI_Controller {

	public function __construct()
	{

		parent::__construct();
		$this->load->model('edit_model');
		
    }

	function menu(){
		$data['menu'] = 'calendar'; // Capitalize the first letter
		$data['unapprovedBookings'] = $this->edit_model->getUnapprovedBookings($this->session->userdata('building'));
		$data['bookingformdata'] = $this->edit_model->getBookingformData();
		$data['rooms'] = $this->edit_model->getAllRooms();
		return $data;
		}

	// function insertAdditionalDateTime()
	// {
		
		
	// 	$formated_startTime = date("H:i:s", strtotime($this->input->post('begin')));
	// 	$formated_endTime = date("H:i:s", strtotime($this->input->post('end')));
	// 	$formated_date = date("Y-m-d", strtotime($this->input->post('workoutDate')));

	// 	$start_date = date('Y-m-d H:i:s', strtotime("$formated_date $formated_startTime"));
	// 	$end_date = date('Y-m-d H:i:s', strtotime("$formated_date $formated_endTime"));

	// 	$data = array(
	// 		'roomID' => $this->input->post('roomID'),
	// 		'startTime' => $start_date,
	// 		'endTime' => $end_date,
	// 		'bookingID' =>$this ->input->post('id'),
	// 	);
	// 	print_r($data);

	// 	$this->edit_model->insert($data, $this->input->post('id'));


	// 	$MyVariable=$_POST['timesIdArray'];
	// 	$this->session->set_flashdata('timesIdArray', $MyVariable);

	// 	$this->session->set_userdata('referred_from', current_url());
	// 	$referred_from = $this->session->userdata('referred_from');
	// //	redirect($referred_from, 'refresh');
	
	// 		}
	
	

	function load2($bookingID)
	{
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){


		//	$this->form_validation->set_rules('e-mail', 'Email', 'trim|required|valid_email');
		
	//	$this->input->get('saal', TRUE);
		$event_data = $this->edit_model->fetch_all_event();
		foreach($event_data->result_array() as $row)
		
		{
			if(	$row['bookingID']==$bookingID){
				$data[] = array(
					'id'	=>	$row['bookingID'],
				//	'roomID'	=>	$row['roomID'],
					'timeID'=>	$row['timeID'],
				//	'title'	=>	$row['public_info'],
					'start'	=>	$row['startTime'],
					'end'	=>	$row['endTime'],
				//	'event_in'	=>	$row['event_in'],
				//	'event_out'	=>	$row['event_out'],
				//	'clubname'	=>	$row['c_name'],
				//	'workout'	=>	$row['workout'],
				//	'created_at'	=>	$row['created_at'],
				//	'comment'	=>	$row['comment'],
					'building'	=>	$row['name'],
					'roomName'	=>	$row['roomName'],
				//	'bookingID'	=>	$row['bookingID'],
				//	'takesPlace'	=>	$row['takes_place'],
				//	'approved'	=>	$row['approved'],
				//	'organizer'	=>	$row['organizer'],
					'color'	=>	$row['bookingTimeColor'],

				);
			}
		}
			
			echo json_encode($data);
	}else{redirect('');}
	}
	


	function updateprevtodelete()
	{
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
		if($this->input->post('id'))
		{
			$data = array(
				'title'			=>	$this->input->post('title'),
				'startTime'	=>	$this->input->post('start'),
				'endTime'		=>	$this->input->post('end')
			);

			$this->edit_model->update_event($data, $this->input->post('id'));
		}
	}else{redirect('');}
	}



	public function clubname_check($str= '')
	{
			if ($str == '')
			{
				$data['bookingformdata'] = $this->edit_model->getBookingformData();
				if($data['bookingformdata']['clubname_admin']==0 ){
					return TRUE;
				}
				$this->session->set_flashdata('validationErrorMessageForClubname', "<small class='text-danger'>See väli on kohustuslik</small>");
				return FALSE;
			}
			else if(!preg_match("/^[A-Za-z0-9\x{00C0}-\x{00FF} ][A-Za-z0-9\x{00C0}-\x{00FF}\'\-\.\,]+([\ A-Za-z0-9\x{00C0}-\x{00FF}][A-Za-z0-9\x{00C0}-\x{00FF}\'\-]+)*/u", $str)){
				$this->session->set_flashdata('validationErrorMessageForClubname', "<small class='text-danger'>Sellised märgid ei ole lubatud</small>");
				return FALSE;
			}

			else
			{
				return TRUE;
			}
	}

	public function contactPerson_check($str= '')
	{
			if ($str == '')
			{	
				$data['bookingformdata'] = $this->edit_model->getBookingformData();
				if($data['bookingformdata']['contactname_admin']==0 || $this->input->post('closed')=='4'){
					return TRUE;
				}
				$this->session->set_flashdata('validationErrorMessageContactPerson', "<small class='text-danger'>See väli on kohustuslik</small>");
				return FALSE;
			}
			else if(!preg_match("/^[A-Za-z0-9\x{00C0}-\x{00FF} ][A-Za-z0-9\x{00C0}-\x{00FF}\'\-\.\,]+([\ A-Za-z0-9\x{00C0}-\x{00FF}][A-Za-z0-9\x{00C0}-\x{00FF}\'\-]+)*/u", $str)){
				$this->session->set_flashdata('validationErrorMessageContactPerson', "<small class='text-danger'>Sellised märgid ei ole lubatud</small>");
				return FALSE;
			}

			else
			{
					return TRUE;
			}
	}


	public function phoneNumber_check($str= '')
	{
		if ($str == '')
			{
				$data['bookingformdata'] = $this->edit_model->getBookingformData();
				if($data['bookingformdata']['phone_admin']==0 || $this->input->post('closed')=='4'){
					return TRUE;
				}
				$this->session->set_flashdata('validationErrorMessageForPhone', "<small class='text-danger'>See väli on kohustuslik</small>");
				return FALSE;
			}
		else if(!preg_match('/^\+?[\d\s]+$/', $str))
			{
					$this->session->set_flashdata('phoneIsNotCorrect', "<small class='text-danger'>Numbri formaat ei sobi</small>");
					return FALSE;
			}
			else
			{
					return TRUE;
			}
	}

	public function email_cant_be_empty($str= '')
	{
			if ($str == '' && $this->input->post('closed')!='4')
			{
				$data['bookingformdata'] = $this->edit_model->getBookingformData();
				if($data['bookingformdata']['email_admin']==0){
					return TRUE;
				}
				$this->form_validation->set_message('email_cant_be_empty', 'Email on kohustuslik');
				return false;
			}
			return TRUE;
		
	}
	public function type_cant_be_empty($str= '')
	{
			if ($str == '' && $this->input->post('type')!='4')
			{
				if($this->input->post('closed')=='4'){
					return TRUE;
				}
				$data['bookingformdata'] = $this->edit_model->getBookingformData();
				if($data['bookingformdata']['type_admin']==0){
					return TRUE;
				}
				$this->session->set_flashdata('type_flash', "<small class='text-danger'>Tüüp on kohustuslik</small>");
				return false;
			}
			return TRUE;
		
	}
	

	public function checkIfIsAllowedToUpdate($id)
	{
		
		return $this->edit_model->can_update_or_not($this->session->userdata('building'),$id);

	}


	public function getAllConflictData($allEventsForConflictCheck,$bookingDataWhichUserWantsToChange)
	{
		$conflictTimes = array();
		foreach($allEventsForConflictCheck as $key => $value){
			$property1 = 'startTime'; 
			$property2 = 'endTime'; 
			$property3 = 'public_info'; 
			$property4 = 'workout'; 
			$property5 = 'timeID'; 
			foreach($bookingDataWhichUserWantsToChange as $key2 => $value2){
		 
		 
				if($value->$property1<$value2[0]['endTime'] && $value->$property2>$value2[0]['startTime']){
					$conflictTimes[] = array(
						'start' => $value->$property1,
						'end' =>  $value->$property2,
						'title' => $value->$property3,
						'description' => $value->$property4,
						'timeID' => $value->$property5
						);
						
			
				}
			
				//$value['startTime'],$value['endTime']
			
			}
		}
		return json_encode($conflictTimes);
	}

	public function index()
	{
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
			$data=$this->menu();
			$data['allPostData']=$this->input->post();
			$data['selectedRoom']= $this->edit_model->getSelectedRoom($data['allPostData']['timesIdArray'][0]);
		
			
			// print_r($data['allPostData']);
			// echo '<br>';
			// print_r($this->security->xss_clean($data['allPostData']));
			// echo '<br>';
			
			foreach ($this->input->post('timesIdArray') as $id) {
				$bookingDataWhichUserWantsToChange[]=$this->edit_model->get_allbookingtimes($this->input->post('BookingID'),$id);
				if(!$this->checkIfIsAllowedToUpdate($id)){
					redirect('fullcalendar?roomId='.$this->input->post('roomID').'&date='. date('d.m.Y', strtotime($this->input->post('bookingtimesFrom')[0])));
		
				};
			}
			$data['bookingData']=$bookingDataWhichUserWantsToChange[0][0];
			$data['allBookingData']=$bookingDataWhichUserWantsToChange;
			
			$roomWhereSearchForConflicts=$this->edit_model->get_room($this->input->post('BookingID'));
			$allEventsForConflictCheck=$this->edit_model->get_conflictsDates($roomWhereSearchForConflicts['roomID'], $this->input->post('BookingID'));


			if($this->input->post('isPeriodic')){
				$bookingDataWhichUserWantsToChange=array();
				$whichWeekDaynumberToSearch=date('N', strtotime($data['bookingData']['startTime']));
				$getAlltimeIDToChange=$this->edit_model->get_lastDate($whichWeekDaynumberToSearch, $data['bookingData']['bookingID'],$data['bookingData']['startTime']);
				foreach ($getAlltimeIDToChange as $id) {
					
					$bookingDataWhichUserWantsToChange[]=$this->edit_model->get_allbookingtimes($this->input->post('BookingID'),$id['timeID']);
					if(!$this->checkIfIsAllowedToUpdate($id['timeID'])){
						redirect('fullcalendar?roomId='.$this->input->post('roomID').'&date='. date('d.m.Y', strtotime($this->input->post('bookingtimesFrom')[0])));
			
					};
				}

			}
			$conflictTimes=$this->getAllConflictData($allEventsForConflictCheck,$bookingDataWhichUserWantsToChange);
		
			$data['conflictTimes']= $conflictTimes;
			
			
			$this->load->view('templates/header',$this->security->xss_clean($data));
			$this->load->view('pages/edit',$this->security->xss_clean($data));//see leht laeb vajalikku vaadet. ehk saab teha controllerit ka mujale, mis laeb õiget lehte
			$this->load->view('templates/footer');

			return $data;
		}
	}


	// Update Once
	public function update()
	{	
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){

		$this->form_validation->set_rules('publicInfo', 'Klubi nimi', 'trim|htmlspecialchars|callback_clubname_check');
		$this->form_validation->set_rules('contactPerson', 'Kontaktisik', 'trim|htmlspecialchars|callback_contactPerson_check');
		$this->form_validation->set_rules('phone', 'Telefon', 'trim|htmlspecialchars|callback_PhoneNumber_check');
		$this->form_validation->set_rules('email', 'E-mail', 'trim|htmlspecialchars|valid_email|callback_email_cant_be_empty');
		$this->form_validation->set_rules('workoutType', 'Sündmus / Treeningu tüüp', 'trim|htmlspecialchars|callback_type_cant_be_empty');
		$this->form_validation->set_rules('additionalComment', 'Lisainfo', 'trim|htmlspecialchars');
	
		if($this->form_validation->run() === FALSE ){

			if($this->input->post('dontShow')!=1){
			$this->session->set_flashdata("emailIsNotCorrect", form_error('email', '<small class="text-danger">','</small>')); 
			$this->session->set_flashdata('validationErrorMessage', 'Vormiga on midagi valesti');
			}
			$data=$this-> index();

		} 
		else{

			$data1 = array(
				'public_info'=>$this->input->post('publicInfo'),
				'c_name' => $this ->input->post('contactPerson'),
				'organizer' => $this ->input->post('organizer'),
				'c_phone' => $this ->input->post('phone'),
				'c_email' => $this ->input->post('email'),
				'comment' => $this ->input->post('additionalComment'),
				'workout' => $this ->input->post('workoutType'),
				
			);
			
				$this->edit_model->update_booking($data1, $this->input->post('BookingID'));
				
				$insert_data = array();
				$start_data = $this->input->post('bookingtimesFrom');
				
				
				$RedirectToCalendar='';
				for($i = 0; $i < count($start_data); $i++)
				{
					

					$formated_startTime = date("H:i:s", strtotime($this->input->post('timeStart')[$i])); //kell alates
					$formated_endTime = date("H:i:s", strtotime($this->input->post('timeEnd')[$i])); //kell kuni
					$formated_date = date("Y-m-d", strtotime($this->input->post('bookingtimesFrom')[$i])); //kuupäev
			
					$start_date = date('Y-m-d H:i:s', strtotime("$formated_date $formated_startTime"));
					$end_date = date('Y-m-d H:i:s', strtotime("$formated_date $formated_endTime"));
			
					if(strtotime("$formated_date $formated_startTime")>=strtotime("$formated_date $formated_endTime")){

						$this->form_validation->set_message('validationErrorMessage', 'Kuupäevad ei ole õigesti sisestatud.');
						$this->session->set_flashdata('validationErrorMessage', 'Kellaaeg on valesti sisestatud');
						$data=$this-> index();
					
						$RedirectToCalendar=false;
					break;

					}
					else{
						$RedirectToCalendar=true;
						$insert_data[] = array(
						'roomID' => $this->input->post('roomID'),
						'startTime' => $start_date, 
						'endTime' => $end_date,
						'bookingTimeColor' =>$this->input->post('color')[$i],
						'hasChanged' => '1',
						
						);
						$dataForVersion=$this->edit_model->get_info_for_version($this->input->post('timesIdArray')[$i]);
					
						
							
							$saveVersion=$this->saveVersionOrNot($this->input->post('timesIdArray')[$i], $dataForVersion[0]['startTime'],$dataForVersion[0]['endTime'], 
							$insert_data[$i],  $this->input->post('reason'), $dataForVersion[0]['bookingTimeColor'],  $dataForVersion[0]);
						
							if($saveVersion){
							$this->edit_model->insert_version($saveVersion);
							}
							$this->edit_model->update_bookingTimes($insert_data[$i], $this->input->post('timesIdArray')[$i]);
							
						
					}
				}
				$allEventsForConflictCheck=$this->edit_model->get_conflictsDates2($this->session->userdata('building'),$this->input->post('roomID'), $this->input->post('BookingID'));
				foreach($allEventsForConflictCheck as $key => $value){
					$property1 = 'startTime'; 
					$property2 = 'endTime'; 
					$property3 = 'public_info'; 
					$property4 = 'workout'; 
					foreach($insert_data as $key2 => $value2){
				 
						
						if($value->$property1<$value2['endTime'] && $value->$property2>$value2['startTime']){
							//insert_data3 mean conflick days
							$insert_data3[] = array(
							  
								'startTime' => $value->$property1,
								'endTime' =>  $value->$property2,
								'public_info' => $value->$property3,
								'workout' => $value->$property4
								);
		
						break;
						}
					
						//$value['startTime'],$value['endTime']
					
					}
		
				}
			
				if($RedirectToCalendar&&!empty($insert_data3)&&$this->input->post('allowSave')==0){
				//	$this->session->set_flashdata('validationErrorMessage', 'Tuvastatud kattuvus');
					$this->session->set_flashdata('conflictDates', $this->security->xss_clean($insert_data3));
					$data=$this-> index();
					$RedirectToCalendar=false;
					
					}
			
				
			//print_r($this->input->post());

		if($this->input->post('additionalBookingDate')){
		
			$addtimes = array();
			for($t = 0; $t <= count($this->input->post('additionalBookingDate')); $t++) {
			

				if(isset($this->input->post('additionalBookingDate')[$t])){
				$formated_startTime = date("H:i:s", strtotime($this->input->post('additionalBookingtimeStart')[$t]));
				$formated_endTime = date("H:i:s", strtotime($this->input->post('additionalBookingtimeEnd')[$t]));
				$formated_date = date("Y-m-d", strtotime($this->input->post('additionalBookingDate')[$t]));
		
				$start_date = date('Y-m-d H:i:s', strtotime("$formated_date $formated_startTime"));
				$end_date = date('Y-m-d H:i:s', strtotime("$formated_date $formated_endTime"));
		
				$addtimes[] = array(
					'roomID' => $this->input->post('roomID'),
					'startTime' => $start_date,
					'endTime' => $end_date,
					'bookingID' =>$this ->input->post('BookingID'),
					'bookingTimeColor' =>$this->input->post('addcolor')[$t],
				
				);
			
				$this->edit_model->insert($addtimes[$t], $this->input->post('id'));
				
			}
			}
		}	
	
			if($RedirectToCalendar){
				redirect('fullcalendar?roomId='.$this->input->post('roomID').'&date='. date('d.m.Y', strtotime($this->input->post('bookingtimesFrom')[0])));
	
			}
		
		
	}

	}else{
		//Kui pole juht ega haldur, siis viib avalehele
		redirect('');}
	
	}

	



	public function updatePeriod()
	{	
	
		
		if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
		


			$this->form_validation->set_rules('publicInfoPeriod', 'Klubi nimi', 'trim|htmlspecialchars|callback_clubname_check');
			$this->form_validation->set_rules('contactPersonPeriod', 'Kontaktisik', 'trim|htmlspecialchars|callback_contactPerson_check');
			$this->form_validation->set_rules('phonePeriod', 'Telefon', 'trim|htmlspecialchars|callback_PhoneNumber_check');
			$this->form_validation->set_rules('emailPeriod', 'E-mail', 'trim|htmlspecialchars|valid_email|callback_email_cant_be_empty');
			$this->form_validation->set_rules('workoutTypePeriod', 'Sündmus / Treeningu tüüp', 'trim|htmlspecialchars|callback_type_cant_be_empty');
			$this->form_validation->set_rules('additionalCommentPeriod', 'Lisainfo', 'trim|htmlspecialchars');
			
		

			if($this->form_validation->run() === FALSE ){

				if($this->input->post('dontShow')!=1){
				$this->session->set_flashdata("emailIsNotCorrect", form_error('email', '<small class="text-danger">','</small>')); 
				$this->session->set_flashdata('validationErrorMessage', 'Vormiga on midagi valesti');
				}
				$data=$this-> index();
			//	print_r($data);

			} 
			else{

			$data1 = array(
				'public_info'=>$this->input->post('publicInfoPeriod'),
				'c_name' => $this ->input->post('contactPersonPeriod'),
				'organizer' => $this ->input->post('organizerPeriod'),
				'c_phone' => $this ->input->post('phonePeriod'),
				'c_email' => $this ->input->post('emailPeriod'),
				'comment' => $this ->input->post('additionalCommentPeriod'),
				//'building' => $this ->input->post('building'), //pole seda formis
				'workout' => $this ->input->post('workoutTypePeriod'),
				// 'event_in' => $this ->input->post('eventIn'),
				// 'event_out' => $this ->input->post('phone')
			);
			
				$this->edit_model->update_booking($data1, $this->input->post('BookingID'));
				
					$insert_data = array();
					$start_data = $this->input->post('bookingtimesFrom');
					
					$weekday=$this->input->post('weekendNumber');
					if ($weekday==0? $weekday=7:$weekday);
					$weekdayToChange=$this->input->post('weekday')[0];
					$days= array('Esmaspäev', 'Teisipäev', 'Kolmapäev', 'Neljapäev', 'Reede', 'Laupäev','Pühapäev');
					$key = array_search($weekdayToChange, $days);
					$difference=$key-$weekday+1;
			//		echo $difference;
					
					$RedirectToCalendar='';
					for($i = 0; $i < count($start_data); $i++)
					{
						$datestr=$this->input->post('bookingtimesFrom')[$i];
						$added_timestamp = 	strtotime($datestr. '  '.$difference.' days');
					
						$formated_startTime = date("H:i:s", strtotime($this->input->post('timesStart'))); //kell alates
						$formated_endTime = date("H:i:s", strtotime($this->input->post('timeTo'))); //kell kuni
						$formated_date = date("Y-m-d", $added_timestamp); //kuupäev
				
						$start_date = date('Y-m-d H:i:s', strtotime("$formated_date $formated_startTime"));
						$end_date = date('Y-m-d H:i:s', strtotime("$formated_date $formated_endTime"));
				
						if(strtotime("$formated_date $formated_startTime")>=strtotime("$formated_date $formated_endTime")){

							$this->form_validation->set_message('validationErrorMessage', 'Kuupäevad ei ole õigesti sisestatud.');
							$this->session->set_flashdata('validationErrorMessage', 'Kellaaeg on valesti sisestatud');

							
							$data=$this-> index();
						
							$RedirectToCalendar=false;
							break;

						}
						else{
							$RedirectToCalendar=true;
						$insert_data[] = array(
							'roomID' => $this->input->post('roomID'),
							'startTime' => $start_date, 
							'endTime' => $end_date,
							'bookingTimeColor' =>$this->input->post('color'),	
							'hasChanged' => '1',
							
							);
							//print_r( $this->input->post('timesIdArray')[$i]) ;

							$dataForVersion=$this->edit_model->get_info_for_version($this->input->post('timesIdArray')[$i]);

							$saveVersion=$this->saveVersionOrNot($this->input->post('timesIdArray')[$i], $dataForVersion[0]['startTime'],$dataForVersion[0]['endTime'], 
							$insert_data[$i],  $this->input->post('reason'), $dataForVersion[0]['bookingTimeColor'],  $dataForVersion[0]);
						
							if($saveVersion){
							$this->edit_model->insert_version($saveVersion);
							}
						
							$this->edit_model->update_bookingTimes($insert_data[$i], $this->input->post('timesIdArray')[$i]);
						}
				
				}
		
				$allEventsForConflictCheck=$this->edit_model->get_conflictsDates2($this->session->userdata('building'),$this->input->post('roomID'), $this->input->post('BookingID'));
				foreach($allEventsForConflictCheck as $key => $value){
					$property1 = 'startTime'; 
					$property2 = 'endTime'; 
					$property3 = 'public_info'; 
					$property4 = 'workout'; 
					foreach($insert_data as $key2 => $value2){
				 
						
						if($value->$property1<$value2['endTime'] && $value->$property2>$value2['startTime']){
							//insert_data3 mean conflick days
							$insert_data3[] = array(
							  
								'startTime' => $value->$property1,
								'endTime' =>  $value->$property2,
								'public_info' => $value->$property3,
								'workout' => $value->$property4
								);
		
						break;
						}
					
						//$value['startTime'],$value['endTime']
					
					}
		
				}
			
				if($RedirectToCalendar&&!empty($insert_data3)&&$this->input->post('allowSave')==0){
				//	$this->session->set_flashdata('validationErrorMessage', 'Tuvastatud kattuvus');
					$this->session->set_flashdata('conflictDates', $this->security->xss_clean($insert_data3));
					$data=$this-> index();
					$RedirectToCalendar=false;
					
					}

		if($this->input->post('additionalBookingDate')){
		
			$addtimes = array();
			for($t = 0; $t <= count($this->input->post('additionalBookingDate')); $t++) {
		
			}

		}	
		else{
			if($RedirectToCalendar){
				redirect('fullcalendar?roomId='.$this->input->post('roomID').'&date='. date('d.m.Y', strtotime($this->input->post('bookingtimesFrom')[0])));
		
			
			}
		
		}
	}

	}else{
		//Kui pole juht ega haldur, siis viib avalehele
		redirect('');}
	
	}


	function saveVersionOrNot($timesID, $startTimeFromDB, $endTimefromDB, $insertData, $reason, $color, $roomfromDB){
	
	$dataForVersioning = array(
			'timeID'	=>$timesID,
			'nameWhoChanged'		=>	$this->session->userdata('userName'),
			'reason'	=>	$reason,
		);
		if(!in_array( $roomfromDB['roomID'] , $insertData) ){
		
			$dataForVersioning['reason']=	"Ruum: ".$roomfromDB['roomName'].'. '.$reason;
		}
	if( !in_array( $startTimeFromDB , $insertData) 
			|| !in_array( $endTimefromDB , $insertData) 
			)
			{
				$dataForVersioning['startTime']= $startTimeFromDB;
				$dataForVersioning['endTime']=	$endTimefromDB;
				return $dataForVersioning;
			}

		else if(  $dataForVersioning['reason']!=''){
			return $dataForVersioning;
		}
		else if(!in_array( $color , $insertData) &&  $reason==''){
			$dataForVersioning['reason']="värvi muudatus";
			return $dataForVersioning;
		}
		else{
			return false;
		}
	}


}

?>
