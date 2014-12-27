<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class auto_schedule_vehicle extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_route');
        $this->load->model('m_station');
        $this->load->model('m_vehicle');
        $this->load->model('m_schedule');
        $this->load->model('m_auto_schedule_vehicle');
        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array(
            'page_title' => '',
            'page_title_small' => '',
            'route_detail' => $this->m_auto_schedule_vehicle->get_route_detail(),
        );

        $gen_schedule = $this->m_auto_schedule_vehicle->run_schedule();
//        $rs = $this->m_auto_schedule_vehicle->insert_schedule($gen_schedule);

        $data['schedules'] = $this->m_auto_schedule_vehicle->get_schedules($this->m_datetime->getDateToday());
        $data['vehicles'] = $this->m_vehicle->get_vehicle();       
        $data['route'] = $this->m_route->get_route();
        $data['stations'] = $this->m_station->get_stations();
        
        $set_vehicle_to_schedule = $this->m_auto_schedule_vehicle->set_vehicle_to_schedule();
        
        $data_debug = array(
//            'vehicles' => $data['vehicles'],
//            'vehicles_type' => $data['vehicles_type'],
//            'route' => $data['route'],
//            'route_detail' => $data['route_detail'],
//            'form_search' => $data['form_search'],
//            'stations' => $data['stations'],
//            'schedules' => $data['schedules'],
//            'data_gen' => $gen_schedule,
//            'data_insert' => $rs,
//            'set_vehicle_to_schedule'=>$set_vehicle_to_schedule,
        );



        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title("กำหนดตารางเดินรถ");
        $this->m_template->set_Content('schedule/auto_schedule_vehicle', $data);
        $this->m_template->showTemplate();
    }

}
