<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_employee extends CI_Model {

    function prepare_data_from_candidate($data) {
        $data['emergency'] = array(
            'ECTitle' => $data['ECTitle'],
            'ECFirstName' => $data['ECFirstName'],
            'ECLastName' => $data['ECLastName'],
            'ECAddress' => $data['ECAddress'],
            'ECRelationShip' => $data['ECRelationShip'],
            'ECMobilePhone' => $data['ECMobilePhone']
        );
        unset($data['ECTitle']);
        unset($data['ECLastName']);
        unset($data['ECFirstName']);
        unset($data['ECAddress']);
        unset($data['ECRelationShip']);
        unset($data['ECMobilePhone']);

        $data['parent'] = array(
            'FatherTitle' => $data['FatherTitle'],
            'FatherFirstName' => $data['FatherFirstName'],
            'FatherLastName' => $data['FatherLastName'],
            'FatherIsAlive' => $data['FatherIsAlive'],
            'FatherAge' => $data['FatherAge'],
            'FatherOccupation' => $data['FatherOccupation'],
            'MotherTitle' => $data['MotherTitle'],
            'MotherFirstName' => $data['MotherFirstName'],
            'MotherLastName' => $data['MotherLastName'],
            'MotherIsAlive' => $data['MotherIsAlive'],
            'MotherAge' => $data['MotherAge'],
            'MotherOccupation' => $data['MotherOccupation'],
        );
        unset($data['FatherTitle']);
        unset($data['FatherFirstName']);
        unset($data['FatherLastName']);
        unset($data['FatherIsAlive']);
        unset($data['FatherAge']);
        unset($data['FatherOccupation']);
        unset($data['MotherTitle']);
        unset($data['MotherFirstName']);
        unset($data['MotherLastName']);
        unset($data['MotherIsAlive']);
        unset($data['MotherAge']);
        unset($data['MotherOccupation']);

        if (isset($data['experience']) && count($data['experience']) > 0) {
            $ExSeqNo = array();
            $ExDateForm = array();
            $ExDateTo = array();
            $ExCompanyName = array();
            $ExPositionName = array();
            $ExSaraly = array();
            $ReasonOfResign = array();
            foreach ($data['experience'] as $row) {
                array_push($ExSeqNo, $row['ExSeqNo']);
                array_push($ExDateForm, $row['ExDateForm']);
                array_push($ExDateTo, $row['ExDateTo']);
                array_push($ExCompanyName, $row['ExCompanyName']);
                array_push($ExPositionName, $row['ExPositionName']);
                array_push($ExSaraly, $row['ExSaraly']);
                array_push($ReasonOfResign, $row['ReasonOfResign']);
            }
            $data['experience'] = array(
                'ExSeqNo' => $ExSeqNo,
                'ExDateForm' => $ExDateForm,
                'ExDateTo' => $ExDateTo,
                'ExCompanyName' => $ExCompanyName,
                'ExPositionName' => $ExPositionName,
                'ExSaraly' => $ExSaraly,
                'ReasonOfResign' => $ReasonOfResign,
            );
        }

        if (isset($data['education']) && count($data['education']) > 0) {
            $EDSeqNo = array();
            $EDMajor = array();
            $EDDateFrom = array();
            $EDDateTo = array();
            $InstitutionName = array();

            foreach ($data['education'] as $row) {
                array_push($EDSeqNo, $row['EDSeqNo']);
                array_push($EDMajor, $row['EDMajor']);
                array_push($EDDateFrom, $row['EDDateFrom']);
                array_push($EDDateTo, $row['EDDateTo']);
                array_push($InstitutionName, $row['InstitutionName']);
            }
            $data['education'] = array(
                'EDSeqNo' => $EDSeqNo,
                'EDMajor' => $EDMajor,
                'EDDateFrom' => $EDDateFrom,
                'EDDateTo' => $EDDateTo,
                'InstitutionName' => $InstitutionName,
            );
        }

        if (isset($data['family']) && count($data['family']) > 0) {
            unset($data['family']['CID']);
            unset($data['family']['FID']);
        }
        if (isset($data['family_detail']) && count($data['family_detail']) > 0) {
            $SonTitle = array();
            $SonFirstName = array();
            $SonLastName = array();
            $SonOccupation = array();
            $SonAge = array();

            foreach ($data['family_detail'] as $row) {
                array_push($SonTitle, $row['SonTitle']);
                array_push($SonFirstName, $row['SonFirstName']);
                array_push($SonLastName, $row['SonLastName']);
                array_push($SonOccupation, $row['SonOccupation']);
                array_push($SonAge, $row['SonAge']);
            }
            $data['family_detail'] = array(
                'SonTitle' => $SonTitle,
                'SonFirstName' => $SonFirstName,
                'SonLastName' => $SonLastName,
                'SonOccupation' => $SonOccupation,
                'SonAge' => $SonAge,
            );
        }

        return $data;
    }

    function set_create_date($data) {
        $name = $this->session->userdata('name');
        $data['CreateDate'] = date('Y-m-d H:i:s');
        $data['CreateBy'] = $name;
        return $data;
    }

    function set_update_date($data) {
        $name = $this->session->userdata('name');
        $data['UpdateDate'] = date('Y-m-d H:i:s');
        $data['UpdateBy'] = $name;
        return $data;
    }

    function check_last_emp() {
        $this->db->select('*,ca.CreateDate as AcceptDate');
        $this->db->from('employees AS ca');
        $this->db->join('employee_positions AS ep', 'ep.PID = ca.PID', 'left');
        $this->db->order_by('EID', 'desc');
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result_array();
    }

    function check_candidate_by_status($status = '0') {
        $this->db->select('*,ca.CreateDate as RegisterDate');
        $this->db->from('candidate AS ca');
        $this->db->join('employee_positions AS ep', 'ep.PID = ca.PID', 'left');
        $this->db->where('ca.CandidateStatus', $status);
        $query = $this->db->get();
        return $query->result_array();
    }

    function insert_employee_from_candidate($data) {
        $ECID = $this->insert_emergency($data['emergency']);
        if ($ECID == FALSE)
            return FALSE;
        $data['ECID'] = $ECID;

        $EID = $this->insert_person_information($data);
        if ($EID == FALSE)
            return FALSE;

        $data['parent']['EID'] = $EID;
        if (count($data['parent']) > 0 && !$this->insert_parent($data['parent']))
            return FALSE;

        if ($data['experience']['ExCompanyName'][0] != NULL && !$this->insert_experience($data['experience'], $EID))
            return FALSE;

        if ($data['education']['InstitutionName'][0] != NULL && !$this->insert_education($data['education'], $EID))
            return FALSE;

        if ($data['family']['SpouseFirstName'] != NULL) {
            $FID = $this->insert_family($data['family'], $EID);
            if ($FID == FALSE)
                return FALSE;
            if (!$this->insert_family_detail($data['family_detail'], $FID))
                return FALSE;
        }

        return $EID;
    }

    function insert_emergency($data) {
        $data = $this->set_create_date($data);
        if ($this->db->insert('employee_emergency_contact', $data))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function insert_person_information($data) {
        unset($data['experience']);
        unset($data['education']);
        unset($data['parent']);
        unset($data['emergency']);
        unset($data['family']);
        unset($data['family_detail']);
        unset($data['CID']);
        unset($data['CandidateStatus']);
        unset($data['UpdateBy']);
        unset($data['UpdateDate']);

        $data['EID'] = $this->gen_eid();
        $data['UserName'] = $this->gen_user($data['EID'], $data['PersonalID']);

        //Default value
        $data['RoleID'] = '1';
        $data['EmployeeStatus'] = '1';
        $data['StatusID'] = '1';

        $data = $this->set_create_date($data);
        if ($this->db->insert('employees', $data))
            return $data['EID'];
        else
            return FALSE;
    }

    function insert_parent($data) {
        $data = $this->set_create_date($data);
        if ($this->db->insert('employee_parent', $data))
            return $data['EID'];
        else
            return FALSE;
    }

    function gen_eid() {
        $this->db->order_by("EID", "desc");
        $this->db->limit(2);
        $query = $this->db->get('employees');
        $temp = $query->result_array();
        $last_eid = 'ESHMPT0000';
        foreach ($temp as $row) {
            $last_eid = $row['EID'];
            break;
        }
        $num = substr($last_eid, 6);
        $num+=1;
        $num = str_pad($num, 4, '0', STR_PAD_LEFT);
        $last_eid = 'ESHMPT' . $num;
        return $last_eid;
    }

    function gen_user($EID, $PersonalID) {
        $query = $this->db->get_where('username', array('UserName' => $EID));
        $data = array(
            'UserName' => $EID,
            'Password' => md5($PersonalID),
            'IsNormal' => 1
        );
        if ($query->num_rows() != 0) {
            $this->db->where('UserName', $data['UserName']);
            unset($data['UserName']);
            if ($this->db->update('username', $data))
                return $EID;
            else
                return FALSE;
        } else {
            if ($this->db->insert('username', $data))
                return $data['UserName'];
            else
                return FALSE;
        }
    }

    function insert_experience($data, $EID) {
        $pre_data = array();
        for ($i = 0; $i < count($data['ExCompanyName']); $i++) {
            $pre_data[$i]['EID'] = $EID;
            $pre_data[$i]['ExSeqNo'] = $i;
            $pre_data[$i]['ExCompanyName'] = $data['ExCompanyName'][$i];
            $pre_data[$i]['ExDateForm'] = $data['ExDateForm'][$i];
            $pre_data[$i]['ExDateTo'] = $data['ExDateTo'][$i];
            $pre_data[$i]['ExPositionName'] = $data['ExPositionName'][$i];
            $pre_data[$i]['ExSaraly'] = $data['ExSaraly'][$i];
            $pre_data[$i]['ReasonOfResign'] = $data['ReasonOfResign'][$i];
            $pre_data[$i] = $this->set_create_date($pre_data[$i]);
        }
        if ($this->db->insert_batch('employee_experience', $pre_data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_education($data, $EID) {
        $pre_data = array();
        for ($i = 0; $i < count($data['InstitutionName']); $i++) {
            $pre_data[$i]['EID'] = $EID;
            $pre_data[$i]['EDSeqNo'] = $i;
            $pre_data[$i]['InstitutionName'] = $data['InstitutionName'][$i];
            $pre_data[$i]['EDMajor'] = $data['EDMajor'][$i];
            $pre_data[$i]['EDDateFrom'] = $data['EDDateFrom'][$i];
            $pre_data[$i]['EDDateTo'] = $data['EDDateTo'][$i];
        }
        if ($this->db->insert_batch('employee_education', $pre_data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_family($data, $EID) {
        $this->db->order_by("FID", "desc");
        $this->db->limit(2);
        $query = $this->db->get('employee_family');
        $temp = $query->result_array();
        $last_fid = 'FSHMPT0000';
        foreach ($temp as $row) {
            $last_fid = $row['FID'];
            break;
        }
        $num = substr($last_fid, 6);
        $num+=1;
        $num = str_pad($num, 4, '0', STR_PAD_LEFT);
        $last_fid = 'FSHMPT' . $num;

        $data['EID'] = $EID;
        $data['FID'] = $last_fid;
        $data = $this->set_create_date($data);
        if ($this->db->insert('employee_family', $data))
            return $last_fid;
        else
            return FALSE;
    }

    function insert_family_detail($data, $FID) {
        $pre_data = array();
        for ($i = 0; $i < count($data['SonTitle']); $i++) {
            $pre_data[$i]['FID'] = $FID;
            $pre_data[$i]['FDSeqNo'] = $i;
            $pre_data[$i]['SonTitle'] = $data['SonTitle'][$i];
            $pre_data[$i]['SonFirstName'] = $data['SonFirstName'][$i];
            $pre_data[$i]['SonLastName'] = $data['SonLastName'][$i];
            $pre_data[$i]['SonOccupation'] = $data['SonOccupation'][$i];
            $pre_data[$i]['SonAge'] = $data['SonAge'][$i];
            $pre_data[$i] = $this->set_create_date($pre_data[$i]);
        }
        if ($this->db->insert_batch('employee_family_detail', $pre_data))
            return TRUE;
        else
            return FALSE;
    }

}
