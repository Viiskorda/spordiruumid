<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public function __construct($slug=FALSE)
    {
        parent::__construct();
		$this->load->model('profile_model');
		if (empty($this->session->userdata('userID'))){
			$this->session->set_flashdata('errors', 'Sa pole sisse logitud');
			redirect('');
		}
       
	}
	
	function menu(){
		$data['menu'] = 'profile'; // Capitalize the first letter
		$data['unapprovedBookings'] = $this->profile_model->getUnapprovedBookings($this->session->userdata('building'));
		return $data;
	}
	

	public function view($slug=FALSE){
   
        if ($this->session->userdata['userID']!=$slug){
            redirect('profile/view/'.$this->session->userdata['userID']);
        }else{
		$data=$this->menu();
        $data['editProfile'] = $this->profile_model->get_profile($slug);
    //	var_dump($slug);
        $this->load->view('templates/header', $this->security->xss_clean($data));
        $this->load->view('pages/profile', $this->security->xss_clean($data));
        $this->load->view('templates/footer');
    }	}



	public function edit($slug=FALSE){
   
        if ($this->session->userdata['userID']!=$slug){
            redirect('profile/edit/'.$this->session->userdata['userID']);
        }else{
		$data=$this->menu();

        $data['editProfile'] = $this->profile_model->get_profile($slug);
		$data['allMyRoles'] = $this->profile_model->getAllBuildingIdsWhereIAmAdmin($slug);

    //	var_dump($slug);
        $this->load->view('templates/header', $this->security->xss_clean($data));
        $this->load->view('pages/editProfile', $this->security->xss_clean($data));
        $this->load->view('templates/footer');
		
	}	
	}

	public function acceptTheRequest(){
		$data = array();
		$this->form_validation->set_rules('giveaccess', 'Ligipääs', 'integer|max_length[1]');
		if($this->form_validation->run() === FALSE ){
			$this->session->set_flashdata('message','Proovi uuesti');
			redirect('profile/edit/'.$this->session->userdata['userID']);
		}


		$desicion=$this->input->post('giveaccess');
		$buildingID='';
		if($desicion=='1'){
			$data = array(
				'requestFromBuilding' =>'0',
				'buildingID' => '0',
				'roleID' => '4'
			);
			$this->profile_model->update_profile($data);
			$this->session->unset_userdata('session_id');
			$this->session->sess_destroy();
			$this->session->set_flashdata('user_loggedout', 'Turvalisuse huvides teid logiti välja');
		//	redirect('');
		}else{
			$data = array(
				'requestFromBuilding' =>'0',
			);
			$building=$this->profile_model->get_my_buildingID($data);
		//	print_r($building);
			$sesdata = array(
				'building'     => $building['buildingID'],
				'buildingName'     => $this->profile_model->getBuildingName($building['buildingID'])['name'],
				'room'=> $this->profile_model->getRoomID($building['buildingID'])['id']
			);
			$this->session->set_userdata($sesdata);
		}

		$this->profile_model->update_profile($data);
		$this->session->set_flashdata('post_updated', 'Vastus salvestatud ning juurdepääs asutuse andmetele on tagatud.');
		redirect('profile/view/'.$this->session->userdata['userID']);

	}


    public function updateProfile(){
		$postData = $this->input->post();

		$this->form_validation->set_rules('name', 'Nimi', 'trim|htmlspecialchars|required|callback_clubname_check');
		$this->form_validation->set_rules('phone', 'Telefon', 'trim|htmlspecialchars|callback_PhoneNumber_check');
		$this->form_validation->set_rules('buildingID', 'Asutus/roll', 'trim|htmlspecialchars|required|is_numeric');
	
		if($this->form_validation->run() === FALSE ){
			$this->session->set_flashdata('key',$this->security->xss_clean($postData));
			redirect('profile/edit/'.$this->session->userdata['userID']);
		}

		$data['allMyRoles'] = $this->profile_model->getAllBuildingIdsWhereIAmAdmin($this->session->userdata['userID']);
		$roleID=4;
		$buildingID=$this->input->post('buildingID');
		$array_key=array_search($buildingID, array_column($data['allMyRoles'], 'buildingID'));
		if(!in_array($buildingID, array_column($data['allMyRoles'], 'buildingID'))){
			$buildingID=0;
		} else {
			$roleID=$data['allMyRoles'][$array_key]['roleID'];
		}
	
		if($this->input->post('passwordnow')=='' && $this->input->post('password')==''){
			$data = array(
				'userName' => $this->input->post('name'),
				'userPhone' => $this->input->post('phone'),
				'buildingID' => $buildingID,
				'roleID' => $roleID,
			);
		} else {
			
			
			$getpasswordhash = $this->profile_model->get_hash($this->session->userdata('email'));
			// print_r($getpasswordhash['pw_hash'] );
			// print_r(password_verify($this->input->post('passwordnow'), $getpasswordhash['pw_hash']));
			if(password_verify($this->input->post('passwordnow'), $getpasswordhash['pw_hash'])=='1' || $this->session->userdata('oauth')){
				$this->form_validation->set_rules('password', 'Parool', 'required|min_length[5]');
				$this->form_validation->set_rules('password2', 'Parool uuesti', 'required|matches[password]');
		
				if($this->form_validation->run() === FALSE ){
					$this->session->set_flashdata("password", form_error('password', '<small class="text-danger">','</small>'));
					$this->session->set_flashdata("password2", form_error('password2', '<small class="text-danger">','</small>'));
					$this->session->set_flashdata('key',$this->security->xss_clean($postData));
					$this->session->set_flashdata('show',true);
					redirect('profile/edit/'.$this->session->userdata['userID']);
		
				}
				$data = array(
					'userName' => $this->input->post('name'),
					'userPhone' => $this->input->post('phone'),
					'pw_hash' =>  password_hash($this->input->post('password'), PASSWORD_DEFAULT),
					'buildingID' => $buildingID,
					'roleID' => $roleID,
					);
			
			} else {
				$this->session->set_flashdata('passwordnow', 'Parool ei sobi');
				$this->session->set_flashdata('key',$this->security->xss_clean($postData));
				$this->session->set_flashdata('show',true);
				redirect('profile/edit/'.$this->session->userdata['userID']);
			}

	
		}
		
		$this->profile_model->update_profile($data);
        // Set message
        $this->session->set_flashdata('post_updated', 'Uuendasid oma profiili');
        redirect('profile/edit/'.$this->session->userdata['userID']);
	}
	

	public function clubname_check($str= '')
	{
			if ($str == '')
			{
				$this->session->set_flashdata('validationErrorMessageForName', "<small class='text-danger'>See väli on kohustuslik</small>");
				return FALSE;
			}
			else if(!preg_match("/^[A-Za-z0-9\x{00C0}-\x{00FF} ][A-Za-z0-9\x{00C0}-\x{00FF}\'\-\.\,]+([\ A-Za-z0-9\x{00C0}-\x{00FF}][A-Za-z0-9\x{00C0}-\x{00FF}\'\-]+)*/u", $str)){
				$this->session->set_flashdata('validationErrorMessageForName', "<small class='text-danger'>Sellised märgid ei ole lubatud</small>");
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
				return TRUE;
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


}
