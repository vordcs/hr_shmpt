<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnCandidate").addClass("active");
    });
</script>

<div class="container">
    <div class="row">        

        <div class="page-header">
            <h1>ผู้สมัครงาน&nbsp;
                <small>รอการอนุมัติ</small>
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-right"> 
            <a href="<?= base_url('candidate/add') ?>" class="btn btn-lg btn-success">เพิ่มผู้สมัครงาน</a>
        </div>
    </div>
    <div class="row animated bounceInUp">
        <div class="col-md-12 text-left">
            <?= $form_open ?>
            <div class="panel panel-info" style="margin: 10px 0px 4px 4px">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="glyphicon glyphicon-search"></span>ค้นหา</h3>
                </div>
                <div class="panel-body">
                    <div class="row">

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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="exampleInputEmail1">ตำแหน่ง</label>
                                <?= $form_input['PID'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-lg btn-primary"><span class="fa fa-search"></span>ค้นหา</button>
                            <?= anchor('candidate', '<span class="fa fa-refresh">เริ่มค้นหาใหม่</span>', array('class' => 'btn btn-lg btn-default')) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?= $form_close ?>
        </div>
    </div>

    <?php if (isset($list)) { ?>
        <div class="row animated bounceInLeft">
            <div class="col-md-12">
                <h2>ผลการค้นหา</h2>
            </div>
        </div>
        <div class="row animated bounceInUp">
            <div class="col-md-12" style="">
                <table class="table table-hover" style="margin:15px 0px 4px 4px;">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขประจำตัวประชาชน</th>
                            <th>ชื่อ - นามสกุล</th>
                            <th>ตำแหน่งที่สมัคร</th>
                            <th>วันที่สมัคร</th>
                            <th>สถานะ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($list) == 0) {
                            echo '<tr><td colspan="7" class="text-center">ไม่พบข้อมูลตามที่ค้นหา</td></tr>';
                        } else {
                            $i = 0;
                            foreach ($list as $row) {
                                $i++;
                                ?>
                                <tr>
                                    <td class="text-center"><?= $i ?></td>
                                    <td><?= $row['PersonalID'] ?></td>
                                    <td><?= $row['Title'].$row['FirstName'].' '.$row['LastName'] ?></td>
                                    <td><?= $row['PositionName'] ?></td>
                                    <td><?= $row['CreateDate'] ?></td>
                                    <td><?= ($row['CandidateStatus'] == '0') ? '<strong class="color-orange">รอการอนุมัติ</strong>' : '<strong class="color-green">รับแล้ว</strong>'; ?></td>
                                    <td class="text-center">
                                        <?= anchor('candidate/detail/' . $row['CID'], '<i class="fa fa-search"></i>', array('class' => 'btn btn-primary btn-sm')) ?>
                                        <?= anchor('candidate/edit/' . $row['CID'], '<i class="fa fa-pencil"></i>', array('class' => 'btn btn-warning btn-sm')) ?>
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
