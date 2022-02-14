<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Statistics extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID') === '4'  || $this->session->userdata('roleID') === '1') {
			//	$this->session->set_flashdata('errors', 'Sul ei ole õigusi');
			//	redirect('');
		}
		//	if ( $this->session->userdata('roleID')==='2'  || $this->session->userdata('roleID')==='3'){

			$this->load->model('statistics_model');
		//	}
	}

	function menu()
	{
		if (empty($this->session->userdata('roleID'))  || $this->session->userdata('roleID')==='4' ){
			$this->session->set_flashdata('errors', 'Sul ei ole õigusi');
			redirect('');
		}
		$data['menu'] = 'statistics'; // Capitalize the first letter
		$data['unapprovedBookings'] = $this->statistics_model->getUnapprovedBookings($this->session->userdata('building'));
		return $data;
	}

	function index()
	{ //	$data['title'] = "Hello Everyone!";
		$data = $this->menu();
		$data['statistics_data'] = $this->statistics_model->fetch_all_statistics();
	

		$this->load->view('templates/header', $data);
		
		$this->load->view('pages/statistics', $data);
		
		$this->load->view('templates/footer', $data);
	}
}
