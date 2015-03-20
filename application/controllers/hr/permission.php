<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class permission extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('hr/m_permission');
        $this->load->model('hr/m_employee');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array();
        //Change permission
        if ($this->m_permission->set_validation_change() && $this->form_validation->run()) {
            $input_data = $this->m_permission->get_post_change();
            if ($this->m_permission->update_permission($input_data)) {
                //Alert success and redirect to candidate
                $alert['alert_message'] = "แก้ไขสิทธิ์พนักงานแล้ว";
                $alert['alert_mode'] = "success";
                $this->session->set_flashdata('alert', $alert);
            } else {
                //Alert success and redirect to candidate
                $alert['alert_message'] = "แก้ไขสิทธิ์พนักงานไม่สำเร็จ";
                $alert['alert_mode'] = "danger";
                $this->session->set_flashdata('alert', $alert);
            }
            redirect('hr/permission');
        }

        if ($this->m_permission->set_validation_search() && $this->form_validation->run()) {
            $input_data = $this->m_permission->get_post_search();
            $data['list_all'] = $this->m_permission->search_all_office($input_data);
        } else {
            if (($this->input->server('REQUEST_METHOD') === 'POST') == FALSE) {
                $data['list_all'] = $this->m_permission->search_all_office();
            }
        }

        $data['form_open'] = form_open('hr/permission');
        $data['form_input'] = $this->m_permission->set_form_search();
        $data['form_close'] = form_close();
        $data['form_change'] = $this->m_permission->set_form_change();

        $data_debug = array(
//            'post' => $this->input->post(),
//            'list' => $data['list_all'],
//        'change' => $this->m_permission->set_form_change(),
        );

        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('hr/permission', $data);
        $this->m_template->showTemplate();
    }

}
