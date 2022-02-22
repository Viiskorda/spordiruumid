<?php
class Pages extends CI_Controller
{
        public function __construct()
	{
		parent::__construct();
		$this->load->model('pages_model');
	}

	function menu(){
	$data['menu'] = 'calendar'; // Capitalize the first letter
	$data['unapprovedBookings'] = $this->pages_model->getUnapprovedBookings($this->session->userdata('building'));
	return $data;
	}


        public function view($page = 'home')
        {
                if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
                        // Whoops, we don't have a page for that!
                        redirect('');
                      //  show_404();
		}
		$data=$this->menu();
	        $data['title'] = ucfirst($page); // Capitalize the first letter
                $roomid=$this->input->get('roomId', TRUE);
		$activity_id='';
		if($this->input->get('activity')&&$this->input->get('activity')!='---'){
			$activity_id =$this->pages_model->getActivityID($this->input->get('activity'));
		};
                $data['rooms'] = $this->pages_model->getAllRooms($roomid, $activity_id);
                $data['sportPlaces'] = $this->pages_model->getAllBuildings($activity_id);
		$data['sportPlacesToChoose'] = $this->pages_model->getAllBuildingRooms($activity_id);
		
		
               // $data['allBookingInfo'] = $this->pages_model->getAllBookings();
               
                foreach ( $data['sportPlacesToChoose'] as $each) {
                        if($each->buildingID!=$this->session->userdata('building')&&$this->input->get('roomId')==$each->id&&($this->session->userdata('roleID')==='2' or $this->session->userdata('roleID')==='3')){
                             
                        //        echo  $this->input->get('roomId');
                        //         var_dump( $each->id);
                        $this->session->set_flashdata('access_deniedToUrl', 'Kahjuks teil puuduvad õigused selle ruumi redigeerimiseks. Ruumi seisu vaatamiseks peate välja logima või avama teise veebilehitsejaga');
                        redirect('');
                        };
                };
	   
	       if($page=='fullcalendar' ||$page=='privacypolicy' ||$page=='guide'){
		$data['regions'] = $this->pages_model->getAllRegions($activity_id);
		$data['activities'] = $this->pages_model->getAllActivities();
                //print_r($data['rooms']);
                $this->load->view('templates/header', $data);
                $this->load->view('pages/' . $page, $data);
                $this->load->view('templates/footer', $data);
	       }else if($this->session->userdata('roleID')==='1' && ($page=='createBuilding'||$page=='createRegion') ){
		$data['regions'] = $this->pages_model->getAllRegions();
                //print_r($data['rooms']);
                $this->load->view('templates/header', $data);
                $this->load->view('pages/' . $page, $data);
                $this->load->view('templates/footer', $data);
	       }

	       else{
		redirect('');
	       }
                
        }


}
