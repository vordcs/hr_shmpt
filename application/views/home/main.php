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
<div class="container">
    <!--TimeLine
================================================== -->
    <div class="row">
        <h2 class="example-title">ลำดับเวลาการส่งเงิน</h2>
        <div class="col-md-12">
            <div class="timeline">
                <dl>
                    <?php foreach ($timeline as $key => $row) { ?>
                        <dt><?= $this->m_datetime->getDateThaiString($key) ?></dt>
                        <?php foreach ($row as $index => $report) { ?>
                            <dd class="pos-<?= (($index % 2) == 0) ? 'right' : 'left' ?> clearfix">
                                <div class="circ"></div>
                                <div class="time"><?= date('H:i', strtotime($report['ReportTime'])); ?></div>
                                <div class="events">
                                    <div class="pull-right"><?= ($report['ReportStatus'] == 0) ? 'รอตรวจสอบ' : 'ตรวจสอบแล้ว'; ?><span class="label label-primary"><i class="fa fa-info"></i></span></div>
                                    <div class="events-body">
                                        <a href="#" data-toggle="collapse" data-target="#<?= $report['ReportID'] ?>">
                                            <h4 class="events-heading"><i class="fa fa-chevron-circle-down"></i> รถ<?= ($report['VTID'] == 1) ? 'ตู้' : 'บัส' ?> สาย:<?= $report['RCode'] ?></h4>
                                        </a>
                                        <div class="collapse" id="<?= $report['ReportID'] ?>">
                                            <p style="margin: 0px;">จำนวนเงินทั้งหมด  <strong><?= $report['Total'] ?></strong> บาท , หักเปอร์เซนต์พนักงาน <strong><?= $report['Vage'] ?></strong> บาท </p>
                                            <p style="margin: 0px;">ยอดสุทธิที่ต้องส่ง  <strong><?= $report['Net'] ?></strong> บาท</p>
                                            <p><strong>ส่งโดย</strong> <?= $report['Title'] . $report['FirstName'] . ' ' . $report['LastName'] ?></p>
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