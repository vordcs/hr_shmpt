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
                        <?php if (count($e_list) > 0) { ?>
                            <ul class="media-list">
                                <?php
                                foreach ($e_list as $row) {
                                    $date = new DateTime($row['AcceptDate']);
                                    ?>
                                    <li class="media">
                                        <a class="pull-left" href="#"><img class="media-object img-rounded" src="http://placehold.it/100x100"></a>
                                        <div class="media-body">
                                            <h4 class="media-heading"><?= $row['Title'] . $row['FirstName'] . " " . $row['LastName'] ?></h4>
                                            <p>วันที่อนุมัติ <?= $date->format('j F Y') ?></p>
                                            <p>ตำแหน่งงาน <?= $row['PositionName'] ?></p>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>  
                        <?php } else { ?>
                            ไม่พบข้อมูลพนักงานใหม่
                        <?php } ?>
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
                                <th style="width: 10%">ลำดับ</th>
                                <th style="width: 30%">ชื่อ-นามสกุล</th>
                                <th style="width: 20%">ตำแหน่งงาน</th>
                                <th style="width: 25%">วันที่สมัคร</th>
                                <th style="width: 15%">สถานะ</th>
                                <th style="width: 10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($c_list != NULL) {
                                $i = 1;
                                foreach ($c_list as $row) {
                                    $date = new DateTime($row['RegisterDate']);
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $i++ ?></td>
                                        <td><?= $row['Title'] . $row['FirstName'] . " " . $row['LastName'] ?></td>
                                        <td><?= $row['PositionName'] ?></td>
                                        <td><?= $date->format('j F Y') ?></td>
                                        <td><?= ($row['CandidateStatus'] == '0') ? '<strong class="color-orange">รอการอนุมัติ</strong>' : '<strong class="color-green">รับแล้ว</strong>'; ?></td>
                                        <td class="text-center"><?= anchor('hr/home/detail/' . $row['CID'], '<i class="fa fa-search"></i>') ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr><td colspan="5" class="text-center">ไม่พบข้อมูลผู้สมัครงานที่ยังไม่ได้รับอนุมัติ</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
</div>





