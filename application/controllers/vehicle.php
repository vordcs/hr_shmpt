<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class vehicle extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_vehicle');

//Initial language
        $this->m_template->set_Language(array('plan'));
    }

    private $vtid = NULL;

    function set_vtid($id) {
        $this->vtid = $id;
    }

    private $rcode = NULL;

    function set_rcode($id) {
        $this->rcode = $id;
    }

    public function index() {
        $data = array(
            'form' => $this->m_vehicle->set_form_search(),
            'page_title' => "การจัดการรถโดยสาร",
            'page_title_small' => '',
        );

        $data['strtitle'] = '';
        $vtid = $this->input->post('VTID');
        $rcode = $this->input->post('RCode');
        $vcode = $this->input->post('VCode');
        $number_plate = $this->input->post('NumberPlate');

        if ($vtid != '0' | $rcode != '0' | $vcode != NULL || $number_plate != NULL) {
            $data['strtitle'] = '<strong>ผลการค้นหา : </strong> ';
        }

        if ($vtid != '0') {
            $data['vehicle_types'] = $this->m_vehicle->get_vehicle_types($vtid);
            $data['strtitle'] .= $data['vehicle_types'][0]['VTDescription'] . '  ';
        } else {
            $data['vehicle_types'] = $this->m_vehicle->get_vehicle_types();
        }

        if ($rcode != '0') {
            $data['route'] = $this->m_vehicle->get_route($rcode, NULL);
            $data['strtitle'] .= 'เส้นทาง ' . $data['route'][0]['RCode'] . ' ' . $data['route'][0]['RSource'] . ' - ' . $data['route'][0]['RDestination'] . '  ';
        } else {
            $data['route'] = $this->m_vehicle->get_route();
        }

        if ($vcode != NULL || $number_plate != NULL) {
            $data['vehicles'] = $this->m_vehicle->search_vehicle();
            if ($vcode != NULL)
                $data['strtitle'] .= 'เบอร์ ' . $vcode . '  ';
            if ($number_plate != NULL)
                $data['strtitle'] .= 'ทะเบียน ' . $number_plate . '  ';
        } else {
            $data['vehicles'] = $this->m_vehicle->get_vehicle();
        }

        $data_debug = array(
//            'RCode' => $this->input->post('RCode'),
//            'strtitle' => $data['strtitle'],
//            'vehicle_types' => $data['vehicle_types'],
//            'route' => $data['route'],
//            'vehicles' => $data['vehicles'],
        );

        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title('จัดการรถโดยสาร');
        $this->m_template->set_Content('vehicle/vehicles', $data);
        $this->m_template->showTemplate();
    }

    public function add($rcode, $vtid) {
        $this->set_rcode($rcode);
        $this->set_vtid($vtid);
        $route = $this->m_vehicle->get_route($rcode, $vtid);

        if (count($route) <= 0)
            redirect('vehicle/');

        $route_name = $route[0]['RCode'] . ' ' . $route[0]['RSource'] . ' - ' . $route[0]['RDestination'];
        $type_name = $route[0]['VTDescription'];


        if ($this->m_vehicle->validation_form_add() && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_vehicle->get_post_form_add($rcode, $vtid);
//            $this->m_template->set_Debug($form_data);
            $this->m_vehicle->insert_vehicle($form_data);
            redirect('vehicle/');
        }

//        $this->m_template->set_Debug($r);
        $data = array(
            'VID' => NULL,
            'page_title' => 'เพิ่มข้อมูล ' . $type_name . ' เส้นทาง ' . $route_name,
            'page_title_small' => '',
            'RCode' => $route[0]['RCode'],
            'form' => $this->m_vehicle->set_form_add($rcode, $vtid),
        );
//Load form add
//        $this->m_template->set_Debug($data);
        $this->m_template->set_Title('เพิ่มรถโดยสาร');
        $this->m_template->set_Content('vehicle/frm_vehicle', $data);
        $this->m_template->showTemplate();
    }

    public function edit($rcode, $vtid, $vid) {

        if ($this->m_vehicle->validation_form_edit() && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_vehicle->get_post_form_edit($vid);
//            $this->m_template->set_Debug($form_data);
//Update data
            $this->m_vehicle->update_vehicle($form_data);
            redirect('vehicle/');
        }

        $route = $this->m_vehicle->get_route($rcode, $vtid);
        $route_name = $route[0]['RCode'] . ' ' . $route[0]['RSource'] . ' - ' . $route[0]['RDestination'];
        $type_name = $route[0]['VTDescription'];

//      get detail and sent to load form
        $detail = $this->m_vehicle->get_vehicle($vid);
        if ($detail[0] != NULL) {
            $data = array(
                'VID' => $vid,
                'page_title' => 'แก้ไขข้อมูล ' . $type_name . ' เส้นทาง ' . $route_name,
                'page_title_small' => '',
                'RCode' => $route[0]['RCode'],
                'form' => $this->m_vehicle->set_form_edit($rcode, $vtid, $detail[0]),
            );
        } else {
            redirect('vehecle');
        }

//        $this->m_template->set_Debug($detail);
        $this->m_template->set_Title('แก้ไขข้อมูลรถโดยสาร');
        $this->m_template->set_Content('vehicle/frm_vehicle', $data);
        $this->m_template->showTemplate();
    }

//ตรวจสอบหมายเลขทะเบียนรถ
    public function check_numberplate($str) {
        $con = array(
            'NumberPlate' => $str,
            'VTID' => $this->vtid,
        );
        $query = $this->db->get_where('vehicles', $con);
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('check_numberplate', 'เลขทะเบียน ' . $str . ' ถูกใช้งานเเล้ว');
            return FALSE;
        } else {
            return TRUE;
        }
    }

//ตรวจสอบเบอร์รถ
    public function check_vcode($str) {
        $con = array(
            'VCode' => $str,
            'VTID' => $this->vtid,
        );
        $query = $this->db->get_where('vehicles', $con);
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('check_vcode', ' %s ถูกใช้งานเเล้ว');
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
