<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnHR").addClass("active");
        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'yyyy-m-d'
        });
    });
</script>
<div class="container" style="margin-top: 60px;">
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><?= anchor('hr/home', 'ระบบบริหารงานบุคคล') ?></li>
                <li><?= anchor('hr/employee', 'พนักงาน') ?></li>
                <li class="active">ข้อมูลการเข้างาน</li>
            </ul>
            <div class="page-header">
                <h1>พนักงาน                    <font color="#777777">
                    <span style="font-size: 23px; line-height: 23.399999618530273px;">
                        ระบบบริหารงานบุคคล(ข้อมูลการเข้างาน)  ของ <?= $employe['Title'] . $employe['FirstName'] . ' ' . $employe['LastName'] ?></span>
                    </font>
                </h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-calendar"></i>&nbsp;แสดงรายงานตามวันที่กำหนด</h3>
                </div>
                <div class="panel-body" style="min-height: 114px;">                
                    <?= $form_open; ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">วันที่เริ่มต้น</label>
                                <input type="text" name="begin_date" value="<?= $begin_date ?>" class="form-control datepicker">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">วันที่สิ้นสุด</label>
                                <input type="text" name="end_date" value="<?= $end_date ?>" class="form-control datepicker">
                            </div>
                        </div>
                        <div class="col-md-4" style="padding-top: 24px;">
                            <button type="submit" class="btn btn-success">แสดง</button> 
                        </div>
                    </div>
                    <?= $form_close; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-clock-o"></i> วันที่เวลาปัจจุบัน</h3></div>
                <div class="panel-body">
                    <span class="clock" id="clock">
                        <div id="Date"></div>
                        <ul id="time">
                            <li id="hours"> </li>
                            <li id="point">:</li>
                            <li id="min"> </li>
                            <li id="point">:</li>
                            <li id="sec"> </li>
                        </ul>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h5>รายงานการเข้างาน ของ <?= $employe['Title'] . $employe['FirstName'] . ' ' . $employe['LastName'] ?></h5>
            <table class="table table-hover table-bordered" style="width: 100%">
                <thead>
                <th>วันที่</th>
                <th>เวลาเข้างาน</th>                                          
                <th>เวลาออกงาน</th>
                </thead>
                <tbody>
                    <?php foreach ($log_clocking as $row) { ?>
                        <tr>
                            <td><?= explode('เวลา', $this->m_datetime->DateTimeThai($row['clock_in_date']))[0] ?></td>
                            <td class="text-center"><?= explode('เวลา', $this->m_datetime->DateTimeThai($row['clock_in_date']))[1] ?></td>
                            <td class="text-center"><?= ($row['clock_out_date'] != NULL) ? explode('เวลา', $this->m_datetime->DateTimeThai($row['clock_out_date']))[1] : '-'; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>