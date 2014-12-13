<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");

    });
</script>
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
    return date('H:i:s', $time);
}
?>

<div class="container">
    <br>
    <div class="row">        
        <div class="page-header">
            <h3>เส้นทางเดินรถ&nbsp;
                <small></small>
            </h3>
        </div>
    </div> 
    <div class="row">        
        <div class="col-md-12">           
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search"></i>&nbsp;&nbsp;ค้นหา</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-12">                        
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
    </div>    
    <?php if ($strtitle != '') { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>                    
                    <p class="lead">
                        <?php echo $strtitle; ?>
                    </p>
                </div>
            </div>
        </div>
    <?php } ?>     

    <div class="row">
        <?php
        foreach ($vehicles_type as $v_type) {
            $add = array(
                'type' => "button",
                'class' => "btn btn-primary btn-lg pull-right",
            );
            $n = 0;
            foreach ($route as $r) {
                if ($r['VTID'] == $v_type['VTID']) {
                    $n++;
                }
            }
            ?>
            <div class="col-md-12">
                <div class="col-xs-8">
                    <h3 class=""><?= $v_type['VTDescription'] ?></h3> 
                </div>
                <div class="col-xs-4">
                    <?php echo anchor('route/add/' . $v_type['VTID'], '<i class="fa fa-plus"></i>&nbsp;เพิ่มเส้นทาง&nbsp' . $v_type['VTDescription'], $add); ?>
                </div>
            </div>        
            <?php
            if ($n <= 0) {
                ?>

                <div class="col-md-12">
                    <div class="well" style="padding-bottom: 100px;padding-top: 100px;">
                        <p class="lead text-center">ไม่พบข้อมูล</p>
                    </div>
                </div>        

                <?php
            } else {
                foreach ($route as $r) {
                    if ($r['VTID'] == $v_type['VTID']) {
                        $vtid = $r['VTID'];
                        $type_name = $v_type['VTDescription'];
                        $rcode = $r['RCode'];
                        $source = $r['RSource'];
                        $destination = $r['RDestination'];
                        $schedule_type = $r["ScheduleType"];
                        $route_name = '  เส้นทางสาย ' . $rcode . ' ' . ' ' . $source . ' - ' . $destination;
                        ?>
                        <div class="col-md-offset-1 col-sm-10">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title"> 
                                        <?= $route_name . ' (' . $type_name . ')' ?>                                         
                                    </h3> 
                                </div>
                                <div class="panel-body">

                                    <div class="col-md-offset-1 col-md-10">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th style="width: 20%"><?= $source ?></th>
                                                    <th style="width: 20%">เที่ยวแรก</th>
                                                    <th style="width: 20%">เที่ยวสุดท้าย</th>
                                                    <th style="width: 15%">ออกทุกๆ (นาที)</th>
                                                    <th style="width: 15%">จำนวนเที่ยว</th>
                                                    <th style="width: 20%">ใช้เวลา (ชั่วโมง)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $first_time = '';
                                                $last_time = '';
                                                $IntervalEachAround = '';
                                                $AroundNumber = '';
                                                $time = '';
                                                foreach ($route_detail as $rd) {
                                                    if ($rcode == $rd['RCode'] && $vtid == $rd['VTID']) {
                                                        echo $rd["RID"] . $rd['StartPoint'];
                                                    }
                                                }
                                                ?>
                                                <tr>   
                                                    <?php
                                                    foreach ($route_detail as $rd) {
                                                        if ($rcode == $rd['RCode'] && $vtid == $rd['VTID'] && 'S' == $rd['StartPoint']) {
                                                            $rid_s = $rd['RID'];
                                                            if ($schedule_type != 1) {
                                                                //auto
                                                                $first_time = $rd['StartTime'];
                                                                $last_time = last_around($rd['StartTime'], $rd['IntervalEachAround'], $rd['AroundNumber']);
                                                                $IntervalEachAround = $rd['IntervalEachAround'];
                                                                $AroundNumber = $rd['AroundNumber'];
                                                            } else {
                                                                //manual 
                                                                $n = 0;
                                                                foreach ($schedule_manual as $sm) {
                                                                    if ($sm["RID"] == $rid_s) {
                                                                        $n++;
                                                                    }
                                                                    if ($sm["RID"] == $rid_s && $sm['SeqNo'] == 1) {
                                                                        $first = date('H:i', strtotime($sm['Time']));
                                                                    }
                                                                    if ($sm["RID"] == $rid_s && $sm['SeqNo'] == $n) {
                                                                        $last = date('H:i', strtotime($sm['Time']));
                                                                    }
                                                                }

                                                                $first_time = $first;
                                                                $last_time = $last;
                                                                $IntervalEachAround = '-';
                                                                $AroundNumber = $n;
                                                            }
                                                            $time = gmdate('H.i', $rd['Time'] * 60);
                                                        }
                                                    }
                                                    ?>
                                                    <td><strong>ไป <?= $destination ?></strong></td>
                                                    <td class="text-center"><?= $first_time ?></td>
                                                    <td class="text-center"><?= $last_time ?></td>
                                                    <td class="text-center"><?= $IntervalEachAround ?></td>
                                                    <td class="text-center"><?= $AroundNumber ?></td>
                                                    <td class="text-center"><?= $time ?></td>
                                                </tr> 

                                                <tr>
                                                    <?php
//                                                    StartPoint -> D
                                                    foreach ($route_detail as $rd) {
                                                        if ($rcode == $rd['RCode'] && $vtid == $rd['VTID'] && 'D' == $rd['StartPoint']) {
                                                            $rid_d = $rd['RID'];
                                                            if ($schedule_type != 1) {
                                                                //auto
                                                                $first_time = $rd['StartTime'];
                                                                $last_time = last_around($rd['StartTime'], $rd['IntervalEachAround'], $rd['AroundNumber']);
                                                                $IntervalEachAround = $rd['IntervalEachAround'];
                                                                $AroundNumber = $rd['AroundNumber'];
                                                            } else {
                                                                //manual
                                                                $n = 0;
                                                                foreach ($schedule_manual as $sm) {
                                                                    if ($sm["RID"] == $rid_d) {
                                                                        $n++;
                                                                    }
                                                                    if ($sm["RID"] == $rid_d && $sm['SeqNo'] == 1) {
                                                                        $first = date('H:i', strtotime($sm['Time']));
                                                                    }
                                                                    if ($sm["RID"] == $rid_d && $sm['SeqNo'] == $n) {
                                                                        $last = date('H:i', strtotime($sm['Time']));
                                                                    }
                                                                }
                                                                $first_time = $first;
                                                                $last_time = $last;
                                                                $IntervalEachAround = '-';
                                                                $AroundNumber = $n;
                                                            }
                                                            $time = gmdate('H.i', $rd['Time'] * 60);
                                                        }
                                                    }
                                                    ?>
                                                    <td><strong>ไป <?= $source ?></strong></td>
                                                    <td class="text-center"><?= $first_time ?></td>
                                                    <td class="text-center"><?= $last_time ?></td>
                                                    <td class="text-center"><?= $IntervalEachAround ?></td>
                                                    <td class="text-center"><?= $AroundNumber ?></td>
                                                    <td class="text-center"><?= $time ?></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>  
                                    <div class="col-md-offset-1 col-md-10">
                                        <div class="col-md-2">
                                            <span class="text">
                                                จุดขายตั๋ว  
                                            </span>                                            
                                        </div>
                                        <div class="col-md-10 text-left">
                                            <blockquote>   
                                                <?php
                                                foreach ($stations as $s) {
                                                    $station_name = $s['StationName'];
                                                    if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == 1) {
                                                        ?>
                                                        &nbsp;&nbsp;                                                        
                                                            <span class="fa fa-tag">                                                                
                                                                <span class="text">
                                                                    <?= $station_name ?> 
                                                                </span>
                                                            </span> 
                                                         
                                                        &nbsp;&nbsp;
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </blockquote>
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-right">
                                        <br>
                                        <?php
                                        $view = array(
                                            'type' => "button",
                                            'class' => "btn btn-primary",
                                            'data-toggle' => "tooltip",
                                            'data-placement' => "top",
                                            'title' => "ข้อมูล" . $route_name . " (" . $type_name . ") ",
                                        );

                                        $edit = array(
                                            'type' => "button",
                                            'class' => "btn btn-warning",
                                            'data-toggle' => "tooltip",
                                            'data-placement' => "top",
                                            'title' => "แก้ไขข้อมูล " . $type_name . " " . $route_name,
                                        );

                                        $delete = array(
                                            'type' => "button",
                                            'class' => "btn btn-danger",
                                            'data-toggle' => "tooltip",
                                            'data-placement' => "top",
                                            'title' => "ลบเส้นทาง " . $type_name . " " . $route_name,
                                        );

                                        echo anchor('route/view/' . $rcode . '/' . $vtid, '<i class="fa fa-eye" ></i>', $view);
                                        echo '  ';
                                        echo anchor('route/edit/' . $rcode . '/' . $vtid, '<i class="fa fa-pencil"></i>', $edit);
                                        echo ' ';
                                        echo anchor('route/delete/' . $rcode . '/' . $vtid, '<i class="fa fa-trash-o" ></i>', $delete);
                                        echo '  ';
                                        ?>                                                
                                    </div>

                                </div>
                            </div>       
                        </div>                  
                        <?php
                    }
                }
            }
        }
        ?>
    </div>
</div>

<?php
//                                                    echo anchor('route/station/' . $rcode . '/' . $vtid, '<i class="fa fa-bus" ></i>  จุดจอดและอัตราค่าโดยสาร', $point_price);
//                                                    $view_detail = array(
//                                                        'type' => "button",
//                                                        'class' => "btn btn-primary  hidden",
//                                                        'data-toggle' => "tooltip",
//                                                        'data-placement' => "top",
//                                                        'title' => "ตารางเวลา " . $type_name . " " . $route_name,
//                                                    );
//                                                    $point_price = array(
//                                                        'type' => "button",
//                                                        'class' => "btn btn-primary  hidden",
//                                                        'data-toggle' => "tooltip",
//                                                        'data-placement' => "top",
//                                                        'title' => "จุดจอดและอัตราค่าโดยสาร " . $type_name . " " . $route_name,
//                                                    );
?>

