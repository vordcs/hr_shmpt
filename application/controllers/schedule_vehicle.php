<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class schedule_vehicle extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_schedule_vehicle');
        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array(
            'page_title' => 'ทดสอบรัน รอบรถ ',
            'page_title_small' => '',
            'routes' => $this->m_schedule_vehicle->get_route(),
            'route_detail' => $this->m_schedule_vehicle->get_route_detail(),
        );
        $rs = $data['gen_schedule'] = $get_vehicle_curent_stations = array();

//        
        $this->db->truncate('t_schedules_day');
        $this->db->truncate('vehicles_has_schedules');
        $this->db->truncate('vehicles_current_stations');
//        
//      สร้างตารางเวลาเดินรถเดินรถ
        $data['gen_schedule'] = $this->m_schedule_vehicle->run_schedule();
        $rs = $this->m_schedule_vehicle->insert_schedule($data['gen_schedule']);
//        
//        
//        กำหนดจุดเริ่มต้นให้กับรถแต่ละคัน
        $vehicles_initicial_station = array();
        $vehicles_initicial_station = $this->m_schedule_vehicle->set_vehicles_initicial_station();
        $set_time_initicial_vehicles = array();
        $set_time_initicial_vehicles = $this->m_schedule_vehicle->set_time_initicial_vehicles();

        $run_vehicles_to_schedule = array();
        $run_vehicles_to_schedule = $this->m_schedule_vehicle->run_vehicles_to_schedule();

        $data['schedules'] = $this->m_schedule_vehicle->get_schedule($this->m_datetime->getDateToday());
        $data['vehicles'] = $this->m_schedule_vehicle->get_vehicle();
        $data['stations'] = $this->m_schedule_vehicle->get_stations();

        $data_debug = array(
//            'vehicles' => $data['vehicles'],
//            'route_detail' => $data['route_detail'],
//            'stations' => $data['stations'],
//            'schedules' => $data['schedules'],
//            'gen_schedule' => $data['gen_schedule'],
//            'data_insert' => $rs,
//            'get_vehicle_curent_stations' => $get_vehicle_curent_stations,
//            'vehicles_initicial_station' => $vehicles_initicial_station,
//            'set_time_initicial_vehicles'=>$set_time_initicial_vehicles,
//            'run_vehicles_to_schedule' => $run_vehicles_to_schedule,
        );

        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title("ระบบจัดตารางเวลาเดินรถอัตติโนมัติ");
        $this->m_template->set_Content('schedule/schedule_vehicle', $data);
        $this->m_template->showTemplate();
    }

}
