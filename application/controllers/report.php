<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_route');
        $this->load->model('m_station');
        $this->load->model('m_fares');
        $this->load->model('m_report');
        $this->load->model('m_vehicle');
        $this->load->model('m_cost');
        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $end_date = $this->m_datetime->getDateToday();
        $temp_date = str_replace('-', '/', $end_date);
        $begin_date = date('Y-m-d', strtotime($temp_date . "-7 days"));
        $begin_date = $this->m_datetime->changeENToThai($begin_date);
        $end_date = $this->m_datetime->changeENToThai($end_date);
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $begin_date = $this->input->post('begin_date');
            $end_date = $this->input->post('end_date');
        }

        $data = array(
            'form_open' => form_open('report'),
            'form_close' => form_close(),
            'begin_date' => $begin_date,
            'end_date' => $end_date,
            'all_route' => $this->m_report->all_route(),
        );




        $data_debug = array(
//            'vehicles'=>$data['vehicles'],
//            'stations_sale_ticket'=>$data['stations_sale_ticket'],
//            ''=>$data[''],
        );

        $this->m_template->set_Debug($data_debug);

        $this->m_template->set_Title('รายงาน');
        $this->m_template->set_Permission('REI');
        $this->m_template->set_Content('report/report', $data);
        $this->m_template->showTemplate();
    }

    public function view($rcode, $vtid, $begin_date, $end_date) {
        if ($rcode == null || $vtid == NULL || $begin_date == NULL || $end_date == NULL) {
            redirect("report/");
        }
        $begin_date = $this->m_datetime->changeThaiToEn($begin_date);
        $end_date = $this->m_datetime->changeThaiToEn($end_date);

        $data = array(
            'begin_date' => $begin_date,
            'end_date' => $end_date,
            'get_route' => $this->m_report->get_route($rcode, $vtid)[0],
            'report_vehicle' => $this->m_report->report_vehicle($rcode, $vtid, $begin_date, $end_date),
            'report_sale' => $this->m_report->report_sale($rcode, $vtid, $begin_date, $end_date),
            'report_station' => $this->m_report->report_station($rcode, $vtid, $begin_date, $end_date),
            'report_vehicle_cost' => $this->m_report->report_vehicle_cost($rcode, $vtid, $begin_date, $end_date),
        );


        $data_debug = array(
//            'begin_date' => $begin_date,
//            'end_date' => $end_date,
//            'get_route' => $this->m_report->get_route($rcode, $vtid)[0],
//            'report_vehicle' => $this->m_report->report_vehicle($rcode, $vtid, $begin_date, $end_date),
//            'report_sale' => $this->m_report->report_sale($rcode, $vtid, $begin_date, $end_date),
//            'get_vehicle' => $this->m_report->get_vehicle($rcode, $vtid),
//            'report_station' => $this->m_report->report_station($rcode, $vtid, $begin_date, $end_date),
            'report_vehicle_cost' => $this->m_report->report_vehicle_cost($rcode, $vtid, $begin_date, $end_date),
//            'test' => $this->m_report->test($rcode, $vtid, $begin_date, $end_date),
        );

        $this->m_template->set_Debug($data_debug);

        $this->m_template->set_Title('รายงาน');
        $this->m_template->set_Permission('REV');
        $this->m_template->set_Content('report/view_report', $data);
        $this->m_template->showTemplate();
    }

}
