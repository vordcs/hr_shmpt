<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnHR").addClass("active");
        $('.dataTable').DataTable({
            "columnDefs": [
                {"visible": false, "targets": 2}
            ],
            "order": [[2, 'asc']],
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({page: 'current'}).nodes();
                var last = null;

                api.column(2, {page: 'current'}).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                                '<tr class="group info"><td colspan="6">' + group + '</td></tr>'
                                );

                        last = group;
                    }
                });
            }
        });

        $('#modal_join').on('show.bs.modal', function (e) {
            $(this).find("[name='EID']").val($(e.relatedTarget).data('eid'));
            $(this).find("#em_id").html($(e.relatedTarget).data('eid'));
            $(this).find("#em_name").html($(e.relatedTarget).data('name'));
        });
    });

</script>

<div class="container" style="margin-bottom: 20px;">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h2>ระบบบริหารงานบุคคล <small style="color: #777777;">พนักงาน</small></h2>
            </div>
        </div>
    </div>
    <div class="row">   
        <div class="col-md-12">
            <ul class="nav nav-pills nav-justified">
                <li><a href="<?= base_url('hr/home') ?>">หน้าหลัก</a></li>
                <li class="active"><a href="<?= base_url('hr/employee/') ?>">พนักงาน</a></li>
                <li><a href="<?= base_url('hr/seller/') ?>">พนักงานขายตั๋ว</a></li>
                <li><a href="<?= base_url('hr/permission/') ?>" >กำหนดสิทธิ์การเข้าใช้</a></li>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="exampleInputEmail1">รหัสพนักงาน</label>
                                <?= $form_input['EID'] ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="exampleInputEmail1">ชื่อ</label>
                                <?= $form_input['FirstName'] ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="exampleInputEmail1">นามสกุล</label>
                                <?= $form_input['LastName'] ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="exampleInputEmail1">ตำแหน่ง</label>
                                <?= $form_input['PID'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary"><span class="fa fa-search"></span>ค้นหา</button>
                            <?= anchor('hr/employee', '<span class="fa fa-refresh">เริ่มค้นหาใหม่</span>', array('class' => 'btn btn-default')) ?>
                        </div>
                    </div>
                    <?= $form_close; ?>
                </div>
            </div>
        </div>
    </div>

    <!--แสดงตอนค้นหา-->
    <?php if (isset($list)) { ?>
        <div class="row">
            <div class="col-sm-12">
                <h5>ผลการค้นหา</h5>
                <table class="table table-hover table-bordered dataTable" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="width: 10%" >รหัสพนักงาน</th>
                            <th style="width: 20%">ชื่อ-สกุล</th>
                            <th style="width: 20%">ตำแหน่ง</th>
                            <th style="width: 12%">รหัสบัตรประชาชน</th>
                            <th style="width: 25%">ที่อยู่</th>
                            <th style="width: 20%">รายละเอียด</th>
                            <th style="width: 13%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($list) && $list == NULL) { ?>
                            <tr><td class="text-center" colspan="6">ไม่พบข้อมูลพนักงานที่ค้นหา</td></tr>
                        <?php } else { ?>
                            <?php
                            foreach ($list as $row) {
                                ?>
                                <tr>
                                    <td class="text-center"><?= $row['EID'] ?></td>
                                    <td><?= $row['Title'] . $row['FirstName'] . ' ' . $row['LastName'] ?></td>
                                    <td><h5>ตำแหน่งงานที่ <?= $row['PositionName'] ?></h5></td>
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
                                            <?php
                                            $pre_edit = 'data-name="' . $row['Title'] . $row['FirstName'] . ' ' . $row['LastName'] . '"';
                                            $pre_edit .= 'data-eid="' . $row['EID'] . '"';
                                            ?>
                                            <span class="label label-danger">สถานะ ลาออก</span> 
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_join" <?= $pre_edit ?>><i class="fa fa-undo"></i> กลับเข้าทำงาน</button>
                                        <?php } else { ?>
                                            <?= anchor('hr/employee/log/' . $row['EID'], '<i class="fa fa-signal"></i>', array('class' => 'btn btn-success btn-sm')) ?>
                                            <?= anchor('hr/employee/detail/' . $row['EID'], '<i class="fa fa-search"></i>', array('class' => 'btn btn-primary btn-sm')) ?>
                                            <?= anchor('hr/employee/edit/' . $row['EID'], '<i class="fa fa-pencil"></i>', array('class' => 'btn btn-warning btn-sm')) ?>
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

    <!--แสดงทั้งหมด ตอนยังไม่ได้ทำการค้นหา-->
    <?php if (isset($list_all)) { ?>
        <div class="row">
            <div class="col-sm-12">
                <h5>พนักงานทั้งหมด</h5>
                <table class="table table-hover table-bordered dataTable" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="width: 10%" >รหัสพนักงาน</th>
                            <th style="width: 20%">ชื่อ-สกุล</th>
                            <th style="width: 20%">ตำแหน่ง</th>
                            <th style="width: 12%">รหัสบัตรประชาชน</th>
                            <th style="width: 25%">ที่อยู่</th>
                            <th style="width: 20%">รายละเอียด</th>
                            <th style="width: 13%"></th>
                        </tr>
                    </thead>
                    <tbody>   
                        <?php if (isset($list_all) && $list_all == NULL) { ?>
                            <tr><td class="text-center" colspan="6">ไม่พบข้อมูลพนักงานที่ค้นหา</td></tr>
                        <?php } else { ?>
                            <?php
                            foreach ($list_all as $row) {
                                //ต้องการซ่อนถ้าใครออกแล้ว เพราะอยากเพิ่มใหม่แค่การกดกลับมาทำงาน
                                if ($row['StatusDescription'] == 'ออก') {
                                    continue;
                                }
                                ?>
                                <tr>
                                    <td class="text-center"><?= $row['EID'] ?></td>
                                    <td><?= $row['Title'] . $row['FirstName'] . ' ' . $row['LastName'] ?></td>
                                    <td><h5>ตำแหน่งงานที่ <?= $row['PositionName'] ?></h5></td>
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
                                            <?php
                                            $pre_edit = 'data-name="' . $row['Title'] . $row['FirstName'] . ' ' . $row['LastName'] . '"';
                                            $pre_edit .= 'data-eid="' . $row['EID'] . '"';
                                            ?>
                                            <span class="label label-danger">สถานะ ลาออก</span> 
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_join" <?= $pre_edit ?>><i class="fa fa-undo"></i> กลับเข้าทำงาน</button>
                                        <?php } else { ?>
                                            <?= anchor('hr/employee/log/' . $row['EID'], '<i class="fa fa-signal"></i>', array('class' => 'btn btn-success btn-sm')) ?>
                                            <?= anchor('hr/employee/detail/' . $row['EID'], '<i class="fa fa-search"></i>', array('class' => 'btn btn-primary btn-sm')) ?>
                                            <?= anchor('hr/employee/edit/' . $row['EID'], '<i class="fa fa-pencil"></i>', array('class' => 'btn btn-warning btn-sm')) ?>
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
<hr>
<!-- Modal join -->
<?= $form_open_join ?>
<div class="modal fade bs-example-modal-sm" id="modal_join" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-title">กลับเข้าทำงานอีกครั้ง</h4>
            </div>
            <div class="modal-body" id="modal-body">
                <p>รหัสพนักงาน <span id="em_id"></span></p>
                <p>ชื่อพนักงาน <span id="em_name"></span></p>
                <input name="EID" type="hidden"/>
            </div>
            <div class="modal-footer" align="center">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_no"><i class="fa fa-times fa-lg"></i>&nbsp;ยกเลิก</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-check fa-lg"></i>&nbsp;บันทึก</button>
            </div>
        </div>
    </div>
</div>
<?= $form_close ?>