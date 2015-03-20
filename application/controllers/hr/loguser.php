<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class loguser extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('hr/m_employee');
        $this->load->model('hr/m_loguser');
        $this->load->library('form_validation');

        //Initial language
        //$this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array();
        $username = $this->session->userdata('username');

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $POST = $this->input->post();
            if ($this->m_loguser->check_password($username, $POST['old_pass'])) {
                $input = array(
                    'UserName' => $username,
                    'Password' => md5($POST['new_pass']),
                );
                if ($this->m_loguser->update_username($input)) {
                    //Alert success and redirect to candidate
                    $alert['alert_message'] = "เปลี่ยนรหัสผ่านสำเร็จแล้ว";
                    $alert['alert_mode'] = "success";
                    $this->session->set_flashdata('alert', $alert);
                } else {
                    //Alert success and redirect to candidate
                    $alert['alert_message'] = "กรุณาลองใหม่อีกครั้ง";
                    $alert['alert_mode'] = "danger";
                    $this->session->set_flashdata('alert', $alert);
                }
                redirect('hr/loguser');
            } else {
                //Alert success and redirect to candidate
                $alert['alert_message'] = "คุณกรอกรหัสผ่านเก่าไม่ถูกต้อง";
                $alert['alert_mode'] = "danger";
                $this->session->set_flashdata('alert', $alert);
                redirect('hr/loguser');
            }
        }

        if ($username != 'Admin' && $username != 'admin')
            $data['detail'] = $this->m_employee->check_employee_detail($this->session->userdata('username'));


        $data['form_open'] = form_open('hr/loguser');
        $data['form_close'] = form_close();

//        $this->m_template->set_Debug($this->input->post());
        $this->m_template->set_Content('hr/loguser', $data);
        $this->m_template->showTemplate();
    }

}
