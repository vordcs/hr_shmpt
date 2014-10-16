<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
        
    }

}
