<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnReport").addClass("active");
    });
</script>

<div class="container-fluid">
    <div class="row">      
        <div class="col-md-12">
            <div class="page-header">
                <h2>รายงาน <?= $get_route['RCode'] . ' ' . $get_route['RSource'] . ' - ', $get_route['RDestination'] ?> <small style="color: #444;">ช่วงวันที่ <?= $this->m_datetime->changeTHDBMonthToText($begin_date) ?> - <?= $this->m_datetime->changeTHDBMonthToText($end_date) ?></small></h2>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row-fluid">
        <div class="col-md-12">
            <div class="panel panel-primary" style="overflow: auto;">
                <div class="panel-heading" style="width: max-content;">
                    <i class="fa fa-bus"></i>&nbsp;รายงานข้อมูลการลงที่สถานีต่างๆ
                </div>
                <table class="table table-responsive table-striped table-hover">
                    <thead>
                        <tr>
                            <?php foreach ($report_station['thead'] as $row) { ?>
                                <th nowrap="nowrap"><?= $row['th'] ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report_station['tbody'] as $row) { ?>
                            <tr>
                                <?php foreach ($row as $row2) { ?>
                                    <td class="text-center"><?= $row2['td'] ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <?php foreach ($report_station['tfoot'] as $row) { ?>
                                <th><?= $row['th'] ?></th>
                            <?php } ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row-fluid">
        <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-bus"></i>&nbsp;รายงานข้อมูลสถิติการวิ่งรถ
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <?php foreach ($report_vehicle['thead'] as $row) { ?>
                                <th><?= $row['th'] ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report_vehicle['tbody'] as $row) { ?>
                            <tr>
                                <?php
                                foreach ($row as $key => $detail) {
                                    if ($key == 'vehicle') {
                                        ?>
                                        <td class="text-center"><?= $detail['VCode'] ?></td>
                                    <?php } else { ?>
                                        <td class="text-center"><?= $detail ?></td>
                                        <?php
                                    }
                                }
                                ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <?php foreach ($report_vehicle['tfoot'] as $row) { ?>
                                <th><?= $row['th'] ?></th>
                            <?php } ?>
                        </tr>
                    </tfoot>
                </table>            
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-user"></i>&nbsp;รายงานข้อมูลสถิติการขายตั๋วของพนักงาน
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <?php foreach ($report_sale['thead'] as $row) { ?>
                                <th><?= $row['th'] ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report_sale['tbody'] as $row) { ?>
                            <tr>
                                <?php
                                foreach ($row as $key => $detail) {
                                    if ($key == 'EID' || $key == 'name') {
                                        ?>
                                        <td><?= $detail ?></td>
                                    <?php } else { ?>
                                        <td class="text-center"><?= $detail ?></td>
                                        <?php
                                    }
                                }
                                ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <?php foreach ($report_sale['tfoot'] as $row) { ?>
                                <th><?= $row['th'] ?></th>
                            <?php } ?>
                        </tr>
                    </tfoot>
                </table>            
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="col-md-12">
            <div class="panel panel-default" style="overflow: auto;">
                <div class="panel-heading" style="width: max-content;">
                    <i class="fa fa-bus"></i>&nbsp;รายงานข้อมูลรถ
                </div>
                <table class="table table-responsive table-hover">
                    <thead>
                        <tr>
                            <th rowspan="2" class="th-blue-jeans"><?= $report_vehicle_cost['thead']['carnum'] ?></th>
                            <th colspan="2" class="th-bittersweet">จำนวนเที่ยว</th>
                            <th colspan="<?= count($report_vehicle_cost['thead']['income']) ?>">รายรับ</th>
                            <th colspan="<?= count($report_vehicle_cost['thead']['charge']) ?>" class="th-grass">รายจ่าย</th>
                            <th rowspan="2" class="th-dark-gray"><?= $report_vehicle_cost['thead']['balance'] ?></th>
                        </tr>
                        <tr>
                            <th class="th-bittersweet"><em>ไป</em> <?= $report_vehicle_cost['thead']['frequencies'][0] ?></th>
                            <th class="th-bittersweet"><em>ไป</em> <?= $report_vehicle_cost['thead']['frequencies'][1] ?></th>
                            <?php foreach ($report_vehicle_cost['thead']['income'] as $row_income) { ?>
                                <th><?= $row_income ?></th>
                            <?php } ?>
                            <?php foreach ($report_vehicle_cost['thead']['charge'] as $row_charge) { ?>
                                <th class="th-grass"><?= $row_charge ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report_vehicle_cost['tbody'] as $row) { ?>
                            <tr>
                                <td class="td-blue-jeans text-center"><?= $row['vehicle']['VCode'] ?></td>
                                <td class="td-bittersweet text-center"><?= $row['f_station'] ?></td>
                                <td class="td-bittersweet text-center"><?= $row['l_station'] ?></td>
                                <?php foreach ($row['income'] as $income) { ?>
                                    <td class="text-right"><?= $income['total'] ?></td>
                                <?php } ?>
                                <td class="text-right"><?= $row['onway'] ?></td>
                                <td class="text-right"><?= $row['messenger'] ?></td>
                                <td class="text-right"><?= $row['queue_price'] ?></td>
                                <td class="text-right"><?= $row['in_other'] ?></td>
                                <td class="td-grass text-right"><?= $row['license'] ?></td>
                                <td class="td-grass text-right"><?= $row['gas'] ?></td>
                                <td class="td-grass text-right"><?= $row['oil'] ?></td>
                                <td class="td-grass text-right"><?= $row['part'] ?></td>
                                <td class="td-grass text-right"><?= $row['percent_price'] ?></td>
                                <td class="td-grass text-right"><?= $row['out_other'] ?></td>
                                <td class="td-dark-gray text-right"><?= $row['balance'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>