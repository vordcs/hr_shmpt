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
        $data = array(
            'page_title' => 'รายงาน',
            'page_title_small' => '',
            'previous_page' => '',
            'next_page' => '',
            'vehicles' => $this->m_report->get_vehicle(),
            'vehicle_types' => $this->m_route->get_vehicle_types(),
            'routes' => $this->m_route->get_route(),
            'routes_detail' => $this->m_route->get_route_detail(),
            'stations_sale_ticket' => $this->m_station->get_stations_sale_ticket(),
            
            //New
            'form_open'=>  form_open('report'),
            'form_close'=> form_close(),
            'begin_date'=> $this->m_datetime->setTHDateToDB('2558-4-22'),
            'end_date' => $this->m_datetime->setTHDateToDB('2558-4-22'),
        );



        $data_debug = array(
//            'vehicles'=>$data['vehicles'],
//            'stations_sale_ticket'=>$data['stations_sale_ticket'],
//            ''=>$data[''],
//            ''=>$data[''],
        );

        $this->m_template->set_Debug($data_debug);

        $this->m_template->set_Title('รายงาน');
        $this->m_template->set_Permission('REI');
        $this->m_template->set_Content('report/report', $data);
        $this->m_template->showTemplate();
    }

    public function view($rcode, $vtid) {
        if ($rcode == null || $vtid == NULL) {
            redirect("report/");
        }
        $route_detail = $this->m_route->get_route_detail($rcode, $vtid);
        $source = $route_detail[0]['RSource'];
        $desination = $route_detail[0]['RDestination'];
        $vt_name = $route_detail[0]['VTDescription'];
        $route_name = "$vt_name " . $route_detail[0]['RCode'] . ' ' . ' ' . $source . ' - ' . $desination;
        $date = $this->m_datetime->DateThaiToDay();

        $mounth = date('m');
        $year = date('Y');

        $str_mounth = $this->m_datetime->getMonthThai((int) $mounth);
        $str_year = (int) $year + 543;
        $data = array(
            'page_title' => "$str_mounth  $str_year",
            'page_title_small' => "$route_name",
            'previous_page' => '',
            'next_page' => '',
            'vehicles' => $this->m_report->get_vehicle($rcode, $vtid),
            'vehicle_types' => $this->m_route->get_vehicle_types(),
            'routes' => $route_detail,
            'stations_sale_ticket' => $this->m_station->get_stations_sale_ticket($rcode, $vtid),
            'cost' => $this->m_cost->get_cost(),
        );



        $data['cost_detail'] = $this->m_cost->get_cost_detail();
        $data['cost_types'] = $this->m_cost->get_cost_type();



        $data['number_day'] = cal_days_in_month(CAL_GREGORIAN, $mounth, $year);
        $data['mounth'] = $mounth;
        $data['year'] = $year;
        $data['form_search_mounth'] = $this->m_report->set_form_search_mounth($rcode, $vtid, (int) $mounth);

        $data_debug = array(
//            'vehicles'=>$data['vehicles'],
//            'stations_sale_ticket'=>$data['stations_sale_ticket'],
//            'routes'=>$data['routes'],`   
//            'mounth' => $data['mounth'],
//            'number_day' => $data['number_day'],
//            'form_search_mounth'=>$data['form_search_mounth'],
//            ''=>$data[''],
//            ''=>$data[''],
//            ''=>$data[''],
//            ''=>$data[''],
        );

        $this->m_template->set_Debug($data_debug);

        $this->m_template->set_Title('รายงาน');
        $this->m_template->set_Permission('REV');
        $this->m_template->set_Content('report/view_report', $data);
        $this->m_template->showReportTemplate();
    }

    public function test() {
        $this->load->view('report/view_report_test');
    }

}
