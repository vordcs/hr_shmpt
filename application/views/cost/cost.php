<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnCost").addClass("active");

        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'yyyy-m-d'
        });
        $('[data-toggle="popover"]').popover({
            html: true,
            trigger: 'hover'
        });
    });
</script>
<div class="container-fluid">
    <div class="row">      
        <div class="col-md-12">
            <div class="page-header">
                <h2>รายรับ-รายจ่าย <small>ประจำวันที่ <?= $this->m_datetime->DateThai($date) ?></small></h2>
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

<!--All tab of cost-->
<div class="container-fluid">
    <?php foreach ($all_cost as $row_vtid) { ?>
        <div class="row">
            <div class="col-md-12">
                <h3><?= $row_vtid['VTDescription'] ?></h3>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php if (count($row_vtid['line']) > 0) { ?>
                    <div class="panel">
                        <!--start tab bar-->
                        <ul class="nav nav-tabs nav-justified">
                            <?php
                            $flag_first_tab = TRUE;
                            foreach ($row_vtid['line'] as $row_line) {
                                $tab_id = $row_vtid['VTID'] . '__' . $row_line['RCode'];
                                $set_active = NULL;
                                if ($flag_first_tab) {
                                    $flag_first_tab = FALSE;
                                    $set_active = 'active';
                                }
                                ?>
                                <li class="<?= $set_active ?>"><a href="#<?= $tab_id ?>" data-toggle="tab"><?= $row_line['tab_title'] ?></a></li>
                            <?php } ?>
                        </ul>
                        <!--end tab bar-->
                        <!--start tab content-->
                        <div class="tab-content">
                            <?php
                            $flag_first_tab = TRUE;
                            foreach ($row_vtid['line'] as $row_line) {
                                $tab_id = $row_vtid['VTID'] . '__' . $row_line['RCode'];
                                $set_active = NULL;
                                if ($flag_first_tab) {
                                    $flag_first_tab = FALSE;
                                    $set_active = 'active';
                                }
                                ?>
                                <div class="tab-pane <?= $set_active ?>" id="<?= $tab_id ?>">                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="lead text-center">ค่าใช้จ่ายประจำวันที่ <?= $row_line['date'] ?></p>
                                        </div>
                                        <div class="col-md-12">                                            
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="th-blue-jeans"><?= $row_line['thead']['carnum'] ?></th>
                                                        <th colspan="2" class="th-bittersweet">จำนวนเที่ยว</th>
                                                        <th colspan="<?= count($row_line['thead']['income']) ?>">รายรับ</th>
                                                        <th colspan="<?= count($row_line['thead']['charge']) ?>" class="th-grass">รายจ่าย</th>
                                                        <th rowspan="2" class="th-dark-gray"><?= $row_line['thead']['balance'] ?></th>
                                                    </tr>
                                                    <tr>
                                                        <th class="th-bittersweet"><em>ไป</em> <?= $row_line['thead']['frequencies'][0] ?></th>
                                                        <th class="th-bittersweet"><em>ไป</em> <?= $row_line['thead']['frequencies'][1] ?></th>
                                                        <?php foreach ($row_line['thead']['income'] as $row_income) { ?>
                                                            <th><?= $row_income ?></th>
                                                        <?php } ?>
                                                        <?php foreach ($row_line['thead']['charge'] as $row_charge) { ?>
                                                            <th class="th-grass"><?= $row_charge ?></th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($row_line['tbody'] as $row_body) { ?>
                                                        <tr>
                                                            <td class="text-center td-blue-jeans"><?= $row_body['carnum'] ?></td>
                                                            <td class="text-center td-bittersweet"><?= $row_body['f_station'] ?></td>
                                                            <td class="text-center td-bittersweet"><?= $row_body['l_station'] ?></td>
                                                            <?php
                                                            foreach ($row_body['income']as $row_income) {
                                                                $popup_title = 'รายการจาก';
                                                                $popup_content = '';
                                                                //อ่านว่าเงินมาจากใครเท่าไหร่
                                                                foreach ($row_income['list'] as $row_saler) {
                                                                    if ($row_saler['price'] == '0')
                                                                        continue;
                                                                    $popup_content.=$row_saler['name'] . ' ' . $row_saler['price'] . '<br/>';
                                                                }
                                                                ?>
                                                                <td class="text-right"><div data-toggle="popover" title="<?= $popup_title ?>" data-content="<?= $popup_content ?>"><?= $row_income['price'] ?></div></td>
                                                            <?php } ?>
                                                            <?php
                                                            foreach ($row_body['outcome']as $row_outcome) {
                                                                $popup_title = 'รายการจาก';
                                                                $popup_content = '';
                                                                //อ่านว่าเงินมาจากใครเท่าไหร่
                                                                foreach ($row_outcome['list'] as $row_saler) {
                                                                    if ($row_saler['price'] == '0')
                                                                        continue;
                                                                    $popup_content.=$row_saler['name'] . ' ' . $row_saler['price'] . '<br/>';
                                                                }
                                                                ?>
                                                                <td class="text-right td-grass"><div data-toggle="popover" title="<?= $popup_title ?>" data-content="<?= $popup_content ?>"><?= $row_outcome['price'] ?></div></td>
                                                            <?php } ?>
                                                            <td class="text-right td-dark-gray"><?= $row_body['balance'] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <?php
                                                    //กรณีไม่มีข้อมูล
                                                    if (count($row_line['tbody']) == 0) {
                                                        ?>
                                                        <tr><td colspan="100%" class="text-center">ไม่พบข้อมูล</td></tr>
                                                    <?php } ?>
                                                </tbody>
                                                <tfoot>
                                                    <?php if (isset($row_line['tfoot']) && count($row_line['tfoot']) > 3) { ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center td-dark-gray">ยอดรวมแต่ละช่อง</td>
                                                            <?php
                                                            foreach ($row_line['tfoot'] as $key => $row) {
                                                                if ($key < 3)
                                                                    continue;
                                                                ?>
                                                                <td class="text-right td-dark-gray"><?= $row ?></td>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php } ?>
                                                </tfoot>
                                            </table>   
                                        </div>
                                    </div>
                                </div> 
                            <?php } ?>
                        </div>
                        <!--end tab content-->

                    </div>
                <?php } else {//End (count($row_vtid['line']) > 0)      ?>
                    <div class="panel" style="min-height: 100px;">
                        <p class="lead text-center">ไม่พบข้อมูล</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
<!--End All tab of cost-->