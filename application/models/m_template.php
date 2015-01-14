<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class m_template extends CI_Model {

    private $title = 'บริษัท สหมิตรภาพ(2512) จำกัด';
    private $view_name = NULL;
    private $real_alert = NULL;
    private $set_data = NULL;
    private $permission = "ALL";
    private $debud_data = NULL;
    private $lang_value = array('theme');
    private $version = '1.0';

    function set_RealAlert($data) {
        $this->real_alert = $data;
    }

    function set_Debug($data) {
        $this->debud_data = $data;
    }

    function set_Title($name) {
        $this->title = $name . ' | ' . $this->title;
    }

    function set_Content($name, $data) {
        $this->view_name = $name;
        $this->set_data = $data;
    }

    function set_Permission($mode) {
        $this->permission = $mode;
    }

    function check_Alert() {
        return $this->session->flashdata('alert');
    }

    function check_RealAlert() {
        return $this->real_alert;
    }

    function check_permission() {
        $sess = $this->session->userdata('login');
        $permit = $this->session->userdata('permittion');
        if ($sess == NULL || $sess == FALSE) {
            redirect('login');
        }
        if (strpos($permit, $this->permission) !== false) {
            return TRUE;
        } elseif ($this->permission == 'ALL') {
            return TRUE;
        } else {
            return FALSE;
        }
        return TRUE;
    }

    function set_Language($in) {
        foreach ($in as $data) {
            array_push($this->lang_value, $data);
        }
    }

    public function set_user() {
        $user_data = array(
            'EID' => 'E123456789',
            'UserName' => 'admin',
            'sale_type' => '1',
        );
        $this->session->set_userdata($user_data);
    }

    function showTemplate() {
        //--- Load language --//
        $site_lang = $this->session->userdata('site_lang');
        if (!$site_lang) {
            $site_lang = 'thai'; //Default set language to Thai
        }
        foreach ($this->lang_value as $path) {
            $this->lang->load($path, $site_lang); //Load message
        }

        //Load version for Cache CSS and JS
        $data['version'] = $this->version;

        //--- Redirect to current page ---//
        $data['page'] = $this->uri->segment(1);

        //--- Alert System ---//
        $data['alert'] = $this->session->userdata('alert');
        $this->session->unset_userdata('alert');

//        กำหนดข้อมูลเริ่มต้นในการทดสอบโปรแกรม
        $this->set_user();

        $data['form_login'] = form_open('logout', array('class' => 'navbar-form pull-right', 'style' => 'height: 40px;'));

        //Check login and permission
//        if ($this->check_permission() == FALSE) {
//            //Alert success and redirect to candidate
//            $alert['alert_message'] = "ระดับสิทธิ์ของคุณไม่สามารถใช้งานระบบในส่วนนี้ได้";
//            $alert['alert_mode'] = "danger";
//            $this->set_RealAlert($alert);
//        }

        $data['username'] = $this->session->userdata('username');
        $data['title'] = $this->title;
        $data['debug'] = $this->debud_data;
        $data['alert'] = $this->check_Alert();
        $data['real_alert'] = $this->check_RealAlert();

        $this->load->view('theme_header', $data);
        if ($this->check_permission() && $this->view_name != NULL) {
            $this->load->view($this->view_name, $this->set_data);
        } else {
            $this->load->view('permission_deny');
        }

        $this->load->view('theme_footer');
    }

    function showReportTemplate() {
        //--- Load language --//
        $site_lang = $this->session->userdata('site_lang');
        if (!$site_lang) {
            $site_lang = 'thai'; //Default set language to Thai
        }
        foreach ($this->lang_value as $path) {
            $this->lang->load($path, $site_lang); //Load message
        }

        //Load version for Cache CSS and JS
        $data['version'] = $this->version;

        //--- Redirect to current page ---//
        $data['page'] = $this->uri->segment(1);

        //--- Alert System ---//
        $data['alert'] = $this->session->userdata('alert');
        $this->session->unset_userdata('alert');

//        กำหนดข้อมูลเริ่มต้นในการทดสอบโปรแกรม
        $this->set_user();

        $user = $this->session->userdata('user');
        $data['u_name'] = $user['u_name'];
        $data['form_login'] = form_open('logout', array('class' => 'navbar-form pull-right', 'style' => 'height: 40px;'));

        //Check login and permission
//        if ($this->check_permission() == FALSE) {
//            //Alert success and redirect to candidate
//            $alert['alert_message'] = "ระดับสิทธิ์ของคุณไม่สามารถใช้งานระบบในส่วนนี้ได้";
//            $alert['alert_mode'] = "danger";
//            $this->set_RealAlert($alert);
//        }

        $data['title'] = $this->title;
        $data['debug'] = $this->debud_data;
        $data['alert'] = $this->check_Alert();
        $data['real_alert'] = $this->check_RealAlert();

        $this->load->view('theme_header_report', $data);
        if ($this->check_permission() && $this->view_name != NULL) {
            $this->load->view($this->view_name, $this->set_data);
        } else {
            $this->load->view('permission_deny');
        }

        $this->load->view('theme_footer');
    }

}
