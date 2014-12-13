<script language="javascript">
    $(document).ready(function () {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");
    });
</script>
<?php

function get_price($data, $source_id, $destination_id) {
    $result = array();
    foreach ($data as $f) {
        if ($source_id == $f['SourceID'] && $destination_id == $f['DestinationID']) {
            $result[0] = $f['Price'];
            $result[1] = $f['PriceDicount'];
        }
    }
    return $result;
}
?>
<div class="container">

    <div class="row">        
        <div class="page-header">        
            <h3>
                <?php echo $page_title; ?>
                <br>
                <font color="#777777">
                <span style="font-size: 23px; line-height: 23.399999618530273px;"><?php echo $page_title_small; ?></span>                
                </font>
            </h3>        
        </div>
    </div>
    <div class="row clearfix animated fadeIn">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"> 
                        ข้อมูลเวลาเดินรถ
                        <?php
                        $edit_time = array(
                            'type' => "button",
                            'class' => "btn btn-warning btn-sm pull-right",
                            'data-toggle' => "tooltip",
                            'data-placement' => "top",
                            'title' => "แก้ไขข้อมูลเวลาเดินรถ",
                        );
                        echo anchor('route/time/' . $rcode . '/' . $vtid, '<i class="fa fa fa-edit"></i>', $edit_time);
                        ?>                        
                    </h3>                    
                </div>           
                <div class="panel-body">
                    <?php
                    foreach ($route_detail as $rd) {
                        $rid = $rd["RID"];
                        $start_point = $rd['StartPoint'];
                        $start_time = $rd['StartTime'];
                        $interval = $rd['IntervalEachAround'];
                        $around = $rd['AroundNumber'];
                        if ($rd["ScheduleType"] == "1") {
                            ?>
                            <div class="col-md-6">
                                <div class="col-md-12">                                
                                    <p class="lead">                            
                                        <?php echo '<strong>' . $rd['RSource'] . '  <span class="fa fa-arrow-circle-right fa-fw"></span>  ' . $rd['RDestination'] . '<strong>'; ?>
                                    </p>
                                </div>
                                <div class="col-md-8 col-md-offset-2">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 20%">เที่ยวที่</th>
                                                <th style="width: 60%">เวลา</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($schedule_manual as $sm) {
                                                if ($rid == $sm["RID"]) {
                                                    $seq_no = $sm["SeqNo"];
                                                    $time = date("H:i", strtotime($sm["Time"]));
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"> <?= $seq_no ?></td>
                                                        <td class="text-center"><?= $time ?></td>
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
                        } else {
                            ?>

                            <div class="col-md-6">
                                <div class="">                                
                                    <p class="lead">                            
                                        <?php echo '<strong>' . $rd['RSource'] . '  <span class="fa fa-arrow-circle-right fa-fw"></span>  ' . $rd['RDestination'] . '<strong>'; ?>
                                    </p>
                                </div>
                                <div class="col-md-8 form-horizontal">
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">เวลาเที่ยวแรก</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control text-center" value="<?php echo date('H:i', strtotime($start_time)) ?>" disabled> 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">ระยะห่าง</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control text-center" value="<?php echo $interval ?>" disabled> 
                                        </div>
                                        <div class="col-sm-2">
                                            <span class="">นาที</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">จำนวนเที่ยว</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control text-center" value="<?php echo $around ?>" disabled> 
                                        </div>
                                        <div class="col-sm-2">
                                            <span class="">เที่ยว/วัน</span>
                                        </div>
                                    </div>                                
                                </div>
                                <div class="col-md-4">
                                    <div class="panel-group panel-group-lists" id="accordion<?= $start_point ?>">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion<?= $start_point ?>" href="#<?= $start_point ?>">
                                                        ตารางเวลาเดินรถ
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="<?= $start_point ?>" class="panel-collapse collapse">
                                                <table id="table" class="table table-striped table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 20%">ลำดับ</th>
                                                            <th style="width: 80%">เวลา</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="table_body<?= $start_point ?>">
                                                        <?php
                                                        if ($start_time != '' && $interval != '' && $around != '') {
                                                            for ($j = 0; $j < $around; $j++) {
                                                                $time = strtotime($start_time) + $interval * 60 * $j;
                                                                ?>
                                                                <tr>
                                                                    <td class="text-center"><?= $j + 1 ?></td>
                                                                    <td class="text-center"><?= date('H:i', $time); ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                            </div>

                            <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix animated fadeIn">
        <div class="col-md-3">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"> 
                        ข้อมูลจุดจอด
                        <?php
                        $edit_station = array(
                            'type' => "button",
                            'class' => "btn btn-warning btn-sm pull-right",
                            'data-toggle' => "tooltip",
                            'data-placement' => "top",
                            'title' => "แก้ไขข้อมูลจุดจอด",
                        );
                        if (count($stations)) {
                            echo anchor('station/edit/' . $rcode . '/' . $vtid, '<i class="fa fa fa-edit"></i>', $edit_station);
                        }
                        ?> 
                    </h3>                    
                </div>           
                <div class="panel-body">
                    <?php
                    $num_station = count($stations);
                    if ($num_station <= 0) {
                        ?>   
                        <div class="col-md-12">
                            <div class="text-center" style="padding-bottom: 100px;padding-top: 100px;">
                                <?php
                                $add_station = array(
                                    'type' => "button",
                                    'class' => "btn btn-primary",
                                    'data-toggle' => "tooltip",
                                    'data-placement' => "top",
                                    'title' => "กำหนดข้อมูลจุดจอด",
                                );

                                echo anchor('station/add/' . $rcode . '/' . $vtid, '<i class="fa fa fa-plus"></i>  กำหนดจุดจอด', $add_station);
                                ?>
                            </div>
                        </div>   
                        <?php
                    } else {
                        foreach ($route_detail as $rd) {
                            $start_point = $rd['StartPoint'];
                            $start_time = $rd['StartTime'];
                            $interval = $rd['IntervalEachAround'];
                            $around = $rd['AroundNumber'];
                            ?>                       
                            <table class="table table-striped">                            
                                <thead>
                                    <tr class="info">
                                        <th colspan="3">
                                            <?php echo '<strong>' . $rd['RSource'] . '  <span class="fa fa-arrow-circle-right fa-fw"></span>  ' . $rd['RDestination'] . '<strong>'; ?>                     
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%">#</th>
                                        <th style="width: 80%">จุดจอด</th>
                                        <th style="width: 10%">ขายตั๋ว</th>
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php
                                    if ($start_point == "S") {
                                        for ($i = 0, $j = 1; $i < $num_station; $i++, $j++) {
                                            $station_name = $stations[$i]['StationName'];
                                            $is_sale = "";
                                            if ($stations[$i]['IsSaleTicket'] == '1') {
                                                $is_sale = "<i class='fa fa-check'></i>";
                                            }
                                            ?> 
                                            <tr>
                                                <td class="text-center">
                                                    <?= $j ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $station_name ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $is_sale ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        for ($i = $num_station - 1, $j = 1; $i >= 0; $i--, $j++) {
                                            $station_name = $stations[$i]['StationName'];
                                            $is_sale = "";
                                            if ($stations[$i]['IsSaleTicket'] == '1') {
                                                $is_sale = "<i class='fa fa-check'></i>";
                                            }
                                            ?>    

                                            <tr>
                                                <td class="text-center">
                                                    <?= $j ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $station_name ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $is_sale ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>                        
                                </tbody>
                            </table>
                            <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"> 
                        ค่าโดยสาร
                        <?php
                        $edit_fares = array(
                            'type' => "button",
                            'class' => "btn btn-warning btn-sm pull-right",
                            'data-toggle' => "tooltip",
                            'data-placement' => "top",
                            'title' => "แก้ไขข้อมูลค่าโดยสาร",
                        );

                        if (count($stations) > 0) {
                            echo anchor('fares/edit/' . $rcode . '/' . $vtid, '<i class="fa fa fa-edit"></i>', $edit_fares);
                        }
                        ?> 
                    </h3>                    
                </div>


                <?php
                $num_station = count($stations);
                if ($num_station <= 0) {
                    ?>   
                    <div class="col-md-12">
                        <div class="" style="padding-bottom: 100px;padding-top: 100px;">
                            <p class="lead text-center">ไม่พบข้อมูล</p>
                        </div>
                    </div> 
                <?php } else {
                    ?>
                    <table id="tableStation" class="table table-hover table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th rowspan="2"  class="text-center" style="width:30%;">ต้นทาง</th>
                                <th rowspan="2" class="text-center" style="width:30%;">ปลายทาง</th>
                                <th colspan="2" class="text-center" style="width:30%;">ค่าโดยสาร</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="width:10%;">เต็ม</th>
                                <th class="text-center" style="width:10%;">ลด</th>  
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $number_station = count($stations) - 1;
                            for ($i = 0; $i < $number_station; $i++) {
                                $s = array_shift($stations);
                                $source_id = $s['SID'];
                                $source_name = $s['StationName'];
                                $num_destination = count($stations);
                                ?>
                                <tr>
                                    <td rowspan="<?= $num_destination + 1 ?>" class="text-center">
                                        <?= $source_name ?>
                                    </td>
                                </tr>
                                <?php
                                $j = 0;
                                foreach ($stations as $st) {
                                    $destination_id = $st['SID'];
                                    $destination_name = $st['StationName'];
                                    $p = get_price($fares, $source_id, $destination_id);
                                    $price = '';
                                    $price_dis = '';
                                    if (count($p) > 0) {
                                        $price = $p[0];
                                        $price_dis = $p[1];
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            <?= $destination_name ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $price ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $price_dis ?>
                                        </td>
                                    </tr>

                                <?php } ?>

                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!--<form id="frm_route_detail" class="form-horizontal" >   
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">ไป มุกดาหาร</h3>
                    </div>
                    <div class="panel-body">                        
                        <div class="form-group has-feedback">
                            <label for="" class="col-sm-3 control-label">เวลาเที่ยวแรก</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="" placeholder="">
                                <span class="fa fa-clock-o form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">เวลาห่าง</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" id="" placeholder="">
                            </div>
                            <div class="col-sm-1">
                                <p>นาที</p> 
                            </div>                            
                            <label for="" class="col-sm-2 control-label">จำนวน</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" id="" placeholder="">
                            </div>
                            <div class="col-sm-2">
                                <p>เที่ยว / วัน</p>  
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">ไป ขอนแก่น</h3>
                    </div>
                    <div class="panel-body">

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">จุดจอดและค่าโดยสาร</h3>
                    </div>
                    <div class="panel-body">
                        <table id="tableStation" class="table table-hover">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center" style="width:11%;">ขายตั๋ว</th>
                                    <th rowspan="2" class="text-center" style="width:20%;">ต้นทาง</th>
                                    <th rowspan="2" class="text-center" style="width:20%;">ปลายทาง</th>
                                    <th colspan="2" class="text-center" style="width:20%;">ค่าโดยสาร</th>
                                    <th rowspan="2"></th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>

                                    <th class="text-center" style="width:10%;">เต็ม</th>
                                    <th class="text-center" style="width:10%;">ลด</th>

                                </tr>
                            </thead>
                            <tbody id="tableBodyStation">
                                <tr>
                                    <td class="text-center">
                                        <div class="checkbox">
                                            <input type="checkbox"> 
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"> 
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"> 
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"> 
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"> 
                                    </td>
                                    <td>
                                        <a class="btn btn-danger btn-sm"><i class="fa fa-minus"</a>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td>
                                        <a name="btnAddSource"  id="btnAddSource" class="btn btn-block" onClick="CreateNewRow();"><i class="fa fa-plus"></i>&nbsp;ต้นทาง</a>
                                    </td>
                                    <td>                                       
                                        <a name="btnAddDes" id="btnAddDes" class="btn btn-block" onClick="CreateDestination()"><i class="fa fa-plus"></i>&nbsp;ปลายทาง </a>
                                    </td>

                                </tr>
                            </tfoot>
                        </table>
                        <div class="col-md-12">
                            <a class="btn btn-danger btn-sm" onClick="RemoveDes()"><i class="fa fa-minus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </form>-->