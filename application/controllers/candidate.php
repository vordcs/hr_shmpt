<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class candidate extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('m_candidate');
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

        $data['form_open'] = form_open('candidate/add', 'id="frm_main" class="form-horizontal"');
        $data['form_input'] = $this->m_candidate->set_form();
        $data['form_close'] = form_open('candidate/add');

        $this->m_template->set_Debug(NULL);
        $this->m_template->set_Content('candidate/frm_candidate', $data);
        $this->m_template->showTemplate();
    }

}
