<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_home extends CI_Model {

    function check_report_day() {
        $ans = array();
        $end = date('Y-m-d');
        $date1 = str_replace('-', '/', $end);
        $start = date('Y-m-d', strtotime($date1 . "-2 days"));

        $code_select = 'rd.ReportID,rd.ReportDate,rd.ReportTime,rd.Total,rd.Vage,';
        $code_select .= 'rd.Net,rd.ReportStatus,rd.ReportNote,rd.SID,rd.RCode,rd.VTID,';
        $code_select .= 'em.UserName,em.Title,em.FirstName,em.LastName,';

        $this->db->select($code_select);
        $this->db->from('report_day AS rd');
        $this->db->join('employees AS em', 'em.EID = rd.CreateBy');


        $this->db->where('rd.ReportDate >=', $start);
        $this->db->where('rd.ReportDate <=', $end);
        $this->db->order_by('rd.CreateDate', 'desc');
        $query = $this->db->get();
        $temp = $query->result_array();

        $code_select = 'tsdhr.TSID,vhs.VID,ve.VCode,tsd.TimeDepart,tsd.TimeArrive';
        foreach ($temp as $row) {
            if (!isset($ans[$row['ReportDate']])) {
                $ans[$row['ReportDate']] = array();
            }
            //Check vehicle in report
            $this->db->select($code_select);
            $this->db->from('t_schedules_day_has_report AS tsdhr');
            $this->db->join('vehicles_has_schedules AS vhs', 'vhs.TSID = tsdhr.TSID');
            $this->db->join('vehicles AS ve', 've.VID = vhs.VID');
            $this->db->join('t_schedules_day AS tsd', 'tsd.TSID = tsdhr.TSID');
            $this->db->where('tsdhr.ReportID', $row['ReportID']);
            $query = $this->db->get();
            $row['list'] = $query->result_array();

            //Insert full data to ans for use
            array_push($ans[$row['ReportDate']], $row);
        }
        return $ans;
    }

    function check_report($ReportID, $EID) {
        $data = array(
            'EID' => $EID,
            'ReportID' => $ReportID,
            'ReadDateTime' => $this->m_datetime->getDatetimeNow()
        );
        if ($this->db->insert('reading_report', $data)) {
            $data2 = array(
                'ReportStatus' => 1
            );
            $this->db->where('ReportID', $ReportID);
            if ($this->db->update('report_day', $data2)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

}
