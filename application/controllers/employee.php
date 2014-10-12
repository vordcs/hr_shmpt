<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class employee extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array();
        $this->m_template->set_Content('hr/employees', $data);
        $this->m_template->showTemplate();
    }
    
    public function view($eid){
        $data = array();
        $this->m_template->set_Content('hr/detail_employee', $data);
        $this->m_template->showTemplate();
    }
    
    public function edit($eid=NULL){
        $data = array();
        $this->m_template->set_Content('hr/frm_employee', $data);
        $this->m_template->showTemplate();
    }
    
}

