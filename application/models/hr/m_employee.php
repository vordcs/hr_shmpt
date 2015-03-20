<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_employee extends CI_Model {

    function check_employee_detail($EID) {
        $data = array();
        $this->db->from('employees AS em');
        $this->db->join('employee_emergency_contact AS ce', 'ce.ECID = em.ECID');
        $this->db->join('employee_parent AS ep', 'ep.EID = em.EID');
        $this->db->where('em.EID', $EID);
        $query = $this->db->get();
        $data = $query->result_array()[0];

        $this->db->from('employee_family AS ef');
        $this->db->where('ef.EID', $EID);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data['family'] = $query->result_array()[0];

            $this->db->from('employee_family_detail AS efd');
            $this->db->where('efd.FID', $data['family']['FID']);
            $query = $this->db->get();
            if ($query->num_rows() > 0)
                $data['family_detail'] = $query->result_array();
        }

        $this->db->from('employee_education AS edu');
        $this->db->where('edu.EID', $EID);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            $data['education'] = $query->result_array();

        $this->db->from('employee_experience AS exp');
        $this->db->where('exp.EID', $EID);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            $data['experience'] = $query->result_array();

        //Change format all date
        $data['BirthDate'] = $this->change_format_date($data['BirthDate']);
        $data['AvaliableStartDate'] = $this->change_format_date($data['AvaliableStartDate']);
        $data['AvaliableStartDate'] = $this->change_format_date($data['AvaliableStartDate']);
        if (isset($data['education'])) {
            for ($i = 0; $i < count($data['education']); $i++) {
                $data['education'][$i]['EDDateFrom'] = $this->change_format_date($data['education'][$i]['EDDateFrom']);
                $data['education'][$i]['EDDateTo'] = $this->change_format_date($data['education'][$i]['EDDateTo']);
            }
        }
        if (isset($data['experience'])) {
            for ($i = 0; $i < count($data['experience']); $i++) {
                $data['experience'][$i]['ExDateForm'] = $this->change_format_date($data['experience'][$i]['ExDateForm']);
                $data['experience'][$i]['ExDateTo'] = $this->change_format_date($data['experience'][$i]['ExDateTo']);
            }
        }

        return $data;
    }

    function change_format_date($date) {
        if ($date == '0000-00-00 00:00:00')
            return NULL;
        $DateTime = new DateTime($date);
        $date = $DateTime->format('Y-m-d');
        return $date;
    }

    function check_employee($EID) {
        $this->db->from('employees');
        $this->db->where('EID', $EID);
        $num = $this->db->count_all_results();
        if ($num == 1)
            return TRUE;
        else
            return FALSE;
    }

    function search($data) {
        $this->db->from('employees AS em');
        $this->db->join('employee_positions AS ep', 'ep.PID = em.PID');
        $this->db->join('employee_work_status AS es', 'es.StatusID = em.StatusID');
        $this->db->join('employee_role_permission AS erp', 'erp.RoleID = em.RoleID', 'left');
        if ($data['PID'] != 0)
            $this->db->where('em.PID', $data['PID']);
        if ($data['EID'] != NULL)
            $this->db->where('em.EID', $data['EID']);
        if ($data['FirstName'] != NULL)
            $this->db->like('em.FirstName', $data['FirstName']);
        if ($data['LastName'] != NULL)
            $this->db->like('em.LastName', $data['LastName']);
        $this->db->order_by('em.PID', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    function search_all() {
        $this->db->from('employees AS em');
        $this->db->join('employee_positions AS ep', 'ep.PID = em.PID');
        $this->db->join('employee_work_status AS es', 'es.StatusID = em.StatusID');
        $this->db->join('employee_role_permission AS erp', 'erp.RoleID = em.RoleID', 'left');
        $this->db->order_by('em.PID', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

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

    function insert_employee_from_candidate($data, $CID) {
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

        //Update candidate CandidateStatus to 1(Accept)
        $temp['CandidateStatus'] = 1;
        $temp = $this->set_update_date($temp);
        $this->db->where('CID', $CID);
        $this->db->update('candidate', $temp);
        if ($this->db->affected_rows() != 1)
            return FALSE;

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

    function set_form_search() {
        $temp = $this->check_employee_positions();
        $i_PID = array('0' => 'ทั้งหมด');
        foreach ($temp as $row) {
            $i_PID[$row['PID']] = trim($row['PositionName']);
        }
        $i_EID = array(
            'name' => 'EID',
            'value' => set_value('EID'),
            'placeholder' => 'รหัสพนักงาน',
            'class' => 'form-control');
        $i_FirstName = array(
            'name' => 'FirstName',
            'value' => set_value('FirstName'),
            'placeholder' => 'ชื่อ',
            'class' => 'form-control');
        $i_LastName = array(
            'name' => 'LastName',
            'value' => set_value('LastName'),
            'placeholder' => 'นามสกุล',
            'class' => 'form-control');

        $data = array(
            'PID' => form_dropdown('PID', $i_PID, set_value('PID'), "class=\"selecter_1\""),
            'EID' => form_input($i_EID),
            'FirstName' => form_input($i_FirstName),
            'LastName' => form_input($i_LastName),);
        return $data;
    }

    function check_employee_positions() {
        $query = $this->db->get('employee_positions');
        return $query->result_array();
    }

    function set_validation_search() {
        $this->form_validation->set_rules('PID', '', 'trim|xss_clean');
        $this->form_validation->set_rules('EID', '', 'trim|xss_clean');
        $this->form_validation->set_rules('FirstName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('LastName', '', 'trim|xss_clean');
        return true;
    }

    function get_post_search() {
        $f_data = array(
            'EID' => $this->input->post('EID'),
            'PID' => $this->input->post('PID'),
            'FirstName' => $this->input->post('FirstName'),
            'LastName' => $this->input->post('LastName'));
        return $f_data;
    }

    function update_emplyee_layof($EID) {
        $data = array();
        $data['StatusID'] = 0;

        $data = $this->set_update_date($data);
        $this->db->where('EID', $EID);
        $this->db->update('employees', $data);
        if ($this->db->affected_rows() == 1)
            return TRUE;
        else
            return FALSE;
    }

    function update_all_data($data) {
        $ECID = $this->update_emergency($data['emergency']);
        if ($ECID == FALSE)
            return FALSE;
        $data['ECID'] = $ECID;

        $EID = $this->update_person_information($data);
        if ($EID == FALSE)
            return FALSE;

        $data['parent']['EID'] = $EID;
        if (count($data['parent']) > 0 && !$this->update_parent($data['parent']))
            return FALSE;

        if ($data['experience']['ExCompanyName'][0] != NULL && !$this->update_experience($data['experience'], $EID))
            return FALSE;

        if ($data['education']['InstitutionName'][0] != NULL && !$this->update_education($data['education'], $EID))
            return FALSE;

        if ($data['family']['SpouseFirstName'] != NULL) {
            $FID = $this->update_family($data['family'], $EID);
            if ($FID == FALSE)
                return FALSE;
            if (!$this->update_family_detail($data['family_detail'], $FID[0]['FID']))
                return FALSE;
        }

        return $ECID;
    }

    function update_person_information($data) {
        unset($data['emergency']);
        unset($data['experience']);
        unset($data['family']);
        unset($data['family_detail']);
        unset($data['parent']);
        unset($data['education']);
        $data = $this->set_update_date($data);
        $this->db->where('EID', $data['EID']);
        $this->db->update('employees', $data);
        if ($this->db->affected_rows() == 1)
            return $data['EID'];
        else
            return FALSE;
    }

    function update_emergency($data) {
        $data = $this->set_update_date($data);
        $this->db->where('ECID', $data['ECID']);
        $this->db->update('employee_emergency_contact', $data);
        if ($this->db->affected_rows() == 1)
            return $data['ECID'];
        else
            return FALSE;
    }

    function update_parent($data) {
        $data = $this->set_update_date($data);
        $this->db->where('EID', $data['EID']);
        $this->db->update('employee_parent', $data);
        if ($this->db->affected_rows() == 1)
            return TRUE;
        else
            return FALSE;
    }

    function update_experience($data, $EID) {
        $pre_data = array();
        $count = 0;
        for ($i = 0; $i < count($data['ExCompanyName']); $i++) {
            $pre_data[$i]['EID'] = $EID;
            $pre_data[$i]['ExSeqNo'] = $i;
            $pre_data[$i]['ExCompanyName'] = $data['ExCompanyName'][$i];
            $pre_data[$i]['ExDateForm'] = $data['ExDateForm'][$i];
            $pre_data[$i]['ExDateTo'] = $data['ExDateTo'][$i];
            $pre_data[$i]['ExPositionName'] = $data['ExPositionName'][$i];
            $pre_data[$i]['ExSaraly'] = $data['ExSaraly'][$i];
            $pre_data[$i]['ReasonOfResign'] = $data['ReasonOfResign'][$i];
            $pre_data[$i] = $this->set_update_date($pre_data[$i]);
            $query = $this->db->get_where('employee_experience', array('EID' => $EID, 'ExSeqNo' => $i));
            if ($query->num_rows() == 0) {
                $pre_data[$i] = $this->set_create_date($pre_data[$i]);
                if ($this->db->insert('employee_experience', $pre_data[$i]))
                    $count++;
            } else {
                $this->db->where('EID', $EID);
                $this->db->where('ExSeqNo', $i);
                if ($this->db->update('employee_experience', $pre_data[$i]))
                    $count++;
            }
        }
        if (count($pre_data) == $count)
            return TRUE;
        else
            return FALSE;
    }

    function update_family_detail($data, $FID) {
        $pre_data = array();
        $count = 0;
        for ($i = 0; $i < count($data['SonFirstName']); $i++) {
            $pre_data[$i]['FID'] = $FID;
            $pre_data[$i]['FDSeqNo'] = $i;
            $pre_data[$i]['SonTitle'] = $data['SonTitle'][$i];
            $pre_data[$i]['SonFirstName'] = $data['SonFirstName'][$i];
            $pre_data[$i]['SonLastName'] = $data['SonLastName'][$i];
            $pre_data[$i]['SonOccupation'] = $data['SonOccupation'][$i];
            $pre_data[$i]['SonAge'] = $data['SonAge'][$i];
            $query = $this->db->get_where('employee_family_detail', array('FID' => $FID, 'FDSeqNo' => $i));
            if ($query->num_rows() == 0) {
                if ($this->db->insert('employee_family_detail', $pre_data[$i]))
                    $count++;
            } else {
                $this->db->where('FID', $FID);
                $this->db->where('FDSeqNo', $i);
                if ($this->db->update('employee_family_detail', $pre_data[$i]))
                    $count++;
            }
        }
        if (count($pre_data) == $count)
            return TRUE;
        else
            return FALSE;
    }

    function update_family($data, $EID) {
        $data = $this->set_update_date($data);
        $this->db->where('EID', $EID);
        $this->db->update('employee_family', $data);
        if ($this->db->affected_rows() == 1) {
            $query = $this->db->get_where('employee_family', array('EID' => $EID));
            return $query->result_array();
        } else
            return FALSE;
    }

    function update_education($data, $EID) {
        $pre_data = array();
        $count = 0;
        for ($i = 0; $i < count($data['InstitutionName']); $i++) {
            $pre_data[$i]['EID'] = $EID;
            $pre_data[$i]['EDSeqNo'] = $i;
            $pre_data[$i]['InstitutionName'] = $data['InstitutionName'][$i];
            $pre_data[$i]['EDMajor'] = $data['EDMajor'][$i];
            $pre_data[$i]['EDDateFrom'] = $data['EDDateFrom'][$i];
            $pre_data[$i]['EDDateTo'] = $data['EDDateTo'][$i];
            $query = $this->db->get_where('employee_education', array('EID' => $EID, 'EDSeqNo' => $i));
            if ($query->num_rows() == 0) {
                if ($this->db->insert('employee_education', $pre_data[$i]))
                    $count++;
            } else {
                $this->db->where('EID', $EID);
                $this->db->where('EDSeqNo', $i);
                if ($this->db->update('employee_education', $pre_data[$i]))
                    $count++;
            }
        }
        if (count($pre_data) == $count)
            return TRUE;
        else
            return FALSE;
    }

}
