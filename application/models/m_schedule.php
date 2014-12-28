<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class m_schedule extends CI_Model {

    public function get_schedule($date = NULL, $rcode = NULL, $vtid = NULL, $rid = NULL) {
        $this->db->join('t_routes', ' t_schedules_day.RID = t_routes.RID ', 'left');
        $this->db->join('vehicles_has_schedules', ' vehicles_has_schedules.TSID = t_schedules_day.TSID', 'left');
        $this->db->join('vehicles', ' vehicles.VID = vehicles_has_schedules.VID', 'left');

        if ($date != NULL) {
            $this->db->where('Date', $date);
        }
        if ($rcode != NULL) {
            $this->db->where('t_routes.RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('t_routes.VTID', $vtid);
        }
        if ($rid != NULL) {
            $this->db->where('t_schedules_day.RID', $rid);
        }
        $this->db->order_by('SeqNo', 'asc');
        $query_schedule = $this->db->get("t_schedules_day");
        return $query_schedule->result_array();
    }

    public function insert_schedule($data) {
//        $this->db->truncate('t_schedules_day'); 
        $rs = array();
        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                $rid = $data[$i]['RID'];
                $date = $data[$i]['Date'];
                $time_depart = $data[$i]['TimeDepart'];

                $rs[$i] = count($this->check_schedule($date, $rid, $time_depart));

                if (count($this->check_schedule($date, $rid, $time_depart)) == 0) {
                    $this->db->insert('t_schedules_day', $data[$i]);
                    $tsid = $data[$i]['TSID'];
                    $rs[$i] = "Date $date INSERT -> TSID = " . $tsid;
                }
            }
        }
        return $rs;
    }

    public function check_schedule($date, $rid, $time_depart) {

        $this->db->where('RID', $rid);
        $this->db->where('Date', $date);
        $this->db->where('TimeDepart', $time_depart);
        $query_schedule = $this->db->get("t_schedules_day");

        return $query_schedule->result_array();
    }

    public function get_vehicle($rcode = NULL, $vtid = NULL) {
        $this->db->join('t_routes_has_vehicles', 'vehicles.VID = t_routes_has_vehicles.VID', 'left');
        if ($rcode != NULL) {
            $this->db->where('t_routes_has_vehicles.RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('vehicles.VTID', $vtid);
        }
        $query = $this->db->get('vehicles');
        return $query->result_array();
    }

    public function get_stations($rcode = null, $vtid = null, $sid = NULL, $seq = NULL) {
        if ($rcode != NULL) {
            $this->db->where('RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('VTID', $vtid);
        }
        if ($sid != NULL) {
            $this->db->where('SID', $sid);
        }
        if ($seq != NULL) {
            $this->db->where('Seq', $seq);
        }
        $this->db->order_by('Seq');
        $query = $this->db->get('t_stations');

        $rs = $query->result_array();

        return $rs;
    }

    public function get_schedule_master($rid = NULL, $smid = NULL) {
        $this->db->join('t_routes_has_schedules_manual', 't_routes_has_schedules_manual.SMID = t_schedules_manual.SMID', 'left');
        if ($rid != NULL) {
            $this->db->where('RID', $rid);
        }
        $this->db->order_by('SeqNo');
        $query = $this->db->get('t_schedules_manual');

        return $query->result_array();
    }

    public function get_route($rcode = NULL, $vtid = NULL) {

        $this->db->join('vehicles_type', 'vehicles_type.VTID = t_routes.VTID');

        if ($rcode != NULL) {
            $this->db->where('RCode', $rcode);
        }

        if ($vtid != NULL) {
            $this->db->where('t_routes.VTID', $vtid);
        }
        if ($rid != NULL) {
            
        } else {
            $this->db->group_by(array('RCode', 't_routes.VTID'));
            $this->db->where('StartPoint', 'S');
        }

        $query = $this->db->get('t_routes');
        return $query->result_array();
    }

    public function get_route_detail($rcode = NULL, $vtid = NULL) {
        $this->db->join('vehicles_type', 'vehicles_type.VTID = t_routes.VTID');
        if ($rcode != NULL) {
            $this->db->where('RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('t_routes.VTID', $vtid);
        }
        $this->db->order_by('StartPoint', 'DESC');
        $query = $this->db->get('t_routes');
        return $query->result_array();
    }

    public function set_form_search($rcode = NULL, $vtid = NULL) {
        $i_RCode = array(
            'name' => 'RCode',
            'value' => set_value('RID'),
            'placeholder' => 'รหัสเส้นทาง',
            'class' => 'form-control');

        $i_VTID[0] = 'เลือกประเภทรถ';
        foreach ($this->get_vehicle_types() as $value) {
            $i_VTID[$value['VTID']] = $value['VTDescription'];
        }
        $i_RSource = array(
            'name' => 'RSource',
            'value' => set_value('RSource'),
            'placeholder' => 'ต้นทาง',
            'class' => 'form-control');

        $i_RDestination = array(
            'name' => 'RDestination',
            'value' => set_value('RDestination'),
            'placeholder' => 'ปลายทาง',
            'class' => 'form-control');

        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';



        $form_search_route = array(
            'form' => form_open('schedule/', array('id' => 'form_search_schedule')),
            'RCode' => form_input($i_RCode),
            'VTID' => form_dropdown('VTID', $i_VTID, set_value('VTID'), $dropdown),
            'RSource' => form_input($i_RSource),
            'RDestination' => form_input($i_RDestination),
        );
        return $form_search_route;
    }

    public function set_form_add($rcode, $vtid, $rid) {

        $route_detail = $this->m_route->get_route($rcode, $vtid, $rid);

        $vt_name = $route_detail[0]['VTDescription'];
        $source = $route_detail[0]['RSource'];
        $desination = $route_detail[0]['RDestination'];
        $route_name = $vt_name . ' เส้นทาง ' . $route_detail[0]['RCode'] . ' ' . ' ' . $source . ' - ' . $desination;

        $i_route_name = array(
            'type' => 'text',
            'name' => 'route_name',
            'value' => $route_name,
            'placeholder' => 'ชื่อเส้นทาง',
            'class' => 'form-control text-center'
        );

        $i_date_thai = array(
            'type' => 'type',
            'name' => 'date_thai',
            'value' => $this->m_datetime->getDateThaiString(),
            'placeholder' => 'วันที่',
            'class' => 'form-control text-center'
        );

        $i_RID = array(
            'type' => 'hidden',
            'name' => 'RID',
            'value' => $rid,
            'placeholder' => 'รหัสเส้นทาง',
            'class' => 'form-control'
        );

        $i_Date = array(
            'type' => 'hidden',
            'name' => 'Date',
            'value' => $this->m_datetime->getDateToday(),
            'placeholder' => 'วันที่',
            'class' => 'form-control'
        );

        $i_ScheduleNote = array(
            'type' => 'text',
            'name' => 'ScheduleNote',
            'rows' => '3',
            'placeholder' => 'หมายเหตุ',
            'class' => 'form-control'
        );

        $time = 30 * 60; //30 minutes
        $time_start = ceil((time() + $time) / 300) * 300;
        $temp = 0;
        $i_TimeDepart[0] = "เลือกเวลา";
        for ($n = 1; $n < 30; $n++) {
            $t = date('H:i', strtotime("+$temp minutes", $time_start));
            $i_TimeDepart[$t] = $t;
            $temp +=5;
        }
        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';
        $form_search_add = array(
            'form' => form_open("schedule/add/$rcode/$vtid/$rid", array('id' => 'form_add_schedule')),
            'route_name' => form_input($i_route_name),
            'RID' => form_input($i_RID),
            'TimeDepart' => form_dropdown('TimeDepart', $i_TimeDepart, set_value('TimeDepart'), $dropdown),
            'date_thai' => form_input($i_date_thai),
            'Date' => form_input($i_Date),
            'ScheduleNote' => form_textarea($i_ScheduleNote),
        );

        return $form_search_add;
    }

    public function get_post_form_search() {
        $form_data = array(
            'RCode' => $this->input->post('RCode'),
            'VTID' => $this->input->post('VTID'),
            'RSource' => $this->input->post('RSource'),
            'RDestination' => $this->input->post('RDestination'),
        );
        return $form_data;
    }

    public function get_post_form_add() {
        $form_data = array(
            'RID' => $this->input->post('RID'),
            'TimeDepart' => $this->input->post('TimeDepart'),
            'Date' => $this->input->post('Date'),
            'ScheduleNote' => $this->input->post('ScheduleNote'),
        );
        return $form_data;
    }

    public function validation_form_search() {
        $this->form_validation->set_rules('RCode', 'รหัสเส้นทาง', 'trim|xss_clean');
        $this->form_validation->set_rules('VTID', 'ประเภทรถ', 'trim|xss_clean');
        $this->form_validation->set_rules('RSource', 'ต้นทาง', 'trim|xss_clean');
        $this->form_validation->set_rules('RDestination', 'ปลายทาง', 'trim|xss_clean');
        return TRUE;
    }

    public function validation_form_add() {
        $this->form_validation->set_rules('RID', 'รหัสเส้นทาง', 'trim|xss_clean');
        $this->form_validation->set_rules('TimeDepart', 'เวลา', 'trim|xss_clean|callback_check_dropdown');
        return TRUE;
    }

    public function get_vehicle_types($id = NULL) {
        if ($id != NULL) {
            $this->db->where('VTID', $id);
        }
        $temp = $this->db->get('vehicles_type');
        return $temp->result_array();
    }

}
