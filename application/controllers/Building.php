<?php
	class Building extends CI_Controller{
        
        public function __construct()
        {
            parent::__construct();
            $this->load->model('building_model');
			if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID')==='4'){
				$this->session->set_flashdata('errors', 'Sul ei ole õigusi');
				redirect('');
			}
			
		}
		

		function menu(){
			$data['menu'] = 'building'; // Capitalize the first letter
			$data['unapprovedBookings'] = $this->building_model->getUnapprovedBookings($this->session->userdata('building'));
			return $data;
			}

		// public function createRoom(){
			
		// 	$this->building_model->createNewRoom();
		// }

		public function edit($slug){
			if ($this->session->userdata('roleID')==='1'){
				$data=$this->menu();
				$data['regions'] = $this->building_model->getAllRegions();
				$data['editBuildings'] = $this->building_model->get_building($slug);
				$data['bookingformdata'] = $this->building_model->getBookingformData($slug);
				$data['getBookingformDataDetailsOnce'] = $this->building_model->getBookingformDataDetailsOnce($slug);
				$data['getBookingformDataDetailsPeriod'] = $this->building_model->getBookingformDataDetailsPeriod($slug);
				$data['getBookingformDataDetailsEvent'] = $this->building_model->getBookingformDataDetailsEvent($slug);
				if(empty($data['bookingformdata'])){
					$this->building_model->registerBuildingSettings($slug);
					$data['bookingformdata'] = $this->building_model->getBookingformData($slug);
				}
				if(empty($data['getBookingformDataDetailsOnce'])){
					$this->building_model->registerBuildingSettingsDetails($slug, 1);
					$this->building_model->registerBuildingSettingsDetails($slug, 2);
					$this->building_model->registerBuildingSettingsDetails($slug, 3);
					$data['getBookingformDataDetailsOnce'] = $this->building_model->getBookingformDataDetailsOnce($slug);
					$data['getBookingformDataDetailsPeriod'] = $this->building_model->getBookingformDataDetailsPeriod($slug);
					$data['getBookingformDataDetailsEvent'] = $this->building_model->getBookingformDataDetailsEvent($slug);
				}
				$this->load->view('templates/header', $this->security->xss_clean($data));
				$this->load->view('pages/editBuilding', $this->security->xss_clean($data));
				$this->load->view('templates/footer');
			}

			else if ($this->session->userdata['building']!=$slug){

				redirect('building/view/'.$this->session->userdata['building']);
			}else if ($this->session->userdata('roleID')==='2'){
			$data=$this->menu();
			$data['regions'] = $this->building_model->getAllRegions();
			$data['editBuildings'] = $this->building_model->get_building($slug);
			$data['bookingformdata'] = $this->building_model->getBookingformData($this->session->userdata('building'));
			$data['getBookingformDataDetailsOnce'] = $this->building_model->getBookingformDataDetailsOnce($slug);
			$data['getBookingformDataDetailsPeriod'] = $this->building_model->getBookingformDataDetailsPeriod($slug);
			$data['getBookingformDataDetailsEvent'] = $this->building_model->getBookingformDataDetailsEvent($slug);
			if(empty($data['bookingformdata'])){
				$this->building_model->registerBuildingSettings($this->session->userdata('building'));
				$data['bookingformdata'] = $this->building_model->getBookingformData($this->session->userdata('building'));
			}
			if(empty($data['getBookingformDataDetailsOnce'])){
				$this->building_model->registerBuildingSettingsDetails($slug, 1);
				$this->building_model->registerBuildingSettingsDetails($slug, 2);
				$this->building_model->registerBuildingSettingsDetails($slug, 3);
				$data['getBookingformDataDetailsOnce'] = $this->building_model->getBookingformDataDetailsOnce($slug);
				$data['getBookingformDataDetailsPeriod'] = $this->building_model->getBookingformDataDetailsPeriod($slug);
				$data['getBookingformDataDetailsEvent'] = $this->building_model->getBookingformDataDetailsEvent($slug);
			}
			$this->load->view('templates/header', $this->security->xss_clean($data));
			$this->load->view('pages/editBuilding', $this->security->xss_clean($data));
			$this->load->view('templates/footer');
			}else{
			$this->session->set_flashdata('errors', 'Sul ei ole õigusi');
			redirect('');
			}
		}


		
		public function view($slug=FALSE){
			if ($this->session->userdata['building']!=$slug){
			redirect('building/view/'.$this->session->userdata['building']);
			}else{
				if ($this->session->userdata('roleID')==='2'){
					redirect('building/edit/'.$this->session->userdata['building']);
				}
			$data=$this->menu();
			$data['editBuildings'] = $this->building_model->get_building($slug);
			$data['regions'] = $this->building_model->getAllRegions();
			$data['editAllBuildings'] = $this->building_model->get_building();
			$data['editAllRooms'] = $this->building_model->get_rooms();
			$data['unapprovedBookings'] = $this->building_model->getUnapprovedBookings($this->session->userdata('building'));
			$this->load->view('templates/header', $this->security->xss_clean($data));
			$this->load->view('pages/viewBuilding', $this->security->xss_clean($data));
			$this->load->view('templates/footer');
			}	
		}

		public function delete(){
			// Check login
			if ($this->session->userdata('roleID')==='1'){
				$this->form_validation->set_rules('buildingID', 'Asutus', 'integer|required');
				if($this->form_validation->run() === FALSE ){
				$this->session->set_flashdata('errors', 'Ei tohi süsteemi kompromiteerida');
				redirect('');
				}
				$buildingID=$this->input->post('buildingID');
				$allRoomsID=$this->building_model->get_all_roomsID_from_one_building($buildingID);
				$doesBuildingHaveAdmins=$this->building_model->does_building_have_admin($buildingID);
				
				if(empty($allRoomsID) && empty($doesBuildingHaveAdmins)){
					$this->building_model->delete_building($buildingID);
					$this->building_model->delete_building_settings($buildingID);
					$this->session->set_flashdata('building_deleted', 'Asutus kustutatud');
					redirect('building/view/'.$this->session->userdata['building']);
				}
				foreach ($allRoomsID as $id)
				{
					echo $this->building_model->check_if_room_has_reservations_only_in_past($id->id);
					if(!$this->building_model->check_if_room_has_reservations_only_in_past($id->id)){
					$this->session->set_flashdata('message', 'Kahjuks ei saa asutust kustutada, kuna selles on broneeringud alates eelmisest aastast');
					redirect('building/view/'.$this->session->userdata['building']);	
					};	
				}
				if(!empty($allRoomsID)){
					$this->session->set_flashdata('message', 'Ennem tuleb kustutada ruumid, seejärel saab asutust kustutada');
					redirect('building/view/'.$this->session->userdata['building']);	
				}
				if(!empty($doesBuildingHaveAdmins)){
					$this->session->set_flashdata('message', 'Asutusele on määratud peaadministraatorid/administraatorid. Asutuse kustutamiseks tuleb kasutajatelt ennem õigused ära võtta');
					redirect('building/view/'.$this->session->userdata['building']);	
				}
				print_r($doesBuildingHaveAdmins);
				$this->building_model->delete_building($buildingID);
				$this->building_model->delete_building_settings($buildingID);
				$this->session->set_flashdata('building_deleted', 'Asutus kustutatud');
				redirect('building/view/'.$this->session->userdata['building']);
			}
			else{
				$this->session->set_flashdata('errors', 'Sul ei ole õigusi');
				redirect('');
			}
		}

		public function editRoom($slug){
			// check if user has right to change 
			$isAllowedOrNot = $this->building_model->checkIfRoomIsBookable($slug);
			if(!($isAllowedOrNot || $this->session->userdata('roleID')==='1')){
				redirect('');
			}

			$data=$this->menu();

			$data['room_info'] = $this->building_model->get_room($slug);
			$data['room_activity_info'] = $this->building_model->get_room_activities($slug);

			//do changes

			if($this->input->post()){
				
				$data['additionalRoomActivity']=$this->input->post('additionalRoomActivity');
				$this->form_validation->set_rules('room_activity[]', 'Väli', 'trim|htmlspecialchars|xss_clean|max_length[40]');
				$this->form_validation->set_rules('additionalRoomActivity[]', 'Väli', 'trim|htmlspecialchars|xss_clean|max_length[40]');
				if ($this->form_validation->run() === FALSE) {
					$this->session->set_flashdata('errors',
						'Sisestatud andmetega on midagi korrast ära. Palun proovi uuesti.'
					);
					$this->load->view('templates/header', $this->security->xss_clean($data));
					$this->load->view('pages/editRoom', $this->security->xss_clean($data));
					$this->load->view('templates/footer');
									
				}
				else {

				$data_to_db = array(
					'room_id' => $slug
				);

				//first delete all records
				$this->building_model->delete_RoomActivity($data_to_db);
				$this->building_model->delete_ActivityIfIsNotInUse();

				//secondly insert all records
				$this->insertActivityInfo($this->input->post('room_activity'), $slug);
				$this->insertActivityInfo($this->input->post('additionalRoomActivity'), $slug);
				redirect('building/edit/'.$this->session->userdata['building']);
				// if ($this->session->userdata('roleID') === '2') {
				// 	redirect('building/edit/' . $this->session->userdata['building']);
				// }
				// redirect('building/view/' . $this->session->userdata['building']);
			}}
			else {
				$this->load->view('templates/header', $this->security->xss_clean($data));
				$this->load->view('pages/editRoom', $this->security->xss_clean($data));
				$this->load->view('templates/footer');
			}

				
		}
			private function insertActivityInfo($room_activity, $roomID)
			{
				
			if (!empty($room_activity)) {
				for ($t = 0; $t < count($room_activity); $t++) {
					if ($room_activity[$t] !== null) {

						$data1[] = array(
							'activityName' => $room_activity[$t],

						);
						$getActivityID = $this->building_model->createNewActivity($data1[$t]);
						$data2[] = array(
							'activity_id' => $getActivityID,
							'room_id' => $roomID

						);
						$this->building_model->createRoomActivity($data2[$t]);
					}
				}
				$this->session->set_flashdata('post_updated', 'Andmed salvestatud');
				
			}
			
		}
	
		public function deleteRoomActivity()
		{
			
			if ($this->session->userdata('roleID') === '1' || $this->session->userdata('roleID') === '2') {
				$this->form_validation->set_rules('roomID', 'Ruum', 'integer|required');
				$this->form_validation->set_rules('activityID', 'Tegevus', 'integer|required');
				if ($this->form_validation->run() === FALSE) {
					$this->session->set_flashdata('errors', 'Ei tohi süsteemi kompromiteerida');
					return false;
				}

				$id = $this->input->post('roomID');
				if ($this->session->userdata('roleID') === '2' || $this->session->userdata('roleID') === '3') {
					$isAllowedOrNot = $this->building_model->checkIfRoomIsBookable($id);
					if (empty($isAllowedOrNot)) {
						echo json_encode('Sa ei saa võõraid ruume muuta!', JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
						return false;
					};
				}
				$data = array(
					'activity_id' => $this->input->post('activityID'),
					'room_id' => $id
				);

				$deletequery = $this->building_model->delete_RoomActivity($data);
				// Set message
				if ($deletequery === FALSE) {
					//	$this->session->set_flashdata('building_deleted', 'Ei saa ruumi kustutada kuna selles on kehtivad broneeringud. Palun kustuta kõik broneeringud ära ja seejärel proovi uuesti.');	
					echo json_encode('Ei saa ruumi tegevusi.', JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
					//	redirect('building/view/'.$this->session->userdata['building']);
				} else {
					echo json_encode('');
					//	redirect('building/edit/'.$this->session->userdata['building']);
				}
			}
		}

	
		public function deleteRoom(){
			if($this->session->userdata('roleID')==='1' || $this->session->userdata('roleID')==='2'){
			$this->form_validation->set_rules('roomID', 'Ruum', 'integer|required');
			if($this->form_validation->run() === FALSE ){
			$this->session->set_flashdata('errors', 'Ei tohi süsteemi kompromiteerida');
			redirect('');
			}

			$id=$this->input->post('roomID');
			if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
			$isAllowedOrNot=$this->building_model->checkIfRoomIsBookable($id);
			if(empty($isAllowedOrNot)){
				echo json_encode('Sa ei saa kustutada võõraid ruume!',JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			};
			}
			
			$deletequery=$this->building_model->delete_room($id);
			// Set message
			if($deletequery===FALSE){
			//	$this->session->set_flashdata('building_deleted', 'Ei saa ruumi kustutada kuna selles on kehtivad broneeringud. Palun kustuta kõik broneeringud ära ja seejärel proovi uuesti.');	
				echo json_encode('Ei saa ruumi kustutada kuna selles on kehtivad broneeringud. Palun kustuta kõik tulevased broneeringud ära ja seejärel proovi uuesti.',JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			//	redirect('building/view/'.$this->session->userdata['building']);
			}
		
			
			else{
				echo json_encode('');
		//	redirect('building/edit/'.$this->session->userdata['building']);
			}
		}
		}


		public function roomStatus(){
			if($this->session->userdata('roleID')==='1' || $this->session->userdata('roleID')==='2'){
			$this->form_validation->set_rules('roomID', 'Ruum', 'integer|required');
			$this->form_validation->set_rules('roomStatus', 'Ruum', 'integer|required');
			if($this->form_validation->run() === FALSE ){
			$this->session->set_flashdata('errors', 'Ei tohi süsteemi kompromiteerida');
			redirect('');
			}
			$id=$this->input->post('roomID');
			if($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
			$isAllowedOrNot=$this->building_model->checkIfRoomIsBookable($id);
			if(empty($isAllowedOrNot)){
					echo json_encode('Sa ei saa muuta teiste ruumide staatust. Andmeid ei salvestatud!',JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			};
			}
			$data = array(
			
					'roomActive' => $this->input->post('roomStatus')
				);
				$this->building_model->update_room($data, $id);
			
			}
		}



		// public function edit($slug){
		// 	// Check login
		// 	// if(!$this->session->buildingdata('logged_in')){
		// 	// 	redirect('buildings/login');
		// 	// }
		// 	$data['editBuildings'] = $this->building_model->get_building();
		// 	$data['post'] = $this->building_model->get_building($slug);
		// 	// Check building
		// 	// if($this->session->buildingdata('building_id') != $this->post_model->get_building($slug)['building_id']){
		// 	// 	redirect('posts');
		// 	// }
		// //	$data['categories'] = $this->building_model->get_categories();
		// 	if(empty($data['post'])){
		// 		show_404();
		// 	}
		// 	$data['title'] = 'Edit Post';
		// 	$this->load->view('templates/header');
		// 	$this->load->view('pages/editbuilding', $this->security->xss_clean($data));
		// 	$this->load->view('templates/footer');
			
		// }

		public function check_color($input){
			if(preg_match('/^#[a-f0-9]{6}$/', $input)){
				return TRUE;
			}
		
		}

		public function updateBookingSettings(){
			if($this->session->userdata('roleID')==='2'){
				
				foreach($this->input->post() as $postedthing => $key){
					if(substr( $postedthing, 0, 5 ) === "intro" ||substr( $postedthing, 0, 19 ) === "additionalemailtext"  ){
						$this->form_validation->set_rules($postedthing, 'Trimmi ära', 'trim');
					}
					elseif(!(substr( $postedthing, 0, 8 ) === "favcolor")){
						$this->form_validation->set_rules($postedthing, 'Vaikimisi kinnitatud', 'integer|max_length[1]');
					}
					elseif(substr( $postedthing, 0, 8 ) === "favcolor"){
						$this->form_validation->set_rules($postedthing, 'Värvid valed', 'regex_match[/^#([A-Fa-f0-9]{6})$/]');
					}
				}
				if($this->form_validation->run() === FALSE){
					$this->session->set_flashdata('errors','Sisestatud andmetega on midagi korrast ära. Palun proovi uuesti.');
					redirect('building/edit/'.$this->session->userdata['building']);
				}
			
				$data = array(
					//	'name' => $this->input->post('building'),
						'approved_admin' => $this->input->post('approveNow'),
						'clubname_admin' => $this->input->post('clubname_admin'),
						'contactname_admin' => $this->input->post('contact_admin'),
						'phone_admin' => $this->input->post('phone_admin'),
						'email_admin' => $this->input->post('email_admin'),
						'type_admin' => $this->input->post('type_admin'),
						'color1' => $this->input->post('favcolor1'),
						'color2' => $this->input->post('favcolor2'),
						'color3' => $this->input->post('favcolor3'),
						'color4' => $this->input->post('favcolor4'),
						'color5' => $this->input->post('favcolor5'),
						'color6' => $this->input->post('favcolor6'),
						'color7' => $this->input->post('favcolor7'),
						'color8' => $this->input->post('favcolor8'),
						'allow_booking' => $this->input->post('allowBooking'),
						'clubname_user' => $this->input->post('clubname_user'),
						'contactname_user' => $this->input->post('name_user'),
						'phone_user' => $this->input->post('phone_user'),
						'email_user' => $this->input->post('email_user'),
						'type_user' => $this->input->post('type_user'),
					);
					$this->building_model->update_booking_settings($data);
					$threeforms= array('once', 'period', 'event');
					for ($i=0; $i < count($threeforms) ; $i++) { 
						$datasettingsonce = array(
							//	'name' => $this->input->post('building'),
								'showtopublicuser' => $this->input->post('showtopublicuser'.$threeforms[$i]),
								'maxpeaplenumbersee' => $this->input->post('maxpeaplenumbersee'.$threeforms[$i]),
								'maxpeaplenumberrequired' => $this->input->post('maxpeaplenumberrequired'.$threeforms[$i]),
								'groupsee' => $this->input->post('groupsee'.$threeforms[$i]),
								'grouprequired' => $this->input->post('grouprequired'.$threeforms[$i]),
								'publicsee' => $this->input->post('publicsee'.$threeforms[$i]),
								'publicrequired' => $this->input->post('publicrequired'.$threeforms[$i]),
								'prepsee' => $this->input->post('prepsee'.$threeforms[$i]),
								'preprequired' => $this->input->post('preprequired'.$threeforms[$i]),
								'cleansee' => $this->input->post('cleansee'.$threeforms[$i]),
								'cleanrequired' => $this->input->post('cleanrequired'.$threeforms[$i]),
								'agreementsee' => $this->input->post('agreementsee'.$threeforms[$i]),
								'agreementrequired' => $this->input->post('agreementrequired'.$threeforms[$i]),
								'agreementnamesee' => $this->input->post('agreementnamesee'.$threeforms[$i]),
								'agreementnamerequired' => $this->input->post('agreementnamerequired'.$threeforms[$i]),
								'agreementcodesee' => $this->input->post('agreementcodesee'.$threeforms[$i]),
								'agreementcoderequired' => $this->input->post('agreementcoderequired'.$threeforms[$i]),
								'agreementaddresssee' => $this->input->post('agreementaddresssee'.$threeforms[$i]),
								'agreementaddressrequired' => $this->input->post('agreementaddressrequired'.$threeforms[$i]),
								'agreementcontactsee' => $this->input->post('agreementcontactsee'.$threeforms[$i]),
								'agreementcontactrequired' => $this->input->post('agreementcontactrequired'.$threeforms[$i]),
								'agreementemailsee' => $this->input->post('agreementemailsee'.$threeforms[$i]),
								'agreementemailrequired' => $this->input->post('agreementemailrequired'.$threeforms[$i]),
								'agreementphonesee' => $this->input->post('agreementphonesee'.$threeforms[$i]),
								'agreementphonerequired' => $this->input->post('agreementphonerequired'.$threeforms[$i]),
								'methodofpaymentsee' => $this->input->post('methodofpaymentsee'.$threeforms[$i]),
								'methodofpaymentrequired' => $this->input->post('methodofpaymentrequired'.$threeforms[$i]),
								'methodofpaymentcash' => $this->input->post('methodofpaymentcash'.$threeforms[$i]),
								'methodofpaymentcard' => $this->input->post('methodofpaymentcard'.$threeforms[$i]),
								'methodofpaymentbill' => $this->input->post('methodofpaymentbill'.$threeforms[$i]),
								'methodofpaymentprepayment' => $this->input->post('methodofpaymentprepayment'.$threeforms[$i]),
								'methodofpaymentother' => $this->input->post('methodofpaymentother'.$threeforms[$i]),
								'invoicesee' => $this->input->post('invoicesee'.$threeforms[$i]),
								'invoicerequired' => $this->input->post('invoicerequired'.$threeforms[$i]),
								'invoicenamesee' => $this->input->post('invoicenamesee'.$threeforms[$i]),
								'invoicenamerequired' => $this->input->post('invoicenamerequired'.$threeforms[$i]),
								'invoicecodesee' => $this->input->post('invoicecodesee'.$threeforms[$i]),
								'invoicecoderequired' => $this->input->post('invoicecoderequired'.$threeforms[$i]),
								'invoiceaddresssee' => $this->input->post('invoiceaddresssee'.$threeforms[$i]),
								'invoiceaddressrequired' => $this->input->post('invoiceaddressrequired'.$threeforms[$i]),
								'invoicecontact' => $this->input->post('invoicecontact'.$threeforms[$i]),
								'invoicecontactrequired' => $this->input->post('invoicecontactrequired'.$threeforms[$i]),
								'invoiceemailsee' => $this->input->post('invoiceemailsee'.$threeforms[$i]),
								'invoiceemailrequired' => $this->input->post('invoiceemailrequired'.$threeforms[$i]),
								'invoicephonesee' => $this->input->post('invoicephonesee'.$threeforms[$i]),
								'invoicephonerequired' => $this->input->post('invoicephonerequired'.$threeforms[$i]),

								'intro' => $this->input->post('intro'.$threeforms[$i]),
								'emailtext' => $this->input->post('additionalemailtext'.$threeforms[$i])
							);
							$this->building_model->update_booking_settings_details($datasettingsonce, $this->input->post('typeID'.$threeforms[$i]));
					}
					
						
			}
			//print_r($data);
			redirect('building/edit/'.$this->session->userdata['building']);
		}
	

		public function update(){
			if($this->session->userdata('roleID')==='1' || $this->session->userdata('roleID')==='2'){
				$this->form_validation->set_rules('phone', 'Telefon', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('email', 'E-mail', 'trim|htmlspecialchars|valid_email');
				$this->form_validation->set_rules('notifyEmail', 'Teavitamise E-mail', 'trim|htmlspecialchars|valid_email');
				$this->form_validation->set_rules('price_url', 'URL', 'valid_url');
				$this->form_validation->set_rules('place', 'Regiooni ID', 'integer');
				$this->form_validation->set_rules('room[]', 'Ruumi nimetus', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('color[]', 'Ruumi värv', 'trim|htmlspecialchars|callback_check_color');
				$this->form_validation->set_rules('additionalRoom[]', 'Ruumi värv', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('colorForNewRoom[]', 'Ruumi värv', 'trim|htmlspecialchars|callback_check_color');
				$this->form_validation->set_rules('id', 'Asutuse ID', 'integer');
				if($this->form_validation->run() === FALSE){
				
					$this->session->set_flashdata('errors','Sisestatud andmetega on midagi korrast ära. Palun proovi uuesti.');
					redirect('building/view/');
				}
				$data = array(
					//	'name' => $this->input->post('building'),
						'contact_email' => $this->input->post('email'),
						'phone' => $this->input->post('phone'),
						'notify_email' => $this->input->post('notifyEmail'),
						'price_url' => $this->input->post('price_url'),
						'regionID' => $this->input->post('place'),
						
						
					);
				$this->building_model->update_building($data);

				if(count($this->input->post('room'))==0){
					$this->session->unset_userdata('room');
				}
				for($i = 0; $i < count($this->input->post('room')); $i++)
				{
					
					if( $this->input->post('room')[$i]!==null){
					
					$data2[] = array(
							'roomName' => $this->input->post('room')[$i],
							'roomColor' => $this->input->post('color')[$i],
							// 'activeRoom' => 1,
									
					);
				
					print_r($this->input->post('roomID')[$i]);
					
					$this->building_model->update_room($data2[$i], $this->input->post('roomID')[$i] );
					}
				}

				if(!empty($this->input->post('additionalRoom'))){
					for($t = 0; $t <= count($this->input->post('additionalRoom')); $t++)
					{
						if( $this->input->post('additionalRoom')[$t]!==null){
						
						$data3[] = array(
								'roomName' => $this->input->post('additionalRoom')[$t],
								'roomColor' => $this->input->post('colorForNewRoom')[$t],
								'buildingID' => $this->input->post('id'),
								'roomActive' =>  $this->input->post('newRoomStatus')[$t],
										
						);
						$newRoomID=$this->building_model->createNewRoom($data3[$t]);
						if($this->session->userdata('room')==''){
							$sesdata = array(
								'room'     => $newRoomID,
							);
							$this->session->set_userdata($sesdata);
						}
						}
					}
				}
				
				$this->session->set_flashdata('post_updated', 'Andmed salvestatud');
				if ($this->session->userdata('roleID')==='2'){
					redirect('building/edit/'.$this->session->userdata['building']);
				}
				redirect('building/view/'.$this->session->userdata['building']);
			}	
		}


		public function register(){
			if($this->session->userdata('roleID')==='1'){
				$this->form_validation->set_rules('name', 'Name', 'required|trim|htmlspecialchars');
				$this->form_validation->set_rules('email', 'E-mail', 'trim|htmlspecialchars|valid_email');
				$this->form_validation->set_rules('phone', 'Telefon', 'trim|htmlspecialchars');
				//$this->form_validation->set_rules('notifyEmail', 'Teavitamise E-mail', 'trim|htmlspecialchars|valid_email');
				$this->form_validation->set_rules('place', 'Regiooni ID', 'integer');
				$this->form_validation->set_rules('price_url', 'URL', 'valid_url');
			
				$data['regions'] = $this->building_model->getAllRegions();
				if($this->form_validation->run() === FALSE){
					$this->load->view('templates/header');
					$this->load->view('pages/createBuilding');
					$this->load->view('templates/footer');
					
				} else {
					$data = array(
						'name' => $this->input->post('name'),
						'contact_email' => $this->input->post('email'),
						'phone' => $this->input->post('phone'),
					//	'notify_email' => $this->input->post('notifyEmail'),
						'regionID' => $this->input->post('place'),
						'price_url' => $this->input->post('price_url'),				
					);
					$id=$this->building_model->registerBuilding($data);
					$this->building_model->registerBuildingSettings($id);
					$this->building_model->registerBuildingSettingsDetails($id, 1);
					$this->building_model->registerBuildingSettingsDetails($id, 2);
					$this->building_model->registerBuildingSettingsDetails($id, 3);
					$this->session->set_flashdata('user_registered', 'Asutus lisatud');
					redirect('building/view/'.$this->session->userdata['building']);
				}
			}
		}

	}
