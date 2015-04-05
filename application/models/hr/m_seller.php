<?php

/*
 * กำหนดสิทธิ์พนักงานขายตั๋ว 
 */


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_seller extends CI_Model {

    public function get_employee_by_EID($EID) {
        $this->db->select('*,em.EID as EID');
        $this->db->from('employees AS em');
        $this->db->join('employee_positions AS ep', 'ep.PID = em.PID');
        $this->db->join('employee_work_status AS es', 'es.StatusID = em.StatusID');
        $this->db->join('employee_role_permission AS erp', 'erp.RoleID = em.RoleID');
        $this->db->join('sellers AS se', 'se.EID = em.EID', 'left');
        $this->db->join('t_stations AS ts', 'se.SID = ts.SID', 'left');
        $this->db->join('t_routes AS tr', 'se.RCode = tr.RCode', 'left');
        $this->db->where('em.EID', $EID);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_employee_by_PID($PID = '4') {
        $this->db->select('*,em.EID as EID');
        $this->db->from('employees AS em');
        $this->db->join('employee_positions AS ep', 'ep.PID = em.PID');
        $this->db->join('employee_work_status AS es', 'es.StatusID = em.StatusID');
        $this->db->join('employee_role_permission AS erp', 'erp.RoleID = em.RoleID');
        $this->db->join('sellers AS se', 'se.EID = em.EID', 'left');
        $this->db->join('t_stations AS ts', 'se.SID = ts.SID', 'left');
        $this->db->join('t_routes AS tr', 'se.RCode = tr.RCode', 'left');
        $this->db->where('em.PID', $PID);
        $this->db->group_by('em.EID');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function check_seller($rcode = NUll, $vtid = NULL, $sid = NULL, $eid = NULL) {
        $this->db->select('SellerID,EID,SID');
//        $this->db->select('SellerID,sellers.EID,Title,FirstName,LastName,sellers.RCode,sellers.VTID,sellers.SID,SellerNote');
//        $this->db->join('employees', 'sellers.EID = employees.EID', 'left');
//        $this->db->join('t_stations', 'sellers.SID = t_stations.SID', 'left');
        if ($rcode != NULL) {
//            $this->db->where('sellers.RCode', $rcode);
        }
        if ($vtid != NULL) {
//            $this->db->where('sellers.VTID', $vtid);
        }
        if ($sid != NULL) {
            $this->db->where('sellers.SID', $sid);
        }
        if ($eid != NULL) {
            $this->db->where('sellers.EID', $eid);
        }

        $query = $this->db->get('sellers');

        $rs = NULL;
        if ($query->num_rows() > 0) {
            $rs = $query->result_array();
        } else {
//            $rs = 0;
        }
        return $rs;
    }

    public function get_seller($rcode = NUll, $vtid = NULL, $sid = NULL, $eid = NULL) {
        $this->db->select('SellerID,sellers.EID,Title,FirstName,LastName,sellers.RCode,sellers.VTID,sellers.SID,StationName,SellerNote');
        $this->db->join('employees', 'sellers.EID = employees.EID', 'left');
        $this->db->join('t_stations', 'sellers.SID = t_stations.SID', 'left');
//        $this->db->join('t_routes', 'sellers.RCode = t_routes.RCode', 'left');
//        $this->db->where('StartPoint', 'S');
        if ($rcode != NULL) {
            $this->db->where('sellers.RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('sellers.VTID', $vtid);
        }
        if ($sid != NULL) {
            $this->db->where('sellers.SID', $sid);
        }
        if ($eid != NULL) {
            $this->db->where('sellers.EID', $eid);
            $this->db->group_by('sellers.EID');
        }
        $query = $this->db->get('sellers');

        return $query->result_array();
    }

    public function get_sellers($EID) {
        $this->db->where('sellers.EID', $EID);
        $query = $this->db->get('sellers');
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
        $this->db->group_by(array('RCode', 't_routes.VTID'));
        $this->db->where('StartPoint', 'S');
//        $this->db->order_by('StartPoint');
        $query = $this->db->get('t_routes');
        return $query->result_array();
    }

    public function insert_seller($form_data) {
        $debug = array();
        $i = 0;
        foreach ($form_data as $data) {
            $rs = 'มีเเล้ว';
            $rcode = $data['RCode'];
            $vtid = $data['VTID'];
            $sid = $data['SID'];
            $eid = $data['EID'];
            $seller = $this->get_seller($rcode, $vtid, $sid, $eid);
            if (count($seller) <= 0) {
//        insert data seller
                $this->db->insert('sellers', $data);
                $SellerID = $this->db->insert_id();
                $rs = "INSERT -> $SellerID";
            }
            $debug[$i] = $rs;
            $i++;
        }

        return $debug;
    }

    public function delete_seller($seller_id) {
        $this->db->where('SellerID', $seller_id);
        $this->db->delete('sellers');
        $rs = FALSE;
        if ($this->db->affected_rows() > 0) {
            $rs = TRUE;
        }
        return $rs;
    }

    public function set_form_search($RCode = NULL, $VTID = NULL) {

        $this->load->model('m_vehicle');
        $i_VTID[0] = 'เลือกประเภทรถ';
        foreach ($this->m_vehicle->get_vehicle_types() as $value) {
            $i_VTID[$value['VTID']] = $value['VTDescription'];
        }
//ข้อมูลเส้นทาง        
        $i_RCode[0] = 'พนักงานขายตั๋วทั้งหมด';
        foreach ($this->get_route() as $value) {
            $i_RCode[$value['RCode']] = $value['RCode'] . ' ' . $value['RSource'] . ' - ' . $value['RDestination'];
        }
        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';
        $form_search = array(
            'form' => form_open('hr/seller', array('class' => 'form-horizontal', 'id' => 'form_search_seller')),
            'RCode' => form_dropdown('RCode', $i_RCode, (set_value('RCode') == NULL) ? $RCode : set_value('RCode'), $dropdown . ' id="RCode"'),
            'VTID' => form_dropdown('VTID', $i_VTID, (set_value('VTID') == NULL) ? $VTID : set_value('VTID'), $dropdown),
        );
        return $form_search;
    }

    public function set_form_view($RCode = NULL, $VTID = NULL) {
        $this->load->model('m_vehicle');
        $this->load->model('m_route');
        $this->load->model('m_station');
        $data_sellers = array();

        $vehicle_types = $this->m_route->get_vehicle_types($VTID);
        $rs = array();
        foreach ($vehicle_types as $type) {
            $vtid = $type['VTID'];
            $VTName = $type['VTDescription'];
            $routes = $this->m_route->get_route($RCode, $vtid);
            $data_routes = array();
            foreach ($routes as $route) {
                $rcode = $route['RCode'];
                $RSource = $route['RSource'];
                $RDestination = $route['RDestination'];

                $RouteName = "$VTName สาย $rcode $RSource - $RDestination";

                $stations = $this->m_station->get_stations_sale_ticket($rcode, $vtid);
                $station_in_route = array();
                foreach ($stations as $station) {
                    $SID = $station['SID'];
                    $StationName = $station['StationName'];
                    $sellers = $this->get_seller($rcode, $vtid, $SID);
                    $temp_station = array(
                        'SID' => $SID,
                        'StationName' => $StationName,
                        'sellers' => $sellers,
                    );
                    if (count($sellers) > 0) {
                        array_push($station_in_route, $temp_station);
                    }
                }

                $temp_route = array(
                    'RCode' => $rcode,
                    'RouteName' => $RouteName,
                    'stations' => $station_in_route,
                );
                array_push($data_routes, $temp_route);
            }
            $temp_type = array(
                'VTID' => $vtid,
                'VTName' => $VTName,
                'routes' => $data_routes,
            );
            array_push($data_sellers, $temp_type);
        }
        return $data_sellers;
    }

    public function set_form_add($rcode, $vtid, $eid = NULL) {

        $this->load->model('m_route');
        $this->load->model('m_station');

        $i_RCode = array(
            'type' => 'hidden',
            'id' => 'EID',
            'name' => "RCode",
            'class' => 'form-control text-center',
            'readonly' => TRUE,
            'value' => $rcode,
        );
        $i_VTID = array(
            'type' => 'hidden',
            'id' => 'VTID',
            'name' => "VTID",
            'class' => 'form-control text-center',
            'readonly' => TRUE,
            'value' => $vtid,
        );

        $i_SellerNote = array(
            'name' => 'SellerNote',
            'id' => 'SellerNote',
            'value' => set_value('SellerNote'),
            'placeholder' => '',
            'rows' => '1',
            'class' => 'form-control'
        );
        $debug = array();
        $i_sellers = array();
        $sellers = $this->get_employee_by_PID();
        foreach ($sellers as $seller) {

            $EID = $seller['EID'];
            $Title = $seller['Title'];
            $FirstName = $seller['FirstName'];
            $LastName = $seller['LastName'];
            $FullName = "$Title$FirstName $LastName";


            $stations = $this->m_station->get_stations_sale_ticket($rcode, $vtid);
            $flag = TRUE;
            $num_station_sale = 0;
            foreach ($stations as $station) {
                $SID_ = $station['SID'];
                $check_seller = $this->check_seller($rcode, $vtid, $SID_, $EID);
                $num_check_seller = count($check_seller);
                if ($num_check_seller != 0) {
                    $debug[$SID_] = $check_seller;
                    $num_station_sale = $num_check_seller;
                    $flag = FALSE;
                    break;
                }
            }
            if ($flag) {
                $i_sellers[$EID] = $FullName;
            }
        }

        $data_station = array();
        $stations = $this->m_station->get_stations_sale_ticket($rcode, $vtid);

        foreach ($stations as $station) {
            $SID = $station['SID'];
            $StationName = $station['StationName'];
            $i_SID = array(
                'type' => 'radio',
                'name' => 'SID',
                'id' => "SID<?=$SID?>",
                'value' => $SID,
            );
            $temp_station = array(
                'SID' => form_checkbox($i_SID),
                'StationName' => $StationName,
            );
            array_push($data_station, $temp_station);
        }


        $route = reset($this->m_route->get_route($rcode, $vtid));
        $VTName = $route['VTDescription'];
        $RSource = $route['RSource'];
        $RDestination = $route['RDestination'];

        $RouteName = "$VTName สาย $rcode $RSource - $RDestination";


        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';
        $dropdown_multiple = 'multiple class="selecter_5 selecter" data-selecter-options = \'{"cover":"true"}\' style="font-size: 16pt ! important"';


        $form_add = array(
            'form' => form_open("hr/seller/add/$rcode/$vtid/$eid", array('class' => '', 'id' => 'form_seller')),
            'RouteName' => $RouteName,
            'RCode' => form_input($i_RCode),
            'VTID' => form_input($i_VTID),
            'SellerNote' => form_textarea($i_SellerNote),
            'debug' => $debug,
            'EID' => form_dropdown('EID[]', $i_sellers, set_value('EID'), $dropdown_multiple),
            'stations' => $data_station,
        );
        return $form_add;
    }

    public function validation_form_search() {
        $this->form_validation->set_rules('RCode', 'เส้นทาง', 'trim|xss_clean');
        return TRUE;
    }

    public function validation_form_add() {
        $this->form_validation->set_rules('EID[]', 'พนักงาน', 'trim|xss_clean|required');
        $this->form_validation->set_rules('RCode', 'เส้นทาง', 'trim|required|xss_clean');
        $this->form_validation->set_rules('VTID', 'ประเภทรถ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('SID', 'จุดขายตั๋วโดยสาร', 'trim|xss_clean|callback_check_dropdown');
        $this->form_validation->set_rules('SellerNote', 'หมายเหตุ', 'trim|xss_clean');
        return TRUE;
    }

    public function get_post_form_add() {
        $form_data = array();
        $emp = $this->input->post('EID');
        foreach ($emp as $eid) {
            $temp_form_data = array(
                'EID' => $eid,
                'RCode' => $this->input->post('RCode'),
                'VTID' => $this->input->post('VTID'),
                'SID' => $this->input->post('SID'),
                'SellerNote' => $this->input->post('SellerNote'),
                'CreateBy' => 'admin',
                'CreateDate' => $this->m_datetime->getDatetimeNow(),
            );
            array_push($form_data, $temp_form_data);
        }


        return $form_data;
    }

}
