<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class permission extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('hr/m_employee');
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

        $data['form_open'] = form_open('hr/permission');
        $data['form_input'] = $this->m_employee->set_form_search();
        $data['form_close'] = form_close();

        $this->m_template->set_Content('hr/permission', $data);
        $this->m_template->showTemplate();
    }

}
