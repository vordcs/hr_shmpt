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
        <div class="col-sm-6" >
            <div id="panalPositions" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">ตำแหน่งงาน</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ชื่อตำแหน่ง</th>
                                        <th>จำนวนพนักงาน</th>
                                        <th></th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            ชื่อตำแหน่งงาน
                                        </td>
                                        <td>
                                            10
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#frm_position"><i class="fa fa-pencil"></i></button>
                                            <a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-sm-12">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#frm_position">
                                <i class="fa fa-plus"></i>&nbsp;&nbsp;เพิ่มตำแหน่งงาน
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6" >
            <div id="panalPositions" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">คำนำหน้าชื่อ</h3>
                </div>
                <div class="panel-body">
                    Panel content
                </div>
            </div>
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

