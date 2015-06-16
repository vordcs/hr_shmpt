<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_cost extends CI_Model {

    private $mCostID = array();

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

    public function insert_cost($form_data) {
        //insert cost data  
        $this->db->insert('cost', $form_data['cost']);
        $form_data['vehicles_has_cost']['CostID'] = $this->db->insert_id();

        //insert vehicles has cost
        if ($this->db->insert('vehicles_has_cost', $form_data['vehicles_has_cost'])) {
            return TRUE;
        } else {
            return FALSE;
        }
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
        $form_data = array(
            'CostDetailID' => $this->input->post('CostDetailID'),
            'CostValue' => $this->input->post('CostValue'),
            'IsCash' => ($this->input->post('IsCash') == 'on') ? '1' : '0',
            'CostNote' => $this->input->post('CostNote'),
        );
        return array('cost' => $form_data);
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
        //Reset mCost
        $this->mCostID = array();

        if ($date == NULL) {
            $date = $this->m_datetime->getDateToday();
        }
        $ans = $this->get_vehicle_types();

        foreach ($ans as $key => $value) {
            $temp = $this->get_route(NULL, $value['VTID']);

            foreach ($temp as $temp_key => $temp_value) {
                $temp[$temp_key]['tab_title'] = $temp[$temp_key]['RCode'] . ' ' . $temp[$temp_key]['RSource'] . ' - ' . $temp[$temp_key]['RDestination'];
                $temp[$temp_key]['date'] = $this->m_datetime->DateThai($date);
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
                $temp[$temp_key]['tbody'] = $this->generate_tbody($temp[$temp_key]['RCode'], $value['VTID'], $date);

                //Generate tbody with cost vehicle from old tbody
                $temp[$temp_key]['tbody'] = $this->generate_tbody_cost_vehicle($temp[$temp_key]['tbody'], $date);

                //Generate tbody
                $temp[$temp_key]['tfoot'] = $this->m_cost->generate_tfoot($temp[$temp_key]['tbody']);
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
        $ans = array();

        // สร้างตัวแปรเก็บค่าพนักงานขายตั๋ว และตรวจว่าวันนั้นขายอะไรไปบ้าง
        // รวม รายทาง, ค่าฝากของ, อื่นๆ ด้วย
        // รวมรายจ่าย ค่าเที่ยว, ค่าก๊าซ, ค่าน้ำมัน, อื่นๆ ด้วย
        $all_seller_in_station = array();
        $this->db->from('t_stations');
        $this->db->where('RCode', $rcode);
        $this->db->where('VTID', $vtid);
        $this->db->where('IsSaleTicket', '1');
        $this->db->order_by('Seq', 'ASC');
        $query = $this->db->get();
        $temp_station = $query->result_array();
        foreach ($temp_station as $row) {
            $this->db->from('sellers as se');
            $this->db->join('employees', 'employees.EID = se.EID');
            $this->db->where('se.SID', $row['SID']);
            $query = $this->db->get();
            $temp_seller = $query->result_array();

            foreach ($temp_seller as $key => $row2) {
                // ตั๋วที่คนนั้นขายทั้งหมด
                $this->db->select('ts.TicketID, ts.RID, ts.VID, ts.PriceSeat');
                $this->db->from('ticket_sale as ts');
                $this->db->where('ts.Seller', $row2['EID']);
                $this->db->where('ts.DateSale', $date);
                $this->db->where('ts.StatusSeat', '1');
                $query = $this->db->get();
                $temp_sale = $query->result_array();
                $temp_seller[$key]['sale'] = $temp_sale;

                // รายทาง, ค่าฝากของ, อื่นๆ ที่คนนั้นรับ
                $this->db->from('cost as co');
                $this->db->join('vehicles_has_cost as vhc', 'vhc.CostID=co.CostID');
                $this->db->where('co.CreateBy', $row2['EID']);
                $this->db->where('co.CostDate', $date);
                $this->db->where('co.CostDetailID', '1'); // 1 = รายทาง
                $query = $this->db->get();
                $temp_sale = $query->result_array();
                $temp_seller[$key]['onway'] = $temp_sale;

                $this->db->from('cost as co');
                $this->db->join('vehicles_has_cost as vhc', 'vhc.CostID=co.CostID');
                $this->db->where('co.CreateBy', $row2['EID']);
                $this->db->where('co.CostDate', $date);
                $this->db->where('co.CostDetailID', '6'); // 6 = ฝากของ
                $query = $this->db->get();
                $temp_sale = $query->result_array();
                $temp_seller[$key]['messenger'] = $temp_sale;

                $this->db->from('cost as co');
                $this->db->join('vehicles_has_cost as vhc', 'vhc.CostID=co.CostID');
                $this->db->where('co.CreateBy', $row2['EID']);
                $this->db->where('co.CostDate', $date);
                $this->db->where('co.CostDetailID', '7'); // 7 = ค่าคิว
                $query = $this->db->get();
                $temp_sale = $query->result_array();
                $temp_seller[$key]['queue_price'] = $temp_sale;

                $this->db->from('cost as co');
                $this->db->join('vehicles_has_cost as vhc', 'vhc.CostID=co.CostID');
                $this->db->where('co.CreateBy', $row2['EID']);
                $this->db->where('co.CostDate', $date);
                $this->db->where('co.CostDetailID', '888'); // 888 = รายรับอื่นๆ
                $query = $this->db->get();
                $temp_sale = $query->result_array();
                $temp_seller[$key]['in_other'] = $temp_sale;

                $this->db->from('cost as co');
                $this->db->join('vehicles_has_cost as vhc', 'vhc.CostID=co.CostID');
                $this->db->where('co.CreateBy', $row2['EID']);
                $this->db->where('co.CostDate', $date);
                $this->db->where('co.CostDetailID', '2'); // 2 = ค่าเที่ยว
                $query = $this->db->get();
                $temp_sale = $query->result_array();
                $temp_seller[$key]['license'] = $temp_sale;

                $this->db->from('cost as co');
                $this->db->join('vehicles_has_cost as vhc', 'vhc.CostID=co.CostID');
                $this->db->where('co.CreateBy', $row2['EID']);
                $this->db->where('co.CostDate', $date);
                $this->db->where('co.CostDetailID', '3'); // 3 = ค่าก๊าซ
                $query = $this->db->get();
                $temp_sale = $query->result_array();
                $temp_seller[$key]['gas'] = $temp_sale;

                $this->db->from('cost as co');
                $this->db->join('vehicles_has_cost as vhc', 'vhc.CostID=co.CostID');
                $this->db->where('co.CreateBy', $row2['EID']);
                $this->db->where('co.CostDate', $date);
                $this->db->where('co.CostDetailID', '4'); // 4 = ค่าน้ำมัน
                $query = $this->db->get();
                $temp_sale = $query->result_array();
                $temp_seller[$key]['oil'] = $temp_sale;

                $this->db->from('cost as co');
                $this->db->join('vehicles_has_cost as vhc', 'vhc.CostID=co.CostID');
                $this->db->where('co.CreateBy', $row2['EID']);
                $this->db->where('co.CostDate', $date);
                $this->db->where('co.CostDetailID', '5'); // 5 = ค่าอะไหล่
                $query = $this->db->get();
                $temp_sale = $query->result_array();
                $temp_seller[$key]['part'] = $temp_sale;

                $this->db->from('cost as co');
                $this->db->join('vehicles_has_cost as vhc', 'vhc.CostID=co.CostID');
                $this->db->where('co.CreateBy', $row2['EID']);
                $this->db->where('co.CostDate', $date);
                $this->db->where('co.CostDetailID', '8'); // 8 = ค่าเปอร์เซ็นต์
                $query = $this->db->get();
                $temp_sale = $query->result_array();
                $temp_seller[$key]['percent_price'] = $temp_sale;

                $this->db->from('cost as co');
                $this->db->join('vehicles_has_cost as vhc', 'vhc.CostID=co.CostID');
                $this->db->where('co.CreateBy', $row2['EID']);
                $this->db->where('co.CostDate', $date);
                $this->db->where('co.CostDetailID', '999'); // 999 = รายจ่ายอื่น
                $query = $this->db->get();
                $temp_sale = $query->result_array();
                $temp_seller[$key]['out_other'] = $temp_sale;
            }

            $all_seller_in_station[$row['SID']] = $temp_seller;
        }


        $this->db->from('vehicles as ve');
        $this->db->join('t_routes_has_vehicles as trhv', 'trhv.VID=ve.VID');
        $this->db->where('trhv.RCode', $rcode);
        $this->db->where('ve.VTID', $vtid);
        $this->db->order_by('ve.VID', 'ASC');
        $query = $this->db->get();
        $temp_vehicle = $query->result_array();

        //สร้างตัวแปรเก็บค่ารอบรถต้นสายปลายสาย ไว้ใช้ในการนับจำนวนรอบรถที่ไปกลับ
        $all_round = array();
        $this->db->from('t_routes as tr');
        $this->db->where('tr.RCode', $rcode);
        $this->db->where('tr.VTID', $vtid);
        $this->db->order_by('tr.StartTime', 'ASC');
        $query = $this->db->get();
        $temp_routes = $query->result_array();
        foreach ($temp_routes as $row) {
            $this->db->select('*,tsd.RID as RID');
            $this->db->from('t_schedules_day as tsd');
            $this->db->join('vehicles_has_schedules as vhs', 'vhs.TSID=tsd.TSID');
            $this->db->where('tsd.Date', $date);
            $this->db->where('tsd.RID', $row['RID']);
            $this->db->where('tsd.ScheduleStatus', '1');
            $query = $this->db->get();
            $temp_schedules_day = $query->result_array();
            $all_round[$row['RID']] = $temp_schedules_day;
        }



        foreach ($temp_vehicle as $vehicle) {
            $temp = array();
            $temp['ref_vid'] = $vehicle['VID'];
            $temp['carnum'] = $vehicle['VCode'];

            // ตรวจจำนวนรอบรถจาก $all_round
            // f_station l_station
            // จากการใช้ array pointer reset next
            reset($all_round);
            $temp_count = current($all_round);

            // ตรวจสอบถ้ารถนั้นไม่มีตารางเดินรถ TSID ก็ไม่ต้องทำ
            if (count($temp_count) == 0) {
                return $ans;
            }

            $count = 0;
            foreach ($temp_count as $row) {
                if ($row['VID'] == $vehicle['VID']) {
                    $count++;
                }
            }
            $temp['ref_f_station'] = $temp_count[0]['RID'];
            $temp['f_station'] = $count;

            $temp_count = next($all_round);
            $count = 0;
            foreach ($temp_count as $row) {
                if ($row['VID'] == $vehicle['VID']) {
                    $count++;
                }
            }

            if (isset($temp_count[0]))
                $temp['ref_l_station'] = $temp_count[0]['RID'];
            $temp['l_station'] = $count;

            // วนนับจำนวนเงินของรถคันนั้นๆที่ขายได้จาก $all_seller_in_station
            // เข้าผ่าน RID แล้วก็จะได้มีพนักงานขายตั๋วกี่คน
            // แล้วค่อยไปนับว่าคนนั้นขายใบไหนบ้างที่ตรงกับรถ
            // นับ รายทาง, ฝากของ, อื่นๆ ด้วยเลย
            // นับ รายจ่ายด้วยเลย
            $income = array();
            $onway = array();
            $messenger = array();
            $queue_price = array();
            $in_other = array();
            $license = array();
            $gas = array();
            $oil = array();
            $part = array();
            $percent_price = array();
            $out_other = array();

            $all_onway = array();
            $all_messenger = array();
            $all_in_other = array();
            $all_license = array();
            $all_gas = array();
            $all_oil = array();
            $all_part = array();
            $all_out_other = array();

            //รวมรายรับรายจ่ายอื่นๆ นอกจากค่าตั๋ว
            $total_onway = 0;
            $total_messenger = 0;
            $total_queue_price = 0;
            $total_in_other = 0;

            $total_license = 0;
            $total_gas = 0;
            $total_oil = 0;
            $total_part = 0;
            $total_percent_price = 0;
            $total_out_other = 0;

            foreach ($all_seller_in_station as $key => $row_rid) {
                $total_sale = 0;

                $temp_name = array();
                foreach ($row_rid as $row_seller) {
                    //Temp
                    $temp_data_sale = array(
                        'EID' => $row_seller['EID'],
                        'name' => $row_seller['Title'] . $row_seller['FirstName'] . ' ' . $row_seller['LastName'],
                        'price' => 0
                    );
                    $temp_data_onway = array(
                        'EID' => $row_seller['EID'],
                        'name' => $row_seller['Title'] . $row_seller['FirstName'] . ' ' . $row_seller['LastName'],
                        'price' => 0
                    );
                    $temp_data_messenger = array(
                        'EID' => $row_seller['EID'],
                        'name' => $row_seller['Title'] . $row_seller['FirstName'] . ' ' . $row_seller['LastName'],
                        'price' => 0
                    );
                    $temp_data_queue_price = array(
                        'EID' => $row_seller['EID'],
                        'name' => $row_seller['Title'] . $row_seller['FirstName'] . ' ' . $row_seller['LastName'],
                        'price' => 0
                    );
                    $temp_data_in_other = array(
                        'EID' => $row_seller['EID'],
                        'name' => $row_seller['Title'] . $row_seller['FirstName'] . ' ' . $row_seller['LastName'],
                        'price' => 0
                    );
                    $temp_data_license = array(
                        'EID' => $row_seller['EID'],
                        'name' => $row_seller['Title'] . $row_seller['FirstName'] . ' ' . $row_seller['LastName'],
                        'price' => 0
                    );
                    $temp_data_gas = array(
                        'EID' => $row_seller['EID'],
                        'name' => $row_seller['Title'] . $row_seller['FirstName'] . ' ' . $row_seller['LastName'],
                        'price' => 0
                    );
                    $temp_data_oil = array(
                        'EID' => $row_seller['EID'],
                        'name' => $row_seller['Title'] . $row_seller['FirstName'] . ' ' . $row_seller['LastName'],
                        'price' => 0
                    );
                    $temp_data_part = array(
                        'EID' => $row_seller['EID'],
                        'name' => $row_seller['Title'] . $row_seller['FirstName'] . ' ' . $row_seller['LastName'],
                        'price' => 0
                    );
                    $temp_data_percent_price = array(
                        'EID' => $row_seller['EID'],
                        'name' => $row_seller['Title'] . $row_seller['FirstName'] . ' ' . $row_seller['LastName'],
                        'price' => 0
                    );
                    $temp_data_out_other = array(
                        'EID' => $row_seller['EID'],
                        'name' => $row_seller['Title'] . $row_seller['FirstName'] . ' ' . $row_seller['LastName'],
                        'price' => 0
                    );

                    $temp_sale = $row_seller['sale'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID']) {
                            $total_sale+=$ticket['PriceSeat'];
                            $temp_data_sale['price']+=$ticket['PriceSeat'];
                        }
                    }
                    $temp_sale = $row_seller['onway'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID']) {
                            $total_onway+=$ticket['CostValue'];
                            $temp_data_onway['price']+=$ticket['CostValue'];
                            //ทำการจำ CostID ที่เคยนำมาคิดแล้วใส่ mCostID
                            array_push($this->mCostID, $ticket['CostID']);
                        }
                    }
                    $temp_sale = $row_seller['messenger'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID']) {
                            $total_messenger+=$ticket['CostValue'];
                            $temp_data_messenger['price']+=$ticket['CostValue'];
                            //ทำการจำ CostID ที่เคยนำมาคิดแล้วใส่ mCostID
                            array_push($this->mCostID, $ticket['CostID']);
                        }
                    }
                    $temp_sale = $row_seller['queue_price'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID']) {
                            $total_queue_price+=$ticket['CostValue'];
                            $temp_data_queue_price['price']+=$ticket['CostValue'];
                            //ทำการจำ CostID ที่เคยนำมาคิดแล้วใส่ mCostID
                            array_push($this->mCostID, $ticket['CostID']);
                        }
                    }
                    $temp_sale = $row_seller['in_other'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID']) {
                            $total_in_other+=$ticket['CostValue'];
                            $temp_data_in_other['price']+=$ticket['CostValue'];
                            //ทำการจำ CostID ที่เคยนำมาคิดแล้วใส่ mCostID
                            array_push($this->mCostID, $ticket['CostID']);
                        }
                    }
                    $temp_sale = $row_seller['license'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID']) {
                            $total_license+=$ticket['CostValue'];
                            $temp_data_license['price']+=$ticket['CostValue'];
                            //ทำการจำ CostID ที่เคยนำมาคิดแล้วใส่ mCostID
                            array_push($this->mCostID, $ticket['CostID']);
                        }
                    }
                    $temp_sale = $row_seller['gas'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID']) {
                            $total_gas+=$ticket['CostValue'];
                            $temp_data_gas['price']+=$ticket['CostValue'];
                            //ทำการจำ CostID ที่เคยนำมาคิดแล้วใส่ mCostID
                            array_push($this->mCostID, $ticket['CostID']);
                        }
                    }
                    $temp_sale = $row_seller['oil'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID']) {
                            $total_oil+=$ticket['CostValue'];
                            $temp_data_oil['price']+=$ticket['CostValue'];
                            //ทำการจำ CostID ที่เคยนำมาคิดแล้วใส่ mCostID
                            array_push($this->mCostID, $ticket['CostID']);
                        }
                    }
                    $temp_sale = $row_seller['part'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID']) {
                            $total_part+=$ticket['CostValue'];
                            $temp_data_part['price']+=$ticket['CostValue'];
                            //ทำการจำ CostID ที่เคยนำมาคิดแล้วใส่ mCostID
                            array_push($this->mCostID, $ticket['CostID']);
                        }
                    }
                    $temp_sale = $row_seller['percent_price'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID']) {
                            $total_percent_price+=$ticket['CostValue'];
                            $temp_data_percent_price['price']+=$ticket['CostValue'];
                            //ทำการจำ CostID ที่เคยนำมาคิดแล้วใส่ mCostID
                            array_push($this->mCostID, $ticket['CostID']);
                        }
                    }
                    $temp_sale = $row_seller['out_other'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID']) {
                            $total_out_other+=$ticket['CostValue'];
                            $temp_data_out_other['price']+=$ticket['CostValue'];
                            //ทำการจำ CostID ที่เคยนำมาคิดแล้วใส่ mCostID
                            array_push($this->mCostID, $ticket['CostID']);
                        }
                    }
                    array_push($temp_name, $temp_data_sale);
                    array_push($onway, $temp_data_onway);
                    array_push($messenger, $temp_data_messenger);
                    array_push($queue_price, $temp_data_queue_price);
                    array_push($in_other, $temp_data_in_other);
                    array_push($license, $temp_data_license);
                    array_push($gas, $temp_data_gas);
                    array_push($oil, $temp_data_oil);
                    array_push($part, $temp_data_part);
                    array_push($percent_price, $temp_data_percent_price);
                    array_push($out_other, $temp_data_out_other);
                }
                $temp_data = array(
                    'price' => $total_sale,
                    'list' => $temp_name
                );
                array_push($income, $temp_data);
            }
            $all_onway = array(
                'price' => $total_onway,
                'list' => $onway
            );
            $all_messenger = array(
                'price' => $total_messenger,
                'list' => $messenger
            );
            $all_queue_price = array(
                'price' => $total_queue_price,
                'list' => $queue_price
            );
            $all_in_other = array(
                'price' => $total_in_other,
                'list' => $in_other
            );
            $all_license = array(
                'price' => $total_license,
                'list' => $license
            );
            $all_gas = array(
                'price' => $total_gas,
                'list' => $gas
            );
            $all_oil = array(
                'price' => $total_oil,
                'list' => $oil
            );
            $all_part = array(
                'price' => $total_part,
                'list' => $part
            );
            $all_percent_price = array(
                'price' => $total_percent_price,
                'list' => $percent_price
            );
            $all_out_other = array(
                'price' => $total_out_other,
                'list' => $out_other
            );

            $temp['income'] = $income;
            $temp['income']['onway'] = $all_onway;
            $temp['income']['messenger'] = $all_messenger;
            $temp['income']['queue_price'] = $all_queue_price;
            $temp['income']['in_other'] = $all_in_other;

            $temp['outcome']['license'] = $all_license;
            $temp['outcome']['gas'] = $all_gas;
            $temp['outcome']['oil'] = $all_oil;
            $temp['outcome']['part'] = $all_part;
            $temp['outcome']['percent_price'] = $all_percent_price;
            $temp['outcome']['out_other'] = $all_out_other;

            // คำนวนเงินคงเหลือ
            $balance = 0;
            foreach ($temp['income'] as $row) {
                $balance+=$row['price'];
            }
            foreach ($temp['outcome'] as $row) {
                $balance-=$row['price'];
            }
            $temp['balance'] = $balance;

            // รวมค่าทั้งหมดลง $ans เพื่อใช้เป็น tbody ต่อไป
            array_push($ans, $temp);
        }


//        $ans['num'] = $all_seller_in_station;
        return $ans;
    }

    function generate_tbody_cost_vehicle($old_tbody, $date) {
        foreach ($old_tbody as $key => $row) {
            $temp = $this->check_income_by_VID($row['ref_vid'], $date);
            foreach ($temp as $row2) {
                //รายรับ array index = income
                if ($row2['CostTypeID'] == '1') {
                    if ($row2['CostDetailID'] == '1') {
                        //รายทาง onway
                        if (isset($old_tbody[$key]['income']['onway']['price'])) {
                            $old_tbody[$key]['income']['onway']['price'] +=$row2['CostValue'];
                        } else {
                            $old_tbody[$key]['income']['onway']['price'] = $row2['CostValue'];
                        }
                        $temp_array = array(
                            'EID' => $row2['EID'],
                            'name' => $row2['Title'] . $row2['FirstName'] . ' ' . $row2['LastName'],
                            'price' => $row2['CostValue'],
                        );
                        $flag_push = -1;
                        foreach ($old_tbody[$key]['income']['onway']['list'] as $key2 => $row3) {
                            if ($row3['EID'] == $row2['EID']) {
                                $flag_push = $key2;
                                break;
                            }
                        }
                        if ($flag_push == -1) {
                            array_push($old_tbody[$key]['income']['onway']['list'], $temp_array);
                        } else {
                            $old_tbody[$key]['income']['onway']['list'][$flag_push]['price']+=$row2['CostValue'];
                        }
                    } elseif ($row2['CostDetailID'] == '6') {
                        //ค่าของฝาก messenger
                        if (isset($old_tbody[$key]['income']['messenger']['price'])) {
                            $old_tbody[$key]['income']['messenger']['price'] +=$row2['CostValue'];
                        } else {
                            $old_tbody[$key]['income']['messenger']['price'] = $row2['CostValue'];
                        }
                        $temp_array = array(
                            'EID' => $row2['EID'],
                            'name' => $row2['Title'] . $row2['FirstName'] . ' ' . $row2['LastName'],
                            'price' => $row2['CostValue'],
                        );
                        $flag_push = -1;
                        foreach ($old_tbody[$key]['income']['messenger']['list'] as $key2 => $row3) {
                            if ($row3['EID'] == $row2['EID']) {
                                $flag_push = $key2;
                                break;
                            }
                        }
                        if ($flag_push == -1) {
                            array_push($old_tbody[$key]['income']['messenger']['list'], $temp_array);
                        } else {
                            $old_tbody[$key]['income']['messenger']['list'][$flag_push]['price']+=$row2['CostValue'];
                        }
                    } elseif ($row2['CostDetailID'] == '7') {
                        //ค่าคิว queue_price
                        if (isset($old_tbody[$key]['income']['queue_price']['price'])) {
                            $old_tbody[$key]['income']['queue_price']['price'] +=$row2['CostValue'];
                        } else {
                            $old_tbody[$key]['income']['queue_price']['price'] = $row2['CostValue'];
                        }
                        $temp_array = array(
                            'EID' => $row2['EID'],
                            'name' => $row2['Title'] . $row2['FirstName'] . ' ' . $row2['LastName'],
                            'price' => $row2['CostValue'],
                        );
                        $flag_push = -1;
                        foreach ($old_tbody[$key]['income']['queue_price']['list'] as $key2 => $row3) {
                            if ($row3['EID'] == $row2['EID']) {
                                $flag_push = $key2;
                                break;
                            }
                        }
                        if ($flag_push == -1) {
                            array_push($old_tbody[$key]['income']['queue_price']['list'], $temp_array);
                        } else {
                            $old_tbody[$key]['income']['queue_price']['list'][$flag_push]['price']+=$row2['CostValue'];
                        }
                    } elseif ($row2['CostDetailID'] == '888') {
                        //อื่นๆ in_other
                        if (isset($old_tbody[$key]['income']['in_other']['price'])) {
                            $old_tbody[$key]['income']['in_other']['price'] +=$row2['CostValue'];
                        } else {
                            $old_tbody[$key]['income']['in_other']['price'] = $row2['CostValue'];
                        }
                        $temp_array = array(
                            'EID' => $row2['EID'],
                            'name' => $row2['Title'] . $row2['FirstName'] . ' ' . $row2['LastName'],
                            'price' => $row2['CostValue'],
                        );
                        $flag_push = -1;
                        foreach ($old_tbody[$key]['income']['in_other']['list'] as $key2 => $row3) {
                            if ($row3['EID'] == $row2['EID']) {
                                $flag_push = $key2;
                                break;
                            }
                        }
                        if ($flag_push == -1) {
                            array_push($old_tbody[$key]['income']['in_other']['list'], $temp_array);
                        } else {
                            $old_tbody[$key]['income']['in_other']['list'][$flag_push]['price']+=$row2['CostValue'];
                        }
                    }
                    ////// ส่วนของรายจ่าย //////
                } else {
                    //รายจ่าย array index = outcome
                    if ($row2['CostDetailID'] == '2') {
                        //ค่าเที่ยว license
                        if (isset($old_tbody[$key]['outcome']['license']['price'])) {
                            $old_tbody[$key]['outcome']['license']['price'] +=$row2['CostValue'];
                        } else {
                            $old_tbody[$key]['outcome']['license']['price'] = $row2['CostValue'];
                        }
                        $temp_array = array(
                            'EID' => $row2['EID'],
                            'name' => $row2['Title'] . $row2['FirstName'] . ' ' . $row2['LastName'],
                            'price' => $row2['CostValue'],
                        );
                        $flag_push = -1;
                        foreach ($old_tbody[$key]['outcome']['license']['list'] as $key2 => $row3) {
                            if ($row3['EID'] == $row2['EID']) {
                                $flag_push = $key2;
                                break;
                            }
                        }
                        if ($flag_push == -1) {
                            array_push($old_tbody[$key]['outcome']['license']['list'], $temp_array);
                        } else {
                            $old_tbody[$key]['outcome']['license']['list'][$flag_push]['price']+=$row2['CostValue'];
                        }
                    } elseif ($row2['CostDetailID'] == '3') {
                        //ค่าก๊าซ gas
                        if (isset($old_tbody[$key]['outcome']['gas']['price'])) {
                            $old_tbody[$key]['outcome']['gas']['price'] +=$row2['CostValue'];
                        } else {
                            $old_tbody[$key]['outcome']['gas']['price'] = $row2['CostValue'];
                        }
                        $temp_array = array(
                            'EID' => $row2['EID'],
                            'name' => $row2['Title'] . $row2['FirstName'] . ' ' . $row2['LastName'],
                            'price' => $row2['CostValue'],
                        );
                        $flag_push = -1;
                        foreach ($old_tbody[$key]['outcome']['gas']['list'] as $key2 => $row3) {
                            if ($row3['EID'] == $row2['EID']) {
                                $flag_push = $key2;
                                break;
                            }
                        }
                        if ($flag_push == -1) {
                            array_push($old_tbody[$key]['outcome']['gas']['list'], $temp_array);
                        } else {
                            $old_tbody[$key]['outcome']['gas']['list'][$flag_push]['price']+=$row2['CostValue'];
                        }
                    } elseif ($row2['CostDetailID'] == '4') {
                        //ค่านำมัน oil
                        if (isset($old_tbody[$key]['outcome']['oil']['price'])) {
                            $old_tbody[$key]['outcome']['oil']['price'] +=$row2['CostValue'];
                        } else {
                            $old_tbody[$key]['outcome']['oil']['price'] = $row2['CostValue'];
                        }
                        $temp_array = array(
                            'EID' => $row2['EID'],
                            'name' => $row2['Title'] . $row2['FirstName'] . ' ' . $row2['LastName'],
                            'price' => $row2['CostValue'],
                        );
                        $flag_push = -1;
                        foreach ($old_tbody[$key]['outcome']['oil']['list'] as $key2 => $row3) {
                            if ($row3['EID'] == $row2['EID']) {
                                $flag_push = $key2;
                                break;
                            }
                        }
                        if ($flag_push == -1) {
                            array_push($old_tbody[$key]['outcome']['oil']['list'], $temp_array);
                        } else {
                            $old_tbody[$key]['outcome']['oil']['list'][$flag_push]['price']+=$row2['CostValue'];
                        }
                    } elseif ($row2['CostDetailID'] == '5') {
                        //ค่าอะไหล่ part
                        if (isset($old_tbody[$key]['outcome']['part']['price'])) {
                            $old_tbody[$key]['outcome']['part']['price'] +=$row2['CostValue'];
                        } else {
                            $old_tbody[$key]['outcome']['part']['price'] = $row2['CostValue'];
                        }
                        $temp_array = array(
                            'EID' => $row2['EID'],
                            'name' => $row2['Title'] . $row2['FirstName'] . ' ' . $row2['LastName'],
                            'price' => $row2['CostValue'],
                        );
                        $flag_push = -1;
                        foreach ($old_tbody[$key]['outcome']['part']['list'] as $key2 => $row3) {
                            if ($row3['EID'] == $row2['EID']) {
                                $flag_push = $key2;
                                break;
                            }
                        }
                        if ($flag_push == -1) {
                            array_push($old_tbody[$key]['outcome']['part']['list'], $temp_array);
                        } else {
                            $old_tbody[$key]['outcome']['part']['list'][$flag_push]['price']+=$row2['CostValue'];
                        }
                    } elseif ($row2['CostDetailID'] == '8') {
                        //เปอร์เซนต์ percent_price
                        if (isset($old_tbody[$key]['outcome']['percent_price']['price'])) {
                            $old_tbody[$key]['outcome']['percent_price']['price'] +=$row2['CostValue'];
                        } else {
                            $old_tbody[$key]['outcome']['percent_price']['price'] = $row2['CostValue'];
                        }
                        $temp_array = array(
                            'EID' => $row2['EID'],
                            'name' => $row2['Title'] . $row2['FirstName'] . ' ' . $row2['LastName'],
                            'price' => $row2['CostValue'],
                        );
                        $flag_push = -1;
                        foreach ($old_tbody[$key]['outcome']['percent_price']['list'] as $key2 => $row3) {
                            if ($row3['EID'] == $row2['EID']) {
                                $flag_push = $key2;
                                break;
                            }
                        }
                        if ($flag_push == -1) {
                            array_push($old_tbody[$key]['outcome']['percent_price']['list'], $temp_array);
                        } else {
                            $old_tbody[$key]['outcome']['percent_price']['list'][$flag_push]['price']+=$row2['CostValue'];
                        }
                    } elseif ($row2['CostDetailID'] == '999') {
                        //อื่นๆ out_other
                        if (isset($old_tbody[$key]['outcome']['out_other']['price'])) {
                            $old_tbody[$key]['outcome']['out_other']['price'] +=$row2['CostValue'];
                        } else {
                            $old_tbody[$key]['outcome']['out_other']['price'] = $row2['CostValue'];
                        }
                        $temp_array = array(
                            'EID' => $row2['EID'],
                            'name' => $row2['Title'] . $row2['FirstName'] . ' ' . $row2['LastName'],
                            'price' => $row2['CostValue'],
                        );
                        $flag_push = -1;
                        foreach ($old_tbody[$key]['outcome']['out_other']['list'] as $key2 => $row3) {
                            if ($row3['EID'] == $row2['EID']) {
                                $flag_push = $key2;
                                break;
                            }
                        }
                        if ($flag_push == -1) {
                            array_push($old_tbody[$key]['outcome']['out_other']['list'], $temp_array);
                        } else {
                            $old_tbody[$key]['outcome']['out_other']['list'][$flag_push]['price']+=$row2['CostValue'];
                        }
                    }
                }

                // Caculate new balance
                $balance = 0;
                $balance += $old_tbody[$key]['income']['0']['price'];
                $balance += $old_tbody[$key]['income']['1']['price'];
                $balance += $old_tbody[$key]['income']['onway']['price'];
                $balance += $old_tbody[$key]['income']['messenger']['price'];
                $balance += $old_tbody[$key]['income']['queue_price']['price'];
                $balance += $old_tbody[$key]['income']['in_other']['price'];

                $balance -= $old_tbody[$key]['outcome']['license']['price'];
                $balance -= $old_tbody[$key]['outcome']['gas']['price'];
                $balance -= $old_tbody[$key]['outcome']['oil']['price'];
                $balance -= $old_tbody[$key]['outcome']['part']['price'];
                $balance -= $old_tbody[$key]['outcome']['percent_price']['price'];
                $balance -= $old_tbody[$key]['outcome']['out_other']['price'];

                $old_tbody[$key]['balance'] = $balance;
            }
        }

        return $old_tbody;
    }

    function generate_tfoot($tbody) {
        // 0 1 2 เป็นรหัสรถกับต้นทางปลายทาง
        $ans = array();
        $ans[0] = NULL;
        $ans[1] = NULL;
        $ans[2] = NULL;

        $index = 3;
        foreach ($tbody as $key => $row) {

            //รวม income ของทุกคัน
            foreach ($row['income'] as $row2) {
                if (isset($ans[$index]))
                    $ans[$index++] += $row2['price'];
                else
                    $ans[$index++] = $row2['price'];
            }

            //รวม outcome ของทุกคัน
            foreach ($row['outcome'] as $row2) {
                if (isset($ans[$index]))
                    $ans[$index++] += $row2['price'];
                else
                    $ans[$index++] = $row2['price'];
            }

            //รวม balance ของทุกคัน
            if (isset($ans[$index]))
                $ans[$index++] += $row['balance'];
            else
                $ans[$index++] = $row['balance'];

            $index = 3;
        }

        return $ans;
    }

    function check_income_by_VID($VID, $date) {
        $sql_select = 'vhc.CostID,cost.CostDate,cost.CostTypeID,cost.CostDetailID,cost.CostValue,';
        $sql_select.='cost_detail.CostDetail,cost.CostNote,cost.CreateBy,employees.EID,';
        $sql_select.='employees.Title,employees.FirstName,employees.LastName';
        $this->db->select($sql_select);
        $this->db->from('vehicles_has_cost as vhc');
        $this->db->join('cost', 'cost.CostID = vhc.CostID', 'left');
        $this->db->join('cost_detail', 'cost_detail.CostDetailID = cost.CostDetailID', 'left');
        $this->db->join('employees', 'cost.CreateBy = employees.EID', 'left');
        $this->db->where('vhc.VID', $VID);
        $this->db->where('cost.CostDate', $date);
        $query = $this->db->get();
//        return $this->db->last_query(); 
        $ans = $query->result_array();
        $temp_costID = $this->mCostID;
        foreach ($ans as $key => $row) {
            $flag_have = FALSE;
            foreach ($temp_costID as $CostID) {
                if ($row['CostID'] == $CostID) {
                    $flag_have = TRUE;
                    break;
                }
            }
            if ($flag_have)
                unset($ans[$key]);
        }
//        return $this->mCostID;
//        return $query->result_array();
        return $ans;
    }

}
