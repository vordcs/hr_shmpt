<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class fares extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_fares');
        $this->load->model('m_station');
        $this->load->model('m_route');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        
    }

    public function add($rcode, $vtid) {

        $route_detail = $this->m_route->get_route($rcode, $vtid);
        $fares = $this->m_fares->get_fares($rcode, $vtid);

        if (count($route_detail) <= 0) {
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        }
        if (count($fares) > 0) {
            redirect("fares/edit/$rcode/$vtid");
        }

        $vt_name = $route_detail[0]['VTDescription'];
        $route_name = 'เส้นทาง สาย ' . $route_detail[0]['RCode'] . ' ' . ' ' . $route_detail[0]['RSource'] . ' - ' . $route_detail[0]['RDestination'];

        if ($this->m_fares->validation_form_add($rcode, $vtid) && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_fares->get_post_form_add($rcode, $vtid);
            $rs = $this->m_fares->insert_fares($form_data);

//            $data_debug_form = array(
//                'data_insert' => $rs,
//                'form_data' => $form_data,
//            );
//            $this->m_template->set_Debug($data_debug_form);

            $alert['alert_message'] = "กำหนดข้อมูลค่าโดยสาร $route_name สำเร็จ";
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);

            redirect("route/view/$rcode/$vtid");
        }
        $data = array(
            'page_title' => 'กำหนดค่าโดยสาร <i>' . $vt_name . '</i> ' . $route_name,
            'page_title_small' => '',
            'form' => $this->m_fares->set_form_add($rcode, $vtid),
            'rcode' => $rcode,
            'vtid' => $vtid,
            'stations' => $this->m_station->get_stations($rcode, $vtid),
            'route_detail' => $route_detail,
            'previous_page' => "station/edit/$rcode/$vtid",
            'next_page' => '',
        );

        $data_debug = array(
//            'page_title' => 'จุดจอดและค่าโดยสาร ' . $vt_name,
//            'page_title_small' => $route_name,
//            'vtid' => $vtid,
//            'rcode' => $rcode,
//            'stations' => $data['stations'],
//            'route_detail' => $route_detail,
//            'form' => $data['form'],
//            'price' =>  $this->m_fares->get_post_form_add($rcode, $vtid),
        );


        $this->m_template->set_Title('กำหนดอัตตราค่าโดยสาร');
//        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('fares/frm_fares', $data);
        $this->m_template->showTemplate();
    }

    public function edit($rcode, $vtid) {

        $route_detail = $this->m_route->get_route($rcode, $vtid);

        $vt_name = $route_detail[0]['VTDescription'];
        $route_name = 'เส้นทาง สาย ' . $route_detail[0]['RCode'] . ' ' . ' ' . $route_detail[0]['RSource'] . ' - ' . $route_detail[0]['RDestination'];
        $station = $this->m_station->get_stations($rcode, $vtid);
        $fares = $this->m_fares->get_fares($rcode, $vtid);

        $data = array(
            'page_title' => 'แก้ไขค่าโดยสาร <i>' . $vt_name . '</i> ' . $route_name,
            'page_title_small' => '',
            'vtid' => $vtid,
            'rcode' => $rcode,
            'route_detail' => $route_detail,
            'previous_page' => "station/edit/$rcode/$vtid",
            'next_page' => '',
        );
        if (count($route_detail) <= 0) {
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        }
        if (count($station) <= 0) {
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        }
        if (count($fares) <= 0) {
            redirect("fares/add/$rcode/$vtid");
        }

        $data['form'] = $this->m_fares->set_form_edit($rcode, $vtid, $station);
        $data['stations'] = $station;

        $data['fares'] = $fares;

        if ($this->m_fares->validation_form_edit($rcode, $vtid) && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_fares->get_post_form_edit($rcode, $vtid);
            $rs = $this->m_fares->update_fares($rcode, $vtid, $form_data);

//            $data_debug_form = array(
//                'data_update' => $rs,
//                'form_data' => $form_data,
//            );
//            $this->m_template->set_Debug($data_debug_form);

            $alert['alert_message'] = "แก้ไขข้อมูลค่าโดยสาร $route_name สำเร็จ";
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);

            redirect("route/view/$rcode/$vtid");
        }
        //
//        $data_debug = array(
//            'page_title' => 'จุดจอดและค่าโดยสาร ' . $vt_name,
//            'page_title_small' => $route_name,
//            'vtid' => $vtid,
//            'rcode' => $rcode,
//            'stations' => $data['stations'],
//            'route_detail' => $route_detail,
//            'form' => $data['form'],
//            'price' =>  $this->m_fares->get_post_form_add($rcode, $vtid),
//            'fares' => $data['fares']
//        );
        $this->m_template->set_Title('แก้ไขค่าโดยสาร');
//        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('fares/frm_fares', $data);
        $this->m_template->showTemplate();
    }

}
