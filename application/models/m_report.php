<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_report extends CI_Model {

    private $mCostID = array();

    public function set_form_search($mounth = NULL, $year = NULL) {

        //ข้อมูลเส้นทาง
        $i_RCode[0] = 'เส้นทางทั้งหมด';
        foreach ($this->get_route() as $value) {
            $i_RCode[$value['RCode']] = $value['RCode'] . ' ' . $value['RSource'] . ' - ' . $value['RDestination'];
        }

        $i_VTID[0] = 'ประเภทรถทั้งหมด';
        foreach ($this->get_vehicle_types() as $value) {
            $i_VTID[$value['VTID']] = $value['VTDescription'];
        }

        $mounth_th = $this->m_datetime->getMonthThai();
        $i_Mounth[0] = 'เลือกเดือน';
        for ($i = 1; $i < count($mounth_th); $i++) {
            $i_Mounth[$i] = $mounth_th[$i];
        }



        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';

        $form_search_route = array(
            'form' => form_open('report/', array('id' => 'form_search_report')),
            'RCode' => form_dropdown('RCode', $i_RCode, set_value('RCode'), $dropdown),
            'VTID' => form_dropdown('VTID', $i_VTID, set_value('VTID'), $dropdown),
            'Mounth' => form_dropdown('Mounth', $i_Mounth, (set_value('Mounth') == NULL) ? $mounth : set_value('Mounth'), $dropdown),
        );
        return $form_search_route;
    }

    public function set_form_search_mounth($rcode, $vtid, $mounth = NULL, $year = NULL) {
        $mounth_th = $this->m_datetime->getMonthThai();
        $i_Mounth[0] = 'เลือกเดือน';
        for ($i = 1; $i < count($mounth_th); $i++) {
            $i_Mounth[$i] = $mounth_th[$i];
        }

        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';

        $form_search_mounth = array(
            'form' => form_open("report/view/$rcode/$vtid", array('id' => 'form_search_report_mounth')),
            'Mounth' => form_dropdown('Mounth', $i_Mounth, (set_value('Mounth') == NULL) ? $mounth : set_value('Mounth'), $dropdown),
        );

        return $form_search_mounth;
    }

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
//        $this->db->where('cost.CostDate', $this->m_datetime->getDateToday());
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

    //New
    public function all_route() {
        $this->db->from('t_routes');
        $this->db->order_by('RCode', 'ASC');
        $this->db->order_by('VTID', 'ASC');
        $this->db->where('StartPoint', 'S');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_route($rcode = NULL, $vtid = NULL) {
        $this->db->join('vehicles_type', 'vehicles_type.VTID = t_routes.VTID');
        if ($rcode != NULL)
            $this->db->where('RCode', $rcode);
        if ($vtid != NULL)
            $this->db->where('t_routes.VTID', $vtid);
        $this->db->group_by(array('RCode', 't_routes.VTID'));
        $query = $this->db->get('t_routes');
        return $query->result_array();
    }

    public function get_vehicle($rcode = NULL, $vtid = NULL) {
        $this->db->join('vehicles_type', 'vehicles.VTID = vehicles_type.VTID');
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

    public function report_vehicle($rcode, $vtid, $begin_date, $end_date) {
        $ans = array(
            'thead' => array(),
            'tbody' => array(),
            'tfoot' => array(),
        );
        //tfoot
        $total_s = '0';
        $total_d = '0';
        $total_total = '0';
        //Make thead
        $this->db->from('t_stations');
        $this->db->where('RCode', $rcode);
        $this->db->where('VTID', $vtid);
        $this->db->where('IsSaleTicket', '1');
        $this->db->order_by('Seq', 'ASC');
        $query = $this->db->get();
        $temp_station = $query->result_array();
        $temp = array(
            'th' => 'เบอร์รถ'
        );
        array_push($ans['thead'], $temp);
        $temp = array(
            'th' => '<em>ไป</em> ' . $temp_station[0]['StationName']
        );
        array_push($ans['thead'], $temp);
        $temp = array(
            'th' => '<em>ไป</em> ' . end($temp_station)['StationName']
        );
        array_push($ans['thead'], $temp);
        $temp = array(
            'th' => 'รวมรอบ'
        );
        array_push($ans['thead'], $temp);

        //Make tbody
        //Check round
        $where = array(
            'RCode' => $rcode,
            'VTID' => $vtid,
        );
        $query = $this->db->get_where('t_routes', $where);
        $temp = $query->result_array();
        $RID_S = $temp[0]['RID'];
        $RID_D = end($temp)['RID'];

        $this->db->select('*,tsd.RID as RID');
        $this->db->from('t_schedules_day as tsd');
        $this->db->join('vehicles_has_schedules as vhs', 'tsd.TSID=vhs.TSID');
        $this->db->where('tsd.ScheduleStatus', '1');
        $this->db->where('tsd.RID', $RID_S);
        $this->db->where('Date >=', $begin_date);
        $this->db->where('Date <=', $end_date);
        $query = $this->db->get();
        $temp_s = $query->result_array();

        $this->db->select('*,tsd.RID as RID');
        $this->db->from('t_schedules_day as tsd');
        $this->db->join('vehicles_has_schedules as vhs', 'tsd.TSID=vhs.TSID');
        $this->db->where('tsd.ScheduleStatus', '1');
        $this->db->where('tsd.RID', $RID_D);
        $this->db->where('Date >=', $begin_date);
        $this->db->where('Date <=', $end_date);
        $query = $this->db->get();
        $temp_d = $query->result_array();

        $temp_vehicle = $this->get_vehicle($rcode, $vtid);
        foreach ($temp_vehicle as $row) {
            $temp = array(
                'vehicle' => array('VCode' => $row['VCode'], 'VID' => $row['VID']),
                'S' => '0',
                'D' => '0',
                'total' => '0'
            );
            //Count round
            foreach ($temp_s as $key => $row2) {
                if ($row2['VID'] == $row['VID']) {
                    $temp['S'] ++;
                    $temp['total'] ++;
                    $total_s++;
                    $total_total++;
                    unset($temp_s[$key]);
                }
            }
            foreach ($temp_d as $key => $row2) {
                if ($row2['VID'] == $row['VID']) {
                    $temp['D'] ++;
                    $temp['total'] ++;
                    $total_d++;
                    $total_total++;
                    unset($temp_s[$key]);
                }
            }

            array_push($ans['tbody'], $temp);
        }

        //Make tfoot
        $temp = array(
            'th' => 'รวม'
        );
        array_push($ans['tfoot'], $temp);
        $temp = array(
            'th' => $total_s
        );
        array_push($ans['tfoot'], $temp);
        $temp = array(
            'th' => $total_d
        );
        array_push($ans['tfoot'], $temp);
        $temp = array(
            'th' => $total_total
        );
        array_push($ans['tfoot'], $temp);

        return $ans;
    }

    public function report_sale($rcode, $vtid, $begin_date, $end_date) {
        $ans = array(
            'thead' => array(),
            'tbody' => array(),
            'tfoot' => array(),
        );

        //Make thead
        $temp = array(
            'th' => 'รหัสพนังงาน'
        );
        array_push($ans['thead'], $temp);
        $temp = array(
            'th' => 'ชื่อ - นามสกุล'
        );
        array_push($ans['thead'], $temp);
        $temp = array(
            'th' => 'จำนวนตั๋วที่ขาย'
        );
        array_push($ans['thead'], $temp);
        $temp = array(
            'th' => 'รวมเงินที่ขาย'
        );
        array_push($ans['thead'], $temp);

        //Make tbody
        //Temp RID
        $where = array(
            'RCode' => $rcode,
            'VTID' => $vtid,
        );
        $query = $this->db->get_where('t_routes', $where);
        $temp = $query->result_array();
        $RID_S = $temp[0]['RID'];
        $RID_D = end($temp)['RID'];
        $this->db->from('ticket_sale');
        $this->db->where('RID', $RID_S);
        $this->db->where('DateSale >=', $begin_date);
        $this->db->where('DateSale <=', $end_date);
        $query = $this->db->get();
        $temp_sale_s = $query->result_array();
        $this->db->from('ticket_sale');
        $this->db->where('RID', $RID_D);
        $this->db->where('DateSale >=', $begin_date);
        $this->db->where('DateSale <=', $end_date);
        $query = $this->db->get();
        $temp_sale_d = $query->result_array();

        $temp_sale = array_merge($temp_sale_s, $temp_sale_d);
        unset($temp_sale_s);
        unset($temp_sale_d);

        $this->db->from('sellers');
        $this->db->join('employees', 'employees.EID=sellers.EID');
        $this->db->where('RCode', $rcode);
        $this->db->where('VTID', $vtid);
        $query = $this->db->get();
        $temp_seller = $query->result_array();
        foreach ($temp_seller as $row) {
            $temp = array(
                'EID' => $row['EID'],
                'name' => $row['Title'] . $row['FirstName'] . ' ' . $row['LastName'],
                'sale' => '0',
                'price' => '0'
            );
            foreach ($temp_sale as $key => $row2) {
                if ($row2['Seller'] == $row['EID']) {
                    $temp['sale'] ++;
                    $temp['price'] += $row2['PriceSeat'];
                    unset($temp_sale[$key]);
                }
            }

            array_push($ans['tbody'], $temp);
        }

        return $ans;
    }

    public function report_station($rcode, $vtid, $begin_date, $end_date) {
        $ans = array(
            'thead' => array(),
            'tbody' => array(),
            'tfoot' => array(),
        );

        //Make thead
        $this->db->from('t_stations');
        $this->db->where('RCode', $rcode);
        $this->db->where('VTID', $vtid);
        $this->db->order_by('Seq', 'ASC');
        $query = $this->db->get();
        $temp_station = $query->result_array();
        $temp = array(
            'th' => 'เดินทางจาก'
        );
        array_push($ans['thead'], $temp);
        foreach ($temp_station as $row) {
            $temp = array(
                'th' => '<em>ลง</em> ' . $row['StationName'] . '(คน)'
            );
            array_push($ans['thead'], $temp);
        }

        //Make tbody
        //Temp RID
        $where = array(
            'RCode' => $rcode,
            'VTID' => $vtid,
        );
        $query = $this->db->get_where('t_routes', $where);
        $temp = $query->result_array();
        $RID_S = $temp[0]['RID'];
        $RID_S_RSource = $temp[0]['RSource'];
        $RID_D = end($temp)['RID'];
        $RID_D_RSource = end($temp)['RSource'];
        $this->db->from('ticket_sale');
        $this->db->where('RID', $RID_S);
        $this->db->where('DateSale >=', $begin_date);
        $this->db->where('DateSale <=', $end_date);
        $query = $this->db->get();
        $temp_sale_s = $query->result_array();
        $this->db->from('ticket_sale');
        $this->db->where('RID', $RID_D);
        $this->db->where('DateSale >=', $begin_date);
        $this->db->where('DateSale <=', $end_date);
        $query = $this->db->get();
        $temp_sale_d = $query->result_array();

//        $temp_sale = array_merge($temp_sale_s, $temp_sale_d);
//        unset($temp_sale_s);
//        unset($temp_sale_d);

        $temp_body = array(
            'S' => array(),
            'D' => array()
        );

        $temp = array(
            'td' => $RID_S_RSource
        );
        array_push($temp_body['S'], $temp);
        foreach ($temp_station as $row) {
            $temp = array(
                'td' => '0'
            );
            foreach ($temp_sale_s as $key => $row2) {
                if ($row2['DestinationID'] == $row['SID']) {
                    $temp['td'] ++;
                    unset($temp_sale_s[$key]);
                }
            }
            array_push($temp_body['S'], $temp);
        }

        $temp = array(
            'td' => $RID_D_RSource
        );
        array_push($temp_body['D'], $temp);
        foreach ($temp_station as $row) {
            $temp = array(
                'td' => '0'
            );
            foreach ($temp_sale_d as $key => $row2) {
                if ($row2['DestinationID'] == $row['SID']) {
                    $temp['td'] ++;
                    unset($temp_sale_d[$key]);
                }
            }
            array_push($temp_body['D'], $temp);
        }
        $ans['tbody'] = $temp_body;
//        $temp_body

        return $ans;
    }

    function report_vehicle_cost($rcode, $vtid, $begin_date, $end_date) {
        //Reset mCostID
        $this->mCostID = array();

        $ans = array(
            'thead' => $this->generate_thead($rcode, $vtid),
            'tbody' => array(),
        );

        //Make tbody
        //Check round
        $where = array(
            'RCode' => $rcode,
            'VTID' => $vtid,
        );

        //All schedules
        $all_round = array();
        $this->db->from('t_routes as tr');
        $this->db->where('tr.RCode', $rcode);
        $this->db->where('tr.VTID', $vtid);
        $this->db->order_by('tr.StartPoint', 'ASC');
        $query = $this->db->get();
        $temp_routes = $query->result_array();
        foreach ($temp_routes as $row) {
            $this->db->select('*,tsd.RID as RID');
            $this->db->from('t_schedules_day as tsd');
            $this->db->join('vehicles_has_schedules as vhs', 'vhs.TSID=tsd.TSID');
            $this->db->where('tsd.Date >=', $begin_date);
            $this->db->where('tsd.Date <=', $end_date);
            $this->db->where('tsd.RID', $row['RID']);
            $this->db->where('tsd.ScheduleStatus', '1');
            $query = $this->db->get();
            $temp_schedules_day = $query->result_array();
            $all_round[$row['RID']] = $temp_schedules_day;
        }

        $temp_vehicle = $this->get_vehicle($rcode, $vtid);
        foreach ($temp_vehicle as $row) {
            $balance = 0;
            /*
             * Calculate income each station by VID
             */
            /*
             * หาคนขายของแต่ละสถานี
             */
            $all_seller_in_station = array();
            $sql_select = 't_stations.SID,t_stations.StationName';
            $this->db->select($sql_select);
            $this->db->from('t_stations');
            $this->db->where('RCode', $rcode);
            $this->db->where('VTID', $vtid);
            $this->db->where('IsSaleTicket', '1');
            $this->db->order_by('Seq', 'ASC');
            $query = $this->db->get();
            $all_seller_in_station = $query->result_array();
            foreach ($all_seller_in_station as $key2 => $row2) {
                $this->db->select('se.EID');
                $this->db->from('sellers as se');
                $this->db->join('employees', 'employees.EID = se.EID');
                $this->db->where('se.SID', $row2['SID']);
                $query = $this->db->get();
                $all_seller_in_station[$key2]['VID'] = $row['VID'];
                $all_seller_in_station[$key2]['sellers'] = $query->result_array();
            }

            /*
             * หาตั๋วทั้งหมดในช่วงเวลาที่กำหนด
             */
            $all_ticket = array();
            $this->db->from('t_routes as tr');
            $this->db->where('tr.RCode', $rcode);
            $this->db->where('tr.VTID', $vtid);
            $this->db->order_by('tr.StartPoint', 'ASC');
            $query = $this->db->get();
            $temp_routes = $query->result_array();
            foreach ($temp_routes as $row2) {
                $this->db->select('*,tsd.RID as RID');
                $this->db->from('t_schedules_day as tsd');
                $this->db->join('vehicles_has_schedules as vhs', 'vhs.TSID=tsd.TSID');
                $this->db->where('tsd.Date >=', $begin_date);
                $this->db->where('tsd.Date <=', $end_date);
                $this->db->where('tsd.RID', $row2['RID']);
                $this->db->where('tsd.ScheduleStatus', '1');
                $query = $this->db->get();
                $temp_schedules_day = $query->result_array();

                //ตรวจตั๋วที่ขายจาก TSID แล้วรวมเป็นเงินเลย
                foreach ($temp_schedules_day as $key3 => $row3) {
                    $this->db->select('ts.TicketID,ts.Seat,ts.PriceSeat,ts.Seller');
                    $this->db->from('ticket_sale as ts');
                    $this->db->where('ts.TSID', $row3['TSID']);
                    $this->db->where('ts.VID', $row['VID']); //ตรวจจากรถคันนั้นๆ
                    $this->db->where('ts.StatusSeat', '1'); //ไม่ว่างหรือขายแล้ว
                    $query = $this->db->get();
                    $temp = $query->result_array();
                    if (!empty($temp))
                        $all_ticket = array_merge($all_ticket, $temp);
                }
            }

            /*
             * คำนวนเงินที่ได้แต่ละสถานีจาก ตั๋วที่จาก โดยนายท่าที่ประจำสถานี้นั้นๆ
             */
            foreach ($all_seller_in_station as $key2 => $row2) {
                $total_sale = 0;
                foreach ($row2['sellers'] as $key3 => $row3) {
                    if (!isset($all_seller_in_station[$key2]['sellers'][$key3]['total_sale'])) {
                        $all_seller_in_station[$key2]['sellers'][$key3]['total_sale'] = 0;
                    }
                    foreach ($all_ticket as $key4 => $row4) {
                        if ($row3['EID'] == $row4['Seller']) {
                            $all_seller_in_station[$key2]['sellers'][$key3]['total_sale'] += $row4['PriceSeat'];
                            $total_sale+=$row4['PriceSeat'];
                        }
                    }
                }
                $all_seller_in_station[$key2]['total'] = $total_sale;
                $balance+=$total_sale;
            }

//            return $all_seller_in_station;



            $all_onway = 0;
            $all_messenger = 0;
            $all_queue_price = 0;
            $all_in_other = 0;

            $all_license = 0;
            $all_gas = 0;
            $all_oil = 0;
            $all_part = 0;
            $all_percent_price = 0;
            $all_out_other = 0;

            /*
             * Count income and outcome from vehicles_has_cost
             */
            $this->db->select('*');
            $this->db->from('vehicles_has_cost');
            $this->db->join('cost', 'cost.CostID = vehicles_has_cost.CostID', 'left');
            $this->db->join('cost_detail', 'cost_detail.CostDetailID = cost.CostDetailID', 'left');
            $this->db->where('vehicles_has_cost.VID', $row['VID']);
            $this->db->where('cost.CostDate >=', $begin_date);
            $this->db->where('cost.CostDate <=', $end_date);
            $query = $this->db->get();
            $temp_vhs1 = $query->result_array();

            /*
             * Count income and outcome from t_schedules_day_has_cost
             */
            $this->db->select('*');
            $this->db->from('vehicles_has_schedules');
            $this->db->join('t_schedules_day', 't_schedules_day.TSID = vehicles_has_schedules.TSID', 'left');
            $this->db->where('vehicles_has_schedules.VID', $row['VID']);
            $this->db->where('t_schedules_day.Date >=', $begin_date);
            $this->db->where('t_schedules_day.Date <=', $end_date);
            $query = $this->db->get();
            $temp = $query->result_array();

            $temp_vhs2 = array();
            foreach ($temp as $row2) {
                $this->db->select('*');
                $this->db->from('t_schedules_day_has_cost');
                $this->db->join('cost', 'cost.CostID = t_schedules_day_has_cost.CostID', 'left');
                $this->db->where('t_schedules_day_has_cost.TSID', $row2['TSID']);
                $query = $this->db->get();
                $temp2 = $query->result_array();
                $temp_vhs2 = array_merge($temp_vhs2, $temp2);
            }

            //Combined array data and unset
            $temp = array_merge($temp_vhs1, $temp_vhs2);
            unset($temp_vhs1);
            unset($temp_vhs2);

            //Calculate
            foreach ($temp as $row2) {
                //ตรวจสอบ CostID กับ mCostID ก่อนนำไปรวม
                $flag_have = FALSE;
                foreach ($this->mCostID as $CostID) {
                    if ($CostID == $row2['CostID']) {
                        $flag_have = TRUE;
                        break;
                    }
                }
                if ($flag_have) {
                    continue;
                } else {
                    array_push($this->mCostID, $row2['CostID']);
                }

                //รายรับ
                if ($row2['CostTypeID'] == '1') {
                    if ($row2['CostDetailID'] == '1') {
                        //รายทาง onway
                        $all_onway+=$row2['CostValue'];
                    } elseif ($row2['CostDetailID'] == '6') {
                        //ค่าของฝาก messenger
                        $all_messenger+=$row2['CostValue'];
                    } elseif ($row2['CostDetailID'] == '7') {
                        //ค่าคิว queue_price
                        $all_queue_price+=$row2['CostValue'];
                    } elseif ($row2['CostDetailID'] == '888') {
                        //อื่นๆ in_other
                        $all_in_other+=$row2['CostValue'];
                    }
                } else {
                    //รายจ่าย
                    if ($row2['CostDetailID'] == '2') {
                        //ค่าเที่ยว license
                        $all_license+=$row2['CostValue'];
                    } elseif ($row2['CostDetailID'] == '3') {
                        //ค่าก๊าซ gas
                        $all_gas+=$row2['CostValue'];
                    } elseif ($row2['CostDetailID'] == '4') {
                        //ค่านำมัน oil
                        $all_oil+=$row2['CostValue'];
                    } elseif ($row2['CostDetailID'] == '5') {
                        //ค่าอะไหล่ part
                        $all_part+=$row2['CostValue'];
                    } elseif ($row2['CostDetailID'] == '8') {
                        //เปอร์เซนต์ percent_price
                        $all_percent_price+=$row2['CostValue'];
                    } elseif ($row2['CostDetailID'] == '999') {
                        //อื่นๆ out_other
                        $all_out_other+=$row2['CostValue'];
                    }
                }
            }

            //Sum data
            $balance+=$all_onway;
            $balance+=$all_messenger;
            $balance+=$all_queue_price;
            $balance+=$all_in_other;
            $balance-=$all_license;
            $balance-=$all_gas;
            $balance-=$all_oil;
            $balance-=$all_part;
            $balance-=$all_percent_price;
            $balance-=$all_out_other;
            $temp = array(
                'vehicle' => array('VCode' => $row['VCode'], 'VID' => $row['VID']),
                'ref_f_station' => '',
                'f_station' => '',
                'ref_l_station' => '',
                'l_station' => '',
                'income' => $all_seller_in_station,
                'onway' => $all_onway,
                'messenger' => $all_messenger,
                'queue_price' => $all_queue_price,
                'in_other' => $all_in_other,
                'license' => $all_license,
                'gas' => $all_gas,
                'oil' => $all_oil,
                'part' => $all_part,
                'percent_price' => $all_percent_price,
                'out_other' => $all_out_other,
                'balance' => $balance
            );

            //Count round from all_round
            reset($all_round);
            $temp_count = current($all_round);
            $count = 0;
            foreach ($temp_count as $key_r => $round) {
                if ($row['VID'] == $round['VID']) {
                    $count++;
                }
            }
            $temp['ref_f_station'] = $temp_count[0]['RID'];
            $temp['f_station'] = $count;

            $temp_count = next($all_round);
            $count = 0;
            foreach ($temp_count as $round) {
                if ($row['VID'] == $round['VID']) {
                    $count++;
                }
            }
            $temp['ref_l_station'] = $temp_count[0]['RID'];
            $temp['l_station'] = $count;

            //Complete data row by VID
            array_push($ans['tbody'], $temp);
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

    function test($rcode, $vtid, $begin_date, $end_date) {
        if (TRUE) {
            /*
             * หาคนขายของแต่ละสถานี
             */
            $all_seller_in_station = array();
            $sql_select = 't_stations.SID,t_stations.StationName';
            $this->db->select($sql_select);
            $this->db->from('t_stations');
            $this->db->where('RCode', $rcode);
            $this->db->where('VTID', $vtid);
            $this->db->where('IsSaleTicket', '1');
            $this->db->order_by('Seq', 'ASC');
            $query = $this->db->get();
            $all_seller_in_station = $query->result_array();
            foreach ($all_seller_in_station as $key => $row) {
                $this->db->select('se.EID');
                $this->db->from('sellers as se');
                $this->db->join('employees', 'employees.EID = se.EID');
                $this->db->where('se.SID', $row['SID']);
                $query = $this->db->get();
                $all_seller_in_station[$key]['VID'] = '12';
                $all_seller_in_station[$key]['sellers'] = $query->result_array();
            }
//        return $all_seller_in_station;

            /*
             * หาตั๋วทั้งหมดในช่วงเวลาที่กำหนด
             */
            $all_ticket = array();
            $this->db->from('t_routes as tr');
            $this->db->where('tr.RCode', $rcode);
            $this->db->where('tr.VTID', $vtid);
            $this->db->order_by('tr.StartPoint', 'ASC');
            $query = $this->db->get();
            $temp_routes = $query->result_array();
            foreach ($temp_routes as $row) {
                $this->db->select('*,tsd.RID as RID');
                $this->db->from('t_schedules_day as tsd');
                $this->db->join('vehicles_has_schedules as vhs', 'vhs.TSID=tsd.TSID');
                $this->db->where('tsd.Date >=', $begin_date);
                $this->db->where('tsd.Date <=', $end_date);
                $this->db->where('tsd.RID', $row['RID']);
                $this->db->where('tsd.ScheduleStatus', '1');
                $query = $this->db->get();
                $temp_schedules_day = $query->result_array();

                //ตรวจตั๋วที่ขายจาก TSID แล้วรวมเป็นเงินเลย
                foreach ($temp_schedules_day as $key => $row2) {
                    $this->db->select('ts.TicketID,ts.Seat,ts.PriceSeat,ts.Seller');
                    $this->db->from('ticket_sale as ts');
                    $this->db->where('ts.TSID', $row2['TSID']);
                    $this->db->where('ts.VID', '12'); //ตรวจจากรถคันนั้นๆ
                    $this->db->where('ts.StatusSeat', '1'); //ไม่ว่างหรือขายแล้ว
                    $query = $this->db->get();
                    $temp = $query->result_array();
                    if (!empty($temp))
                        $all_ticket = array_merge($all_ticket, $temp);
                }
            }
//        return $all_ticket;

            /*
             * คำนวนเงินที่ได้แต่ละสถานีจาก ตั๋วที่จาก โดยนายท่าที่ประจำสถานี้นั้นๆ
             */
            foreach ($all_seller_in_station as $key => $row) {
                $total_sale = 0;
                foreach ($row['sellers'] as $key2 => $row2) {
                    if (!isset($all_seller_in_station[$key]['sellers'][$key2]['total_sale'])) {
                        $all_seller_in_station[$key]['sellers'][$key2]['total_sale'] = 0;
                    }
                    foreach ($all_ticket as $key3 => $row3) {
                        if ($row2['EID'] == $row3['Seller']) {
                            $all_seller_in_station[$key]['sellers'][$key2]['total_sale'] += $row3['PriceSeat'];
                            $total_sale+=$row3['PriceSeat'];
                        }
                    }
                }
                $all_seller_in_station[$key]['total'] = $total_sale;
            }

            return $all_seller_in_station;
        }
    }

}
