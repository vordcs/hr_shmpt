<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_loguser extends CI_Model {

    function check_password($user, $pass) {
        $this->db->from('username un');
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

    function update_username($data) {
        $this->db->where('UserName', $data['UserName']);
        $this->db->update('username', $data);
        if ($this->db->affected_rows() == 1)
            return TRUE;
        else
            return FALSE;
    }

}
