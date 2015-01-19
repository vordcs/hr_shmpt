<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class candidate extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('m_candidate');
        $this->load->model('m_datetime');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array();

        if ($this->m_candidate->set_validation_search() && $this->form_validation->run()) {
            $input_data = $this->m_candidate->get_post_search();
            $list = $this->m_candidate->search($input_data);
            $input_data['list'] = $list;
            $data['list'] = $list;
            //$this->m_template->set_Debug($input_data);
        }

        $data['form_open'] = form_open('candidate');
        $data['form_input'] = $this->m_candidate->set_form_search();
        $data['form_close'] = form_close();

        //$this->m_template->set_Debug($input_data);
        $this->m_template->set_Permission('CAI');
        $this->m_template->set_Content('candidate/candidates', $data);
        $this->m_template->showTemplate();
    }

    public function add() {
        $data = array();

        if ($this->m_candidate->set_validation() && $this->form_validation->run()) {
            $input_data = $this->m_candidate->get_post();
            $input_data['debug'] = $this->m_candidate->insert_all_data($input_data);
            $data['form_input'] = $this->m_candidate->set_form();

            //Alert success and redirect to candidate
            $alert['alert_message'] = "เพิ่มข้อมูลผู้สมัครงานแล้ว";
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);
            redirect('candidate');

            //$this->m_template->set_Debug($input_data);
        } else {
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                //Alert success and redirect to candidate
                $alert['alert_message'] = "กรุณากรอกข้อมูลที่สำคัญให้ครบถ้วน";
                $alert['alert_mode'] = "danger";
                $this->m_template->set_RealAlert($alert);
            }

            $data['form_input'] = $this->m_candidate->set_form();
        }

        $data['mode'] = 'add';
        $data['form_open'] = form_open('candidate/add', 'id="frm_main" class="form-horizontal"');
        $data['form_close'] = form_close();

        //$this->m_template->set_Debug($temp);
        $this->m_template->set_Permission('CAA');
        $this->m_template->set_Content('candidate/frm_candidate', $data);
        $this->m_template->showTemplate();
    }

    public function edit($CID) {
        if ($CID == NUll || !$this->m_candidate->check_candidate($CID))
            redirect('candidate');

        $data = array();
        $c_data = $this->m_candidate->check_candidate_detail($CID);

        if ($this->m_candidate->set_validation() && $this->form_validation->run()) {
            $input_data['CID'] = $CID;
            $input_data += $this->m_candidate->get_post();
            $input_data['emergency']['ECID'] = $c_data['ECID'];

            $input_data['debug'] = $this->m_candidate->update_all_data($input_data);
            $data['form_input'] = $this->m_candidate->set_form();
            $this->m_template->set_Debug($input_data);

            //Alert success and redirect to candidate
            $alert['alert_message'] = "แก้ไขข้อมูลผู้สมัครงานแล้ว";
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);
            redirect('candidate');
        } else {
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                //Alert success and redirect to candidate
                $alert['alert_message'] = "กรุณากรอกข้อมูลที่สำคัญให้ครบถ้วน";
                $alert['alert_mode'] = "danger";
                $this->m_template->set_RealAlert($alert);
            }

            $data['c_data'] = $c_data;
            $data['form_input'] = $this->m_candidate->set_form($c_data);
        }

        $data['mode'] = 'edit';
        $data['form_open'] = form_open('candidate/edit/' . $CID, 'id="frm_main" class="form-horizontal"');
        $data['form_close'] = form_close();

//        $this->m_template->set_Debug($c_data);
        $this->m_template->set_Permission('CAE');
        $this->m_template->set_Content('candidate/frm_candidate', $data);
        $this->m_template->showTemplate();
    }

    public function detail($CID) {
        if ($CID == NUll || !$this->m_candidate->check_candidate($CID))
            redirect('candidate');

        $data = array();
        $c_data = $this->m_candidate->check_candidate_detail($CID);

        $data['c_data'] = $c_data;
        $data['form_input'] = $this->m_candidate->set_form($c_data, TRUE);

        $data['mode'] = 'detail';
        $data['form_open'] = form_open('candidate/detail/' . $CID, 'id="frm_main" class="form-horizontal"');
        $data['form_close'] = form_close();

//        $this->m_template->set_Debug($c_data);
        $this->m_template->set_Permission('CAD');
        $this->m_template->set_Content('candidate/frm_candidate', $data);
        $this->m_template->showTemplate();
    }

}
