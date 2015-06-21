<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class employee extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('hr/m_employee');
        $this->load->model('m_candidate');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array();

        if ($this->m_employee->set_validation_search() && $this->form_validation->run()) {
            $input_data = $this->m_employee->get_post_search();
            $list = $this->m_employee->search($input_data);
            $input_data['list'] = $list;
            $data['list'] = $list;
        } else {
            if (($this->input->server('REQUEST_METHOD') === 'POST') == FALSE) {
                $data['list_all'] = $this->m_employee->search_all();
            }
        }

        $data['form_open'] = form_open('hr/employee');
        $data['form_open_join'] = form_open('hr/employee/join');
        $data['form_input'] = $this->m_employee->set_form_search();
        $data['form_close'] = form_close();

        //$this->m_template->set_Debug($data);
        $this->m_template->set_Permission('HEI');
        $this->m_template->set_Content('hr/employees', $data);
        $this->m_template->showTemplate();
    }

    public function detail($EID) {
        if ($EID == NUll || !$this->m_employee->check_employee($EID))
            redirect('hr/employee');

        $data = array();
        $e_data = $this->m_employee->check_employee_detail($EID);

        $data['e_data'] = $e_data;
        $data['form_input'] = $this->m_candidate->set_form($e_data, TRUE);

        $data['mode'] = 'employee_detail';
        $data['form_open'] = form_open('hr/employee/detail/' . $EID, 'id="frm_main" class="form-horizontal"');
        $data['form_close'] = form_close();

//        $this->m_template->set_Debug($data);
        $this->m_template->set_Permission('HED');
        $this->m_template->set_Content('candidate/frm_candidate', $data);
        $this->m_template->showTemplate();
    }

    public function edit($EID) {
        if ($EID == NUll || !$this->m_employee->check_employee($EID))
            redirect('hr/employee');

        $data = array();
        $e_data = $this->m_employee->check_employee_detail($EID);

        if ($this->m_candidate->set_validation() && $this->form_validation->run()) {
            $input_data['EID'] = $EID;
            $input_data += $this->m_candidate->get_post();
            $input_data['emergency']['ECID'] = $e_data['ECID'];

            $input_data['debug'] = $this->m_employee->update_all_data($input_data);
            $data['form_input'] = $this->m_candidate->set_form();
            $this->m_template->set_Debug($input_data);

            //Alert success and redirect to candidate
            $alert['alert_message'] = "แก้ไขข้อมูลพนักงานแล้ว";
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);
            redirect('hr/employee');
        } else {
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                //Alert success and redirect to candidate
                $alert['alert_message'] = "กรุณากรอกข้อมูลที่สำคัญให้ครบถ้วน";
                $alert['alert_mode'] = "danger";
                $this->m_template->set_RealAlert($alert);
            }

            $data['e_data'] = $e_data;
            $data['form_input'] = $this->m_candidate->set_form($e_data, FALSE);
        }

        $data['mode'] = 'employee_edit';
        $data['form_open'] = form_open('hr/employee/edit/' . $EID, 'id="frm_main" class="form-horizontal"');
        $data['form_close'] = form_close();

//        $this->m_template->set_Debug($data);
        $this->m_template->set_Permission('HEE');
        $this->m_template->set_Content('candidate/frm_candidate', $data);
        $this->m_template->showTemplate();
    }

    /*
     * ฟังก์ชั่น เลิกจ้าง เป็นหน้าที่จะส่งค่า EID มาเพื่อทำการเลิกจ้าง
     */

    public function layof($EID = NULL) {
        if ($EID == NULL || $EID == " " || !$this->m_employee->check_employee($EID))
            redirect('hr/employee');

        $data = array();
        $data['result'] = $this->m_employee->update_emplyee_layof($EID);

        if ($data['result'] != FALSE) {
            //Alert success and redirect to candidate
            $alert['alert_message'] = "เลิกจ้างพนักงานแล้ว";
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);
            redirect('hr/home');
        }

        $this->m_template->set_Debug($data);
        $this->m_template->set_Permission('HEL');
        $this->m_template->showTemplate();
    }

    public function log($EID = NULL) {
        if ($EID == NULL || $EID == " " || !$this->m_employee->check_employee($EID))
            redirect('hr/employee');

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
            'form_open' => form_open('hr/employee/log/' . $EID, array('class' => 'inline')),
            'form_close' => form_close(),
            'begin_date' => $begin_date,
            'end_date' => $end_date,
            'employe' => $this->m_employee->check_employee_detail($EID)
        );
        $new_begin = $this->m_datetime->changeThaiToEn($data['begin_date']) . ' 00:00:00';
        $new_end = $this->m_datetime->changeThaiToEn($data['end_date']) . ' 00:00:00';
        $data['log_clocking'] = $this->m_employee->check_log_clocking($EID, $new_begin, $new_end);

        $data_debug = array(
//            '1' => $new_begin,
//            '2' => $new_end,
//            'test' => $data['log_clocking'],
//            'emp' => $data['employe']
        );

        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Permission('HED');
        $this->m_template->set_Content('hr/log_clock', $data);
        $this->m_template->showTemplate();
    }

    public function join() {
        $permittion = $this->session->userdata('permittion');
        if (($permittion == 'ALL' || $permittion == 'HEI') == FALSE) {
            redirect('hr/employee');
        }

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $EID = $this->input->post('EID');
            if ($EID == NUll || !$this->m_employee->check_employee($EID))
                redirect('hr/employee');

            $data = array(
                'StatusID' => '1',
            );

            $this->db->where('EID', $EID);
            if ($this->db->update('employees', $data)) {
                //Alert success and redirect to candidate
                $alert['alert_message'] = "กลับเข้าทำงานแล้ว";
                $alert['alert_mode'] = "success";
                $this->session->set_flashdata('alert', $alert);
            } else {
                $alert['alert_message'] = "กรุณาลองใหม่อีกครั้ง";
                $alert['alert_mode'] = "danger";
                $this->session->set_flashdata('alert', $alert);
            }
            redirect('hr/employee');
        }
    }

}
