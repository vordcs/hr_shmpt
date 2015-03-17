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
            $temp['ref_l_station'] = $temp_count[0]['RID'];
            $temp['l_station'] = $count;

            // วนนับจำนวนเงินของรถคันนั้นๆที่ขายได้จาก $all_seller_in_station
            // เข้าผ่าน RID แล้วก็จะได้มีพนักงานขายตั๋วกี่คน
            // แล้วค่อยไปนับว่าคนนั้นขายใบไหนบ้างที่ตรงกับรถ
            // นับ รายทาง, ฝากของ, อื่นๆ ด้วยเลย
            // นับ รายจ่ายด้วยเลย
            $income = array();
            foreach ($all_seller_in_station as $key => $row_rid) {
                $total_sale = 0;
                $total_onway = 0;
                $total_messenger = 0;
                $total_in_other = 0;

                $total_license = 0;
                $total_gas = 0;
                $total_oil = 0;
                $total_part = 0;
                $total_out_other = 0;

                foreach ($row_rid as $row_seller) {
                    $temp_sale = $row_seller['sale'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID'])
                            $total_sale+=$ticket['PriceSeat'];
                    }
                    $temp_sale = $row_seller['onway'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID'])
                            $total_onway+=$ticket['CostValue'];
                    }
                    $temp_sale = $row_seller['messenger'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID'])
                            $total_messenger+=$ticket['CostValue'];
                    }
                    $temp_sale = $row_seller['in_other'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID'])
                            $total_in_other+=$ticket['CostValue'];
                    }
                    $temp_sale = $row_seller['license'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID'])
                            $total_license+=$ticket['CostValue'];
                    }
                    $temp_sale = $row_seller['gas'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID'])
                            $total_gas+=$ticket['CostValue'];
                    }
                    $temp_sale = $row_seller['oil'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID'])
                            $total_oil+=$ticket['CostValue'];
                    }
                    $temp_sale = $row_seller['part'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID'])
                            $total_part+=$ticket['CostValue'];
                    }
                    $temp_sale = $row_seller['out_other'];
                    foreach ($temp_sale as $ticket) {
                        if ($ticket['VID'] == $vehicle['VID'])
                            $total_out_other+=$ticket['CostValue'];
                    }
                }
                array_push($income, $total_sale);
            }
            $temp['income'] = $income;
            $temp['income']['onway'] = $total_onway;
            $temp['income']['messenger'] = $total_messenger;
            $temp['income']['in_other'] = $total_in_other;

            $temp['outcome']['license'] = $total_license;
            $temp['outcome']['gas'] = $total_gas;
            $temp['outcome']['oil'] = $total_oil;
            $temp['outcome']['part'] = $total_part;
            $temp['outcome']['out_other'] = $total_out_other;

            // คำนวนเงินคงเหลือ
            $balance = 0;
            foreach ($temp['income'] as $row) {
                $balance+=$row;
            }
            foreach ($temp['outcome'] as $row) {
                $balance-=$row;
            }
            $temp['balance'] = $balance;

            // รวมค่าทั้งหมดลง $ans เพื่อใช้เป็น tbody ต่อไป
            array_push($ans, $temp);
        }


//        $ans['num'] = $all_round;
        return $ans;
    }

}
