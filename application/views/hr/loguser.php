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
                <h2>ระบบบริหารงานบุคคล <small style="color: #777777;">ข้อมูลการเข้าใช้ระบบ</small></h2>
            </div>
        </div>
    </div>
    <div class="row">   
        <div class="col-md-12">
            <ul class="nav nav-pills nav-justified">
                <li><a href="<?= base_url('hr/home') ?>">หน้าหลัก</a></li>
                <li><a href="<?= base_url('hr/employee/') ?>">พนักงาน</a></li>
                <li><a href="<?= base_url('hr/seller/') ?>">พนักงานขายตั๋ว</a></li>
                <li><a href="<?= base_url('hr/permission/') ?>" >กำหนดสิทธิ์การเข้าใช้</a></li>
                <li class="active"><a href="<?= base_url('hr/loguser/') ?>" >ข้อมูลการเข้าใช้ระบบ</a></li>    
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-6" >
            <div id="panalPositions" class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">ข้อมูลส่วนตัว</h3>
                </div>
                <div class="panel-body">
                    <address>
                        <h4 style="margin: 0px 0px 10px 0px;">ประวัติ</h4>
                        <strong>รหัสประชาชน</strong> <?= $detail['PersonalID'] ?><br>
                        <strong>ชื่อ - นามสกุล</strong> <?= $detail['Title'] . $detail['FirstName'] . ' ' . $detail['LastName'] ?><br>
                        <strong>ชื่อเล่น</strong> <?= $detail['NickName'] ?><br>
                    </address>
                </div>
            </div>
        </div>
        <div class="col-sm-6" >
            <div id="panalPositions" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">รหัสผ่านเพื่อเข้าระบบ</h3>
                </div>
                <div class="panel-body">
                    <address>
                        <strong>Username</strong> <?= $detail['EID'] ?><br>
                    </address>
                </div>
            </div>
            <?= $form_open ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">เปลี่ยนรหัสผ่าน</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">รหัสผ่านเก่า</label>
                            <div class="col-sm-7">
                                <input type="password" name="old_pass" class="form-control">
                            </div>
                        </div>  
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">รหัสผ่านใหม่</label>
                            <div class="col-sm-7">
                                <input type="password" name="new_pass" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-center">
                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> เปลี่ยนรหัสผ่าน</button>
                </div>
            </div>
            <?= $form_close ?>
        </div>
    </div>
</div>

<!-- Modal frm_position -->
<div class="modal fade" id="frm_position" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <p>เพิ่มตำแหน่งงาน</p>
                    <p>แก้ไขแหน่งงาน</p>
                </h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <label for="InputPositionName">ชื่อตำแหน่งงาน</label>
                        <input type="text" class="form-control" id="PositionName" placeholder="ชื่อตำแหน่งงาน">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<hr>

