<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class vehicle extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array();
        $this->m_template->set_Content('vehicle/vehicles', $data);
        $this->m_template->showTemplate();
    }
    
    public function add() {
        $data = array();
        $this->m_template->set_Content('vehicle/frm_vehicle', $data);
        $this->m_template->showTemplate();
    }
}