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

}
