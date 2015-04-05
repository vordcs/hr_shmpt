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

    public function search($RCode, $VTID) {
        if ($RCode == NULL || $VTID == NULL) {
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        }

        $route_detail = $this->m_route->get_route($RCode, $VTID);
        $vt_name = $route_detail[0]['VTDescription'];
        $route_name = 'เส้นทาง สาย ' . $route_detail[0]['RCode'] . ' ' . ' ' . $route_detail[0]['RSource'] . ' - ' . $route_detail[0]['RDestination'];

        $data = array(
            'page_title' => 'กำหนดค่าโดยสาร <i>' . $vt_name . '</i> ' . $route_name,
            'page_title_small' => '',
            'previous_page' => "station/$RCode/$VTID",
            'next_page' => '',
            'data' => $this->m_fares->set_form_search($RCode, $VTID),
        );

        $form_data = array();
        if ($this->m_fares->validation_form_search() && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_fares->get_post_form_search();
            $SID = $form_data['SID'];
            $data['data'] = $this->m_fares->set_form_search($RCode, $VTID, $SID);
        }



        $data_debug = array(
//            'page_title' => 'จุดจอดและค่าโดยสาร ' . $vt_name,
//            'page_title_small' => $route_name,
//            'data' => $data['data']['data'],
//            'form_data' => $form_data
        );

        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title('กำหนดอัตตราค่าโดยสาร');
        $this->m_template->set_Permission('FAA');
        $this->m_template->set_Content('fares/fares', $data);
        $this->m_template->showTemplate();
    }

    public function add($rcode, $vtid, $SID = NULL) {

        if ($SID == NULL) {
            redirect("fares/search/$rcode/$vtid");
        }

        if ($rcode == NULL || $vtid == NULL || $SID == NULL) {
//            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        }

        $route_detail = reset($this->m_route->get_route($rcode, $vtid));

        $vt_name = $route_detail['VTDescription'];
        $route_name = 'เส้นทาง สาย ' . $route_detail['RCode'] . ' ' . ' ' . $route_detail['RSource'] . ' - ' . $route_detail['RDestination'];

        $station = reset($this->m_fares->get_stations($rcode, $vtid, $SID));
        $SourceName = $station['StationName'];

        $form_data = array();
        $rs_insert = array();
        if ($this->m_fares->validation_form_add($rcode, $vtid, $SID) && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_fares->get_post_form_add($rcode, $vtid);
            $rs_insert = $this->m_fares->insert_fares($form_data);
            $alert['alert_message'] = "กำหนดข้อมูลค่าโดยสาร $route_name สำเร็จ";
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);
            redirect("fares/search/$rcode/$vtid");
        }
        $data = array(
            'page_title' => 'กำหนดค่าโดยสาร <i>' . $vt_name . '</i> ' . $route_name,
            'page_title_small' => "$SourceName (ไป-กลับ)",
            'previous_page' => "fares/search/$rcode/$vtid",
            'next_page' => '',
            'data' => $this->m_fares->set_form_add($rcode, $vtid, $SID),
        );

        $data_debug = array(
//            'page_title' => 'จุดจอดและค่าโดยสาร ' . $vt_name,
//            'page_title_small' => $route_name,
//            'form_data' => $form_data,
//            'data_insert' => $rs_insert,
        );

        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title('กำหนดอัตตราค่าโดยสาร');
        $this->m_template->set_Permission('FAA');
        $this->m_template->set_Content('fares/frm_fares', $data);
        $this->m_template->showTemplate();
    }

    public function edit($rcode, $vtid, $SID) {

        if ($rcode == NULL || $vtid == NULL || $SID == NULL) {
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        }

        $route_detail = $this->m_route->get_route($rcode, $vtid);

        $vt_name = $route_detail[0]['VTDescription'];
        $route_name = 'เส้นทาง สาย ' . $route_detail[0]['RCode'] . ' ' . ' ' . $route_detail[0]['RSource'] . ' - ' . $route_detail[0]['RDestination'];

        $station = reset($this->m_fares->get_stations($rcode, $vtid, $SID));
        $SourceName = $station['StationName'];

        $data = array(
            'page_title' => 'แก้ไขค่าโดยสาร <i>' . $vt_name . '</i> ' . $route_name,
            'page_title_small' => "$SourceName (ไป-กลับ)",
            'previous_page' => "fares/search/$rcode/$vtid",
            'next_page' => '',
        );

        $form_data = array();
        $rs = array();
        if ($this->m_fares->validation_form_edit_($rcode, $vtid, $SID) && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_fares->get_post_form_edit_($rcode, $vtid, $SID);
            $rs = $this->m_fares->update_fares($form_data);


            $alert['alert_message'] = "แก้ไขข้อมูลค่าโดยสาร $route_name สำเร็จ";
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);
            redirect("fares/search/$rcode/$vtid");
        }
        $data['data'] = $this->m_fares->set_form_edit_($rcode, $vtid, $SID);
        $data_debug = array(
//            'page_title' => 'จุดจอดและค่าโดยสาร ' . $vt_name,
//            'page_title_small' => $route_name,
//            'vtid' => $vtid,
//            'rcode' => $rcode,
//            'stations' => $data['stations'],
//            'route_detail' => $route_detail,
//            'form' => $data['form'],
//            'price' =>  $this->m_fares->get_post_form_add($rcode, $vtid),
//            'data' => $data['data'],
//            'form_data' => $form_data,
//            'rs' => $rs,
        );

        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title('แก้ไขค่าโดยสาร');
        $this->m_template->set_Permission('FAE');
        $this->m_template->set_Content('fares/frm_fares', $data);
        $this->m_template->showTemplate();
    }

    //    ตรวจสอบค่าใน dropdown
    public function check_dropdown($str) {
        if ($str === '0') {
            $this->form_validation->set_message('check_dropdown', 'เลือก %s');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
