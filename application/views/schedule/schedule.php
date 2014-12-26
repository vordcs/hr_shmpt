<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnSchedule").addClass("active");
    });
</script>
<style>
    .list-route {                    
        max-height: 500px;
        overflow-y: scroll;                   
    }
</style>
<?php

function last_around($start_time, $interval_time, $number) {
    $time = '';
    if ($start_time == '' || $interval_time == '' || $number == 0 || $number == '') {
        return $time;
    }
    $interval = 0;
    for ($i = 0; $i < $number; $i++) {
        $interval = $interval_time * 60 * $i;
        $time = strtotime($start_time) + $interval;
    }
    return date('H:i', $time);
}
?>
<div class="container">
    <div class="row">        
        <div class="page-header">
            <h3>ตารางเวลาเดินรถ&nbsp;
                <small></small>
            </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

        </div>
    </div>
    <div class="row animated fadeInUp hidden">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search"></i>&nbsp;ค้นหา</h3>
                </div>
                <div class="panel-body">
                    <?php echo $form_search['form']; ?>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">รหัสเส้นทาง</label>
                            <?php echo $form_search['RCode']; ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">ประเภทรถ</label>
                        <?php echo $form_search['VTID']; ?>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">ต้นทาง</label>
                            <?php echo $form_search['RSource']; ?>
                        </div>
                    </div>
                    <div class="col-md-4">                                
                        <label class="control-label">ปลายทาง</label>
                        <?php echo $form_search['RDestination']; ?>                              
                    </div>  
                    <br>
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-default">&nbsp;&nbsp;ค้นหา&nbsp;&nbsp;</button>
                    </div>
                    <?php echo form_close(); ?>  


                </div>
            </div>
        </div>     
    </div>
    <div class="row animated fadeInUp">        

    </div>
    <div class="row animated fadeInUp">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">ข้อมูลตารางเวลาเดินรถ</h3>
                </div>
                <div class="panel-body">                   
                    <?php
                    foreach ($vehicles_type as $v_type) {
                        $vtid = $v_type['VTID'];
                        $vt_name = $v_type['VTDescription']
                        ?>
                        <h3><?= $vt_name ?></h3> 

                        <?php
                        $num_v = 0;
                        foreach ($route as $r) {
                            if ($vtid == $r['VTID']) {
                                $num_v ++;
                            }
                        }
                        if (count($route) <= 0 || $num_v == 0) {
                            ?>
                            <div class="col-md-12">
                                <div class="well" style="padding-bottom: 100px;padding-top: 100px;">
                                    <p class="lead text-center">ไม่พบข้อมูล</p>
                                </div>
                            </div> 
                            <?php
                        } else {
                            ?>
                            <div class="panel">
                                <ul id="Tab<?= $vtid ?>" class="nav nav-tabs nav-justified">
                                    <?php
                                    foreach ($route as $r) {
                                        if ($r['VTID'] == $v_type['VTID']) {
                                            $vtid = $r['VTID'];
                                            $type_name = $v_type['VTDescription'];
                                            $rcode = $r['RCode'];
                                            $source = $r['RSource'];
                                            $destination = $r['RDestination'];
                                            $schedule_type = $r["ScheduleType"];
                                            $route_name = ' ' . $rcode . ' ' . ' ' . $source . ' - ' . $destination;
                                            ?>
                                            <li class="">
                                                <a data-toggle="tab" href="#<?= $rcode . $vtid ?>"><?= $route_name ?></a>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>

                                </ul>
                                <div id="TabContent<?= $vtid ?>" class="tab-content">
                                    <div id="home_schedue" class="tab-pane fade active in">
                                        <div class="col-md-12 text-center">
                                            <h4>
                                                <span class=""><?php echo "ตารางเวลาเดินรถ <u>$vt_name</u>   วันที่ "; ?></span>
                                            </h4>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-6 well" >
                                                <p class="lead text-center">
                                                    ข้อมูลเวลา
                                                </p>
                                                <div class="panel-group" id="accordionTime" role="tablist" aria-multiselectable="true">
                                                    <?php
                                                    foreach ($route as $r) {
                                                        $vtid = $r['VTID'];
                                                        $type_name = $v_type['VTDescription'];
                                                        $rcode = $r['RCode'];
                                                        $source = $r['RSource'];
                                                        $destination = $r['RDestination'];
                                                        $schedule_type = $r["ScheduleType"];
                                                        $route_name = ' เส้นทาง ' . $rcode . ' ' . ' ' . $source . ' - ' . $destination;
                                                        ?>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" role="tab" id="headingTime<?= $rcode . $vtid ?>">
                                                                <h4 class="panel-title">
                                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordionTime" href="#collapseTime<?= $rcode . $vtid ?>" aria-expanded="false" aria-controls="collapseTime<?= $rcode . $vtid ?>">
                                                                        <span class="fa fa-bus">&nbsp;<?= $route_name ?></span>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapseTime<?= $rcode . $vtid ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTime<?= $rcode . $vtid ?>">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><?php echo $route_name; ?></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    <?php } ?> 
                                                </div>
                                            </div>
                                            <div class="col-md-6" >                                              
                                                <p class="lead text-center">
                                                    ข้อมูลรถ
                                                </p>  
                                                <div class="panel-group" id="accordionVehicles" role="tablist" aria-multiselectable="true">
                                                    <?php
                                                    foreach ($route as $r) {
                                                        $vtid = $r['VTID'];
                                                        $type_name = $v_type['VTDescription'];
                                                        $rcode = $r['RCode'];
                                                        $source = $r['RSource'];
                                                        $destination = $r['RDestination'];
                                                        $schedule_type = $r["ScheduleType"];
                                                        $route_name = "ข้อมูล $vt_name เส้นทาง " . $rcode . ' ' . ' ' . $source . ' - ' . $destination;
                                                        ?>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" role="tab" id="heading<?= $rcode . $vtid ?>">
                                                                <h4 class="panel-title">
                                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordionVehicles" href="#collapse<?= $rcode . $vtid ?>" aria-expanded="false" aria-controls="collapse<?= $rcode . $vtid ?>">
                                                                        <span class="fa fa-bus">&nbsp;<?= $route_name ?></span>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapse<?= $rcode . $vtid ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?= $rcode . $vtid ?>">
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 20%" rowspan="2">เบอร์รถ</th>
                                                                            <th style="width: 80%" colspan="3">จำนวนเที่ยว</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th><?php echo "ออกจาก $source"; ?></th>
                                                                            <th><?php echo "ออกจาก $destination"; ?></th>
                                                                            <th><?php echo "รวม"; ?></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        foreach ($vehicles as $v) {
                                                                            if ($rcode == $v['RCode'] && $vtid == $v['VTID']) {
                                                                                $vid = $v['VID'];
                                                                                $vcode = $v['VCode'];
                                                                                ?>
                                                                                <tr>
                                                                                    <td class="text-center"><?= "$vcode" ?></td>
                                                                                    <td class="text-center"><?= "vid = $vid" ?></td>
                                                                                    <td class="text-center"></td>
                                                                                    <td class="text-center"></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>                                                        
                                                        <?php
                                                    }
                                                    ?>
                                                </div>                                                
                                            </div>
                                            <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica.</p>
                                        </div>
                                    </div>
                                    <?php
                                    foreach ($route as $r) {
                                        if ($r['VTID'] == $vtid) {
                                            $type_name = $v_type['VTDescription'];
                                            $rcode = $r['RCode'];
                                            $source = $r['RSource'];
                                            $destination = $r['RDestination'];
                                            $schedule_type = $r["ScheduleType"];
                                            $route_name = " ตารางเวลาเดิน $vt_name เส้นทาง " . $rcode . ' ' . ' ' . $source . ' - ' . $destination;
                                            ?>                                           
                                            <div id="<?= $rcode . $vtid ?>" class="tab-pane fade">  
                                                <p class="text-right">
                                                    <a href="<?= base_url("schedule/view/$rcode/$vtid") ?>" class="btn btn-primary btn-sm"><i class="fa fa-list-ol"></i>&nbsp;จัดการตารางเวลาเดินรถ</a>
                                                </p>
                                                <?php
                                                foreach ($route_detail as $rd) {
                                                    if ($rcode == $rd['RCode'] && $vtid == $rd['VTID']) {
                                                        $rid = $rd['RID'];
                                                        $source = $rd['RSource'];
                                                        $destination = $rd['RDestination'];
                                                        $schedule_type = $rd["ScheduleType"];
                                                        $start_point = $rd['StartPoint'];
                                                        $route_time = $rd['Time'];
                                                        $route_name = " ตารางเวลาเดิน $vt_name เส้นทาง " . $rcode . ' ' . ' ' . $source . ' - ' . $destination;
//                                                              นับจำนวนสถานี
                                                        $num_station = 0;
                                                        foreach ($stations as $s) {
                                                            if ($rcode == $s['RCode'] && $vtid == $s['VTID']) {
                                                                $num_station ++;
                                                            }
                                                        }
                                                        ?>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading"><?php echo $route_name ?></div>
                                                            <table class="table table-hover table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 10%"></th>
                                                                        <?php
                                                                        $temp = 0;
                                                                        if ($start_point == 'S') {
//                                                                                    start point S
                                                                            foreach ($stations as $s) {
                                                                                if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == '1') {
                                                                                    $station_name = $s['StationName'];
                                                                                    $last_seq_station = $s['Seq'];
                                                                                    $pre_string = "";
                                                                                    if ($s['Seq'] == '1') {
                                                                                        $pre_string = "ออกจากสถานีขนส่ง ";
                                                                                    }
                                                                                    ?>
                                                                                    <th style="width: 20%"><?php echo " $pre_string $station_name"; ?></th>                                                                            
                                                                                    <?php
                                                                                }
                                                                            }
                                                                        } else {
//                                                                         start point D
                                                                            for ($i = $num_station; $i >= 0; $i--) {
                                                                                foreach ($stations as $s) {
                                                                                    if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == '1' && $s['Seq'] == $i) {
                                                                                        $station_name = $s['StationName'];
                                                                                        $last_seq_station = $s['Seq'];
                                                                                        $pre_string = "";
                                                                                        if ($s['Seq'] == $num_station) {
                                                                                            $pre_string = "ออกจากสถานีขนส่ง ";
                                                                                        }
                                                                                        ?>
                                                                                        <th style="width: 20%"><?php echo " $pre_string $station_name"; ?></th>                                                                            
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($schedules as $sd) {
                                                                        if ($rid == $sd['RID']) {
                                                                            $seq_no_schedule = $sd['SeqNo'];
                                                                            $start_time = $sd['TimeDepart']
                                                                            ?>
                                                                            <tr>
                                                                                <td class="text-center"><?php echo $seq_no_schedule; ?></td>
                                                                                <?php
                                                                                foreach ($stations as $s) {
                                                                                    if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == '1') {
                                                                                        $station_name = $s['StationName'];
                                                                                        $travel_time = $s['TravelTime'];
                                                                                        if ($s['Seq'] == '1') {
//                                                                                  สถานีต้นทาง
                                                                                            $time_depart = strtotime($start_time);
                                                                                        } elseif ($s['Seq'] == $num_station) {
//                                                                                  สถานีปลายทาง
                                                                                            $time_depart = strtotime("+$route_time minutes", strtotime($start_time));
                                                                                        } else {
                                                                                            $temp+=$travel_time;
                                                                                            $time_depart = strtotime("+$temp minutes", strtotime($start_time));
                                                                                        }
                                                                                        $time_depart = date('H:i', $time_depart);
                                                                                        ?>
                                                                                        <td class="text-center"><?= $time_depart ?></td>                                                                            
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </tr> 
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>                                                   

                                                                </tbody>
                                                            </table>
                                                        </div>                                                                

                                                        <?php
                                                    }
                                                }
                                                ?>

                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>   

                                </div>
                            </div>
                            <?php
                        }
                        ?>                                
                    <?php } ?>                    
                </div>
            </div>  
        </div>
    </div>
</div>

