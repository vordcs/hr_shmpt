<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_report extends CI_Model {

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
        $this->db->where('Date >', $begin_date);
        $this->db->where('Date <', $end_date);
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
                'th' => '<em>ลง</em> ' . $row['StationName'].'(คน)'
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

}
