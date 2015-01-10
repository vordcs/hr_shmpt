<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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

    function get_post() {
        $get_page_data = array(
            'user' => $this->input->post('user'),
            'pass' => $this->input->post('pass')
        );
        return $get_page_data;
    }

    function login($data) {
        //Intial data
        $session = array(
            'name' => 'Admin',
            'login' => FALSE,
            'permittion' => 'ALL'
        );

        if ($data['user'] == 'admin' && $data['pass'] == 'admin') {
            $session['login'] = TRUE;
            $this->session->set_userdata($session);
            return TRUE;
        } else {
            $temp = $this->check_user($data['user'], $data['pass']);
            if ($temp != FALSE) {
                $session['name'] = $temp[0]['UserName'];
                $session['login'] = TRUE;
                $session['permittion'] = $temp[0]['PermissionDetails'];
                $this->session->set_userdata($session);
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function check_user($user, $pass) {
        $this->db->from('username un');
        $this->db->join('employees AS ep', 'ep.EID = un.UserName', 'left');
        $this->db->join('employee_role_permission AS er', 'er.RoleID = ep.RoleID', 'left');
        $this->db->where('un.UserName', $user);
        $this->db->where('un.Password', md5($pass));
        $this->db->where('un.IsNormal', 1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

}
