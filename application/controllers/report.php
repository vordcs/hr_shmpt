<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_route');
        $this->load->model('m_station');
        $this->load->model('m_fares');
        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array(
            'page_title' => 'รายงาน',
            'page_title_small' => '',           
            'previous_page' => '',
            'next_page' => '',
        );

        $data_debug = array(
            
        );
        
        $this->m_template->set_Debug($data_debug);
        
        $this->m_template->set_Title('รายงาน');
        $this->m_template->set_Content('report/report', $data);
        $this->m_template->showTemplate();
    }

}
