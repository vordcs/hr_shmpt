<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnCandidate").addClass("active");
        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'yyyy-m-d'
        });
        $("#btn_prepare_save").click(function () {
            if ($('#check_agree').is(':checked'))
                $('#modal_work_expreince').modal();
            else
                alert('กรุณากดยอมรับข้อตกลงก่อน');
        });
        $("#btn_save").click(function () {
            if ($('#check_agree').is(':checked'))
                $("#frm_main").submit();
            else
                alert('กรุณากดยอมรับข้อตกลงก่อน');
        });
        $("#frm_main").mouseover(function () {
            var x = $('input[name=MaritalStatus]:checked', '#frm_main').val();
            if (x == 'แต่งงานเเล้ว' || x == 'หม้าย' || x == 'แยกกันอยู่') {
                $("#not_single").removeClass();
            } else {
                $("#not_single").removeClass().addClass('hidden');
            }
            var rowCount = $('#childen tr').length;
            //$("#childen").append('<tr><td>1</td><td><div class="col-sm-4"><select class="form-control selecter_1"><option>นาย</option><option>นาง</option><option>นางสาว</option><option>เด็กชาย</option><option>เด็กหญิง</option></select></div><div class="col-sm-8"><input type="text" class="form-control" id="" placeholder=""></div></td><td><input type="text" class="form-control" id="" placeholder=""></td><td><input type="text" class="form-control" placeholder=""></td><td><input type="text" class="form-control" placeholder=""></td></tr>');

        });

        $('input[name=NumberSon]', '#frm_main').change(function () {
            var row = $('#childen tr').length - 1;
            var num = $('input[name=NumberSon]', '#frm_main').val();
            if (num > row) {
                $("#childen").removeClass().addClass('table table-hover');
                for (var i = num - row; i > 0; i--) {
                    $("#childen").append('<tr><td>' + num + '</td><td><input type="text" name="SonTitle" value="" class="form-control"  /></td><td><input type="text" name="SonFirstName" value="" class="form-control"  /></td><td><input type="text" name="SonLastName" value="" class="form-control"  /></td><td><input type="text" name="SonAge" value="" class="form-control"  /></td><td><input type="text" name="SonOccupation" value="" class="form-control"  /></td></tr>');
                }
            } else if (num < row) {
                $("#childen").removeClass().addClass('table table-hover');
                for (var i = row - num; i > 0; i--) {
                    $('#childen tr:last').remove();
                }
            } else {
                $("#childen").removeClass().addClass('table table-hover hidden');
            }
        });

        $('#btn_expreience').click(function () {
            $("#table_expreience").append('<tr><td><input type="text" name="ExCompanyName[]" value="" class="form-control"  /></td><td><input type="text" name="ExDateForm[]" value="" class="form-control datepicker"  /></td><td><input type="text" name="ExDateTo[]" value="" class="form-control datepicker"  /></td><td><input type="text" name="ExPositionName[]" value="" class="form-control"  /></td><td><input type="text" name="ExSaraly[]" value="" class="form-control"  /></td><td><input type="text" name="ReasonOfResign[]" value="" class="form-control"  /></td></tr> ');
            $('.datepicker').datepicker({
                language: 'th-th',
                format: 'yyyy-m-d'
            });
        });
        $('#btnDel_expreience').click(function () {
            if ($('#table_expreience tr').length > 1)
                $('#table_expreience tr:last').remove();
        });
    });
</script>
<div class="container" style="margin-top: 60px;">
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li>
                    <a href="#">Home</a>
                </li>
                <li>
                    <a href="#">Library</a>
                </li>
                <li class="active">Data</li>
            </ul>
            <div class="page-header">
                <h1>สมัครงาน
                    <font color="#777777">
                    <span style="font-size: 23px; line-height: 23.399999618530273px;">ระบบรับสมัครพนักงานใหม่</span>
                    </font>
                </h1>
            </div>
        </div>
    </div>
    <?= $form_open ?>
    <!--<form class="form-horizontal" role="form">-->
    <div class="row">
        <div class="col-md-3">
            <div class="pull-right">
                <a class="btn btn-info btn-sm"><span class="glyphicon glyphicon-camera"></span></a>
            </div>
            <img src="http://placehold.it/200x150" class="center-block img-responsive img-rounded">
        </div>
        <div class="col-md-9">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">ตำแหน่ง</label>
                <div class="col-sm-5">
                    <?= $form_input['PID'] ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" contenteditable="true">เงินเดือนที่ต้องการ</label>
                <div class="col-sm-5">
                    <?= $form_input['ExpectedPermanantSalary'] ?>
                </div>                
                <div class="col-sm-3">
                    บาท/เดือน
                </div>
            </div>               
            <div class="form-group">
                <label class="col-sm-1 control-label">ชื่อ</label>
                <div class="col-sm-2">
                    <?= $form_input['Title'] ?>
                </div>
                <div class="col-sm-4">
                    <?= $form_input['FirstName'] ?>
                </div>
                <label class="col-sm-1 control-label">นามสกุล</label>
                <div class="col-sm-4">
                    <?= $form_input['LastName'] ?>
                </div>
            </div>                 
            <div class="form-group">
                <label class="col-sm-3 control-label">เลขประจำตัวประชาชน</label>
                <div class="col-sm-7">
                    <?= $form_input['PersonalID'] ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">เริ่มงานได้วันที่</label>
                <div class="col-sm-3">
                    <?= $form_input['AvaliableStartDate'] ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">ข้อมูลส่วนตัว</h3>
                </div>
                <div class="panel-body">

                    <div class="form-group" draggable="true">
                        <div class="col-sm-5">
                            <label for="" class="col-sm-5 control-label">วัน เดือน ปี เกิด</label>
                            <div class="col-sm-7">
                                <?= $form_input['BirthDate'] ?>
                            </div>
                        </div> 
                        <div class="col-sm-2">
                            <label for="" class="col-sm-2 control-label">อายุ</label>
                            <div class="col-sm-7">
                                <?= $form_input['Age'] ?>
                            </div>
                            <label class="col-sm-2 control-label">ปี</label>
                        </div>
                        <div class="col-sm-5">
                            <label for="" class="col-sm-3 control-label">ชื่อเล่น</label>
                            <div class="col-sm-4">
                                <?= $form_input['NickName'] ?>
                            </div>                                           
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label class="col-sm-4 control-label">เชื้อชาติ</label>
                            <div class="col-sm-8">
                                <?= $form_input['Race'] ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="col-sm-4 control-label">สัญชาติ</label>
                            <div class="col-sm-8">
                                <?= $form_input['Nationality'] ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="col-sm-4 control-label">ศาสนา</label>
                            <div class="col-sm-8">
                                <?= $form_input['Religion'] ?>
                            </div>
                        </div> 
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-3">
                            <label class="col-sm-4 control-label">เพศ</label>
                            <div class="col-sm-8 radio">
                                <?php
                                foreach ($form_input['Sex'] as $row) {
                                    echo $row;
                                }
                                ?>                          
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="col-sm-4 control-label">น้ำหนัก</label>
                            <div class="col-sm-8">
                                <div class="input-group">                                                        
                                    <?= $form_input['Weight'] ?>
                                    <div class="input-group-addon">กก.</div>
                                </div>
                            </div>
                        </div>  
                        <div class="col-sm-4">
                            <label class="col-sm-4 control-label">ส่วนสูง</label>
                            <div class="col-sm-8">
                                <div class="input-group">                                                        
                                    <?= $form_input['Height'] ?>
                                    <div class="input-group-addon">ซม.</div>
                                </div>                                                    
                            </div>
                        </div> 
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label class="col-sm-4 control-label">บ้านเลขที่</label>
                            <div class="col-sm-8">
                                <?= $form_input['CurrentAddress'] ?>
                            </div>  
                        </div>
                        <div class="col-sm-2">
                            <label class="col-sm-6 control-label">หมู่ที่</label>
                            <div class="col-sm-6">
                                <?= $form_input['CurrentMu'] ?>
                            </div>  
                        </div>
                        <div class="col-sm-6">
                            <label class="col-sm-2 control-label">ถนน</label>
                            <div class="col-sm-8">
                                <?= $form_input['CurrentStreet'] ?>
                            </div>
                        </div>
                    </div>     
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label class="col-sm-4 control-label">หมู่บ้าน</label>
                            <div class="col-sm-8">
                                <?= $form_input['CurrentVillage'] ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="col-sm-2 control-label">ตำบล</label>
                            <div class="col-sm-8">
                                <?= $form_input['CurrentSubDistrict'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label class="col-sm-4 control-label">อำเภอ</label>
                            <div class="col-sm-8">
                                <?= $form_input['CurrentDistrict'] ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="col-sm-2 control-label">จังหวัด</label>
                            <div class="col-sm-8">
                                <?= $form_input['CurrentProvince'] ?>
                            </div> 
                        </div>
                        <div class="col-sm-4">
                            <label class="col-sm-4 control-label">รหัสไปรษณีย์</label>
                            <div class="col-sm-6">
                                <?= $form_input['CurrentZipCode'] ?>
                            </div> 
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label class="col-sm-4 control-label">มือถือ</label>
                            <div class="col-sm-8">
                                <?= $form_input['MobilePhone'] ?>
                            </div> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ที่พักอาศัย</label>
                        <div class="col-md-10">
                            <div class="row radio">
                                <?php
                                $i = 0;
                                foreach ($form_input['Residential'] as $row) {
                                    if ($i == 0)
                                        echo '<div class="col-sm-3">';
                                    else
                                        echo '<div class="col-sm-2">';
                                    echo $row;
                                    echo '</div>';
                                    $i++;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ภาวะทางทหาร</label>
                        <div class="col-sm-10">
                            <div class="row radio">
                                <?php
                                $i = 0;
                                foreach ($form_input['MilitaryServiceStatus'] as $row) {
                                    if ($i == 0)
                                        echo '<div class="col-sm-2">';
                                    else
                                        echo '<div class="col-sm-3">';
                                    echo $row;
                                    echo '</div>';
                                    $i++;
                                }
                                ?>                                
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">สถานภาพ</label>
                        <div class="col-sm-10">
                            <div class="row radio">
                                <?php
                                foreach ($form_input['MaritalStatus'] as $row) {
                                    echo '<div class="col-sm-2">';
                                    echo $row;
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div id="not_single" class="hidden">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">ชื่อคู่สมรส</label>
                            <div class="col-sm-2">
                                <?= $form_input['SpouseTitle'] ?>           
                            </div>
                            <div class="col-sm-4">
                                <?= $form_input['SpouseFirstName'] ?>
                            </div>
                            <label class="col-sm-1 control-label">นามสกุล</label>
                            <div class="col-sm-3">
                                <?= $form_input['SpouseLastName'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label class="col-sm-6 control-label">อายุ</label>
                                <div class="col-sm-3">
                                    <?= $form_input['SpouseAge'] ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="col-sm-2 control-label">อาชีพ</label>
                                <div class="col-sm-8">
                                    <?= $form_input['SpouseOccupation'] ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="radio">
                                    <?php
                                    foreach ($form_input['SpouseIsAlive'] as $row) {
                                        echo '<div class="col-sm-4">';
                                        echo $row;
                                        echo '</div>';
                                    }
                                    ?>                                    
                                </div>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="col-sm-2 control-label">จำนวนบุตร</label>
                            <div class="col-sm-1">
                                <?= $form_input['NumberSon'] ?>
                            </div>
                            <div class="col-sm-2">
                                คน
                            </div>
                        </div>
                        <div class="col-sm-10 col-sm-offset-1 well" >
                            <table id="childen" class="table table-hover hidden">
                                <thead>
                                    <tr>
                                        <th style="width: 2%;"></th>
                                        <th style="width: 10%;" class="text-center">คำนำหน้าชื่อ</th>
                                        <th style="width: 28%;" class="text-center">ชื่อ</th>
                                        <th style="width: 20%;" class="text-center">นามสกุล</th>
                                        <th style="width: 10%;" class="text-center">อายุ</th>
                                        <th style="width: 20%;" class="text-center">อาชีพ</th>
                                    </tr>
                                </thead>
                                <tbody>
<!--                                    <tr>
                                        <td>1</td>
                                        <td><?= $form_input['SonTitle'] ?></td>
                                        <td><?= $form_input['SonFirstName'] ?></td>
                                        <td><?= $form_input['SonLastName'] ?></td>
                                        <td><?= $form_input['SonAge'] ?></td>
                                        <td><?= $form_input['SonOccupation'] ?></td>                                        
                                    </tr>                     -->
                                </tbody>
                            </table>                            
                        </div>

                    </div>
                </div> 
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">ประวัติครอบครัว</h3>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">                                   
                            <div class="col-sm-1">
                                <label class="control-label">ชื่อบิดา</label>
                            </div>
                            <div class="col-sm-2">
                                <?= $form_input['FatherTitle'] ?>
                            </div>                                       
                            <div class="col-sm-4">
                                <?= $form_input['FatherFirstName'] ?>
                            </div>
                            <div class="col-sm-4">
                                <label class="col-sm-2 control-label">นามสกุล</label>
                                <div class="col-sm-8">
                                    <?= $form_input['FatherLastName'] ?>
                                </div>
                            </div>
                        </div>  
                        <div class="form-group">
                            <div class="">
                                <label class="col-sm-1 control-label">อายุ</label>
                                <div class="col-sm-1">
                                    <?= $form_input['FatherAge'] ?>
                                </div>
                            </div>
                            <div class="">
                                <label class="col-sm-1 control-label">อาชีพ</label>
                                <div class="col-sm-4">
                                    <?= $form_input['FatherOccupation'] ?>
                                </div>
                            </div>
                            <div class="col-sm-4 radio">
                                <?php
                                foreach ($form_input['FatherIsAlive'] as $row) {
                                    echo '<div class="col-sm-4">';
                                    echo $row;
                                    echo '</div>';
                                }
                                ?>  
                            </div>
                        </div>    
                        <div class="form-group">
                            <label class="col-sm-1 control-label">ชื่อมารดา</label>
                            <div class="col-sm-2">
                                <?= $form_input['MotherTitle'] ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form_input['MotherFirstName'] ?>
                            </div>
                            <label class="col-sm-1 control-label">นามสกุล</label>
                            <div class="col-sm-4">
                                <?= $form_input['MotherLastName'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="">
                                <label class="col-sm-1 control-label">อายุ</label>
                                <div class="col-sm-1">
                                    <?= $form_input['MotherAge'] ?>
                                </div>
                            </div>
                            <div class="">
                                <label class="col-sm-1 control-label">อาชีพ</label>
                                <div class="col-sm-4">
                                    <?= $form_input['MotherOccupation'] ?>
                                </div>
                            </div>
                            <div class="col-sm-4 radio">
                                <?php
                                foreach ($form_input['MotherIsAlive'] as $row) {
                                    echo '<div class="col-sm-4">';
                                    echo $row;
                                    echo '</div>';
                                }
                                ?> 
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="panal_education" class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">การศึกษา</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">ระดับการศึกษา</th>
                                    <th style="width: 30%;" class="text-center">สถาบันการศึกษา</th>
                                    <th style="width: 20%;" class="text-center">สาขาวิชา</th>
                                    <th style="width: 15%;" class="text-center">ตั้งแต่</th>
                                    <th style="width: 15%;" class="text-center">ถึง</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>ประถมศึกษา</td>
                                    <td><?= $form_input['InstitutionName'] ?></td>
                                    <td><?= $form_input['EDMajor'] ?></td>
                                    <td><?= $form_input['EDDateFrom'] ?></td>
                                    <td><?= $form_input['EDDateTo'] ?></td>
                                </tr>
                                <tr>
                                    <td>มัธยมศึกษา</td>
                                    <td><?= $form_input['InstitutionName'] ?></td>
                                    <td><?= $form_input['EDMajor'] ?></td>
                                    <td><?= $form_input['EDDateFrom'] ?></td>
                                    <td><?= $form_input['EDDateTo'] ?></td>
                                </tr>
                                <tr>
                                    <td>ปวช</td>
                                    <td><?= $form_input['InstitutionName'] ?></td>
                                    <td><?= $form_input['EDMajor'] ?></td>
                                    <td><?= $form_input['EDDateFrom'] ?></td>
                                    <td><?= $form_input['EDDateTo'] ?></td>
                                </tr>
                                <tr>
                                    <td>ปวส</td>
                                    <td><?= $form_input['InstitutionName'] ?></td>
                                    <td><?= $form_input['EDMajor'] ?></td>
                                    <td><?= $form_input['EDDateFrom'] ?></td>
                                    <td><?= $form_input['EDDateTo'] ?></td>
                                </tr>
                                <tr>
                                    <td>ปริญญาตรี</td>
                                    <td><?= $form_input['InstitutionName'] ?></td>
                                    <td><?= $form_input['EDMajor'] ?></td>
                                    <td><?= $form_input['EDDateFrom'] ?></td>
                                    <td><?= $form_input['EDDateTo'] ?></td>
                                </tr>
                                <tr>
                                    <td>อื่นๆ</td>
                                    <td><?= $form_input['InstitutionName'] ?></td>
                                    <td><?= $form_input['EDMajor'] ?></td>
                                    <td><?= $form_input['EDDateFrom'] ?></td>
                                    <td><?= $form_input['EDDateTo'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="panal_experience" class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">ประวัติการทำงาน</h3>
                    </div>
                    <div class="panel-body">
                        <table id="table_expreience" class="table table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 20%;"class="text-center">สถานที่ทำงาน</th>
                                    <th style="width: 10%;" class="text-center">เริ่ม</th>
                                    <th style="width: 10%;" class="text-center">สิ้นสุด</th>
                                    <th style="width: 20%;" class="text-center">ตำแหน่งงาน</th>
                                    <th style="width: 15%;" class="text-center">เงินเดือน</th>
                                    <th style="width: 15%;" class="text-center">สาเหตุที่ออก</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $form_input['ExCompanyName'] ?></td>
                                    <td><?= $form_input['ExDateForm'] ?></td>
                                    <td><?= $form_input['ExDateTo'] ?></td>
                                    <td><?= $form_input['ExPositionName'] ?></td>
                                    <td><?= $form_input['ExSaraly'] ?></td>
                                    <td><?= $form_input['ReasonOfResign'] ?></td>                                  
                                </tr>                                    
                            </tbody>
                        </table>
                        <div class="col-md-12 text-center">
                            <button class="btn btn-default btn-sm" type="button" id="btn_expreience">เพิ่มประวัติการทำงาน</button>
                            <button class="btn btn-danger btn-sm" type="button" id="btnDel_expreience">ลบประวัติการทำงาน</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>กรณีฉุกเฉินบุคคลที่สามารถติดต่อได้</h4>                                    
                                <div class="col-sm-10 col-sm-offset-1">

                                    <div class="form-group">
                                        <div class="col-sm-2">
                                            <label class="control-label" for="">คำนำหน้าชื่อ</label>
                                            <?= $form_input['ECTitle'] ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label" for="exampleInputEmail1">ชื่อ</label>
                                            <?= $form_input['ECFirstName'] ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label" for="exampleInputEmail1">นามสกุล</label>
                                            <?= $form_input['ECLastName'] ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="control-label" for="exampleInputEmail1">เกี่ยวข้องเป็น</label>
                                            <?= $form_input['ECRelationShip'] ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-5">
                                            <label class="control-label" for="exampleInputEmail1">ที่อยู่</label>
                                            <?= $form_input['ECAddress'] ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label" for="exampleInputEmail1">โทร</label>
                                            <?= $form_input['ECMobilePhone'] ?>\
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-offset-2 col-md-8">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="check_agree">                                                
                                        <p> ข้าพเจ้าขอรับรองว่า ข้อความดังกล่าวทั้งหมดในใบสมัครนี้เป็นความจริงทุกประการ หลังจากบริษัท สหมิตรภาพ(1992) จำกัด 
                                            จ้างเข้ามาทำงานแล้วปรากฎว่า ข้อความในใบสมัครงานเอกสารที่นำมาเเสดง หรือรายละเอียดที่ให้ไวไม่เป็นความจริง 
                                            บริษัทฯ มีสิทธิ์ที่จะเลิกจ้างข้าพเจ้าโดยไม่ต้องจ่ายเงินค้าชดเชยหรือค่าเสียหายใดๆ ทั้งสิ้น
                                        </p>                                                
                                    </label>
                                </div>
                            </div>                             
                            <div class="col-md-12 text-center">
                                <button id="btn_prepare_save" class="btn btn-primary" type="button">บันทึก</button>
                                <button class="btn btn-danger" type="reset">เริ่มใหม่</button>
                            </div>                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--</form>-->
        <?= $form_close ?>                   
    </div>

    <!-- Modal work expreince -->
    <div class="modal fade" id="modal_work_expreince" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">ยืนยันการเพิ่มข้อมูล</h4>
                </div>
                <div class="modal-body">
                    <p> กรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนการ ยืนยัน
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary" id="btn_save">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>


