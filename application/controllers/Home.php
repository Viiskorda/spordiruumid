<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('home_model');
	}
	

	function menu(){
		$data['menu'] = 'home'; // Capitalize the first letter
		$data['unapprovedBookings'] = $this->home_model->getUnapprovedBookings($this->session->userdata('building'));
		return $data;
		}

	function index()
	{//	$data['title'] = "Hello Everyone!";
		$data=$this->menu();
		$data['rooms'] = $this->fullcalendar_model->getAllRooms();
		$data['regions'] = $this->fullcalendar_model->getAllRegions();
		$data['buildings'] = $this->fullcalendar_model->getAllBuildings();
	
		
		$this->load->view('templates/header',$data);
		$this->load->view('pages/fullcalendar',$data);
		$this->load->view('templates/footer',$data);
	}

	public function view($page = 'home') //pääseb ligi: https://tigu.hk.tlu.ee/~annemarii.hunt/codeigniter/calendar/home
	{
		if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
			// Whoops, we don't have a page for that!
			redirect('');
		  //  show_404();
		}
		$data=$this->menu();
		$data['unapprovedBookings'] = $this->home_model->getUnapprovedBookings($this->session->userdata('building'));
		$data['requestFromBuilding']=$this->home_model->chech_if_has_request($this->session->userdata('email'));
	
		$data['title'] = ucfirst($page); // Capitalize the first letter

		$data['activities'] = $this->home_model->getAllActivities();
		$data['regions'] = $this->home_model->getAllRegions();
		$data['buildings'] = $this->home_model->getAllBuildings();
		$data['rooms'] = $this->home_model->getAllRooms();

		$this->load->view('templates/header', $data);
		$this->load->view('pages/' . $page, $data);
		$this->load->view('templates/footer', $data);

		// $logged = false;
	}

	function fetch_building_from_activity_or_region()
	{
		$activity_id=NULL;
		$country_id=NULL;

		$this->form_validation->set_rules('activity_id', '', 'integer');
		$this->form_validation->set_rules('country_id', '', 'integer');

		if($this->form_validation->run() === FALSE ){
			return;
		}

		if ($this->input->post('activity_id')) {
			$activity_id=$this->input->post('activity_id');
		}
		if ($this->input->post('country_id')) {
			$country_id=$this->input->post('country_id');
		} 
		echo $this->home_model->fetch_building_from_activity_or_region($activity_id, $country_id);
	}

	function fetch_rooms_from_activity()
	{
		$this->form_validation->set_rules('activity_id', '', 'integer');

		if ($this->form_validation->run() === FALSE) {
			return;
		}

		if ($this->input->post('activity_id')) {
			echo $this->home_model->fetch_rooms_from_activity($this->input->post('activity_id'));
		}
	}

	function fetch_region()
	{
		$activity_id = NULL;

		$this->form_validation->set_rules('activity_id', '', 'integer');

		if($this->form_validation->run() === FALSE ){
			return;
		}

		if ($this->input->post('activity_id')) {
			$activity_id = $this->input->post('activity_id');
		}
		echo $this->home_model->fetch_region($activity_id);
	}
	
	function fetch_rooms_from_region_activity_building()
	{
		$activity_id=NULL;
		$country_id=NULL;
		$buildingID=NULL;

		$this->form_validation->set_rules('activity_id', '', 'integer');
		$this->form_validation->set_rules('country_id', '', 'integer');
		$this->form_validation->set_rules('buildingID', '', 'integer');

		if($this->form_validation->run() === FALSE ){
			return;
		}

		if ($this->input->post('activity_id')) {
			$activity_id=$this->input->post('activity_id');
		}
		if ($this->input->post('country_id')) {
			$country_id=$this->input->post('country_id');
		} 
		if ($this->input->post('buildingID')) {
			$buildingID=$this->input->post('buildingID');
		}
		
		echo $this->home_model->fetch_rooms_from_region_activity_building($activity_id, $country_id, $buildingID);

	}

}
