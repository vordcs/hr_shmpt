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
            'cost' => $this->m_cost->get_cost(),
            'cost_detail' => $this->m_cost->get_cost_detail(),
            'cost_types' => $this->m_cost->get_cost_type(),
            'vehicle_types' => $this->m_vehicle->get_vehicle_types(),
            'routes' => $this->m_route->get_route(),
            'vehicles' => $this->m_vehicle->get_vehicle(),
        );

        $data_debug = array(
            'cost' => $data['cost'],
//            'cost_detail' => $data['cost_detail'], 
//            'cost_types' => $data['cost_types'],
//            'vehicles' => $data['vehicles'],
        );

//        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('cost/cost', $data);
        $this->m_template->showTemplate();
    }

    public function add($ctid) {
        $page_title = 'เพิ่ม ' . $this->m_cost->get_cost_type($ctid)[0]['CostTypeName'];

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
            'form' => $this->m_cost->set_form_add($ctid),
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
