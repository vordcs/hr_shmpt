<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnSchedule").addClass("active");

        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'yyyy-m-d'
        });
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
            <h3><?= $page_title ?>&nbsp;
                <small><?= $page_title_small ?></small>
            </h3>
        </div>
    </div>   
    <div class="row animated fadeInUp">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search"></i>&nbsp;ค้นหา</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-10 col-md-offset-1">
                        <?php echo $form_search['form']; ?>
                        <div class="col-md-3">
                            <label class="control-label">ประเภทรถ</label>
                            <?php echo $form_search['VTID']; ?>
                        </div>  
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label">เส้นทาง</label>
                                <?php echo $form_search['RCode']; ?>
                            </div>
                        </div>                                          
                        <div class="col-md-4">                                
                            <label class="control-label">วันที่</label>
                            <?php echo $form_search['Date']; ?>                              
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
                        <h3><?= $vt_name ?> : <small><?= $page_title_small ?></small></h3> 

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
                                    <p class="lead text-center">ไม่พบข้อมูล : <small><?= $page_title_small ?></small></p>
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
                                                    <a href="<?= base_url("schedule/vehicle_point/$rcode/$vtid") ?>" class="btn btn-success btn-sm"><i class="fa fa-car"></i>&nbsp;จัดตำแหน่งรถ</a>
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

                                                        //นับจำนวนสถานี
                                                        $num_station = 0;
                                                        $num_sale_station = 0;
                                                        foreach ($stations as $s) {
                                                            if ($rcode == $s['RCode'] && $vtid == $s['VTID']) {
                                                                $num_station ++;
                                                                if ($s['IsSaleTicket'] == '1') {
                                                                    $num_sale_station ++;
                                                                }
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
                                                                        if ($start_point == "S") {
                                                                            //start point s
                                                                            for ($i = 0; $i <= $num_station; $i++) {
                                                                                foreach ($stations as $s) {
                                                                                    if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == '1' && $s['Seq'] == $i) {
                                                                                        $station_name = $s['StationName'];
                                                                                        $last_seq_station = $s['Seq'];
                                                                                        $pre_string = "";
                                                                                        if ($s['Seq'] == 1) {
                                                                                            $pre_string = "ออกจากสถานีขนส่ง "; //$pre_string, $station_name;
                                                                                        }
                                                                                        $width = 80 / $num_sale_station;
                                                                                        echo "<th style=\"width: $width%\"> $pre_string $station_name</th>";
                                                                                    }
                                                                                }
                                                                            }
                                                                        } else {
                                                                            //start point D
                                                                            for ($i = $num_station; $i >= 0; $i--) {
                                                                                foreach ($stations as $s) {
                                                                                    if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == '1' && $s['Seq'] == $i) {
                                                                                        $station_name = $s['StationName'];
                                                                                        $last_seq_station = $s['Seq'];
                                                                                        $pre_string = "";
                                                                                        if ($s['Seq'] == $num_station) {
                                                                                            $pre_string = "ออกจากสถานีขนส่ง "; //$pre_string, $station_name;
                                                                                        }
                                                                                        $width = 80 / $num_sale_station;
                                                                                        echo "<th style=\"width: $width%\">$pre_string $station_name</th>";
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <th style="width: 10%">เบอร์รถ</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($schedules as $sd) {
                                                                        if ($rid == $sd['RID']) {
                                                                            $seq_no_schedule = $sd['SeqNo'];
                                                                            $start_time = $sd['TimeDepart'];
                                                                            $vcode = $sd['VCode'];
                                                                            if ($vcode == '') {
                                                                                $vcode = '-';
                                                                            }
                                                                            ?>
                                                                            <tr>
                                                                                <td class="text-center"><?php echo $seq_no_schedule; ?></td>
                                                                                <?php
                                                                                $temp = 0;
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
                                                                                <td class="text-center"><strong><?= $vcode ?></strong></td>
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

