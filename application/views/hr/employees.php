<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnHR").addClass("active");
    });

</script>

<div class="container">
    <br>
    <div class="row">      
        <div class="col-md-12">
            <div class="page-header">
                <h2>ระบบบริหารงานบุคคล <small>พนักงาน</small></h2>
            </div>
        </div>
    </div>
    <div class="row">   
        <div class="col-md-12">
            <ul class="nav nav-pills nav-justified">
                <li><a href="<?= base_url('hr/') ?>">หน้าหลัก</a></li>
                <li class="active"><a href="<?= base_url('employee/') ?>">พนักงาน</a></li>
                <li><a href="<?= base_url('master_data/') ?>">ข้อมูลหลัก</a></li>
                <li><a href="<?= base_url('work_history/') ?>" >ประวัติการทำงาน</a></li>
                <li><a href="<?= base_url('role_permission/') ?>" >กำหนดสิทธิ์การเข้าใช้</a></li>
                <li><a href="<?= base_url('username/') ?>" >ข้อมูลการเข้าใช้ระบบ</a></li>    
            </ul>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search"></i>&nbsp;&nbsp;ค้นหาพนักงาน</h3>
                </div>
                <div class="panel-body">
                    <div class="col-sm-offset-2 col-sm-8">
                        <form id="frm_search_employ" class="form-horizontal" role="form">
                            <div class="form-group">                                                    
                                <label for="" class="col-sm-3 control-label">ตำแหน่งงาน</label>
                                <div class="col-sm-5">
                                    <select name="selecter_basic" class="form-control">
                                        <option value="">เลือกตำแหน่งงาน</option>                                                          
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">                                                  
                                <label for="" class="col-sm-3 control-label">รหัสพนักงาน</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="" placeholder="">
                                </div>     
                            </div>
                            <div class="form-group"> 
                                <label for="" class="col-sm-3 control-label">รหัสบัตรประชาชน</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="" placeholder="">
                                    <span class="help-block">ตัวอย่าง xxxxxxxxxxxxxxx</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i>&nbsp;ค้นหา</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h5>พนักงานทั้งหมด</h5>
            <table class="table table-hover table-bordered" style="width: 100%">
                <thead>
                <th style="width: 13%" >รหัสพนักงาน</th>
                <th style="width: 20%">ชื่อ-สกุล</th>                                          
                <th style="width: 12%">รหัสบัตรประชาชน</th>
                <th style="width: 25%">ที่อยู่</th>
                <th style="width: 20%">รายละเอียด</th>
                <th style="width: 10%"></th>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < 5; $i++) {
                        ?>
                        <tr class="info">
                            <td colspan="6"><h5>ตำแหน่งงานที่ <?= $i ?></h5></td>
                        </tr>
                        <?php for ($j = 0; $j < 3; $j++) { ?>
                            <tr>
                                <td class="text-center">XXXXXXXXX<?= $j ?></td>
                                <td>นายฐากูร บุญสาร</td>
                                <td class="text-center">xxxxxxxxxxxxx</td>
                                <td>

                                    xxxxxxxxxxxxxxxxxxcxxxxx
                                    xxxxxxxxxxxxxxxxxxxxxxxx
                                    xxxxxxxxxxxxxxxxxxxxxxxx
                                    xxxxxxxxxxxxxxxxxxxxxxxx

                                </td>
                                <td> พนักงานขับรถเบอร์ สาย</td>
                                <td class="text-center">
                                    <a href="<?= base_url('employee/edit') ?>" class="btn btn-info btn-xs" ><i class="fa fa-pencil"></i> </a>
                                    <a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<hr>
