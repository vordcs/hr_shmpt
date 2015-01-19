<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('m_candidate');
        $this->load->model('hr/m_employee');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array();
        
//        $this->db->truncate('employees');
//        $this->db->truncate('employee_education');
//        $this->db->truncate('employee_emergency_contact');
//        $this->db->truncate('employee_experience');
//        $this->db->truncate('employee_family');
//        $this->db->truncate('employee_family_detail');
//        $this->db->truncate('employee_parent');
//        $this->db->truncate('username');        

        $data['c_list'] = $this->m_employee->check_candidate_by_status();
        $data['e_list'] = $this->m_employee->check_last_emp();

//        $this->m_template->set_Debug($data);
        $this->m_template->set_Permission('HHI');
        $this->m_template->set_Content('hr/home', $data);
        $this->m_template->showTemplate();
    }

    public function detail($CID) {
        if ($CID == NUll || !$this->m_candidate->check_candidate($CID))
            redirect('hr/home');

        $data = array();
        $c_data = $this->m_candidate->check_candidate_detail($CID);

        $data['c_data'] = $c_data;
        $data['form_input'] = $this->m_candidate->set_form($c_data, TRUE);

        $data['mode'] = 'employee_accept';
        $data['form_open'] = form_open('hr/home/detail/' . $CID, 'id="frm_main" class="form-horizontal"');
        $data['form_close'] = form_close();

//        $this->m_template->set_Debug($c_data);
        $this->m_template->set_Permission('HHD');
        $this->m_template->set_Content('candidate/frm_candidate', $data);
        $this->m_template->showTemplate();
    }

    /*
     * ฟังก์ชั่น อนุมัติ เป็นหน้าที่จะส่งค่า CID มาเพื่อทำการอนุมัติ
     */

    public function accept($CID = NULL) {
        if ($CID == NULL || $CID == " " || !$this->m_candidate->check_candidate($CID))
            redirect('hr/home');

        $data = array();
        $temp = $this->m_candidate->check_candidate_detail($CID);
        $data['c_data'] = $this->m_employee->prepare_data_from_candidate($temp);
        $data['result'] = $this->m_employee->insert_employee_from_candidate($data['c_data'], $temp['CID']);

        if ($data['result'] != FALSE) {
            //Alert success and redirect to candidate
            $alert['alert_message'] = "อนุมัติข้อมูลผู้สมัครงานแล้ว";
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);
            redirect('hr/home');
        }


        $this->m_template->set_Debug($data);
        $this->m_template->set_Permission('HHA');
        $this->m_template->showTemplate();
    }

}
