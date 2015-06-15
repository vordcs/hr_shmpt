<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('m_home');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array(
//            'route' => $route,
            'timeline' => $this->m_home->check_report_day(),
            'EID' => $this->session->userdata('username'),
            'permittion' => $this->session->userdata('permittion'),
        );


        $data_debug = array(
//            'timeline' => $this->m_home->check_report_day(),
//            'EID'=>$this->session->userdata('username'),
//            'permittion' => $this->session->userdata('permittion'),
        );

        $this->m_template->set_Debug($data_debug);
        //$this->m_template->set_Permission('SSL');
        $this->m_template->set_Content('home/main', $data);
        $this->m_template->showTemplate();
    }

    public function check_report($ReportID = NULL, $EID = NULL) {
        if ($ReportID == NULL || $EID == NULL)
            redirect('home');

        if ($this->m_home->check_report($ReportID, $EID)) {
            //Alert success and redirect to candidate
            $alert['alert_message'] = "บันทึกรับเงินสำเร็จ";
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);
        } else {
            //Alert success and redirect to candidate
            $alert['alert_message'] = "บันทึกผิดพลาด กรุณาลองใหม่อีกครั้ง";
            $alert['alert_mode'] = "danger";
            $this->session->set_flashdata('alert', $alert);
        }
        redirect('home');
    }

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */