<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class schedule_vehicle extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_schedule_vehicle');
        $this->load->helper("file");
        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    private function index() {
        $data = array(
            'page_title' => 'ทดสอบรัน รอบรถ ',
            'page_title_small' => '',
            'data' => $this->m_schedule_vehicle->set_form_view(),
        );
        $data['gen_schedule'] = $get_vehicle_curent_stations = array();

        /*
         * ลบ file barcode,QRcode
         * อิงจากที่อยู่จริง เพราะแยก Folder กัน
         * ticket/assets/barcode
         */

        delete_files('../ticket/assets/barcode/', TRUE);
        delete_files('../ticket/assets/qrcode/', TRUE);

//        $folder_barcode = FCPATH . "assets/barcode";
//        delete_files($folder_barcode,true);
//
//        $folder_qrcode = FCPATH . "assets/qrcode";
//        delete_files($folder_qrcode,true);   
//        
//        $this->db->truncate('t_schedules_day');
//        $this->db->truncate('vehicles_has_schedules');
//        $this->db->truncate('vehicles_current_stations');
//        
//      สร้างตารางเวลาเดินรถเดินรถ
        $data['gen_schedule'] = $this->m_schedule_vehicle->run_schedule();
        $rs = $this->m_schedule_vehicle->insert_schedule($data['gen_schedule']);


//        กำหนดจุดเริ่มต้นให้กับรถแต่ละคัน
        $vehicles_initicial_station = array();
//        $vehicles_initicial_station = $this->m_schedule_vehicle->set_vehicles_initicial_station();

        /*
         * กำหนดเวลา
         */
        $set_time_initicial_vehicles = array();
        $set_time_initicial_vehicles = $this->m_schedule_vehicle->set_time_initicial_vehicles();

        $run_vehicles_to_schedule = array();
        $run_vehicles_to_schedule = $this->m_schedule_vehicle->run_vehicle_to_schedule();

        $data['schedules'] = $this->m_schedule_vehicle->get_schedule($this->m_datetime->getDateToday());
        $data['vehicles'] = $this->m_schedule_vehicle->get_vehicles();
        $data['stations'] = $this->m_schedule_vehicle->get_stations();

        $data_debug = array(
//            'vehicles' => $data['vehicles'],
//            'route_detail' => $data['route_detail'],
//            'stations' => $data['stations'],
//            'schedules' => $data['schedules'],
//            'gen_schedule' => $data['gen_schedule'],
            'Check insert_schedule' => $rs,
//            'get_vehicle_curent_stations' => $get_vehicle_curent_stations,
//            'vehicles_initicial_station' => $vehicles_initicial_station,
//            'set_time_initicial_vehicles'=>$set_time_initicial_vehicles,
//            'run_vehicles_to_schedule' => $run_vehicles_to_schedule,
//            'data_scheadules' => $data['data']['data_scheadules'],
//            'schedules_manual'=>  $this->m_schedule_vehicle->get_schedule_master(7),
        );

        $data['routes'] = $this->m_schedule_vehicle->get_routes();
        $data['route_detail'] = $this->m_schedule_vehicle->get_routes_detail();

//        $this->m_template->set_Debug($data_debug);
//        $this->m_template->set_Title("ระบบจัดตารางเวลาเดินรถอัตติโนมัติ");
//        $this->m_template->set_Permission('SVI');
//        $this->m_template->set_Content('schedule/schedule_vehicle', $data);
//        $this->m_template->showTemplate();
    }

    public function cron() {
        if ($this->input->is_cli_request()) {
            $this->index();
        } else {
            show_404();
        }
    }

}
