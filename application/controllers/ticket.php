<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ticket extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_ticket');
        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $RCode = NULL;
        $VTID = NULL;
        $form_data = array();
        if ($this->m_ticket->validation_form_search_by_route() && $this->form_validation->run() == TRUE) {
            $rcode = $this->input->post('RCode');
            $vtid = $this->input->post('VTID');
            if ($rcode != "0") {
                $RCode = $rcode;
            }
            if ($vtid != "0") {
                $VTID = $vtid;
            }
            $form_data = $this->input->post();
        }

        $data_ticket = $this->m_ticket->set_data_view($RCode, $VTID);

        $date = $this->m_datetime->getDateToday();

        $data = array(
            'page_title' => "ข้อมูลตั๋วโดยสาร",
            'page_title_small' => $this->m_datetime->DateThai($date),
            'previous_page' => "",
            'next_page' => '',
            'data' => $data_ticket['data'],
            'form_search' => $data_ticket['form_search'],
        );

        $data_debug = array(
//            'form_search' => $data['form_search'],
//            'data' => $data['data'],
//            'form_data' => $form_data,
        );

        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title('จัดการตั๋วโดยสาร');
        $this->m_template->set_Permission('COI');
        $this->m_template->set_Content('ticket/tickets', $data);
        $this->m_template->showTemplate();
    }

    public function view($Rcode, $VTID, $SID = NULL, $EID = NULL) {
        $date = $this->m_datetime->getDateToday();
        $DateSale = $this->session->flashdata('DateSale');


        if ($DateSale != NULL) {
            $date = $DateSale;
        }
        if ($this->session->flashdata('EID') != NULL) {
            $EID = $this->session->flashdata('EID');
        }

        $form_data = array();
        if ($this->m_ticket->validation_form_search_ticket() && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_ticket->get_post_form_search_ticket();
            $date = $form_data['DateDB'];
            if ($form_data['EID'] != '0') {
                $EID = $form_data['EID'];
                $form_data['debug'] = $EID;
            }
        }
        $data_view = $this->m_ticket->set_form_view($date, $Rcode, $VTID, $SID, $EID);
        $data = array(
            'page_title' => "ข้อมูลตั๋วโดยสาร",
            'page_title_small' => '',
            'previous_page' => "",
            'next_page' => '',
            'date' => $this->m_datetime->DateThai($date),
            'form_search' => $data_view['form_search'],
            'data' => $data_view['data'],
        );

        $data_debug = array(
//            'data' => $data['data'],
//            'form' => $data['form_search'], 
//            'form_data' => $form_data,
        );

        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title('ตั๋วโดยสาร');
        $this->m_template->set_Permission('FAA');
        $this->m_template->set_Content('ticket/view_ticket', $data);
        $this->m_template->showTemplate();
    }

    public function delete($TicketID) {

        $ticket = reset($this->m_ticket->get_tickets(NULL, NULL, NULL, NULL, NULL, $TicketID));

        $RCode = $ticket['RCode'];
        $VTID = $ticket['VTID'];
        $TSID = $ticket['TSID'];
        $EID = $ticket['Seller'];
        $SourceID = $ticket['SourceID'];
        $SourceName = $ticket['SourceName'];        
        $DestinationName = $ticket['DestinationName'];
        $DateSale = $ticket['DateSale'];

        $this->session->set_flashdata('TSID', $TSID);
        $this->session->set_flashdata('DateSale', $DateSale);
        $this->session->set_flashdata('EID', $EID);

        $rs = $this->m_ticket->delete($TicketID);
        
        if ($rs) {
            $alert['alert_message'] = "ลบ ตั๋วโดยสาร $SourceName ไป $DestinationName  ";
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);
        } else {
            $alert['alert_message'] = "ลบ ตั๋วโดยไม่สำเร็จ";
            $alert['alert_mode'] = "danger";
            $this->session->set_flashdata('alert', $alert);
        }

        redirect("ticket/view/$RCode/$VTID/$SourceID/");
    }

}
