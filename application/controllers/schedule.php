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

        $data['schedules'] = $this->m_schedule->get_schedule($this->m_datetime->getDateToday());
        $data['vehicles'] = $this->m_schedule->get_vehicle();
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

        $data['schedules'] = $this->m_schedule->get_schedule($this->m_datetime->getDateToday(), $rcode, $vtid);

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
//            'schedules' => $data['schedules'],
            'post' => $this->input->post(),
        );


        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title("ตารางเวลาเดิน $route_name");
        $this->m_template->set_Content('schedule/view_schedule', $data);
        $this->m_template->showTemplate();
    }

    public function add($rcode, $vtid, $rid) {

        $route_detail = $this->m_route->get_route($rcode, $vtid, $rid);
        $vt_name = $route_detail[0]['VTDescription'];
        $source = $route_detail[0]['RSource'];
        $desination = $route_detail[0]['RDestination'];

        $route_name = $vt_name . ' เส้นทาง ' . $route_detail[0]['RCode'] . ' ' . ' ' . $source . ' - ' . $desination;

        $data = array(
//            'form_search' => $this->m_schedule->set_form_search(),
            'page_title' => 'เพิ่มเที่ยวรถ',
            'page_title_small' => ' ' . $route_name,
        );

        $form_data = $rs = array();
        if ($this->m_schedule->validation_form_add() && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_schedule->get_post_form_add();
//            $rs = $this->m_seller->insert_seller($form_data);
//
//            $alert['alert_message'] = "เพิ่มข้อมูลสำเร็จ";
//            $alert['alert_mode'] = "success";
//            $this->session->set_flashdata('alert', $alert);
//
//            redirect('hr/seller/');
        }

        $data['form'] = $this->m_schedule->set_form_add($rcode, $vtid, $rid);

        $data_debug = array(
//            'vehicles_type' => $data['vehicles_type'],
//            'route' => $data['route'],
//            'route_detail' => $data['route_detail'],
//            'stations' => $data['stations'],
//            'form_search' => $data['form_search'],
//            'stations' => $data['stations']
//            'schedules' => $data['schedules'],
//            'post' => $this->input->post(),
            'form_data' => $form_data,
        );



        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title("ตารางเวลาเดิน $route_name");
        $this->m_template->set_Content('schedule/frm_schedule', $data);
        $this->m_template->showTemplate();
    }

    //    ตรวจสอบค่าใน dropdown
    public function check_dropdown($str) {
        if ($str === '0') {
            $this->form_validation->set_message('check_dropdown', 'เลือก %s');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function check_schedule($str) {
        $rid = $this->input->post('RID');
        $time_depart = $str;//$this->input->post('TimeDepart');
        $date = $this->input->post('Date');

        if ($this->m_schedule->IsExitSchedule($date, $rid, $time_depart)) {
            $this->form_validation->set_message('check_schedule', '%s ถูกใช้งานแล้ว');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
