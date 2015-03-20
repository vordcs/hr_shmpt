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
                                <div class="time"><?= $report['ReportTime'] ?></div>
                                <div class="events">
                                    <div class="events-body">
                                        <h4 class="events-heading">รถ<?= ($report['VTID'] == 1) ? 'ตู้' : 'บัส' ?> สาย:<?= $report['RCode'] ?></h4>
                                        <p style="margin: 0px;">จำนวนเงินทั้งหมด  <strong><?= $report['Total'] ?></strong> บาท , หักเปอร์เซนต์พนักงาน <strong><?= $report['Vage'] ?></strong> บาท </p>
                                        <p style="margin: 0px;">ยอดสุทธิที่ต้องส่ง  <strong><?= $report['Net'] ?></strong> บาท</p>
                                        <p><strong>ส่งโดย</strong> <?= $report['Title'] . $report['FirstName'] . ' ' . $report['LastName'] ?></p>
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
