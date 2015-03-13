<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class cost extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('m_cost');
        $this->load->model('m_route');
        $this->load->model('m_station');
        $this->load->model('m_vehicle');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $date = $this->m_datetime->getDateToday();
        $data = array(
            'form' => $this->m_cost->set_form_search(),
            'route_details' => $this->m_route->get_route_detail(),
            'stations' => $this->m_station->get_stations(),
        );

        $data['strtitle'] = '';

        $vtid = $this->input->post('VTID');
        $rcode = $this->input->post('RCode');
        $vcode = $this->input->post('VCode');
        $from = $this->input->post('DateForm');
        $to = $this->input->post('DateTo');

        if ($vtid != '0' | $rcode != 0 | $vcode != NULL | $from != NULL | $to != NULL) {
            $data['strtitle'] .= 'ผลการค้นหา : ';
        }

        if ($vtid != '0') {
            $data['vehicle_types'] = $this->m_vehicle->get_vehicle_types($vtid);
            $data['strtitle'] .= $data['vehicle_types'][0]['VTDescription'] . '  ';
        } else {
            $data['vehicle_types'] = $this->m_vehicle->get_vehicle_types();
        }

        if ($rcode != '0') {
            $data['routes'] = $this->m_route->get_route($rcode, NULL);
            $data['strtitle'] .= 'เส้นทาง ' . $data['routes'][0]['RCode'] . ' ' . $data['routes'][0]['RSource'] . ' - ' . $data['routes'][0]['RDestination'] . '  ';
        } else {
            $data['routes'] = $this->m_route->get_route();
        }


        if ($vcode != NULL) {
            $data['vehicles'] = $this->m_cost->get_vehicle($vcode);
            $data['strtitle'] .= 'เบอร์ ' . $vcode . '  ';
        } else {
            $data['vehicles'] = $this->m_cost->get_vehicle();
        }

        if ($from != NULL || $to != NULL) {

            if ($from != NULL && $to == NULL) {
                $data['strtitle'] .='วันที่ ' . $this->m_datetime->getDateThaiString($from);
            }
            if ($from != NULL && $to != NULL) {
                $data['strtitle'] .='วันที่ ' . $this->m_datetime->getDateThaiString($from);
                $data['strtitle'] .='ถึง ' . $this->m_datetime->getDateThaiString($to);
            }
            $data['cost'] = $this->m_cost->search_cost($from, $to);
        } else {
            $data['cost'] = $this->m_cost->get_cost();
        }

        $data['schedules'] = $this->m_cost->get_schedule($date);
        $data['cost_detail'] = $this->m_cost->get_cost_detail();
        $data['cost_types'] = $this->m_cost->get_cost_type();
        $data['all_cost'] = $this->m_cost->check_cost();

        $data_debug = array(
//            'cost' => $data['cost'],
//            'cost_detail' => $data['cost_detail'], 
//            'cost_types' => $data['cost_types'],
//            'routes' => $data['routes'],
//            'route_details' => $data["route_details"],
//            'stations' => $data['stations'],
//            'vehicles' => $data['vehicles'],
//            'form' => $data['form'],
//            'schedules' => $data['schedules'],
            'date' => $this->m_datetime->getDateToday(),
//            'detail' => $this->m_route->get_route('264', NULL),
//            'all_cost' => $this->m_cost->check_cost(),
            'tbody' => $this->m_cost->generate_tbody('264', '1', $this->m_datetime->getDateToday()),
        );

        $this->m_template->set_Title('ค่าใช้จ่าย');
        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Permission('COI');
        $this->m_template->set_Content('cost/cost', $data);
        $this->m_template->showTemplate();
    }

    public function view($rcode, $vtid) {
        $route_detail = $this->m_route->get_route_detail($rcode, $vtid);
        $source = $route_detail[0]['RSource'];
        $desination = $route_detail[0]['RDestination'];
        $route_name = 'เส้นทาง ' . $route_detail[0]['RCode'] . ' ' . ' ' . $source . ' - ' . $desination;
        $date_th = $this->m_datetime->DateThaiToDay();
        $date = $this->m_datetime->getDateToday();
        $data = array(
            'page_title' => 'ค่าใช้จ่าย ' . $route_name,
            'page_title_small' => 'ประจำวันที่ ' . $date_th,
            'form' => $this->m_cost->set_form_search($rcode, $vtid),
            'route_details' => $route_detail,
            'stations' => $this->m_station->get_stations($rcode, $vtid),
            'cost' => $this->m_cost->get_cost(),
            'rcode' => $rcode,
            'vtid' => $vtid,
        );

        $data['strtitle'] = '';

        if ($this->m_cost->varlidation_form_search() && $this->form_validation->run() == TRUE) {
            $from = $this->input->post('DateForm');
            $to = $this->input->post('DateTo');
            if ($from != NULL | $to != NULL) {
                $data['strtitle'] .= 'ผลการค้นหา : ';
            }
            if ($from != NULL && $to == NULL) {
                $data['strtitle'] .='วันที่ ' . $this->m_datetime->getDateThaiString($from);
                $data['cost'] = $this->m_cost->search_cost($from);
            }
            if ($from != NULL && $to != NULL) {
                $data['strtitle'] .='จากวันที่ ' . $this->m_datetime->getDateThaiString($from);
                $data['strtitle'] .=' ถึง ' . $this->m_datetime->getDateThaiString($to);
                $data['cost'] = $this->m_cost->search_cost($from, $to);
            }
        }

        $data['vehicles'] = $this->m_vehicle->get_vehicle();
        $data['routes'] = $this->m_route->get_route($rcode, $vtid);
        $data['schedules'] = $this->m_cost->get_schedule($date, $rcode, $vtid);

        $data['cost_detail'] = $this->m_cost->get_cost_detail();
        $data['cost_types'] = $this->m_cost->get_cost_type();

        $data['date'] = $date;

        $data_debug = array(
//            'cost' => $data['cost'],
//            'cost_detail' => $data['cost_detail'], 
//            'cost_types' => $data['cost_types'],
//            'routes' => $data['routes'],
//            'route_details' => $data["route_details"],F
//            'stations' => $data['stations'],
//            'vehicles' => $data['vehicles'],
//            'form' => $data['form'],
            'schedules' => $data['schedules'],
        );

        $this->m_template->set_Title("ค่าใช้จ่าย $route_name");
        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Permission('COV');
        $this->m_template->set_Content('cost/view_cost', $data);
        $this->m_template->showTemplate();
    }

    public function add($ctid, $vtid = NULL) {
        $page_title = 'เพิ่ม ' . $this->m_cost->get_cost_type($ctid)[0]['CostTypeName'] . ' ';
        $page_title .= $this->m_vehicle->get_vehicle_types($vtid)[0]['VTDescription'];

        if ($this->m_cost->validation_form_add() && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_cost->get_post_form_add($ctid);
//            $this->m_template->set_Debug($form_data);
            $rs = $this->m_cost->insert_cost($form_data);
//            $this->m_template->set_Debug($rs);
            $this->session->set_flashdata('tab_active', $rs[0]['VTID'] . '_' . $rs[0]['RCode']);
            redirect('cost/');
        }
        $data = array(
            'page_title' => $page_title,
            'form' => $this->m_cost->set_form_add($ctid, $vtid),
        );
        $data_debug = array(
            'form' => $data['form'],
//            'vehicles' => $data['vehicles'],
        );

//        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Permission('COA');
        $this->m_template->set_Content('cost/frm_cost', $data);
        $this->m_template->showTemplate();
    }

//ตรวจสอบเบอร์รถ
    public function check_vcode($str) {
        $con = array(
            'VCode' => $str,
            'VTID' => $this->input->post('VTID'),
        );
        $query = $this->db->get_where('vehicles', $con);
        if ($query->num_rows() <= 0) {
            $this->form_validation->set_message('check_vcode', 'ไม่พบข้อมูล %s');
            return FALSE;
        } else {
            return TRUE;
        }
    }

//    check dropdown
    public function check_dropdown($str) {
//        $this->input->post('PolicyType')
        if ($str === '0') {
            $this->form_validation->set_message('check_dropdown', 'เลือก %s');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
