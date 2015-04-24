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