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

//        $alert['alert_message'] = "test";
//        $alert['alert_mode'] = "warning";
//        $this->session->set_flashdata('alert', $alert);

        $this->m_template->set_Content('candidate/candidates', $data);
        $this->m_template->showTemplate();
    }

    public function add() {
        $data = array();

        if ($this->m_candidate->set_validation() && $this->form_validation->run()) {
            $input_data = $this->m_candidate->get_post();
            $input_data['debug'] = $this->m_candidate->insert_all_data($input_data);
            $data['form_input'] = $this->m_candidate->set_form();
            $this->m_template->set_Debug($input_data);
        } else {
            $data['form_input'] = $this->m_candidate->set_form();
        }

        $data['form_open'] = form_open('candidate/add', 'id="frm_main" class="form-horizontal"');

        $data['form_close'] = form_open('candidate/add');

        $test['in'] = '2557-11-4';
        $test['out'] = $this->m_datetime->setDateFomat($test['in']);

        $temp['POST'] = $this->m_candidate->get_post();
        //$temp['debug'] = count($this->input->post('NumberSon'));
        //$temp['FORM'] = $this->m_candidate->set_form();
        $this->m_template->set_Debug($temp);
        $this->m_template->set_Content('candidate/frm_candidate', $data);
        $this->m_template->showTemplate();
    }

}
