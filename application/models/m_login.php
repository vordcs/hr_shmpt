<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

<<<<<<< HEAD
class m_login extends CI_Model {

    function set_form() {
        $i_user = array(
            'name' => 'user',
            'value' => set_value('user'),
            'placeholder' => 'Username',
            'autofocus' => true,
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');

        $data = array(
            'user' => form_input($i_user),
            'pass' => form_password($i_pass)
        );
        return $data;
    }

    function set_validation() {
        $this->form_validation->set_rules('user', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('pass', 'Password', 'trim|required|xss_clean');
        return TRUE;
    }

    function get_post() {//ดึงข้อมูลจากหน้า form
        $get_page_data = array(
            'user' => $this->input->post('user'),
            'pass' => $this->input->post('pass')
        );
        return $get_page_data;
    }

    function login($data) {
        //Intial data
        $flag = FALSE;
        $session = array(
            'name' => 'Admin',
            'login' => FALSE
        );

        if ($data['user'] == 'admin' && $data['pass'] == 'admin') {
            $session['login'] = TRUE;
            $flag = TRUE;
            $this->session->set_userdata($session);
            return TRUE;
        } else {
            return FALSE;
        }
=======
Class m_home extends CI_Model {

    public function set_form() {
        $i_username = array(
            'type' => 'text',
            'name' => 'username',
            'maxlength' => '20',
            'value' => set_value('username'),
            'class' => 'form-control',
            'placeholder' => 'Username',
            'autofocus' => ''
        );
        $i_password = array(
            'type' => 'password',
            'name' => 'password',
            'maxlength' => '32',
            'value' => set_value('password'),
            'class' => 'form-control',
            'placeholder' => 'Password'
        );

        
        $all_form = array(
            'username' => form_input($i_username),
            'password' => form_input($i_password),
        );
        return $all_form;
    }

    public function set_validation() {
         $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|callback_username_check');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_username_check');

        $this->form_validation->set_message('username_check', '%s ไม่ถูกต้อง');
        $this->form_validation->set_message('password_check', '%s ไม่ถูกต้อง"');
        return TRUE;
    }

    public function get_post() {
        $get_page_data = array(
            'username' => $this->input->post('username'),
            'password' => $this->input->post('password'),
        );
        return $get_page_data;
        
>>>>>>> origin/master
    }

}
