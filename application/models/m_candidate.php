<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_candidate extends CI_Model {

    function check_miscellaneous($MiscName) {
        $query = $this->db->get_where('miscellaneous', array('MiscName' => $MiscName));
        return $query->result_array();
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

    function check_candidate($CID) {
        $this->db->from('candidate');
        $this->db->where('CID', $CID);
        $num = $this->db->count_all_results();
        if ($num == 1)
            return TRUE;
        else
            return FALSE;
    }

    function check_candidate_detail($CID) {
        $data = array();
        $this->db->from('candidate AS ca');
        $this->db->join('candidate_emergency_contact AS ce', 'ce.ECID = ca.ECID');
        $this->db->join('candidate_parent AS cp', 'cp.CID = ca.CID');
        $this->db->where('ca.CID', $CID);
        $query = $this->db->get();
        $data = $query->result_array()[0];

        $this->db->from('candidate_family AS cf');
        $this->db->where('cf.CID', $CID);
        $query = $this->db->get();
        $data['family'] = $query->result_array()[0];

        $this->db->from('candidate_family_detail AS cfd');
        $this->db->where('cfd.FID', $data['family']['FID']);
        $query = $this->db->get();
        $data['family_detail'] = $query->result_array();

        $this->db->from('candidate_education AS edu');
        $this->db->where('edu.CID', $CID);
        $query = $this->db->get();
        $data['education'] = $query->result_array();

        $this->db->from('candidate_experience AS exp');
        $this->db->where('exp.CID', $CID);
        $query = $this->db->get();
        $data['experience'] = $query->result_array();

        return $data;
    }

    function check_employee_positions() {
        $query = $this->db->get('employee_positions');
        return $query->result_array();
    }

    function insert_all_data($data) {
        $ECID = $this->insert_emergency($data['emergency']);
        if ($ECID == FALSE)
            return FALSE;
        $data['ECID'] = $ECID;

        $CID = $this->insert_person_information($data);
        if ($CID == FALSE)
            return FALSE;

        $data['parent']['CID'] = $CID;
        if (count($data['parent']) > 0 && !$this->insert_parent($data['parent']))
            return FALSE;

        if ($data['experience']['ExCompanyName'][0] != NULL && !$this->insert_experience($data['experience'], $CID))
            return FALSE;

        if ($data['education']['InstitutionName'][0] != NULL && !$this->insert_education($data['education'], $CID))
            return FALSE;

        if ($data['family']['SpouseFirstName'] != NULL) {
            $FID = $this->insert_family($data['family'], $CID);
            if ($FID == FALSE)
                return FALSE;
            if (!$this->insert_family_detail($data['family_detail'], $FID))
                return FALSE;
        }

        return $CID;
    }

    function insert_person_information($data) {
        unset($data['emergency']);
        unset($data['experience']);
        unset($data['family']);
        unset($data['family_detail']);
        unset($data['parent']);
        unset($data['education']);
        $data['CID'] = $this->gen_cid();
        $data = $this->set_create_date($data);
        if ($this->db->insert('candidate', $data))
            return $data['CID'];
        else
            return FALSE;
    }

    function gen_cid() {
        $this->db->order_by("CID", "desc");
        $this->db->limit(2);
        $query = $this->db->get('candidate');
        $temp = $query->result_array();
        $last_cid = 'SHMPT00000';
        foreach ($temp as $row) {
            $last_cid = $row['CID'];
            break;
        }
        $num = substr($last_cid, 5);
        $num+=1;
        $num = str_pad($num, 5, '0', STR_PAD_LEFT);
        $last_cid = 'SHMPT' . $num;
        return $last_cid;
    }

    function insert_family($data, $CID) {
        $this->db->order_by("FID", "desc");
        $this->db->limit(2);
        $query = $this->db->get('candidate_family');
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

        $data['CID'] = $CID;
        $data['FID'] = $last_fid;
        $data = $this->set_create_date($data);
        if ($this->db->insert('candidate_family', $data))
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
        if ($this->db->insert_batch('candidate_family_detail', $pre_data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_parent($data) {
        $data = $this->set_create_date($data);
        if ($this->db->insert('candidate_parent', $data))
            return $data['CID'];
        else
            return FALSE;
    }

    function insert_experience($data, $CID) {
        $pre_data = array();
        for ($i = 0; $i < count($data['ExCompanyName']); $i++) {
            $pre_data[$i]['CID'] = $CID;
            $pre_data[$i]['ExSeqNo'] = $i;
            $pre_data[$i]['ExCompanyName'] = $data['ExCompanyName'][$i];
            $pre_data[$i]['ExDateForm'] = $data['ExDateForm'][$i];
            $pre_data[$i]['ExDateTo'] = $data['ExDateTo'][$i];
            $pre_data[$i]['ExPositionName'] = $data['ExPositionName'][$i];
            $pre_data[$i]['ExSaraly'] = $data['ExSaraly'][$i];
            $pre_data[$i]['ReasonOfResign'] = $data['ReasonOfResign'][$i];
            $pre_data[$i] = $this->set_create_date($pre_data[$i]);
        }
        if ($this->db->insert_batch('candidate_experience', $pre_data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_education($data, $CID) {
        $pre_data = array();
        for ($i = 0; $i < count($data['InstitutionName']); $i++) {
            $pre_data[$i]['CID'] = $CID;
            $pre_data[$i]['EDSeqNo'] = $i;
            $pre_data[$i]['InstitutionName'] = $data['InstitutionName'][$i];
            $pre_data[$i]['EDMajor'] = $data['EDMajor'][$i];
            $pre_data[$i]['EDDateFrom'] = $data['EDDateFrom'][$i];
            $pre_data[$i]['EDDateTo'] = $data['EDDateTo'][$i];
        }
        if ($this->db->insert_batch('candidate_education', $pre_data))
            return TRUE;
        else
            return FALSE;
    }

    function insert_emergency($data) {
        $data = $this->set_create_date($data);
        if ($this->db->insert('candidate_emergency_contact', $data))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function set_form($c_data = NULL) {
        $temp = $this->m_candidate->check_employee_positions();
        $i_PID = array();
        foreach ($temp as $row) {
            $i_PID[$row['PID']] = trim($row['PositionName']);
        }
        $temp = $this->m_candidate->check_miscellaneous('title');
        $i_Title = array();
        foreach ($temp as $row) {
            $i_Title[trim($row['StringValue'])] = trim($row['StringValue']);
        }
        $i_ExpectedPermanantSalary = array(
            'name' => 'ExpectedPermanantSalary',
            'type' => 'number',
            'min' => 0,
            'step' => 100,
            'value' => ($c_data != NULL) ? $c_data['ExpectedPermanantSalary'] : set_value('ExpectedPermanantSalary'),
            'placeholder' => 'เงินเดือนที่ต้องการ',
            'class' => 'form-control');
        $i_FirstName = array(
            'name' => 'FirstName',
            'value' => ($c_data != NULL) ? $c_data['FirstName'] : set_value('FirstName'),
            'placeholder' => 'ชื่อ',
            'class' => 'form-control');
        $i_LastName = array(
            'name' => 'LastName',
            'value' => ($c_data != NULL) ? $c_data['LastName'] : set_value('LastName'),
            'placeholder' => 'นามสกุล',
            'class' => 'form-control');
        $i_PersonalID = array(
            'name' => 'PersonalID',
            'value' => ($c_data != NULL) ? $c_data['PersonalID'] : set_value('PersonalID'),
            'placeholder' => 'เลขประจำตัวประชาชน',
            'class' => 'form-control');
        $i_AvaliableStartDate = array(
            'name' => 'AvaliableStartDate',
            'value' => ($c_data != NULL) ? $c_data['AvaliableStartDate'] : set_value('AvaliableStartDate'),
            'class' => 'form-control datepicker');

        // Person information
        $i_BirthDate = array(
            'name' => 'BirthDate',
            'value' => ($c_data != NULL) ? $c_data['BirthDate'] : set_value('BirthDate'),
            'class' => 'form-control datepicker');
        $i_Age = array(
            'name' => 'Age',
            'type' => 'number',
            'min' => 18,
            'value' => ($c_data != NULL) ? $c_data['Age'] : set_value('Age'),
            'class' => 'form-control');
        $i_NickName = array(
            'name' => 'NickName',
            'value' => ($c_data != NULL) ? $c_data['NickName'] : set_value('NickName'),
            'class' => 'form-control');
        $i_Race = array(
            'name' => 'Race',
            'value' => ($c_data != NULL) ? $c_data['Race'] : set_value('Race'),
            'class' => 'form-control');
        $i_Nationality = array(
            'name' => 'Nationality',
            'value' => ($c_data != NULL) ? $c_data['Nationality'] : set_value('Nationality'),
            'class' => 'form-control');
        $i_Religion = array(
            'name' => 'Religion',
            'value' => ($c_data != NULL) ? $c_data['Religion'] : set_value('Religion'),
            'class' => 'form-control');
        $temp = $this->m_candidate->check_miscellaneous('sex');
        $i_Sex = array();
        foreach ($temp as $row) {
            $temp2 = array(
                'name' => 'Sex',
                'type' => 'radio',
                'value' => trim($row['StringValue'])
            );
            if ((($c_data != NULL) ? $c_data['Sex'] : set_value('Sex')) == $row['StringValue'])
                $temp2['checked'] = TRUE;
            $i_Sex[trim($row['StringValue'])] = form_checkbox($temp2) . $temp2['value'];
        }
        $i_Weight = array(
            'name' => 'Weight',
            'value' => ($c_data != NULL) ? $c_data['Weight'] : set_value('Weight'),
            'class' => 'form-control');
        $i_Height = array(
            'name' => 'Height',
            'value' => ($c_data != NULL) ? $c_data['Height'] : set_value('Height'),
            'class' => 'form-control');
        $i_CurrentHouseNumber = array(
            'name' => 'CurrentHouseNumber',
            'value' => ($c_data != NULL) ? $c_data['CurrentHouseNumber'] : set_value('CurrentHouseNumber'),
            'class' => 'form-control');
        $i_CurrentMu = array(
            'name' => 'CurrentMu',
            'value' => ($c_data != NULL) ? $c_data['CurrentMu'] : set_value('CurrentMu'),
            'class' => 'form-control');
        $i_CurrentStreet = array(
            'name' => 'CurrentStreet',
            'value' => ($c_data != NULL) ? $c_data['CurrentStreet'] : set_value('CurrentStreet'),
            'class' => 'form-control');
        $i_CurrentVillage = array(
            'name' => 'CurrentVillage',
            'value' => ($c_data != NULL) ? $c_data['CurrentVillage'] : set_value('CurrentVillage'),
            'class' => 'form-control');
        $i_CurrentSubDistrict = array(
            'name' => 'CurrentSubDistrict',
            'value' => ($c_data != NULL) ? $c_data['CurrentSubDistrict'] : set_value('CurrentSubDistrict'),
            'class' => 'form-control');
        $i_CurrentDistrict = array(
            'name' => 'CurrentDistrict',
            'value' => ($c_data != NULL) ? $c_data['CurrentDistrict'] : set_value('CurrentDistrict'),
            'class' => 'form-control');
        $i_CurrentProvince = array(
            'name' => 'CurrentProvince',
            'value' => ($c_data != NULL) ? $c_data['CurrentProvince'] : set_value('CurrentProvince'),
            'class' => 'form-control');
        $i_CurrentZipCode = array(
            'name' => 'CurrentZipCode',
            'value' => ($c_data != NULL) ? $c_data['CurrentZipCode'] : set_value('CurrentZipCode'),
            'class' => 'form-control');
        $i_MobilePhone = array(
            'name' => 'MobilePhone',
            'value' => ($c_data != NULL) ? $c_data['MobilePhone'] : set_value('MobilePhone'),
            'class' => 'form-control');
        $temp = $this->m_candidate->check_miscellaneous('residential');
        $i_Residential = array();
        foreach ($temp as $row) {
            $temp2 = array(
                'name' => 'Residential',
                'type' => 'radio',
                'value' => trim($row['StringValue'])
            );
            if ((($c_data != NULL) ? $c_data['Residential'] : set_value('Residential')) == $row['StringValue'])
                $temp2['checked'] = TRUE;
            $i_Residential[trim($row['StringValue'])] = form_checkbox($temp2) . $temp2['value'];
        }
        $temp = $this->m_candidate->check_miscellaneous('militaryservicestatus');
        $i_MilitaryServiceStatus = array();
        foreach ($temp as $row) {
            $temp2 = array(
                'name' => 'MilitaryServiceStatus',
                'type' => 'radio',
                'value' => trim($row['StringValue'])
            );
            if ((($c_data != NULL) ? $c_data['MilitaryServiceStatus'] : set_value('MilitaryServiceStatus')) == $row['StringValue'])
                $temp2['checked'] = TRUE;
            $i_MilitaryServiceStatus[trim($row['StringValue'])] = form_checkbox($temp2) . $temp2['value'];
        }
        $temp = $this->m_candidate->check_miscellaneous('maritalstatus');
        $i_MaritalStatus = array();
        foreach ($temp as $row) {
            $temp2 = array(
                'name' => 'MaritalStatus',
                'id' => 'MaritalStatus',
                'type' => 'radio',
                'value' => trim($row['StringValue'])
            );
            if ((($c_data != NULL) ? $c_data['MaritalStatus'] : set_value('MaritalStatus')) == $row['StringValue'])
                $temp2['checked'] = TRUE;
            $i_MaritalStatus[trim($row['StringValue'])] = form_checkbox($temp2) . $temp2['value'];
        }
        $temp = $this->m_candidate->check_miscellaneous('title');
        $i_SpouseTitle = array();
        foreach ($temp as $row) {
            $i_SpouseTitle[trim($row['StringValue'])] = trim($row['StringValue']);
        }
        $i_SpouseFirstName = array(
            'name' => 'SpouseFirstName',
            'value' => ($c_data != NULL) ? $c_data['family']['SpouseFirstName'] : set_value('SpouseFirstName'),
            'class' => 'form-control');
        $i_SpouseLastName = array(
            'name' => 'SpouseLastName',
            'value' => ($c_data != NULL) ? $c_data['family']['SpouseLastName'] : set_value('SpouseLastName'),
            'class' => 'form-control');
        $i_SpouseAge = array(
            'name' => 'SpouseAge',
            'type' => 'number',
            'min' => 18,
            'value' => ($c_data != NULL) ? $c_data['family']['SpouseAge'] : set_value('SpouseAge'),
            'class' => 'form-control');
        $i_SpouseOccupation = array(
            'name' => 'SpouseOccupation',
            'value' => ($c_data != NULL) ? $c_data['family']['SpouseOccupation'] : set_value('SpouseOccupation'),
            'class' => 'form-control');
        $temp = array(
            'name' => 'SpouseIsAlive',
            'type' => 'radio',
            'value' => 0
        );
        if ((($c_data != NULL) ? $c_data['family']['SpouseIsAlive'] : set_value('SpouseIsAlive')) == 0)
            $temp['checked'] = TRUE;
        $i_SpouseIsAlive[0] = form_checkbox($temp) . 'ยังมีชีวิต';
        $temp = array(
            'name' => 'SpouseIsAlive',
            'type' => 'radio',
            'value' => 0
        );
        if ((($c_data != NULL) ? $c_data['family']['SpouseIsAlive'] : set_value('SpouseIsAlive')) == 1)
            $temp['checked'] = TRUE;
        $temp['value'] = 1;
        $i_SpouseIsAlive[1] = form_checkbox($temp) . 'ถึงแก่กรรม';
        $i_NumberSon = array(
            'name' => 'NumberSon',
            'type' => 'number',
            'min' => 0,
            'value' => ($c_data != NULL) ? $c_data['family']['NumberSon'] : set_value('NumberSon'),
            'class' => 'form-control');

        $son_num = $this->input->post('NumberSon');
        if ($son_num == NULL || $son_num == "")
            $son_num = 0;
        if ($c_data != NULL)
            $son_num = count($c_data['family_detail']);
        $f_SonTitle = array();
        $f_SonFirstName = array();
        $f_SonLastName = array();
        $f_SonAge = array();
        $f_SonOccupation = array();
        for ($i = 0; $i < $son_num; $i++) {
            $i_SonTitle = array(
                'name' => 'SonTitle[]',
                'value' => ($c_data != NULL) ? $c_data['family_detail'][$i]['SonTitle'] : set_value('SonTitle[]'),
                'class' => 'form-control');
            $i_SonFirstName = array(
                'name' => 'SonFirstName[]',
                'value' => ($c_data != NULL) ? $c_data['family_detail'][$i]['SonFirstName'] : set_value('SonFirstName[]'),
                'class' => 'form-control');
            $i_SonLastName = array(
                'name' => 'SonLastName[]',
                'value' => ($c_data != NULL) ? $c_data['family_detail'][$i]['SonLastName'] : set_value('SonLastName[]'),
                'class' => 'form-control');
            $i_SonAge = array(
                'name' => 'SonAge[]',
                'value' => ($c_data != NULL) ? $c_data['family_detail'][$i]['SonAge'] : set_value('SonAge[]'),
                'class' => 'form-control');
            $i_SonOccupation = array(
                'name' => 'SonOccupation[]',
                'value' => ($c_data != NULL) ? $c_data['family_detail'][$i]['SonOccupation'] : set_value('SonOccupation[]'),
                'class' => 'form-control');
            array_push($f_SonTitle, form_input($i_SonTitle));
            array_push($f_SonFirstName, form_input($i_SonFirstName));
            array_push($f_SonLastName, form_input($i_SonLastName));
            array_push($f_SonAge, form_input($i_SonAge));
            array_push($f_SonOccupation, form_input($i_SonOccupation));
        }


        // Parent information
        $temp = $this->m_candidate->check_miscellaneous('title');
        $i_FatherTitle = array();
        foreach ($temp as $row) {
            $i_FatherTitle[trim($row['StringValue'])] = trim($row['StringValue']);
        }
        $i_FatherFirstName = array(
            'name' => 'FatherFirstName',
            'value' => ($c_data != NULL) ? $c_data['FatherFirstName'] : set_value('FatherFirstName'),
            'class' => 'form-control');
        $i_FatherLastName = array(
            'name' => 'FatherLastName',
            'value' => ($c_data != NULL) ? $c_data['FatherLastName'] : set_value('FatherLastName'),
            'class' => 'form-control');
        $i_FatherAge = array(
            'name' => 'FatherAge',
            'type' => 'number',
            'min' => 18,
            'value' => ($c_data != NULL) ? $c_data['FatherAge'] : set_value('FatherAge'),
            'class' => 'form-control');
        $i_FatherOccupation = array(
            'name' => 'FatherOccupation',
            'value' => ($c_data != NULL) ? $c_data['FatherOccupation'] : set_value('FatherOccupation'),
            'class' => 'form-control');
        $temp = array(
            'name' => 'FatherIsAlive',
            'type' => 'radio',
            'value' => 0
        );
        if ((($c_data != NULL) ? $c_data['FatherIsAlive'] : set_value('FatherIsAlive')) == 0)
            $temp['checked'] = TRUE;
        $i_FatherIsAlive[0] = form_checkbox($temp) . 'ยังมีชีวิต';
        $temp = array(
            'name' => 'FatherIsAlive',
            'type' => 'radio',
            'value' => 0
        );
        if ((($c_data != NULL) ? $c_data['FatherIsAlive'] : set_value('FatherIsAlive')) == 1)
            $temp['checked'] = TRUE;
        $temp['value'] = 1;
        $i_FatherIsAlive[1] = form_checkbox($temp) . 'ถึงแก่กรรม';

        $temp = $this->m_candidate->check_miscellaneous('title');
        $i_MotherTitle = array();
        foreach ($temp as $row) {
            $i_MotherTitle[trim($row['StringValue'])] = trim($row['StringValue']);
        }
        $i_MotherFirstName = array(
            'name' => 'MotherFirstName',
            'value' => ($c_data != NULL) ? $c_data['MotherFirstName'] : set_value('MotherFirstName'),
            'class' => 'form-control');
        $i_MotherLastName = array(
            'name' => 'MotherLastName',
            'value' => ($c_data != NULL) ? $c_data['MotherLastName'] : set_value('MotherLastName'),
            'class' => 'form-control');
        $i_MotherAge = array(
            'name' => 'MotherAge',
            'type' => 'number',
            'min' => 18,
            'value' => ($c_data != NULL) ? $c_data['MotherAge'] : set_value('MotherAge'),
            'class' => 'form-control');
        $i_MotherOccupation = array(
            'name' => 'MotherOccupation',
            'value' => ($c_data != NULL) ? $c_data['MotherOccupation'] : set_value('MotherOccupation'),
            'class' => 'form-control');
        $temp = array(
            'name' => 'MotherIsAlive',
            'type' => 'radio',
            'value' => 0
        );
        if ((($c_data != NULL) ? $c_data['MotherIsAlive'] : set_value('MotherIsAlive')) == 0)
            $temp['checked'] = TRUE;
        $i_MotherIsAlive[0] = form_checkbox($temp) . 'ยังมีชีวิต';
        $temp = array(
            'name' => 'MotherIsAlive',
            'type' => 'radio',
            'value' => 0
        );
        if ((($c_data != NULL) ? $c_data['MotherIsAlive'] : set_value('MotherIsAlive')) == 1)
            $temp['checked'] = TRUE;
        $temp['value'] = 1;
        $i_MotherIsAlive[1] = form_checkbox($temp) . 'ถึงแก่กรรม';

        // Education information
        $f_InstitutionName = array();
        $f_EDMajor = array();
        $f_EDDateFrom = array();
        $f_EDDateTo = array();
        for ($i = 0; $i < 6; $i++) {
            $i_InstitutionName = array(
                'name' => 'InstitutionName[]',
                'value' => ($c_data != NULL) ? $c_data['education'][$i]['InstitutionName'] : set_value('InstitutionName[]'),
                'class' => 'form-control');
            $i_EDMajor = array(
                'name' => 'EDMajor[]',
                'value' => ($c_data != NULL) ? $c_data['education'][$i]['EDMajor'] : set_value('EDMajor[]'),
                'class' => 'form-control');
            $i_EDDateFrom = array(
                'name' => 'EDDateFrom[]',
                'value' => ($c_data != NULL) ? $c_data['education'][$i]['EDDateFrom'] : set_value('EDDateFrom[]'),
                'class' => 'form-control datepicker');
            $i_EDDateTo = array(
                'name' => 'EDDateTo[]',
                'value' => ($c_data != NULL) ? $c_data['education'][$i]['EDDateTo'] : set_value('EDDateTo[]'),
                'class' => 'form-control datepicker');
            array_push($f_InstitutionName, form_input($i_InstitutionName));
            array_push($f_EDMajor, form_input($i_EDMajor));
            array_push($f_EDDateFrom, form_input($i_EDDateFrom));
            array_push($f_EDDateTo, form_input($i_EDDateTo));
        }

        // Experience information
        $exp_num = ($c_data != NULL) ? count($c_data['experience']) : count($this->input->post('ExCompanyName'));
        $f_ExCompanyName = array();
        $f_ExDateForm = array();
        $f_ExDateTo = array();
        $f_ExPositionName = array();
        $f_ExSaraly = array();
        $f_ReasonOfResign = array();
        for ($i = 0; $i < $exp_num; $i++) {
            $i_ExCompanyName = array(
                'name' => 'ExCompanyName[]',
                'value' => ($c_data != NULL) ? $c_data['experience'][$i]['ExCompanyName'] : set_value('ExCompanyName[]'),
                'class' => 'form-control');
            $i_ExDateForm = array(
                'name' => 'ExDateForm[]',
                'value' => ($c_data != NULL) ? $c_data['experience'][$i]['ExDateForm'] : set_value('ExDateForm[]'),
                'class' => 'form-control datepicker');
            $i_ExDateTo = array(
                'name' => 'ExDateTo[]',
                'value' => ($c_data != NULL) ? $c_data['experience'][$i]['ExDateTo'] : set_value('ExDateTo[]'),
                'class' => 'form-control datepicker');
            $i_ExPositionName = array(
                'name' => 'ExPositionName[]',
                'value' => ($c_data != NULL) ? $c_data['experience'][$i]['ExPositionName'] : set_value('ExPositionName[]'),
                'class' => 'form-control');
            $i_ExSaraly = array(
                'name' => 'ExSaraly[]',
                'value' => ($c_data != NULL) ? $c_data['experience'][$i]['ExSaraly'] : set_value('ExSaraly[]'),
                'class' => 'form-control');
            $i_ReasonOfResign = array(
                'name' => 'ReasonOfResign[]',
                'value' => ($c_data != NULL) ? $c_data['experience'][$i]['ReasonOfResign'] : set_value('ReasonOfResign[]'),
                'class' => 'form-control');
            array_push($f_ExCompanyName, form_input($i_ExCompanyName));
            array_push($f_ExDateForm, form_input($i_ExDateForm));
            array_push($f_ExDateTo, form_input($i_ExDateTo));
            array_push($f_ExPositionName, form_input($i_ExPositionName));
            array_push($f_ExSaraly, form_input($i_ExSaraly));
            array_push($f_ReasonOfResign, form_input($i_ReasonOfResign));
        }

        // Emergency_contact
        $temp = $this->m_candidate->check_miscellaneous('title');
        $i_ECTitle = array();
        foreach ($temp as $row) {
            $i_ECTitle[trim($row['StringValue'])] = trim($row['StringValue']);
        }
        $i_ECFirstName = array(
            'name' => 'ECFirstName',
            'value' => ($c_data != NULL) ? $c_data['ECFirstName'] : set_value('ECFirstName'),
            'class' => 'form-control');
        $i_ECLastName = array(
            'name' => 'ECLastName',
            'value' => ($c_data != NULL) ? $c_data['ECLastName'] : set_value('ECLastName'),
            'class' => 'form-control');
        $i_ECRelationShip = array(
            'name' => 'ECRelationShip',
            'value' => ($c_data != NULL) ? $c_data['ECRelationShip'] : set_value('ECRelationShip'),
            'class' => 'form-control');
        $i_ECAddress = array(
            'name' => 'ECAddress',
            'rows' => 3,
            'value' => ($c_data != NULL) ? $c_data['ECAddress'] : set_value('ECAddress'),
            'class' => 'form-control');
        $i_ECMobilePhone = array(
            'name' => 'ECMobilePhone',
            'value' => ($c_data != NULL) ? $c_data['ECMobilePhone'] : set_value('ECMobilePhone'),
            'class' => 'form-control');

        $data = array(
            'PID' => form_dropdown('PID', $i_PID, ($c_data != NULL) ? $c_data['PID'] : set_value('PID'), "class=\"selecter_1\""),
            'Title' => form_dropdown('Title', $i_Title, ($c_data != NULL) ? $c_data['Title'] : set_value('Title'), "class=\"selecter_1\""),
            'ExpectedPermanantSalary' => form_input($i_ExpectedPermanantSalary),
            'FirstName' => form_input($i_FirstName),
            'LastName' => form_input($i_LastName),
            'PersonalID' => form_input($i_PersonalID),
            'AvaliableStartDate' => form_input($i_AvaliableStartDate),
            // Person information
            'BirthDate' => form_input($i_BirthDate),
            'Age' => form_input($i_Age),
            'NickName' => form_input($i_NickName),
            'Race' => form_input($i_Race),
            'Nationality' => form_input($i_Nationality),
            'Religion' => form_input($i_Religion),
            'Sex' => $i_Sex,
            'Weight' => form_input($i_Weight),
            'Height' => form_input($i_Height),
            'CurrentHouseNumber' => form_input($i_CurrentHouseNumber),
            'CurrentMu' => form_input($i_CurrentMu),
            'CurrentStreet' => form_input($i_CurrentStreet),
            'CurrentVillage' => form_input($i_CurrentVillage),
            'CurrentSubDistrict' => form_input($i_CurrentSubDistrict),
            'CurrentDistrict' => form_input($i_CurrentDistrict),
            'CurrentProvince' => form_input($i_CurrentProvince),
            'CurrentZipCode' => form_input($i_CurrentZipCode),
            'MobilePhone' => form_input($i_MobilePhone),
            'Residential' => $i_Residential,
            'MilitaryServiceStatus' => $i_MilitaryServiceStatus,
            'MaritalStatus' => $i_MaritalStatus,
            'SpouseTitle' => form_dropdown('SpouseTitle', $i_SpouseTitle, ($c_data != NULL) ? $c_data['family']['SpouseTitle'] : set_value('SpouseTitle'), "class=\"selecter_1\""),
            'SpouseFirstName' => form_input($i_SpouseFirstName),
            'SpouseLastName' => form_input($i_SpouseLastName),
            'SpouseAge' => form_input($i_SpouseAge),
            'SpouseOccupation' => form_input($i_SpouseOccupation),
            'SpouseIsAlive' => $i_SpouseIsAlive,
            'NumberSon' => form_input($i_NumberSon),
            'SonTitle' => $f_SonTitle,
            'SonFirstName' => $f_SonFirstName,
            'SonLastName' => $f_SonLastName,
            'SonAge' => $f_SonAge,
            'SonOccupation' => $f_SonOccupation,
            // Parent information
            'FatherTitle' => form_dropdown('FatherTitle', $i_FatherTitle, ($c_data != NULL) ? $c_data['FatherTitle'] : set_value('FatherTitle'), "class=\"selecter_1\""),
            'FatherFirstName' => form_input($i_FatherFirstName),
            'FatherLastName' => form_input($i_FatherLastName),
            'FatherAge' => form_input($i_FatherAge),
            'FatherOccupation' => form_input($i_FatherOccupation),
            'FatherIsAlive' => $i_FatherIsAlive,
            'MotherTitle' => form_dropdown('MotherTitle', $i_MotherTitle, ($c_data != NULL) ? $c_data['MotherTitle'] : set_value('MotherTitle'), "class=\"selecter_1\""),
            'MotherFirstName' => form_input($i_MotherFirstName),
            'MotherLastName' => form_input($i_MotherLastName),
            'MotherAge' => form_input($i_MotherAge),
            'MotherOccupation' => form_input($i_MotherOccupation),
            'MotherIsAlive' => $i_MotherIsAlive,
            // Education information
            'InstitutionName' => $f_InstitutionName,
            'EDMajor' => $f_EDMajor,
            'EDDateFrom' => $f_EDDateFrom,
            'EDDateTo' => $f_EDDateTo,
            // Experience information
            'ExCompanyName' => $f_ExCompanyName,
            'ExDateForm' => $f_ExDateForm,
            'ExDateTo' => $f_ExDateTo,
            'ExPositionName' => $f_ExPositionName,
            'ExSaraly' => $f_ExSaraly,
            'ReasonOfResign' => $f_ReasonOfResign,
            // Emergency_contact
            'ECTitle' => form_dropdown('ECTitle', $i_ECTitle, ($c_data != NULL) ? $c_data['ECTitle'] : set_value('ECTitle'), "class=\"selecter_1\""),
            'ECFirstName' => form_input($i_ECFirstName),
            'ECLastName' => form_input($i_ECLastName),
            'ECRelationShip' => form_input($i_ECRelationShip),
            'ECAddress' => form_textarea($i_ECAddress),
            'ECMobilePhone' => form_input($i_ECMobilePhone),
        );
        return $data;
    }

    function set_validation() {
        $this->form_validation->set_rules('PID', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('Title', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('ExpectedPermanantSalary', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('FirstName', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('LastName', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('PersonalID', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('AvaliableStartDate', '', 'trim|required|xss_clean');
        // Person information
        $this->form_validation->set_rules('BirthDate', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Age', '', 'trim|xss_clean');
        $this->form_validation->set_rules('NickName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Race', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Nationality', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Religion', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Sex', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Weight', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Height', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentHouseNumber', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentMu', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentStreet', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentVillage', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentSubDistrict', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentDistrict', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentProvince', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentZipCode', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MobilePhone', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Residential', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MilitaryServiceStatus', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MaritalStatus', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SpouseTitle', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SpouseFirstName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SpouseLastName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SpouseAge', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SpouseOccupation', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SpouseIsAlive', '', 'trim|xss_clean');
        $this->form_validation->set_rules('NumberSon', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SonTitle[]', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SonFirstName[]', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SonLastName[]', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SonAge[]', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SonOccupation[]', '', 'trim|xss_clean');
        // Parent information
        $this->form_validation->set_rules('FatherTitle', '', 'trim|xss_clean');
        $this->form_validation->set_rules('FatherFirstName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('FatherLastName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('FatherAge', '', 'trim|xss_clean');
        $this->form_validation->set_rules('FatherOccupation', '', 'trim|xss_clean');
        $this->form_validation->set_rules('FatherIsAlive', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MotherTitle', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MotherFirstName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MotherLastName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MotherAge', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MotherOccupation', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MotherIsAlive', '', 'trim|xss_clean');
        // Education information
        $this->form_validation->set_rules('InstitutionName[]', '', 'trim|xss_clean');
        $this->form_validation->set_rules('EDMajor[]', '', 'trim|xss_clean');
        $this->form_validation->set_rules('EDDateFrom[]', '', 'trim|xss_clean');
        $this->form_validation->set_rules('EDDateTo[]', '', 'trim|xss_clean');
        // Experience information
        $this->form_validation->set_rules('ExCompanyName[]', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ExDateForm[]', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ExDateTo[]', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ExPositionName[]', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ExSaraly[]', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ReasonOfResign[]', '', 'trim|xss_clean');
        // Emergency_contact
        $this->form_validation->set_rules('ECTitle', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('ECFirstName', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('ECLastName', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('ECRelationShip', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('ECAddress', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('ECMobilePhone', '', 'trim|required|xss_clean');
        return true;
    }

    function get_post() {
        $f_data = array(
            'PID' => $this->input->post('PID'),
            'Title' => $this->input->post('Title'),
            'ExpectedPermanantSalary' => $this->input->post('ExpectedPermanantSalary'),
            'FirstName' => $this->input->post('FirstName'),
            'LastName' => $this->input->post('LastName'),
            'PersonalID' => $this->input->post('PersonalID'),
            'AvaliableStartDate' => $this->input->post('AvaliableStartDate'),
            // Person information
            'BirthDate' => $this->input->post('BirthDate'),
            'Age' => $this->input->post('Age'),
            'NickName' => $this->input->post('NickName'),
            'Race' => $this->input->post('Race'),
            'Nationality' => $this->input->post('Nationality'),
            'Religion' => $this->input->post('Religion'),
            'Sex' => $this->input->post('Sex'),
            'Weight' => $this->input->post('Weight'),
            'Height' => $this->input->post('Height'),
            'CurrentHouseNumber' => $this->input->post('CurrentHouseNumber'),
            'CurrentMu' => $this->input->post('CurrentMu'),
            'CurrentStreet' => $this->input->post('CurrentStreet'),
            'CurrentVillage' => $this->input->post('CurrentVillage'),
            'CurrentSubDistrict' => $this->input->post('CurrentSubDistrict'),
            'CurrentDistrict' => $this->input->post('CurrentDistrict'),
            'CurrentProvince' => $this->input->post('CurrentProvince'),
            'CurrentZipCode' => $this->input->post('CurrentZipCode'),
            'MobilePhone' => $this->input->post('MobilePhone'),
            'Residential' => $this->input->post('Residential'),
            'MilitaryServiceStatus' => $this->input->post('MilitaryServiceStatus'),
            'MaritalStatus' => $this->input->post('MaritalStatus'),
            // Family
            'family' => array(
                'SpouseTitle' => $this->input->post('SpouseTitle'),
                'SpouseFirstName' => $this->input->post('SpouseFirstName'),
                'SpouseLastName' => $this->input->post('SpouseLastName'),
                'SpouseAge' => $this->input->post('SpouseAge'),
                'SpouseOccupation' => $this->input->post('SpouseOccupation'),
                'SpouseIsAlive' => $this->input->post('SpouseIsAlive'),
                'NumberSon' => $this->input->post('NumberSon')),
            // Family detail
            'family_detail' => array(
                'SonTitle' => $this->input->post('SonTitle'),
                'SonFirstName' => $this->input->post('SonFirstName'),
                'SonLastName' => $this->input->post('SonLastName'),
                'SonAge' => $this->input->post('SonAge'),
                'SonOccupation' => $this->input->post('SonOccupation')),
            // Parent information
            'parent' => array(
                'FatherTitle' => $this->input->post('FatherTitle'),
                'FatherFirstName' => $this->input->post('FatherFirstName'),
                'FatherLastName' => $this->input->post('FatherLastName'),
                'FatherAge' => $this->input->post('FatherAge'),
                'FatherOccupation' => $this->input->post('FatherOccupation'),
                'FatherIsAlive' => $this->input->post('FatherIsAlive'),
                'MotherTitle' => $this->input->post('MotherTitle'),
                'MotherFirstName' => $this->input->post('MotherFirstName'),
                'MotherLastName' => $this->input->post('MotherLastName'),
                'MotherAge' => $this->input->post('MotherAge'),
                'MotherOccupation' => $this->input->post('MotherOccupation'),
                'MotherIsAlive' => $this->input->post('MotherIsAlive'),
            ),
            // Education information
            'education' => array(
                'InstitutionName' => $this->input->post('InstitutionName'),
                'EDMajor' => $this->input->post('EDMajor'),
                'EDDateFrom' => $this->input->post('EDDateFrom'),
                'EDDateTo' => $this->input->post('EDDateTo')),
            // Experience information
            'experience' => array(
                'ExCompanyName' => $this->input->post('ExCompanyName'),
                'ExDateForm' => $this->input->post('ExDateForm'),
                'ExDateTo' => $this->input->post('ExDateTo'),
                'ExPositionName' => $this->input->post('ExPositionName'),
                'ExSaraly' => $this->input->post('ExSaraly'),
                'ReasonOfResign' => $this->input->post('ReasonOfResign')),
            // Emergency_contact
            'emergency' => array(
                'ECTitle' => $this->input->post('ECTitle'),
                'ECFirstName' => $this->input->post('ECFirstName'),
                'ECLastName' => $this->input->post('ECLastName'),
                'ECRelationShip' => $this->input->post('ECRelationShip'),
                'ECAddress' => $this->input->post('ECAddress'),
                'ECMobilePhone' => $this->input->post('ECMobilePhone'))
        );
        return $f_data;
    }

}
