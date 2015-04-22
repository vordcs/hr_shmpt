<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnReport").addClass("active");
        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'yyyy-m-d'
        });
    });
</script>

<div class="container-fluid">
    <div class="row">      
        <div class="col-md-12">
            <div class="page-header">
                <h2>รายงาน <small>ช่วงวันที่ <?= $this->m_datetime->DateThai($begin_date) ?> - <?= $this->m_datetime->DateThai($end_date) ?></small></h2>
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
                <div class="panel-body" style="min-height: 114px;">                
                    <?= $form_open; ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">วันที่เริ่มต้น</label>
                                <input type="text" name="begin_date" value="" class="form-control datepicker">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">วันที่สิ้นสุด</label>
                                <input type="text" name="end_date" value="" class="form-control datepicker">
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
</div>

<div class="container">
    <div class="row-fluid">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-bus"></i>&nbsp;ข้อมูลเส้นทางเดินรถ
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10%">ประเภทรถ</th>
                        <th style="width: 20%">รหัสเส้นทาง</th>
                        <th style="width: 20%">ต้นทาง-ปลายทาง</th>                   
                        <th style="width: 20%"></th>
                    </tr>
                </thead>
                <tbody>
                <div class="tab-content">
                    <?php
                    foreach ($routes as $r) {
                        $rcode = $r['RCode'];
                        $vtid = $r['VTID'];
                        $vt_name = $r['VTDescription'];
                        $route_name = $r['RSource'] . ' - ' . $r['RDestination'];
                        $id = $vtid . '_' . $rcode;
                        ?>                       
                        <tr>
                            <td class="text-center">
                                <?= $vt_name ?>
                            </td>                                  
                            <td class="text-center">
                                <?= $rcode ?>
                            </td>
                            <td class="text-left">
                                <?= $route_name ?>
                            </td>
                            <td class="text-center">
                                <?php
                                $view = array(
                                    'class' => 'btn btn-primary',
                                );
                                echo anchor("report/view/$rcode/$vtid", '<i class="fa fa-eye"></i>&nbsp;ดูรายงาน', $view);
                                ?>                                        
                            </td>
                        </tr>  
                        <?php
                    }
                    ?>
                </div>
                </tbody>
            </table>            
        </div>
    </div>
</div>

