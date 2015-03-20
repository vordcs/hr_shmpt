<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnHR").addClass("active");
    });

</script>

<div class="container" style="margin-bottom: 20px;">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h2>ระบบบริหารงานบุคคล <small style="color: #777777;">กำหนดสิทธิ์การเข้าใช้</small></h2>
            </div>
        </div>
    </div>
    <div class="row">   
        <div class="col-md-12">
            <ul class="nav nav-pills nav-justified">
                <li><a href="<?= base_url('hr/home') ?>">หน้าหลัก</a></li>
                <li><a href="<?= base_url('hr/employee/') ?>">พนักงาน</a></li>
                <li><a href="<?= base_url('hr/seller/') ?>">พนักงานขายตั๋ว</a></li>
                <li class="active"><a href="<?= base_url('hr/permission/') ?>" >กำหนดสิทธิ์การเข้าใช้</a></li>
                <li><a href="<?= base_url('hr/loguser/') ?>" >ข้อมูลการเข้าใช้ระบบ</a></li>    
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search"></i>&nbsp;&nbsp;ค้นหาพนักงาน</h3>
                </div>
                <div class="panel-body">
                    <?= $form_open; ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="exampleInputEmail1">รหัสพนักงาน</label>
                                <?= $form_input['EID'] ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="exampleInputEmail1">ชื่อ</label>
                                <?= $form_input['FirstName'] ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="exampleInputEmail1">นามสกุล</label>
                                <?= $form_input['LastName'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary"><span class="fa fa-search"></span>ค้นหา</button>
                            <?= anchor('hr/permission', '<span class="fa fa-refresh">เริ่มค้นหาใหม่</span>', array('class' => 'btn btn-default')) ?>
                        </div>
                    </div>
                    <?= $form_close; ?>
                </div>
            </div>
        </div>
    </div>


    <!--แสดงทั้งหมด ตอนยังไม่ได้ทำการค้นหา-->
    <?php if (isset($list_all)) { ?>
        <div class="row">
            <div class="col-sm-12">
                <h5>พนักงานทั้งหมด</h5>
                <table class="table table-hover table-bordered" style="width: 100%">
                    <thead>
                    <th style="width: 8%" >รหัสพนักงาน</th>
                    <th style="width: 20%">ชื่อ-สกุล</th>
                    <th style="width: 12%">ระดับสิทธิ์</th> 
                    <th style="width: 12%">รหัสบัตรประชาชน</th>
                    <th style="width: 25%">ที่อยู่</th>
                    <th style="width: 15%">รายละเอียด</th>
                    <th style="width: 8%"></th>
                    </thead>
                    <tbody>   
                        <?php if (isset($list_all) && $list_all == NULL) { ?>
                            <tr><td class="text-center" colspan="7">ไม่พบข้อมูลพนักงานที่ค้นหา</td></tr>
                        <?php } else { ?>
                            <?php
                            $temp = NULL;
                            foreach ($list_all as $row) {
                                if ($temp != $row['PID']) {
                                    $temp = $row['PID'];
                                    ?>
                                    <tr class="info">
                                        <td colspan="7"><h5>ตำแหน่งงานที่ <?= $row['PositionName'] ?></h5></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td class="text-center"><?= $row['EID'] ?></td>
                                    <td><?= $row['Title'] . $row['FirstName'] . ' ' . $row['LastName'] ?></td>
                                    <td class="text-center"><?= $row['RoleLevel'] ?></td>
                                    <td class="text-center"><?= $row['PersonalID'] ?></td>
                                    <td>
                                        <?= '<strong>บ้านเลขที่</strong> ' . $row['CurrentHouseNumber'] . ' <strong>หมู่ที่</strong> ' . $row['CurrentMu'] ?>
                                        <?= '<br/><strong>หมู่บ้าน</strong> ' . $row['CurrentVillage'] . ' <strong>ถนน</strong> ' . $row['CurrentStreet'] ?>
                                        <?= '<br/><strong>ตำบล</strong> ' . $row['CurrentSubDistrict'] . ' <strong>อำเภอ</strong> ' . $row['CurrentDistrict'] ?>
                                        <?= '<br/><strong>จังหวัด</strong> ' . $row['CurrentProvince'] . ' <strong>รหัสไปรษณีย์</strong> ' . $row['CurrentZipCode'] ?>
                                    </td>
                                    <td>
                                        <?= '<strong>มือถือ</strong> ' . $row['MobilePhone'] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row['StatusID'] == 0) { ?>
                                            <span class="label label-danger">ลาออก</span>
                                        <?php } else { ?>
                                            <a href="#" type="button" class="btn btn-warning btn-sm" data-eid="<?= $row['EID'] ?>" data-ename="<?= $row['Title'] . $row['FirstName'] . ' ' . $row['LastName'] ?>" data-toggle="modal" data-target="#change"><i class="fa fa-pencil"></i></a>
                                        <?php } ?>

                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
</div>

<!-- Modal change -->
<?= $form_open ?>
<div class="modal fade bs-example-modal-sm" id="change" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-title">เปลี่ยนสิทธิ์การใช้งาน</h4>
            </div>
            <div class="modal-body" id="modal-body">
                <p>รหัสพนักงาน <span id="em_id"></span></p>
                <p>ชื่อพนักงาน <span id="em_name"></span></p>
                <?= $form_change['RoleID'] ?>
                <?= $form_change['EID'] ?>
            </div>
            <div class="modal-footer" align="center">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_no"><i class="fa fa-times fa-lg"></i>&nbsp;ยกเลิก</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-check fa-lg"></i>&nbsp;บันทึก</button>
            </div>
        </div>
    </div>
</div>
<?= $form_close ?>
<script>
    $('#change').on('show.bs.modal', function (e) {
        var eid = $(e.relatedTarget).data('eid');
        var ename = $(e.relatedTarget).data('ename');
        $('#em_id').html('<strong>' + eid + '</strong>');
        $('#EID').val(eid);
        $('#em_name').html('<strong>' + ename + '</strong>');
    });
</script>


