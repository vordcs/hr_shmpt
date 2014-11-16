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
        $this->load->model('m_vehicle');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {

        $data = array(
            'form' => $this->m_cost->set_form_search(),
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

            if ($from != NULL && $to == NULL){
                $data['strtitle'] .='วันที่ '.$this->m_datetime->getDateThaiString($from);
            }
            
            if ($from != NULL && $to != NULL){
                $data['strtitle'] .='วันที่ '.$this->m_datetime->getDateThaiString($from);
                $data['strtitle'] .='ถึง '.$this->m_datetime->getDateThaiString($to);
            }
            
            $data['cost'] = $this->m_cost->search_cost($from, $to);
        } else {
            $data['cost'] = $this->m_cost->get_cost();
        }
        $data['cost_detail'] = $this->m_cost->get_cost_detail();
        $data['cost_types'] = $this->m_cost->get_cost_type();

        $data_debug = array(
//            'cost' => $data['cost'],
//            'cost_detail' => $data['cost_detail'], 
//            'cost_types' => $data['cost_types'],
            'routes' => $data['routes'],
//            'vehicles' => $data['vehicles'],
//            'form' => $data['form'],
        );

//        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('cost/cost', $data);
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
