<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('m_route');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array(
//            'route' => $route,
        );

//        $this->m_template->set_Debug($this->session->userdata('permittion'));
        //$this->m_template->set_Permission('SSL');
        $this->m_template->set_Content('home/main', $data);
        $this->m_template->showTemplate();
    }

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */