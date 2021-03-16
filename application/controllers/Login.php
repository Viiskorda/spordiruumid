<?php
class Login extends CI_Controller{
    
  function __construct(){
	parent::__construct();
	$this->load->library('facebook'); 
    $this->load->model('login_model');
  }
 
  function menu(){
	$data['menu'] = 'allbookings'; // Capitalize the first letter
	$data['unapprovedBookings'] = $this->login_model->getUnapprovedBookings($this->session->userdata('building'));
	return $data;
	}

  function index(){
	$data=$this->menu();
	$data['google']=$this->login();
	$data['facebook']=$this->fblogin();
    $this->load->view('templates/header', $this->security->xss_clean($data));
    $this->load->view('pages/login', $this->security->xss_clean($data));
    $this->load->view('templates/footer');
  }



//begin with Facebook OAuth
// Source code: https://www.codexworld.com/facebook-login-codeigniter/
	function fblogin()
	{

	$userData = array(); 
         
	// Authenticate user with facebook 
	if($this->facebook->is_authenticated()){ 
		// Get user info from facebook 
		$fbUser = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email'); 

		// Preparing data for database insertion 
	//	$userData['oauth_provider'] = 'facebook'; 
		$userData['login_oauth_uid']    = !empty($fbUser['id'])?$fbUser['id']:'';; 
		$userData['userName']    = !empty($fbUser['first_name'])?$fbUser['first_name'].' '.$fbUser['last_name']:''; 
	//	$userData['last_name']    = !empty($fbUser['last_name'])?$fbUser['last_name']:''; 
		$userData['email']        = !empty($fbUser['email'])?$fbUser['email']:''; 
	//	$userData['gender']        = !empty($fbUser['gender'])?$fbUser['gender']:''; 
	//	$userData['picture']    = !empty($fbUser['picture']['data']['url'])?$fbUser['picture']['data']['url']:''; 
	//	$userData['link']        = !empty($fbUser['link'])?$fbUser['link']:'https://www.facebook.com/'; 
		 
		// Insert or update user data to the database 
		$userID = $this->login_model->checkUser($userData); 
		 
		// Check user data insert or update status 
		if(!empty($userID)){ 
			$data['userData'] = $userData; 
			 
			// Store the user profile info into session 
			$this->session->set_userdata('userData', $userData); 
		}else{ 
		   $data['userData'] = array(); 
		} 
		 
		// Facebook logout URL 
		$data['logoutURL'] = $this->facebook->logout_url(); 
	}else{ 
		// Facebook authentication url 
		$data['authURL'] =  $this->facebook->login_url(); 
	} 
	 

	if(!$this->session->userdata('userData'))
	{
	  
	   return $data;
	   
	}
	else
	{
		$getUserData = $this->login_model->get_user_info($userData['email']);
		if($getUserData->num_rows() > 0){
		$data  = $getUserData->row_array();
		$name  = $data['userName'];
		$phone  = $data['userPhone'];
		$building  = $data['buildingID'];
		$email = $data['email'];
		$userID = $data['userID'];
		$roleID = $data['roleID'];
	//	$room = $data['id'];
		}

		$getAllBuildingIdsWhereIAmAdmin = $this->login_model->getAllBuildingIdsWhereIAmAdmin($email);
		array_push($getAllBuildingIdsWhereIAmAdmin, [ "buildingID" => 0, "name" => 'Eraisik' ]);
		
		$userHasAlreadyAdditionalRights=$this->login_model->this_user_has_rights($userID);

		if(in_array(1, array_column($userHasAlreadyAdditionalRights, 'roleID'))){
			array_push($getAllBuildingIdsWhereIAmAdmin, [ "buildingID" => 'admin', "name" => 'Linnavalitsuse administraator' ]);
		}

		$sesdata = array(
			'userName'  => $name,
			'phone'  => $phone,
		//	'room'  => $room,
			'email'     => $email,
			'userID'  => $userID,
			'roleID'     => $roleID,
			'session_id' => TRUE,
			'oauth'  => true,
			'my_building_ids' => $getAllBuildingIdsWhereIAmAdmin
		);

		if( $data['requestFromBuilding']=='0'){
			$building  = $data['buildingID'];
			$sesdata['building']=$building;
			$sesdata['room']=$this->login_model->getRoomID($building)['id'];
		}
		$this->session->set_userdata($sesdata);
			if(!array_key_exists('building',$this->session->userdata())){
				$this->session->set_flashdata('success', 'Teile on määratud eriõigused. Palun aktsepteerige need või lükake tagasi.');
				redirect('profile/view/'.$this->session->userdata['userID']);
		
			// access login for author
			}
	//	print_r($data);
		$this->load->view('templates/header');
		$this->load->view('pages/fblogin', $data);
		$this->load->view('templates/footer');
		$this->session->unset_userdata('fb_access_token'); 
		$this->session->unset_userdata('fb_expire'); 
		$this->session->unset_userdata('login_oauth_uid');
		$this->session->set_flashdata('success', 'Oled edukalt sisse logitud');
		$this->login_model->update_last_login($email);
		redirect('');
	}
	// Load login/profile view 
	
	  
	
	
		
	}

//end of Facebook OAuth


	// Google OAuth start
	// used Webslesson from youtube: https://www.youtube.com/watch?v=Kd9Yp9CcVIY and his/her homepage https://www.webslesson.info/2020/03/google-login-integration-in-codeigniter.html
	// WPHire from youtube: https://www.youtube.com/watch?v=lAqOZ3nXG7o

	function login()
	{
	 include_once APPPATH . "libraries/vendor/autoload.php";
	 $user_data=array();
	// $data=array();
	 $google_client = new Google_Client();
 	 $google_client->setClientId('840873810053-5dbvqpg2qjpd0ir5a11o3k7srljqdbbu.apps.googleusercontent.com'); //Define your ClientID
 	 $google_client->setClientSecret('XoNmVkn5mPIDGCzxk1RaK_Fi'); //Define your Client Secret Key
 	 $google_client->setRedirectUri('http://localhost/spordiruumid/login/login'); //Define your Redirect Uri
 	 $google_client->addScope('email');
 	 $google_client->addScope('profile');
 
	 if(isset($_GET["code"]))
	 {
		$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
		
		if(!isset($token["error"]))
		{
		 $google_client->setAccessToken($token['access_token']);
 		 $this->session->set_userdata('access_token', $token['access_token']);
 		 $google_service = new Google_Service_Oauth2($google_client);
 		 $data = $google_service->userinfo->get();
 		 $current_datetime = date('Y-m-d H:i:s');
		
		 if($this->login_model->is_already_register($data['email']))
		 {
			//update data
			$user_data = array(
			'userName'  => $data['given_name'].' '.$data['family_name'],
			'email'  => $data['email'],
			'updated_at' => $current_datetime
			);
 
			$this->login_model->update_user_data($user_data, $data['id']);
		 }
		 else
		 {
			//insert data
			$user_data = array(
		//	 'login_oauth_uid' => $data['id'],
			 'userName'  => $data['given_name'].' '.$data['family_name'],
			 'email'  => $data['email'],
			 'created_at'  => $current_datetime
			);
 
			$this->login_model->insert_user_data($user_data);
		 }
		 $this->session->set_userdata('user_data', $user_data);
		}
	 }
	 $login_button = '';
	 if(!$this->session->userdata('access_token'))
	 {
		$login_button = '<a href="'.$google_client->createAuthUrl().'"><img src="'.base_url().'assets/img/sign-in-with-google.png"  width="54%" /></a>';
		$data['login_button'] = $login_button;
		return $data;
		
	 }
	 else
	 {
		
		$getUserData = $this->login_model->get_user_info($user_data['email']);
		print_r($getUserData);
		if($getUserData->num_rows() > 0){
		$data  = $getUserData->row_array();
		$name  = $data['userName'];
		$phone  = $data['userPhone'];
		$building  = $data['buildingID'];
		$email = $data['email'];
		$userID = $data['userID'];
		$roleID = $data['roleID'];
	//	$room = $data['id'];
		}

		$getAllBuildingIdsWhereIAmAdmin = $this->login_model->getAllBuildingIdsWhereIAmAdmin($email);
		array_push($getAllBuildingIdsWhereIAmAdmin, [ "buildingID" => 0, "name" => 'Eraisik' ]);
		
		$userHasAlreadyAdditionalRights=$this->login_model->this_user_has_rights($userID);

		if(in_array(1, array_column($userHasAlreadyAdditionalRights, 'roleID'))){
			array_push($getAllBuildingIdsWhereIAmAdmin, [ "buildingID" => 'admin', "name" => 'Linnavalitsuse administraator' ]);
		}

		$sesdata = array(
			'userName'  => $name,
			'phone'  => $phone,
		//	'room'  => $room,
			'email'     => $email,
			'userID'  => $userID,
			'roleID'     => $roleID,
			'session_id' => TRUE,
			'oauth'  => true,
			'my_building_ids' => $getAllBuildingIdsWhereIAmAdmin
		);

		if( $data['requestFromBuilding']=='0'){
			$building  = $data['buildingID'];
			$sesdata['building']=$building;
			$sesdata['room']=$this->login_model->getRoomID($building)['id'];
		}
		$this->login_model->update_last_login($email);
		$this->session->set_userdata($sesdata);
			if(!array_key_exists('building',$this->session->userdata())){
				$this->session->set_flashdata('success', 'Teile on määratud eriõigused. Palun aktsepteerige need või lükake tagasi.');
				redirect('profile/view/'.$this->session->userdata['userID']);
		
			// access login for author
			}
	
		$this->load->view('templates/header');
		$this->load->view('pages/logingoogle', $data);
		$this->load->view('templates/footer');
		   
		$this->session->unset_userdata('access_token'); 
		$this->session->set_flashdata('success', 'Oled edukalt sisse logitud');
		
		redirect('');
	 }
	}
// Google OAuth finish



 
  function logout(){
			$this->session->sess_destroy();

			$this->session->unset_userdata('access_token');
			$this->session->unset_userdata('user_data');
	
      redirect('login');
  }
 
}
