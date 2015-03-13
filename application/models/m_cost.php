<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_cost extends CI_Model {

    public function get_cost($cid = null, $ctid = NULL) {
        $this->db->join('cost_type', 'cost_type.CostTypeID = cost.CostTypeID');
        $this->db->join('cost_detail', 'cost_detail.CostDetailID = cost.CostDetailID');
        $this->db->join('vehicles_has_cost', 'vehicles_has_cost.CostID = cost.CostID');
        if ($cid != NULL) {
            $this->db->where('cost.CostID', $cid);
        }
        if ($ctid != NULL) {
            $this->db->where('cost.CostTypeID', $ctid);
        }
        $this->db->where('cost.CostDate', $this->m_datetime->getDateToday());
        $query = $this->db->get('cost');
        return $query->result_array();
    }

    public function get_cost_type($id = NULL) {

        if ($id != NULL) {
            $this->db->where('CostTypeID', $id);
        }
        $query = $this->db->get('cost_type');
        return $query->result_array();
    }

    public function get_cost_detail($id = NULL) {
        if ($id != NULL) {
            $this->db->where('CostTypeID', $id);
        }
        $query = $this->db->get('cost_detail');
        return $query->result_array();
    }

    function get_vehicle($vcode = NULL, $vtid = NULL) {
        $this->db->join('vehicles_type', 'vehicles.VTID = vehicles_type.VTID');
        $this->db->join('t_routes_has_vehicles', 'vehicles.VID = t_routes_has_vehicles.VID', 'left');
        if ($vcode != NULL) {
            $this->db->where('vehicles.VCode', $vcode);
        }
        if ($vtid != NULL) {
            $this->db->where('vehicles_type.VTID', $vtid);
        }

        $query = $this->db->get('vehicles');
        return $query->result_array();
    }

    public function get_vehicle_types($id = NULL) {
        if ($id != NULL)
            $this->db->where('VTID', $id);
        $temp = $this->db->get('vehicles_type');
        return $temp->result_array();
    }

    public function get_route($rcode = NULL, $vtid = NULL) {

        $this->db->join('vehicles_type', 'vehicles_type.VTID = t_routes.VTID');

        if ($rcode != NULL)
            $this->db->where('RCode', $rcode);
        if ($vtid != NULL)
            $this->db->where('t_routes.VTID', $vtid);

//        $this->db->where('StartPoint', 'S');
        $this->db->group_by(array('RCode', 't_routes.VTID'));
        $query = $this->db->get('t_routes');
        return $query->result_array();
    }

    public function search_cost($form = NULL, $to = NULL) {

        $this->db->join('cost_type', 'cost_type.CostTypeID = cost.CostTypeID');
        $this->db->join('cost_detail', 'cost_detail.CostDetailID = cost.CostDetailID');
        $this->db->join('vehicles_has_cost', 'vehicles_has_cost.CostID = cost.CostID');

        if ($form != NULL && $to == NULL) {
            $this->db->where('cost.CostDate', $this->m_datetime->setDateFomat($form));
        }
        if ($form != NULL && $to != NULL) {
            $this->db->where('cost.CostDate >=', $this->m_datetime->setDateFomat($form));
            $this->db->where('cost.CostDate <=', $this->m_datetime->setDateFomat($to));
        }
        $query = $this->db->get('cost');
        return $query->result_array();
    }

    public function insert_cost($data) {

//      insert cost data  
        $this->db->insert('cost', $data['data_cost']);
        $cost_id = $this->db->insert_id();

//      insert vehicles has cost
        $vehicle_cost = array(
            'CostID' => $cost_id,
            'VID' => $data['data_vehicle'][0]['VID'],
        );

        $this->db->insert('vehicles_has_cost', $vehicle_cost);

        $rs = $this->get_cost($cost_id);
        $rs[0]['VTID'] = $data['data_vehicle'][0]['VTID'];
        $rs[0]['RCode'] = $data['data_vehicle'][0]['RCode'];
        $rs[0]['VTDescription'] = $data['data_vehicle'][0]['VTDescription'];

        return $rs;
    }

    public function set_form_add($ctid = NULL, $vtid = NULL) {
        $i_CostDetailID[0] = 'เลือกรายการ';
        foreach ($this->get_cost_detail($ctid) as $value) {
            $i_CostDetailID[$value['CostDetailID']] = $value['CostDetail'];
        }
        $i_CostDate = array(
            'name' => 'CostDate',
            'value' => (set_value('CostDate') == NULL) ? $this->m_datetime->getDateTodayTH() : set_value('CostDate'),
            'placeholder' => 'วันที่ทำรายการ',
            'class' => 'form-control datepicker');
        $i_VTID[0] = 'เลือกประเภทรถ';
        foreach ($this->get_vehicle_types() as $value) {
            $i_VTID[$value['VTID']] = $value['VTDescription'];
        }
        $i_VCode = array(
            'name' => 'VCode',
            'value' => set_value('VCode'),
            'placeholder' => 'เบอร์รถ',
            'class' => 'form-control');
        $i_CostValue = array(
            'name' => 'CostValue',
            'value' => set_value('CostValue'),
            'placeholder' => 'จำนวนเงิน',
            'class' => 'form-control');
        $i_CostNote = array(
            'name' => 'CostNote',
            'value' => set_value('CostNote'),
            'placeholder' => 'หมายเหตุ',
            'rows' => '3',
            'class' => 'form-control');

        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';

        $form_add = array(
            'form' => form_open('cost/add/' . $ctid, array('class' => 'form-horizontal', 'id' => 'form_cost')),
            'CostDate' => form_input($i_CostDate),
            'CostDetailID' => form_dropdown('CostDetailID', $i_CostDetailID, set_value('CostDetailID'), $dropdown),
            'VTID' => form_dropdown('VTID', $i_VTID, (set_value('VTID') == NULL) ? $vtid : set_value('VTID'), $dropdown),
            'VCode' => form_input($i_VCode),
            'CostValue' => form_input($i_CostValue),
            'CostNote' => form_textarea($i_CostNote),
        );
        return $form_add;
    }

    public function set_form_search($rcode = NULL, $vtid = NULL) {
        //ข้อมูลเส้นทาง
        $i_RCode[0] = 'เส้นทางทั้งหมด';
        foreach ($this->get_route() as $value) {
            $i_RCode[$value['RCode']] = $value['RCode'] . ' ' . $value['RSource'] . ' - ' . $value['RDestination'];
        }

        $i_VTID[0] = 'ประเภทรถทั้งหมด';
        foreach ($this->get_vehicle_types() as $value) {
            $i_VTID[$value['VTID']] = $value['VTDescription'];
        }

        $i_VCode = array(
            'name' => 'VCode',
            'value' => set_value('VCode'),
            'placeholder' => 'เบอร์รถ',
            'class' => 'form-control');

        $i_DateForm = array(
            'name' => 'DateForm',
            'value' => set_value('DateForm'),
            'placeholder' => 'วันที่',
            'class' => 'form-control datepicker');

        $i_DateTo = array(
            'name' => 'DateTo',
            'value' => set_value('DateTo'),
            'placeholder' => 'ถึงวันที่',
            'class' => 'form-control datepicker');

        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';

        $v = '';
        if ($rcode != NULL && $vtid != NULL) {
            $v = "view/$rcode/$vtid";
        }

        $form_search = array(
            'form' => form_open("cost/$v", array('role=' => 'form', 'id' => 'form_search_cost')),
            'RCode' => form_dropdown('RCode', $i_RCode, set_value('RCode'), $dropdown),
            'VTID' => form_dropdown('VTID', $i_VTID, set_value('VTID'), 'class="selecter_3" '),
            'VCode' => form_input($i_VCode),
            'DateForm' => form_input($i_DateForm),
            'DateTo' => form_input($i_DateTo),
        );

        return $form_search;
    }

    public function validation_form_add() {
        $this->form_validation->set_rules('CostDetailID', 'รายการ', 'trim|required|xss_clean|callback_check_dropdown');
        $this->form_validation->set_rules('CostDate', 'วันที่ทำรายการ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('VTID', 'ประเภทรถ', 'trim|required|xss_clean|callback_check_dropdown');
        $this->form_validation->set_rules('VCode', 'เบอร์รถ', 'trim|required|xss_clean|callback_check_vcode');
        $this->form_validation->set_rules('CostValue', 'จำนวนเงิน', 'trim|required|xss_clean');
        $this->form_validation->set_rules('CostNote', 'หมายเหตุ', 'trim|xss_clean');
        return TRUE;
    }

    public function varlidation_form_search() {
        $this->form_validation->set_rules('RCode', 'เส้นทาง', 'trim|xss_clean');
        $this->form_validation->set_rules('VTID', 'ประเภทรถ', 'trim|xss_clean');
        $this->form_validation->set_rules('VCode', 'เบอร์รถ', 'trim|xss_clean');
        $this->form_validation->set_rules('DateForm', 'จากวันที่', 'trim|xss_clean');
        $this->form_validation->set_rules('DateTo', 'ถึงวันที่', 'trim|xss_clean');

        return TRUE;
    }

    public function get_post_form_add($ctid) {
        //ข้อมูลค่าใช้จ่าย

        $data_cost = array(
            'CostTypeID' => $ctid,
            'CostDetailID' => $this->input->post('CostDetailID'),
            'CostDate' => $this->m_datetime->setDateFomat($this->input->post('CostDate')),
            'CostValue' => $this->input->post('CostValue'),
            'CostNote' => $this->input->post('CostNote'),
        );
        $vcode = $this->input->post('VCode');
        $vtid = $this->input->post('VTID');
        $form_data = array(
            'data_cost' => $data_cost,
            'data_vehicle' => $this->get_vehicle($vcode, $vtid),
        );

        return $form_data;
    }

    public function get_schedule($date = NULL, $rcode = NULL, $vtid = NULL, $rid = NULL) {
        $this->db->select('*,t_schedules_day.RID as RID');
        $this->db->join('t_routes', ' t_schedules_day.RID=t_routes.RID', 'left');
        $this->db->join('vehicles_has_schedules', 't_schedules_day.TSID = vehicles_has_schedules.TSID', 'left');
        $this->db->join('vehicles', 'vehicles_has_schedules.VID = vehicles.VID', 'left');
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
            $this->db->where('t_routes.RID', $rid);
        }
        $this->db->order_by('TimeDepart', 'ASC');
        $query_schedule = $this->db->get("t_schedules_day");
        return $query_schedule->result_array();
    }

    /*
     * **** Check cost ****
     * 
     * ****Result ****
     * result
     * 
     */

    function check_cost($date = NULL) {
        if ($date == NULL) {
            $date = $this->m_datetime->getDateToday();
        }
        $ans = $this->get_vehicle_types();

        foreach ($ans as $key => $value) {
            $temp = $this->get_route(NULL, $value['VTID']);

            foreach ($temp as $temp_key => $temp_value) {
                $temp[$temp_key]['tab_title'] = $temp[$temp_key]['RCode'] . ' ' . $temp[$temp_key]['RSource'] . ' - ' . $temp[$temp_key]['RDestination'];
                $temp[$temp_key]['date'] = $this->m_datetime->DateThaiToDay();
                unset($temp[$temp_key]['RID']);
                unset($temp[$temp_key]['VTID']);
                unset($temp[$temp_key]['RSource']);
                unset($temp[$temp_key]['RDestination']);
                unset($temp[$temp_key]['StartPoint']);
                unset($temp[$temp_key]['Time']);
                unset($temp[$temp_key]['StartTime']);
                unset($temp[$temp_key]['ScheduleType']);
                unset($temp[$temp_key]['IntervalEachAround']);
                unset($temp[$temp_key]['AroundNumber']);
                unset($temp[$temp_key]['CreateBy']);
                unset($temp[$temp_key]['CreateDate']);
                unset($temp[$temp_key]['UpdateBy']);
                unset($temp[$temp_key]['UpdateDate']);
                unset($temp[$temp_key]['VTDescription']);

                //Generate thead
                $temp[$temp_key]['thead'] = $this->generate_thead($temp[$temp_key]['RCode'], $value['VTID']);

                //Generate tbody
//                $temp[$temp_key]['tbody'] = $this->generate_tbody($temp[$temp_key]['RCode'], $value['VTID'],$date);
            }
            $ans[$key]['line'] = $temp;
        }




        return $ans;
    }

    function generate_thead($rcode, $vtid) {
        $ans = array();

        //Prepare data for use to make thead
        $this->db->from('t_stations');
        $this->db->where('RCode', $rcode);
        $this->db->where('VTID', $vtid);
        $this->db->where('IsSaleTicket', '1');
        $this->db->order_by('Seq', 'ASC');
        $query = $this->db->get();
        $temp_station = $query->result_array();

        $this->db->from('cost_detail');
        $this->db->where('CostTypeID', '1');
        $this->db->order_by('CostDetailID', 'ASC');
        $query = $this->db->get();
        $temp_income = $query->result_array();

        $this->db->from('cost_detail');
        $this->db->where('CostTypeID', '2');
        $this->db->order_by('CostDetailID', 'ASC');
        $query = $this->db->get();
        $temp_charge = $query->result_array();

        //Gen car number
        $ans['carnum'] = 'เบอร์รถ';

        //Gen frequencies
        $ans['frequencies'][0] = $temp_station[0]['StationName'];
        $ans['frequencies'][1] = end($temp_station)['StationName'];

        //Gen income
        $temp2 = array();
        foreach ($temp_station as $row) {
            array_push($temp2, $row['StationName']);
        }
        foreach ($temp_income as $row) {
            array_push($temp2, $row['CostDetail']);
        }
        $ans['income'] = $temp2;

        //Gen charge
        $temp3 = array();
        foreach ($temp_charge as $row) {
            array_push($temp3, $row['CostDetail']);
        }
        $ans['charge'] = $temp3;

        //Gen balance
        $ans['balance'] = 'คงเหลือ';


        return $ans;
    }

    function generate_tbody($rcode, $vtid, $date) {
        $date = '2015-02-11';
        $ans = array();

        $this->db->from('vehicles as ve');
        $this->db->join('t_routes_has_vehicles as trhv', 'trhv.VID=ve.VID');
        $this->db->where('trhv.RCode', $rcode);
        $this->db->where('ve.VTID', $vtid);
        $this->db->order_by('ve.VID', 'ASC');
        $query = $this->db->get();
        $temp_vehicle = $query->result_array();

        $this->db->from('t_schedules_day as ts');
        $this->db->join('vehicles_has_schedules as vhs', 'vhs.TSID=ts.TSID ');
        $this->db->join('vehicles as ve', 've.VID=vhs.VID ');
        $this->db->where('ts.Date', $date);
        $query = $this->db->get();
        $temp_schedule = $query->result_array();


        foreach ($temp_vehicle as $vehicle) {
            $temp = array();
            $temp['carnum'] = $vehicle['VCode'];


            array_push($ans, $temp);
        }


        $ans['num'] = count($temp_schedule);
        return $ans;
    }

}
