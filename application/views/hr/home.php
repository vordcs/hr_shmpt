<style>
    .list-scholl {                    
        max-height: 400px;
        overflow-y: scroll;                   
    }
</style>
<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnHR").addClass("active");
        $('#myTab a[href="#tabEmployees"]').tab('show') // Select tab by name
    });

</script>

<div class="container" style="margin-bottom: 20px;">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h2>ระบบบริหารงานบุคคล <small style="color: #777777;">หน้าหลัก</small></h2>
            </div>
        </div>
    </div>
    <div class="row">   
        <div class="col-md-12">
            <ul class="nav nav-pills nav-justified">
                <li class="active"><a href="<?= base_url('hr/home') ?>">หน้าหลัก</a></li>
                <li><a href="<?= base_url('hr/employee/') ?>">พนักงาน</a></li>
                <li><a href="<?= base_url('hr/seller/') ?>">พนักงานขายตั๋ว</a></li>
                <li><a href="<?= base_url('hr/permission/') ?>" >กำหนดสิทธิ์การเข้าใช้</a></li>
                <li><a href="<?= base_url('hr/loguser/') ?>" >ข้อมูลการเข้าใช้ระบบ</a></li>    
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-4">            
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">พนักงานใหม่</h3>
                </div>
                <div class="panel-body">
                    <div class="list-scholl">
                        <ul class="media-list">
                            <?php for ($i = 0; $i < 8; $i++) { ?>
                                <li class="media">
                                    <a class="pull-left" href="#"><img class="media-object img-rounded" src="http://placehold.it/100x100"></a>
                                    <div class="media-body">
                                        <h4 class="media-heading">Media heading</h4>
                                        <p>12 Apr, 2013 at 12:00</p>
                                        <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.</p>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">ผู้สมัครงาน</div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10%"></th>
                                <th style="width: 30%">ชื่อ-นามสกุล</th>
                                <th style="width: 20%">ตำแหน่งงาน</th>
                                <th style="width: 25%">วันที่สมัคร</th>
                                <th style="width: 15%">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $date = date('j F Y');
                            for ($i = 1; $i <= 30; $i++) {
                                ?>
                                <tr>
                                    <td class="text-center"><a><i class="fa fa-file-text"></i></a></td>
                                    <td>&nbsp;<?= $i ?>  Mark Otto</td>
                                    <td></td>
                                    <td><?= $date ?></td>
                                    <td>@mdo</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
</div>





