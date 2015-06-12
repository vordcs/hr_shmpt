<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnCost").addClass("active");

        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'yyyy-m-d'
        });

    });

</script>
<div class="container">
    <div class="row">      
        <div class="col-md-12">
            <div class="page-header">
                <h3><?php echo $page_title; ?> <span class="badge badge-primary">ประจำวันที่ <?= $this->m_datetime->DateThai($date) ?></span></h3>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-calendar"></i>&nbsp;แสดงตามวันที่กำหนด</h3>
                </div>
                <div class="panel-body" style="min-height: 114px; padding: 30px;">                
                    <?= $form_open; ?>
                    <div class="form-group">
                        <label class="col-md-4 control-label" style="padding-top: 10px;">เลือกวันที่</label>
                        <div class="col-md-8 ">
                            <input type="text" name="date" value="" class="form-control datepicker">                </div>
                    </div>
                    <button type="submit" class="btn btn-success">แสดง</button> 
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
</div>

<div class="container">
    <div class="row">

        <div class="col-md-6">
            <div class="panel panel-primary">
                <?php $set_data = array_pop($route_rid); ?>
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-list-ol"></i> เพิ่มค่าใช้จ่าย ตามรอบเวลา</h3></div>
                <div class="panel-body" style="padding: 0px;">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">ออกจากสถานีขนส่ง <?= $set_data['RSource'] ?></th>
                                <th class="text-center"><?= $set_data['RDestination'] ?></th>
                                <th class="text-center">เบอร์รถ</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($set_data['list'] as $row) { ?>
                                <tr>
                                    <td class="text-center"><?= substr($row['TimeDepart'], 0, 5) ?></td>
                                    <td class="text-center"><?= substr($row['TimeArrive'], 0, 5) ?></td>
                                    <td class="text-center"><?= $row['VCode'] ?></td>
                                    <td class="text-center"></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-success">
                <?php $set_data = array_pop($route_rid); ?>
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-list-ol"></i> เพิ่มค่าใช้จ่าย ตามรอบเวลา</h3></div>
                <div class="panel-body" style="padding: 0px;">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">ออกจากสถานีขนส่ง <?= $set_data['RSource'] ?></th>
                                <th class="text-center"><?= $set_data['RDestination'] ?></th>
                                <th class="text-center">เบอร์รถ</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($set_data['list'] as $row) { ?>
                                <tr>
                                    <td class="text-center"><?= substr($row['TimeDepart'], 0, 5) ?></td>
                                    <td class="text-center"><?= substr($row['TimeArrive'], 0, 5) ?></td>
                                    <td class="text-center"><?= $row['VCode'] ?></td>
                                    <td class="text-center"></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-car"></i> เพิ่มค่าใช้จ่าย ตามรถ</h3></div>
                <div class="panel-body" style="padding: 0px;">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">เบอร์รถ</th>
                                <th class="text-center">ทะเบียนรถ</th>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vehicles as $row) { ?>
                                <tr>
                                    <td class="text-center"><?= $row['VCode'] ?></td>
                                    <td class="text-center"><?= $row['NumberPlate'] ?></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>