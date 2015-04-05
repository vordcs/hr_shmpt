<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_ticket extends CI_Model {

    public function get_tickets($date = NULL, $tsid = NULL, $eid = NULL, $status_seat = NULL, $SourceID = NULL, $TicketID = NULL) {

        $this->db->select('*,ticket_sale.CreateDate as CreateDate');
        $this->db->join('sellers', 'sellers.EID = ticket_sale.Seller '
                . 'AND sellers.SID = ticket_sale.SourceID', 'left');
        $this->db->join('employees', 'employees.EID = ticket_sale.Seller', 'left');
        $this->db->join('t_stations', 't_stations.SID = sellers.SID', 'left');

        if ($tsid != NULL) {
            $this->db->where('TSID', $tsid);
        }
        if ($status_seat != NULL) {
            $this->db->where('StatusSeat', $status_seat);
        }
        if ($eid != NULL) {
            $this->db->where('Seller', $eid);
        }
        if ($date == NULL) {
            $date = $this->m_datetime->getDateToday();
        }

        if ($SourceID != NULL) {
            $this->db->where('SourceID', $SourceID);
        }
        if ($TicketID != NULL) {
            $this->db->where('TicketID', $TicketID);
        } else {
            $this->db->where('DateSale', $date);
            $this->db->group_by('TicketID');
        }
        $this->db->order_by('ticket_sale.DestinationID,t_stations.Seq,ticket_sale.CreateDate', 'DESC');
        $query = $this->db->get('ticket_sale');

        return $query->result_array();
    }

    public function delete($TicketID) {

        $ticket = reset($this->get_tickets(NULL, NULL, NULL, NULL, NULL, $TicketID));

        $TSID = $ticket['TSID'];
        $EID = $ticket['Seller'];
        $PriceSeat = $ticket['PriceSeat'];

        $this->db->where('TSID', $TSID);
        $query_has_report = $this->db->get('t_schedules_day_has_report');
        if ($query_has_report->num_rows() > 0) {
            $schedule_has_report = $query_has_report->row_array();
            $ReportID = $schedule_has_report['ReportID'];

            $this->db->where('ReportID', $ReportID);
            $query_report = $this->db->get('report_day');

            if ($query_report->num_rows() > 0) {
                $report = $query_report->row_array();
                $Total_old = $report['Total'];
                $Net_old = $report['Net'];

                $data_report_new = array(
                    'Total' => $Total_old - $PriceSeat,
                    'Net' => $Net_old - $PriceSeat,
                );

                $this->db->where('ReportID', $ReportID);
                $this->db->where('CreateBy', $EID);
                $this->db->update('report_day', $data_report_new);
            }
        }

        $this->db->where('TicketID', $TicketID);
        $this->db->delete('ticket_sale');
        if ($this->db->affected_rows() == 1) {
            $rs = TRUE;
        } else {
            $rs = FALSE;
        }
        return $rs;
    }

    public function get_ticket_report($TSID, $SourceID, $EID = NULL) {
        $this->db->select('SourceID,SourceName,DestinationID,DestinationName,PriceSeat,COUNT(TicketID) as NumberTicket,SUM(PriceSeat) as Total');
        $this->db->where('SourceID', $SourceID);
        $this->db->where('TSID', $TSID);
        if ($EID != NULL) {
            $this->db->where('Seller', $EID);
        }
        $this->db->group_by('SourceID,DestinationID');
        $query = $this->db->get('ticket_sale');
        return $query->result_array();
    }

    public function sum_ticket_by_station($date = NULL, $tsid = NULL, $SourceID = NULL, $StatusSeat = NULL) {

        $this->db->select('ticket_sale.TSID as TSID,SourceID,SourceName,DestinationID,DestinationName,PriceSeat,StatusSeat, COUNT(TicketID) as num_ticket,SUM(PriceSeat) as total_price_ticket,DateSale');

        if ($date == NULL) {
            $date = $this->m_datetime->getDateToday();
        }
        if ($tsid != NULL) {
            $this->db->where('TSID', $tsid);
        }
        if ($SourceID != NULL) {
            $this->db->where('SourceID', $SourceID);
        }
        if ($StatusSeat != NULL) {
            $this->db->where('StatusSeat', $StatusSeat);
        }
        if ($date == NULL) {
            $date = $this->m_datetime->getDateToday();
        }

        $this->db->where('DateSale', $date);

        $query = $this->db->get('ticket_sale');

        return $query->result_array();
    }

    public function set_data_view($RCode = NULL, $VTID = NULL, $SID = NULL, $EID = NULL) {
        $this->load->model('m_vehicle');
        $this->load->model('m_route');
        $this->load->model('m_station');
        $this->load->model('m_schedule');
        $this->load->model('hr/m_seller');

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

                $stations = $this->m_station->get_stations_sale_ticket($rcode, $vtid, $SID);

                $station_in_route = array();
                foreach ($stations as $station) {
                    $sid = $station['SID'];
                    $StationName = $station['StationName'];

                    $sellers = $this->m_seller->get_seller($rcode, $vtid, $sid, $EID);

                    $SaleTotal = reset($this->sum_ticket_by_station(NULL, NULL, $sid, 1))['total_price_ticket'];

                    $temp_station = array(
                        'SaleTotal' => $SaleTotal,
                        'SID' => $sid,
                        'StationName' => $StationName,
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

        /* -------------set form search ---------------- */

        $i_VTID[0] = 'ประเภทรถทั้งหมด';
        foreach ($this->m_route->get_vehicle_types() as $type) {
            $i_VTID[$type['VTID']] = $type['VTDescription'];
        }

        //ข้อมูลเส้นทาง
        $i_RCode[0] = 'เส้นทางทั้งหมด';
        foreach ($this->m_route->get_route() as $value) {
            $i_RCode[$value['RCode']] = $value['RCode'] . ' ' . $value['RSource'] . ' - ' . $value['RDestination'];
        }

        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';

        $form_search = array(
            'form_open' => form_open('ticket/', array('id' => 'form_search_ticket', 'class' => "form-inline")),
            'VTID' => form_dropdown('VTID', $i_VTID, (set_value('VTID') == NULL) ? $VTID : set_value('VTID'), $dropdown),
            'RCode' => form_dropdown('RCode', $i_RCode, (set_value('RCode') == NULL) ? $RCode : set_value('RCode'), $dropdown),
            'form_close' => form_close(),
        );

        $data = array(
            'form_search' => $form_search,
            'data' => $data_sellers,
        );

        return $data;
    }

    public function get_checkin($TSID, $SID = NULL, $EID = NULL, $RCode = NULL, $VTID = NULL) {

        $this->db->select('CheckInID,TimeCheckIn,sellers.SellerNote,check_in.CreateBy as EID');

        $this->db->join('sellers', 'sellers.EID = check_in.CreateBy');
        $this->db->join('t_stations', 't_stations.SID = check_in.SID AND t_stations.SID = sellers.SID');


        if ($TSID != NULL) {
            $this->db->where('check_in.TSID', $TSID);
        }

        if ($SID != NULL) {
            $this->db->where('check_in.SID', $SID);
        }

        if ($EID != NULL) {
            $this->db->where('check_in.CreateBy', $EID);
        }

        if ($RCode != NULL) {
            $this->db->where('t_stations.RCode', $RCode);
        }
        if ($VTID != NULL) {
            $this->db->where('t_stations.VTID', $VTID);
        }

        $this->db->group_by('CheckInID');

        $query = $this->db->get('check_in');

        return $query->result_array();
    }

    public function set_form_view($Date, $RCode, $VTID, $SID = NULL, $EID = NULL) {
        $this->load->model('m_vehicle');
        $this->load->model('m_route');
        $this->load->model('m_station');
        $this->load->model('m_schedule');
        $this->load->model('hr/m_seller');

        $stations = $this->m_station->get_stations($RCode, $VTID);
        $num_station = count($stations);
        if ($SID != NULL) {
            $station_sale = reset($this->m_station->get_stations_sale_ticket($RCode, $VTID, $SID));
            $Seq = $station_sale['Seq'];
            if ($Seq == 1) {
                $StartPoint = 'S';
            } elseif ($Seq == $num_station) {
                $StartPoint = 'D';
            } else {
                $StartPoint = NULL;
            }
            $routes = $this->m_route->get_route_by_start_point($StartPoint, $RCode, $VTID);
        } else {
            $StartPoint = NULL;
            $routes = $this->m_route->get_route_detail($RCode, $VTID);
        }
        $data_routes = array();
        foreach ($routes as $route) {
            $rid = $route['RID'];
            $rcode = $route['RCode'];
            $vtid = $route['VTID'];
            if ($SID == NULL) {
                $StartPoint = $route['StartPoint'];
            }
            $RSource = $route['RSource'];
            $RDestination = $route['RDestination'];
            $VTName = $route['VTDescription'];
            $RouteName = "$VTName สาย $rcode $RSource - $RDestination";


            $data_schedule_in_route = array();
            $schedules = $this->m_schedule->get_schedule($Date, $RCode, $VTID, $rid);
            foreach ($schedules as $schedule) {
                $TSID = $schedule['TSID'];


                /* ข้อมูลพนักขายตั๋ว */
                $sellers = $this->m_seller->get_seller($rcode, $vtid, $SID, $EID);
                $data_sellers_in_route = array();
                foreach ($sellers as $seller) {
                    $Title = $seller['Title'];
                    $FirstName = $seller['FirstName'];
                    $LastName = $seller['LastName'];
                    $SellerName = "$Title$FirstName $LastName";

                    $eid = $seller['EID'];

                    $SellerStationID = $seller['SID'];
                    $SellerStation = $seller['StationName'];
                    $SellerNote = $seller['SellerNote'];

                    if ($SellerNote != NULL) {
                        $SellerStation .=" ($SellerNote)";
                    }
                    $tickets_report = $this->get_ticket_report($TSID, $SellerStationID, $eid);
                    $Numberticket = 0;
                    $Total = 0;
                    foreach ($tickets_report as $ticket_report) {
                        $Numberticket+=$ticket_report['NumberTicket'];
                        $Total += $ticket_report['Total'];
                    }

                    $temp_seller = array(
                        'SellerID' => $seller['SellerID'],
                        'EID' => $eid,
                        'SellerName' => $SellerName,
                        'RCode' => $seller['RCode'],
                        'VTID' => $seller['VTID'],
                        'SID' => $SellerStationID,
                        'StationName' => $SellerStation,
                        'NumberTicket' => $Numberticket,
                        'Total' => $Total,
                    );
                    if ($Numberticket > 0) {
                        array_push($data_sellers_in_route, $temp_seller);
                    }
                }

                /* ข้อมูลตารางเวลาออก และเวลาถึง */
                $stations_sale_ticket = $this->m_station->get_station_sale_ticket($RCode, $VTID, $StartPoint);
                $data_time_schedules = array();
                array_pop($stations_sale_ticket);
                foreach ($stations_sale_ticket as $station_sale) {

                    $sid = $station_sale['SID'];
                    $time_depart = $this->m_schedule->time_depart($rid, $TSID, $sid);

                    $temp_time_schedule = array(
                        'StationName' => $station_sale['StationName'],
                        'TimeDepart' => $time_depart,
                        'checkins' => $this->get_checkin($TSID, $sid, NULL, $RCode, $VTID),
                    );
                    array_push($data_time_schedules, $temp_time_schedule);
                }

                /* ข้อมูลตั๋วโดยสาร */

                $tickets = $this->get_tickets($Date, $TSID, $EID, 1, $SID);
                $num_ticket = (count($tickets));

                $tickes_in_schedule = array();
                foreach ($tickets as $ticket) {
                    $VCode = $ticket['VCode'];
                    $SourceID = $ticket['SourceID'];
                    $SourceName = $ticket['SourceName'];
                    $DestinationID = $ticket['DestinationID'];
                    $DestinationName = $ticket['DestinationName'];
                    $PriceSeat = $ticket['PriceSeat'];

                    $TimeDepart = $this->m_schedule->time_depart($rid, $TSID, $SourceID);

                    $Title = $ticket['Title'];
                    $FirstName = $ticket['FirstName'];
                    $LastName = $ticket['LastName'];

                    $SellerName = "$Title$FirstName $LastName";

                    $SellerStation = $ticket['StationName'];
                    $SellerNote = $ticket['SellerNote'];

                    if ($SellerNote != NULL) {
                        $SellerStation .=" ($SellerNote)";
                        $SourceName .=" ($SellerNote)";
                    }

                    $temp_ticket = array(
                        'TicketID' => $ticket['TicketID'],
                        'Seat' => $ticket['Seat'],
                        'PriceSeat' => $PriceSeat,
                        'VCode' => $VCode,
                        'SourceID' => $SourceID,
                        'SourceName' => $SourceName,
                        'DestinationID' => $DestinationID,
                        'DestinationName' => $DestinationName,
                        'TimeDepart' => $TimeDepart,
                        'SellerName' => $SellerName,
                        'SellerStation' => $SellerStation,
                        'TimeSale' => date('H:i', strtotime($ticket['CreateDate'])),
                        'NumberPrint' => $ticket['NumberPrint'],
                        'IsDiscount' => $ticket['IsDiscount'],
                        'EID' => $ticket['EID'],
                    );

                    array_push($tickes_in_schedule, $temp_ticket);
                }

                $temp_schedule = array(
                    'TSID' => $TSID,
                    'TimeDepart' => date('H:i', strtotime($schedule['TimeDepart'])),
                    'NumberTicket' => $num_ticket,
                    'time_schedules' => $data_time_schedules,
                    'sellers' => $data_sellers_in_route,
                    'tickets' => $tickes_in_schedule,
                );
                if ($num_ticket > 0) {
                    array_push($data_schedule_in_route, $temp_schedule);
                }
            }

            $temp_route = array(
                'RID' => $rid,
                'RCode' => $RCode,
                'VTID' => $VTID,
                'RouteName' => $RouteName,
                'schedules' => $data_schedule_in_route,
            );
            array_push($data_routes, $temp_route);
        }
        /* -------------set form search ---------------- */

        $date_th = $this->m_datetime->setTHDateToDB($Date);
        $i_Date = array(
            'type' => "text",
            'name' => "Date",
            'value' => (set_value('Date') == NULL) ? $date_th : set_value('Date'),
            'class' => "form-control datepicker",
        );

        //ข้อมูลพนักงาน        
        $i_EID[0] = 'พนักงานขายตั๋วทั้งหมด';
        $sellers = $this->m_seller->get_seller($RCode, $VTID, $SID);
        foreach ($sellers as $seller) {
            $Title = $seller['Title'];
            $FirstName = $seller['FirstName'];
            $LastName = $seller['LastName'];
            $SellerName = "$Title$FirstName $LastName";

            $eid = $seller['EID'];

            $SellerNote = $seller['SellerNote'];

            if ($SellerNote != NULL) {
                $SellerName .=" ($SellerNote)";
            }

            $i_EID[$eid] = $SellerName;
        }

        //ข้อมูลสถานี
        $i_SID[0] = 'สถานีทั้งหมด';
        foreach ($this->m_station->get_station_sale_ticket($RCode, $VTID) as $value) {
            $i_SID[$value['SID']] = $value['StationName'];
        }

        $dropdown = 'class="selecter_3" disabled data-selecter-options = \'{"cover":"true"}\' ';
        $dropdown_eid = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';
        $form_search = array(
            'form_open' => form_open("ticket/view/$RCode/$VTID/$SID/", array('id' => 'form_search_ticket', 'class' => "")),
            'Date' => form_input($i_Date),
            'EID' => form_dropdown('EID', $i_EID, (set_value('EID') == NULL) ? $EID : set_value('EID'), $dropdown_eid),
            'SID' => form_dropdown('SID', $i_SID, (set_value('SID') == NULL) ? $SID : set_value('SID'), $dropdown),
            'form_close' => form_close(),
        );
        $rs = array(
            'form_search' => $form_search,
            'data' => $data_routes,
        );
        return $rs;
    }

    function get_post_form_search_ticket() {
        $date_th = $this->input->post('Date');
        $rs = array(
            'DateTH' => $date_th,
            'DateDB' => $this->m_datetime->setTHDateToDB($date_th),
            'SID' => $this->input->post('SID'),
            'EID' => $this->input->post('EID'),
        );
        return $rs;
    }

    function validation_form_search_by_route() {
        $this->form_validation->set_rules('RCode', 'เส้นทาง', 'trim|xss_clean');
        $this->form_validation->set_rules('VTID', 'ประเภทรถ', 'trim|xss_clean');
        return TRUE;
    }

    function validation_form_search_ticket() {
        $this->form_validation->set_rules('Date', 'วันที่', 'trim|xss_clean|required');
        $this->form_validation->set_rules('SID', 'สถานี', 'trim|xss_clean');
        $this->form_validation->set_rules('EID', 'พนักงาน', 'trim|xss_clean');
        return TRUE;
    }

}
