<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class candidate extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('m_candidate');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array();
        $this->m_template->set_Content('candidate/candidates', $data);
        $this->m_template->showTemplate();
    }
    
    public function add() {
        $data = array();
        
        
        $data['form_input'] = $this->m_candidate->set_form();
        
        $this->m_template->set_Debug($this->m_candidate->check_miscellaneous('sss'));
        $this->m_template->set_Content('candidate/frm_candidate', $data);
        $this->m_template->showTemplate();
    }
}