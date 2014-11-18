<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class route extends CI_Controller {

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
        
        $route_detail = $this->m_route->get_route($rcode, $vtid);

        if (count($route_detail) <= 0)
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        $vt_name = $route_detail[0]['VTDescription'];
        $route_name = 'เส้นทาง สาย ' . $route_detail[0]['RCode'] . ' ' . ' ' . $route_detail[0]['RSource'] . ' - ' . $route_detail[0]['RDestination'];

        $data = array(
            'page_title' => 'จุดจอดและอัตตราค่าโดยสาร ' . $vt_name,
            'page_title_small' => $route_name,
            'rcode' => $rcode,
            'vtid' => $vtid,
            'route_detail' => $route_detail[0],
        );

        $data_debug = array(
//            'page_title' => 'จุดจอดและอัตตราค่าโดยสาร ' . $vt_name,
//            'page_title_small' => $route_name,
            'vtid' => $vtid,
            'rcode' => $rcode,
//            'route_detail' => $route_detail,
        );


        $this->m_template->set_Title('จุดจอดและอัตตราค่าโดยสาร');
        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('stations/stations', $data);
        $this->m_template->showTemplate();
    }
    
    public function add_station($rcode = NULL, $vtid = NULL) {
        $route_detail = $this->m_route->get_route($rcode, $vtid);
        if (count($route_detail) <= 0)
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        $vt_name = $route_detail[0]['VTDescription'];
        $route_name = 'เส้นทาง สาย ' . $route_detail[0]['RCode'] . ' ' . ' ' . $route_detail[0]['RSource'] . ' - ' . $route_detail[0]['RDestination'];

        $data = array(
            'page_title' => 'เพิ่มจุดจอด <i>' . $vt_name . '</i> ' . $route_name,
            'page_title_small' => '',
            'rcode' => $rcode,
            'route_detail' => $route_detail[0],
        );

        $data_debug = array(
            'page_title' => $data['page_title'],
            'page_title_small' => $data['page_title_small'],
            'rcode' => $data['rcode'],
            'route_detail' => $data['route_detail'],
//            '' => $data[''],
        );

        $this->m_template->set_Title('เพิ่มจุดจอด');
        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('stations/frm_station', $data);
        $this->m_template->showTemplate();
    }

}
