<?php
	class Users extends CI_Controller{
        
        public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');
    
		}
		
		function menu(){
			$data['menu'] = 'users'; // Capitalize the first letter
			$data['unapprovedBookings'] = $this->user_model->getUnapprovedBookings($this->session->userdata('building'));
			return $data;
		}

			
		public function index(){
			if ($this->session->userdata('roleID')==='1' || $this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
			
			$data=$this->menu();
			$data['manageUsers'] = $this->user_model->get_users();
		//	print_r($data['manageUsers']);
			for ($i=0; $i<count($data['manageUsers']); $i++){

				$userHasAlreadyAdditionalRights=$this->user_model->this_user_has_rights($data['manageUsers'][$i]['userID']);
				if(!$userHasAlreadyAdditionalRights){
					//kirjuta õigused andmebaasi
					$getOldRightsData=$this->user_model->check_if_user_has_already_rights_in_building($data['manageUsers'][$i]['userID']);
					$roomID1 = $this->user_model->get_room($getOldRightsData['buildingID']);
					$this->user_model->insert_old_rights($getOldRightsData, $roomID1);
				}
				$userHasAlreadyAdditionalRights=$this->user_model->this_user_has_rights_and_get_building_names($data['manageUsers'][$i]['userID']);
	
					$data['additionalRights'] = $userHasAlreadyAdditionalRights;
					// echo "<pre>";
					// print_r($data['manageUsers'][$i]['userID']);
					// print_r($userHasAlreadyAdditionalRights);
					// echo "</pre>";
					$data['manageUsers'][$i]['buildingName']=array_column($userHasAlreadyAdditionalRights, 'name');
					$data['manageUsers'][$i]['roleName']=array_column($userHasAlreadyAdditionalRights, 'role');
					$data['manageUsers'][$i]['additionalBuilding']=array_column($userHasAlreadyAdditionalRights, 'buildingID');
					
					if ($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
						if(!in_array($this->session->userdata('building'),array_column($userHasAlreadyAdditionalRights, 'buildingID'))){
							$data['manageUsers'][$i]['buildingName']='';
					};
				}

			}
			
			
			$data['buildings'] = $this->user_model->getAllBuildings();
			$data['unapprovedBookings'] = $this->user_model->getUnapprovedBookings($this->session->userdata('building'));
			//check if all users has all rights in db named userrights
			
			$this->load->view('templates/header', $this->security->xss_clean($data));
			$this->load->view('pages/manageUsers', $this->security->xss_clean($data));
			$this->load->view('templates/footer');
		
			} else {
				$this->session->set_flashdata('errors', 'Sul ei ole õigusi');
				redirect('');
			}
		}


		public function registerSelf(){
			$this->load->helper(array('form', 'url'));

			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'Nimi', 'trim|required|htmlspecialchars|callback_contactPerson_check');
			$this->form_validation->set_rules('phone', 'Telefon', 'trim|htmlspecialchars|callback_phoneNumber_check');
			$this->form_validation->set_rules('email', 'E-mail', 'trim|required|callback_check_email_and_password_exists|valid_email');
			$this->form_validation->set_rules('password', 'Parool', 'required|min_length[7]');
            $this->form_validation->set_rules('password2', 'Parool uuesti', 'matches[password]');
			
			$data['postdata']=$this->input->post();
			if($this->form_validation->run() === FALSE){
				$this->session->set_flashdata("password", form_error('password', '<small class="text-danger">','</small>')); // tekst '{field} ei ole korrektselt sisestatud' tuleb failist form_validation_lang.php
				$this->form_validation->set_message("matches", 'TESRES'); // tekst '{field} ei ole korrektselt sisestatud' tuleb failist form_validation_lang.php
				$this->menu();
				$this->load->view('templates/header', $this->security->xss_clean($data));
				$this->load->view('pages/register', $this->security->xss_clean($data));
                $this->load->view('templates/footer');
                
			} else {
				// Encrypt password
              //  $enc_password = md5($this->input->post('password'));
                $enc_password =  password_hash($this->input->post('password'), PASSWORD_DEFAULT);

				$newEntryNotUpdate=$this->user_model->user_is_in_db($this->input->post('email'));
			
				if($newEntryNotUpdate){
					$this->session->set_flashdata('user_registered', 'Lõid endale kasutaja ja nüüs saad sisse logida');
					$this->user_model->update_user_himself($enc_password);
					redirect('login');
					
				}else{
					$this->session->set_flashdata('user_registered', 'You are now registered and can log in');
					$this->user_model->registerSelfDB($enc_password);
					redirect('');
				}
				
				// Set message
			
			//	redirect('');
			}
		}



	//seda vist pole vaja
		// public function register(){
		// 	$data['title'] = 'Sign Up';
		// 	$this->form_validation->set_rules('name', 'Name', 'required');
        //     $this->form_validation->set_rules('phone', 'Phone');
		// 	$this->form_validation->set_rules('email', 'Email', 'required|callback_check_email_exists');
		// 	$this->form_validation->set_rules('password', 'Password', 'required');
        //     $this->form_validation->set_rules('password2', 'Confirm Password', 'matches[password]');
            
		// 	if($this->form_validation->run() === FALSE){
              
		// 		$this->load->view('templates/header');
		// 		$this->load->view('pages/register', $data);
        //         $this->load->view('templates/footer');
                
		// 	} else {
		// 		// Encrypt password
        //       //  $enc_password = md5($this->input->post('password'));
        //         $enc_password = $this->input->post('password');
		// 		$this->user_model->register($enc_password);
		// 		// Set message
		// 		$this->session->set_flashdata('user_registered', 'You are now registered and can log in');
		// 		redirect('fullcalendar?roomId=1');
		// 	}
		// }

	// Register user by gov admin
		public function registerByAdmin(){
			if ($this->session->userdata('roleID')==='1' || $this->session->userdata('roleID')==='2'){
				$this->form_validation->set_rules('email', 'E-mail', 'trim|htmlspecialchars|valid_email');
				$this->form_validation->set_rules('buildingID', 'Asutuse ID', 'integer|required');
				$this->form_validation->set_rules('role', 'Roll', 'integer|required');
			   
			   if($this->form_validation->run() === FALSE ){
				   $this->session->set_flashdata('errors', 'Sisetamisel läks midagi valesti. Palun proovi uuesti.');
				   $this->session->set_flashdata("emailIsNotCorrect", form_error('email', '<small class="text-danger">','</small>'));
				   $inputEmail= $this->input->post('email');
				   $this->session->set_flashdata('email', $inputEmail);
				   redirect('users/addRightsToUser');
				   
			   } else if($this->input->post('buildingID')=='0' && $this->input->post('role')!='1'){
				   $this->session->set_flashdata('errors', 'Asutus valimata!');
				   redirect('users/addRightsToUser');
			   }
			   else		
			   {
					$buildingID=$this->input->post('buildingID');
					
					$role=$this->input->post('role');
					$requestFromBuilding='1';
					
					if($this->session->userdata('roleID')==='2'){
						$buildingID=$this->session->userdata('building');
					}
					if($role==='1' || $role==='4' ){
						$requestFromBuilding='0';
					}

					if($this->session->userdata('roleID')==='2' && $this->input->post('role')==='1'){
						$this->session->set_flashdata('errors', 'Saa ei saa määrata adminni õigusi');
						redirect('users/addRightsToUser');
					}
					$data = array(
					'email' => $this->input->post('email'),
					'buildingID' => $buildingID,
					'roleID' => $role,
					'requestFromBuilding' => $requestFromBuilding,
					); 
					$roomID2 = $this->user_model->get_room($buildingID);
				   $emailIsInDB=$this->user_model->check_email_exists($this->input->post('email'));
				   if(!$emailIsInDB){
					   //register username and rights
					   $createdUserID= $this->user_model->insert_user_in_DB_and_give_rights($data);

					   $this->user_model->insert_new_rights( $createdUserID, $buildingID, $role, $roomID2);
					   $this->session->set_flashdata('success', 'Kasutajale lisati õigused, kuid see kasutaja pole veel süsteemi sisse loginud. Palun teavitage teda, et ta teeks endale konto sama e-mailiga konto');
					   //nüüd saada emmail

				   } else {
					   
						//kasutaja on juba olemas, seega vaata, kas tal on olemas midagi userrights all
						$userHasAlreadyAdditionalRights=$this->user_model->this_user_has_rights($emailIsInDB['userID']);
				//		print_r($userHasAlreadyAdditionalRights);

						if(!in_array($buildingID, array_column($userHasAlreadyAdditionalRights, 'buildingID'))){
							//kas kasutajal on juba sellele asutusele ligipääs olemas?
							
							$insertTODb=$this->user_model->insert_new_rights($emailIsInDB['userID'], $buildingID, $role, $roomID2);
							if(!$insertTODb){
								$this->session->set_flashdata('user_registered', 'Kasutajal on õigused juba olemas');
							} else {
								$this->session->set_flashdata('user_registered', 'Kasutajale õigused lisatud');
							}
						}
						else {
							//kasutajal on olemas sellele asutusele ligipääs, kuid äkki tuleb muuta tema adminni staatus? samas ta saab seda teha kui uuendab kasutajat...

							$this->session->set_flashdata('errors', 'Kasutajal on juba juurdepääs sellele asutusele');
						}
					
				   }
				   redirect('manageUsers');
			   }

			}
			else{
				$this->session->set_flashdata('errors', 'Sul ei ole õigusi');
				redirect('');
			}
			
		}

	// recaptcha code source
	// http://avenir.ro/integrating-googles-recaptcha-in-codeigniters-form-validation-the-callback-way/
		public function recaptcha($str='')
		{
		  $google_url="https://www.google.com/recaptcha/api/siteverify";
		  $secret='6LcgVOkUAAAAAHr2Ze8jyESv0RaQhmRYqDI_uWrQ';
		  $ip=$_SERVER['REMOTE_ADDR'];
		  $url=$google_url."?secret=".$secret."&response=".$str."&remoteip=".$ip;
		  $curl = curl_init();
		  curl_setopt($curl, CURLOPT_URL, $url);
		  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
		  $res = curl_exec($curl);
		  curl_close($curl);
		  $res= json_decode($res, true);
		  //reCaptcha success check
		  if($res['success'])
		  {
			return TRUE;
		  }
		  else
		  {
			$this->form_validation->set_message('recaptcha', 'reCAPTCHA on kohustuslik');
			return FALSE;
		  }
		}

		


		// Log in user
		public function login(){
			
			$this->form_validation->set_rules('email', 'E-mail', 'trim|htmlspecialchars|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('g-recaptcha-response','Captcha','callback_recaptcha');
            
			$inputEmail= $this->input->post('email');
			if($this->form_validation->run() === FALSE){
				$this->session->set_flashdata("emailIsNotCorrect", form_error('email', '<small class="text-danger">','</small>')); // tekst '{field} ei ole korrektselt sisestatud' tuleb failist form_validation_lang.php
				$this->session->set_flashdata('email', $inputEmail);
				$this->session->set_flashdata('errors', 'Proovi uuesti');
				$this->session->set_flashdata("recaptcha_response", form_error('g-recaptcha-response', '<small class="text-danger">','</small>')); // tekst '{field} ei ole korrektselt sisestatud' tuleb failist form_validation_lang.php
				redirect('login');
			} else {
             
			
				// Get and encrypt the password
               // $password = md5($this->input->post('password'));
                            	
				// Login user
				$email    = $this->input->post('email',TRUE);
				//$password = md5($this->input->post('password',TRUE));
				$password = $this->input->post('password',TRUE);
				$getpasswordhash = $this->user_model->get_hash($email)['pw_hash'];
			
			
				$validate = $this->user_model->get_user_info($email);
				//print_r(password_verify($password, $getpasswordhash));
				if(password_verify($password, $getpasswordhash)=='1'){
					$data  = $validate;
					$name  = $data['userName'];
					$phone  = $data['userPhone'];

					$email = $data['email'];
					$userID = $data['userID'];
					$roleID = $data['roleID'];
					$getAllBuildingIdsWhereIAmAdmin = $this->user_model->getAllBuildingIdsWhereIAmAdmin($email);
					array_push($getAllBuildingIdsWhereIAmAdmin, [ "buildingID" => 0, "name" => 'Eraisik' ]);
					
					$userHasAlreadyAdditionalRights=$this->user_model->this_user_has_rights($userID);

					if(in_array(1, array_column($userHasAlreadyAdditionalRights, 'roleID'))){
						array_push($getAllBuildingIdsWhereIAmAdmin, [ "buildingID" => 'admin', "name" => 'Linnavalitsuse administraator' ]);
					}

					$sesdata = array(
						'userName'  => $name,
						'phone'  => $phone,
						'email'     => $email,
						'userID'  => $userID,
						'roleID'     => $roleID,
						'my_building_ids' => $getAllBuildingIdsWhereIAmAdmin,
						'session_id' => TRUE
					);

					if( $data['requestFromBuilding']=='0'){
						$building  = $data['buildingID'];
						$sesdata['building']=$building;
						$sesdata['buildingName']=$this->user_model->getBuildingName($building)['name'];
						$sesdata['room']=$this->user_model->getRoomID($building)['id'];
					}
					$this->user_model->update_last_login($email);
					$this->session->set_userdata($sesdata);
					$this->session->set_flashdata('success', 'Oled edukalt sisse logitud');
					// access login for admin
					if($roleID === '1'){
						redirect('');
					
					// access login for staff
					}else if(!array_key_exists('building',$this->session->userdata())){
						$this->session->set_flashdata('success', 'Teile on määratud eriõigused. Palun aktsepteerige need või lükake tagasi.');
						redirect('profile/view/'.$this->session->userdata['userID']);
				
					// access login for author
					}else{
						redirect('');
					}
					
				} else {
					// Set message
					$this->session->set_flashdata('email', $inputEmail);
					$this->session->set_flashdata('login_failed', 'Kasutajanimi või parool ei sobi');
					redirect('login');
				}		
			}
		}
		// Log user out
		public function logout(){
			// Unset user data
			$this->session->unset_userdata('session_id');
			$this->session->unset_userdata('user_id');
			$this->session->unset_userdata('email');
			$this->session->sess_destroy();
			// Set message
			$this->session->set_flashdata('user_loggedout', 'You are now logged out');
			redirect('');
		}

		// Check if email exists
		public function check_email_exists($email){
			if($this->user_model->check_email_exists($email)){
				return true;
			} else {
		//		$this->session->set_flashdata('emailIsNotCorrect', 'See email on juba võetud. Palun vali muu email');
				return false;
			}
		}


		public function check_email_and_password_exists($email){
			if($this->user_model->check_email_and_password_exists($email)){
				$this->form_validation->set_message('check_email_and_password_exists', 'See e-mail on juba võetud');
				return false;
			} else {
				return true;
			}
		}


		public function addRightsToUser(){
			if ($this->session->userdata('roleID')==='1' || $this->session->userdata('roleID')==='2'){
			$data=$this->menu();
			$data['buildings'] = $this->user_model->getAllBuildings();
		//	$data['mybuildings'] = $this->user_model->getAllBuildingsICanGiveAccessTo($this->session->userdata('userID'));
			$this->load->view('templates/header', $this->security->xss_clean($data));
			$this->load->view('pages/createUser',  $this->security->xss_clean($data));
			$this->load->view('templates/footer');
		
			}else{
				$this->session->set_flashdata('errors', 'Sul ei ole õigusi');
				redirect('manageUsers');
			}
		}


		public function delete(){
			// Only admins allowed to make changes
			if ( $this->session->userdata('roleID')==='1'){
				$id=$this->input->post('userID');
				$buildingID=$this->input->post('buildingID');
				$this->user_model->delete_userrights($id, $buildingID);

				$getUserData=$this->user_model->get_user_info_by_id($id);
					if($getUserData['buildingID']==$this->input->post('buildingID')){
					$data = array(
						'roleID' => 4,
						'buildingID' => 0
					);
					$this->user_model->update_user($data, $id);
				}
				
				//$this->user_model->delete_user($id);
			
				$this->session->set_flashdata('user_deleted', 'Your user has been deleted');
				redirect('manageUsers');
			}
		}


		public function edit($slug, $buildingID){
			if ($this->session->userdata('roleID')==='1' || $this->session->userdata('roleID')==='2'){
				$data=$this->menu();
				$data['post'] = $this->user_model->get_users($slug);
				$data['buildings'] = $this->user_model->getAllBuildings();
				

				if ($this->session->userdata('roleID')==='2'){
					$data['buildings'] = $this->user_model->get_one_building_data($this->session->userdata('building'));
					if($data['post']['roleID']==="1" || !in_array($data['post']['buildingID'], array_column($this->session->userdata('my_building_ids'), 'buildingID'))){
				
						$this->session->set_flashdata('message', 'Sul ei ole õigusi muuta neid kasutajaid');
						redirect('manageUsers');
					}
				} else if($this->session->userdata('roleID')==='1'){
					$data['additionalRights'] = $this->user_model->this_user_has_rights_and_get_building_names($data['post']['userID']);
					$data['post']['additionalBuilding']=array_column($data['additionalRights'], 'buildingID');
					//siia peab tulema uus roleID
					print_r($this->user_model->get_user_where_id_and_buildingID($slug, $buildingID));
					$data['post']['roleID']= ($this->user_model->get_user_where_id_and_buildingID($slug, $buildingID))['roleID'];

				//	print_r(in_array($buildingID, $data['post']['additionalBuilding']));
					if(in_array($buildingID, $data['post']['additionalBuilding'])){
						$data['post']['buildingID']=$buildingID;
					}
				
				}
			
				if(empty($data['post'])){
					show_404();
				}
			
				$this->load->view('templates/header', $this->security->xss_clean($data));
				$this->load->view('pages/editUser', $this->security->xss_clean($data));
				$this->load->view('templates/footer');
			}
			else{
				$this->session->set_flashdata('errors', 'Sul ei ole õigusi');
				redirect('');
			}
		}


		public function update(){
			if ($this->session->userdata('roleID')==='1' || $this->session->userdata('roleID')==='2' ){

				$buildingID=$this->input->post('buildingID');
				$oldbuildingID=$this->input->post('oldbuildingID');
				$oldRoleID=$this->input->post('oldRoleID');
				
			//	$requestFromBuilding='1';
				$userID=$this->input->post('id');

				if($this->session->userdata('roleID')==='2'){
					$buildingID=$this->session->userdata('building');
					
					if($this->input->post('roleID')=='1'){
						$this->session->set_flashdata('errors', 'Sul ei ole õigust panna kasutajatele Linnavalitsuse adminni õigusi');
						redirect('manageUsers');
					}
				}

				if($this->input->post('roleID')==='4' ){
			//		$requestFromBuilding='0';
					$buildingID='0';
				}
				if($this->input->post('roleID')==='1' && $this->input->post('buildingID')!='0'){
					//		$requestFromBuilding='0';
					$this->session->set_flashdata('errors', 'Linnavalitsuse administraatori määramiseks tuleb jätta asutus valimata');
					redirect('users/edit/'.$userID.'/'.$this->input->post('buildingID'));
				}

				if( $buildingID=='0' && ($this->input->post('roleID')==='2' || $this->input->post('roleID')==='3') ){
					$this->session->set_flashdata('role',$this->input->post('roleID'));
					$this->session->set_flashdata('errors', 'Asutus valimata');
			//		redirect('users/edit/'.$userID.'/'.$this->input->post('buildingID'));
				}
				$roomID2 = $this->user_model->get_room($buildingID);

				$this->form_validation->set_rules('id', 'Asutuse ID', 'integer|required');
				$this->form_validation->set_rules('roleID', 'Roll', 'integer|required');
				$this->form_validation->set_rules('buildingID', 'Ruumi ID', 'integer|required');

				if($this->form_validation->run() === FALSE){
				
					$this->session->set_flashdata('errors', 'Midagi läks valesti');
					redirect('users/edit/'.$userID.'/'.$this->input->post('buildingID'));
				} 

				// if user was already in one buildingID and we want to change roleID from 2 to 3 or 3 to 2, then requestFromBuilding has to be 0
				$ifUserIsAlreadyInBuildingAndWeWantToChangeroleID2or3=$this->user_model->this_user_has_rights($userID);

				foreach($ifUserIsAlreadyInBuildingAndWeWantToChangeroleID2or3 as $rights){
					if($rights['buildingID']==$buildingID){
					//	$requestFromBuilding='0';
					}
				}
			
				$roleIDtoDB= $this->input->post('roleID');
			//	
				//check if user has some other permissions
				
				$userHasAdditionalRights=$this->user_model->this_user_has_rights($userID);
				if($roleIDtoDB==4){
					if(in_array($this->input->post('buildingID'), array_column($userHasAdditionalRights, 'buildingID'))){
						$this->user_model->delete_userrights($userID, $this->input->post('buildingID'));
					}

					$getUserData=$this->user_model->get_user_info_by_id($userID);
					if($getUserData['buildingID']==$this->input->post('buildingID')){
						$data = array(
							'roleID' => $roleIDtoDB,
							'buildingID' => 0
						);
						$this->user_model->update_user($data, $userID);
					}
				} else {

					//delete old data
					

					//insert new data
					$data = array(
						'userID' => $userID,
						'roleID' => $roleIDtoDB,
						'buildingID' => $buildingID,
						'default_room_id' => $roomID2
					//	'requestFromBuilding' => $requestFromBuilding,
					);

					
					if($buildingID==$oldbuildingID){
						$this->session->set_flashdata('user_registered', 'Kasutaja õigused uuendatud');
						$this->user_model->update_userrights($data, $userID, $buildingID, $roleIDtoDB);
					}
					else {
						$this->user_model->delete_userrights($userID, $oldbuildingID);
						$insertTODb=$this->user_model->insert_new_rights($userID, $buildingID, $roleIDtoDB, $roomID2);
						if(!$insertTODb){
							$this->session->set_flashdata('user_registered', 'Kasutajal on õigused juba olemas');
						} else {
							$this->session->set_flashdata('user_registered', 'Kasutajale õigused lisatud');
						}
					}
				
				

				}
			
				echo '<pre>';
				print_r($this->db->last_query());   
				$getUserData=$this->user_model->get_user_info_by_id($userID);
				echo '<br>';
			//	print_r($getUserData);
				echo '</pre>';
				if($getUserData['buildingID']==$this->input->post('buildingID')){
					$data = array(
						'roleID' => $roleIDtoDB,
						'buildingID' => $buildingID
					);
					$this->user_model->update_user($data, $userID);
				}
				
				// Set message
			//	$this->session->set_flashdata('post_updated', 'Uuendasid kasutajat');
				redirect('manageUsers');
			}
			else{
				$this->session->set_flashdata('errors', 'Sul ei ole õigusi');
				redirect('');
			}
		}


		//for DataTables
		function fetch_allbookingsInfo(){  
			if ($this->session->userdata('roleID')==='2' || $this->session->userdata('roleID')==='3'){
			$this->load->model("user_model");  
			$fetch_data = $this->user_model->make_datatables();  
			$data = array(); 
			$phoneIsNotZero=""; 
			foreach($fetch_data as $row)  
			{  
				if ($row->c_phone!=0) { $phoneIsNotZero=$row->c_phone; }
				 $sub_array = array();  
				 $sub_array[] = $this->security->xss_clean($row->public_info);  
				 $sub_array[] = $this->security->xss_clean($row->c_name);  
				 $sub_array[] = $this->security->xss_clean($phoneIsNotZero);  
				 $sub_array[] = $this->security->xss_clean($row->c_email);  
			
				 $data[] = $sub_array;  
			}  
			$output = array(  
				 "draw"                    =>     intval($_POST["draw"]),  
				 "recordsTotal"          =>      $this->user_model->get_all_data(),  
				 "recordsFiltered"     =>     $this->user_model->get_filtered_data(),  
				 "data"                    =>     $data  
			);  
			echo json_encode($output);  
		}  
	   }  





	   public function phoneNumber_check($str= '')
	   {
		   if ($str == '')
			   {
				   return TRUE;
			   }
		   else if(!preg_match('/^\+?[\d\s]+$/', $str))
			   {
					$this->form_validation->set_message('phoneNumber_check', 'Numbri formaat ei sobi');
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
				   $this->form_validation->set_message('contactPerson_check', '{field} on kohustuslik');
				   return FALSE;
			   }
			   else if(!preg_match("/^[A-Za-z0-9\x{00C0}-\x{00FF} ][A-Za-z0-9\x{00C0}-\x{00FF}\'\-\.\,]+([\ A-Za-z0-9\x{00C0}-\x{00FF}][A-Za-z0-9\x{00C0}-\x{00FF}\'\-]+)*/u", $str) && $this->input->post('type')!='4'){
			 	   $this->form_validation->set_message('contactPerson_check', 'Sellised märgid ei ole lubatud');
				   return FALSE;
			   }
   
			   else
			   {
				  return TRUE;
			   }
	   }


	   public function changeSessionData($buildingID){
		//check if user can change to this

		//get buildingID that user can change
		$getDataAboutUser=$this->user_model->get_user_buildingids_and_roleids($this->session->userdata('email'), $this->session->userdata('userID'), $buildingID);
	
		//if buildingID match, then set in session buildingID and roleID
		
		if ($buildingID=='0'){
			$this->session->set_userdata('building','');
			$this->session->set_userdata('roleID','4');
			$this->session->set_userdata('room', '');
			$this->session->set_userdata('buildingName','');
			$this->session->set_flashdata('user_registered', 'Oled tavakasutaja rollis');
			redirect('');
		} else if($buildingID=='admin'){
		
			//check if user has admin rights
			if(in_array(1, array_column($getDataAboutUser, 'roleID'))){
				$this->session->set_userdata('building', $getDataAboutUser[0]['buildingID']);
				$this->session->set_userdata('roleID', $getDataAboutUser[0]['roleID']);
				$this->session->set_userdata('room', '');
				$this->session->set_userdata('buildingName','');
				$this->session->set_flashdata('user_registered', 'Muutsid asutust');
				redirect('');
			}
		}
		else if($getDataAboutUser){
			$this->session->set_userdata('building', $getDataAboutUser[0]['buildingID']);
			$this->session->set_userdata('roleID', $getDataAboutUser[0]['roleID']);
			$this->session->set_userdata('room', $this->user_model->getRoomID($getDataAboutUser[0]['buildingID'])['id']);
			$this->session->set_userdata('buildingName',$this->user_model->getBuildingName($getDataAboutUser[0]['buildingID'])['name']);

			$this->session->set_flashdata('user_registered', 'Muutsid asutust');
			redirect('');
		}
		else {
			//good try hijacking ninja
			$this->session->set_flashdata('errors', 'Asutust ei saanud valida');
			redirect('');
		}

		

	   }
	   

	}
