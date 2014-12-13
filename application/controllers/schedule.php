<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class schedule extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_route');
        $this->load->model('m_vehicle');
        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array(
//            'form_search' => $this->m_route->set_form_search_route(),
            'route_detail' => $this->m_route->get_route_detail(),
        );

        $data['vehicles_type'] = $this->m_vehicle->get_vehicle_types();
        $data['route'] = $this->m_route->get_route();

        $data_debug = array(
            'vehicles_type' => $data['vehicles_type'],
            'route' => $data['route'],
//            'route_detail' => $data['route_detail'],
//            'form_route' => $data['form_route'],
//            'stations' => $data['stations']
        );


        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('schedule/schedule', $data);
        $this->m_template->showTemplate();
    }

    public function view() {
        $data = array();
        $this->m_template->set_Content('schedule/view_schedule', $data);
        $this->m_template->showTemplate();
    }

}
