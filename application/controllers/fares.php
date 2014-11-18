<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class fares extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_station');
        $this->load->model('m_route');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        
    }

    public function add($rcode, $vtid) {

        $route_detail = $this->m_route->get_route($rcode, $vtid);

        if (count($route_detail) <= 0)
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        $vt_name = $route_detail[0]['VTDescription'];
        $route_name = 'เส้นทาง สาย ' . $route_detail[0]['RCode'] . ' ' . ' ' . $route_detail[0]['RSource'] . ' - ' . $route_detail[0]['RDestination'];

        $data = array(
            'page_title' => 'กำหนดอัตตราค่าโดยสาร ' . $vt_name,
            'page_title_small' => $route_name,
            'rcode' => $rcode,
            'vtid' => $vtid,
            'stations' => $this->m_station->get_stations($rcode, $vtid),
            'route_detail' => $route_detail,
        );

        $data_debug = array(
//            'page_title' => 'จุดจอดและอัตตราค่าโดยสาร ' . $vt_name,
//            'page_title_small' => $route_name,
//            'vtid' => $vtid,
//            'rcode' => $rcode,
            'stations' => $data['stations'],
//            'route_detail' => $route_detail,
        );


        $this->m_template->set_Title('กำหนดอัตตราค่าโดยสาร');
//        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('fares/frm_fares', $data);
        $this->m_template->showTemplate();
    }

}
