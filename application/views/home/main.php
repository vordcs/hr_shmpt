<script>
    jQuery(window).load(function () {
        $('h4.events-heading').click(function () {
            if ($(this).find("i").hasClass('fa-chevron-circle-down')) {
                $(this).find("i").removeClass("fa-chevron-circle-down").addClass("fa-chevron-circle-up");
            } else {
                $(this).find("i").removeClass("fa-chevron-circle-up").addClass("fa-chevron-circle-down");
            }
        });
    });
</script>
<br>
<?php if ($permittion != 'ALL') { ?>
    <div class="container" style="margin-top: 40px;">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 style="margin:0px;"><span class="fa fa-university"></span> ระบบจัดการภายในออฟฟิศ</h3>
                </div>
                <div class="panel-body">
                    ยินดีต้อนรับ คุณสามารถใช้งานได้เฉพาะบ้างเมนูที่มีสิทธิ์เท่านั้น
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="container">
        <!--TimeLine
    ================================================== -->
        <div class="row">
            <h2 class="example-title">ลำดับเวลาการส่งเงิน</h2>
            <p class="text-right">
                <?php
                foreach ($timeline as $key => $row) {
                    $total = 0;
                    foreach ($row as $row2) {
                        $total+=$row2['Total'];
                    }
                    echo anchor('home#' . $key, $this->m_datetime->getDateThaiString($key) . ' (ยอดรวมส่งเงิน ' . number_format($total, 0, '.', ',') . ')', array('class' => 'btn btn-primary')) . ' ';
                }
                ?>
            </p>
            <div class="col-md-12">
                <div class="timeline">
                    <dl>
                        <?php foreach ($timeline as $key => $row) { ?>
                            <dt id="<?= $key ?>"><?= $this->m_datetime->getDateThaiString($key) ?></dt>
                            <?php foreach ($row as $index => $report) { ?>
                                <dd class="pos-<?= (($index % 2) == 0) ? 'right' : 'left' ?> clearfix">
                                    <div class="circ"></div>
                                    <div class="time"><?= date('H:i', strtotime($report['ReportTime'])); ?></div>
                                    <div class="events well">
                                        <div style="position: absolute; right: 6px;">
                                            <?php if ($report['ReportStatus'] == 0) { ?>
                                                <div class="badge badge-info"><i class="fa fa-info"></i> รอการตรวจสอบ</div>
                                            <?php } else { ?>
                                                <div class="badge badge-success"><i class="fa fa-check"></i> ตรวจสอบแล้ว</div>
                                            <?php } ?>
                                        </div>
                                        <div class="events-body">
                                            <a style="cursor:pointer;" data-toggle="collapse" data-target="#<?= $report['ReportID'] ?>">
                                                <h4 class="events-heading"><i class="fa fa-chevron-circle-down"></i> 
                                                    รถ<?= ($report['VTID'] == 1) ? 'ตู้' : 'บัส' ?> สาย:<?= $report['RCode'] ?> 
                                                    ยอดสุทธิ:<?= number_format($report['Net'], 0, '.', ',') ?>
                                                </h4>
                                            </a>
                                            <div class="collapse" id="<?= $report['ReportID'] ?>">
                                                <p style="margin: 0px;">จำนวนเงินทั้งหมด  <strong><?= $report['Total'] ?></strong> บาท , หักเปอร์เซนต์พนักงาน <strong><?= $report['Vage'] ?></strong> บาท </p>
                                                <p style="margin: 0px;">ยอดสุทธิที่ต้องส่ง  <strong><?= $report['Net'] ?></strong> บาท</p>
                                                <p style="margin: 0px;"><strong>จากรถเบอร์</strong>
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="th-grass">รถเบอร์</th>
                                                            <th class="th-grass">เวลาออก</th>
                                                            <th class="th-grass">เวลาถึง</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($report['list'] as $ve) { ?>
                                                            <tr>
                                                                <td class="text-center"><?= $ve['VCode'] ?></td>
                                                                <td class="text-center"><?= substr($ve['TimeDepart'], 0, 5) ?></td>
                                                                <td class="text-center"><?= substr($ve['TimeArrive'], 0, 5) ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                                </p>
                                                <p><strong>ส่งโดย</strong> <?= $report['Title'] . $report['FirstName'] . ' ' . $report['LastName'] ?></p>
                                                <?php if ($report['ReportStatus'] == 0 && $this->session->userdata('permittion') == 'ALL') { ?>
                                                    <p><?= anchor('home/check_report/' . $report['ReportID'] . '/' . $EID, '<i class="fa fa-btc"></i>&nbsp;ยืนยันรับเงินแล้ว', array('class' => 'btn btn-xs btn-success')) ?></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </dd>
                            <?php } ?>
                        <?php } ?>
                    </dl>
                </div>
            </div>
        </div>
    </div>
<?php } ?>