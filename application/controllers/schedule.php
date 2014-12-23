<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class schedule extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_route');
        $this->load->model('m_station');
        $this->load->model('m_vehicle');
        $this->load->model('m_schedule');
        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {

        $data = array(
            'form_search' => $this->m_schedule->set_form_search(),
            'page_title' => '',
            'page_title_small' => '',
            'route_detail' => $this->m_route->get_route_detail(),
        );

        $data['gen_schedule'] = $this->m_schedule->run_schedule();      
        $rs = $this->m_schedule->insert_schedule($data['gen_schedule']);

        $data['schedules'] = $this->m_schedule->get_schedule($this->m_datetime->getDateToday());
        $data['vehicles'] = $this->m_vehicle->get_vehicle();
        $data['vehicles_type'] = $this->m_vehicle->get_vehicle_types();
        $data['route'] = $this->m_route->get_route();
        $data['stations'] = $this->m_station->get_stations();
        $data['schedule_master'] = $this->m_route->get_schedule_manual();

        $data_debug = array(
//            'vehicles' => $data['vehicles'],
//            'vehicles_type' => $data['vehicles_type'],
//            'route' => $data['route'],
//            'route_detail' => $data['route_detail'],
//            'form_search' => $data['form_search'],
//            'stations' => $data['stations'],
//            'schedules' => $data['schedules'],
//            'gen_schedule' => $data['gen_schedule'],
            'data_insert' => $rs,
        );



        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title("ตารางเวลาเดินรถ");
        $this->m_template->set_Content('schedule/schedule', $data);
        $this->m_template->showTemplate();
    }

    public function view($rcode, $vtid) {

        $route_detail = $this->m_route->get_route($rcode, $vtid);
        $vt_name = $route_detail[0]['VTDescription'];
        $source = $route_detail[0]['RSource'];
        $desination = $route_detail[0]['RDestination'];

        $route_name = $vt_name . ' เส้นทาง ' . $route_detail[0]['RCode'] . ' ' . ' ' . $source . ' - ' . $desination;

        $data = array(
//            'form_search' => $this->m_schedule->set_form_search(),
            'page_title' => 'ตารางเวลาเดิน ' . $route_name,
            'page_title_small' => '',
            'route_detail' => $this->m_route->get_route_detail($rcode, $vtid),
        );

        $data['rs_gen_schedule'] = $this->m_schedule->run_schedule();

        $data['data_insert'] = $this->m_schedule->insert_schedule($this->m_schedule->run_schedule());


        $data['schedule'] = $this->m_schedule->get_schedule($this->m_datetime->getDateToday());

        $data['vehicles_type'] = $this->m_vehicle->get_vehicle_types();
        $data['route'] = $this->m_route->get_route();
        $data['stations'] = $this->m_station->get_stations($rcode, $vtid);
        $data['schedule_master'] = $this->m_route->get_schedule_manual();

        $data_debug = array(
//            'vehicles_type' => $data['vehicles_type'],
//            'route' => $data['route'],
//            'route_detail' => $data['route_detail'],
//            'stations' => $data['stations'],
//            'form_search' => $data['form_search'],
//            'stations' => $data['stations']
//            'schedule' => $data['schedule'],
//            'rs_gen_schedule' => $data['rs_gen_schedule'],
//            'data_insert' => $data['data_insert'],
        );


        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title("ตารางเวลาเดิน $route_name");
        $this->m_template->set_Content('schedule/view_schedule', $data);
        $this->m_template->showTemplate();
    }

}
