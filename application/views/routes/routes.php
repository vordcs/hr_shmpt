<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");

    });
</script>

<div class="container">
    <br>
    <div class="row">        
        <div class="page-header">
            <h1>เส้นทางเดินรถ&nbsp;
                <small></small>
            </h1>
        </div>
    </div>
    <div class="row">        
        <div class="col-md-12">
            <br>
            <div class="panel panel-primary">
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
        $time = date('H:i');

//        for ($i = 5; $i >= 1; $i--) {
        foreach ($vehicles_type as $v_type) {
            $add = array(
                'type' => "button",
                'class' => "btn btn-info btn-lg pull-right",
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="well" style="padding-bottom: 100px;padding-top: 100px;">
                            <p class="lead text-center">ไม่พบข้อมูล</p>
                        </div>
                    </div>        
                </div>
                <?php
            } else {
                foreach ($route as $r) {
                    if ($r['VTID'] == $v_type['VTID']) {
                        $route_name = ' สาย ' . $r['RCode'] . ' ' . ' ' . $r['RSource'] . ' - ' . $r['RDestination'];
                        $vtid = $r['VTID'];
                        $type_name = $v_type['VTDescription'];
                        $rcode = $r['RCode'];
                        $s = $r['RSource'];
                        $d = $r['RDestination'];
                        ?>
                        <div class="col-md-offset-1 col-sm-10">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title"> 
                                        สาย <?= $r['RCode'] . ' : ' . ' ' . $r['RSource'] . ' - ' . $r['RDestination'] . ' (' . $v_type['VTDescription'] . ')' ?>                                         
                                    </h3> 
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-offset-1 col-md-10">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 20%"></th>
                                                        <th>เที่ยวแรก</th>
                                                        <th>เที่ยวสุดท้าย</th>
                                                        <th>ออกทุกๆ</th>
                                                        <th>จำนวนเที่ยว</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <?php
                                                        $first_time = '';
                                                        $last_time = '';
                                                        $IntervalEachAround = '';
                                                        $AroundNumber = '';
//                                                    ไป ปลายทาง -> ขาไป
                                                        foreach ($route_detail as $rd) {
                                                            if ($r['RCode'] == $rd['RCode'] && $r['RSource'] == $rd['RDestination']) {
                                                                $first_time = $rd['StartTime'];
                                                                $last_time = '';
                                                                $IntervalEachAround = $rd['StartTime'];
                                                                $AroundNumber = $rd['AroundNumber'];
                                                            }
                                                        }
                                                        ?>
                                                        <td><strong>ไป <?= $r['RDestination'] ?></strong></td>
                                                        <td><?= $first_time ?></td>
                                                        <td><?= $last_time ?></td>
                                                        <td><?= $IntervalEachAround ?></td>
                                                        <td><?= $AroundNumber ?></td>
                                                    </tr>
                                                    <tr>
                                                        <?php
//                                                    ไป ปลายทาง -> ขากลับ
                                                        foreach ($route_detail as $rd) {
                                                            if ($r['RCode'] == $rd['RCode'] && $r['RSource'] == $rd['RSource']) {
                                                                $first_time = $rd['StartTime'];
                                                                $last_time = '';
                                                                $IntervalEachAround = $rd['StartTime'];
                                                                $AroundNumber = $rd['AroundNumber'];
                                                            }
                                                        }
                                                        ?>
                                                        <td><strong>ไป <?= $r['RSource'] ?></strong></td>
                                                        <td><?= $first_time ?></td>
                                                        <td><?= $last_time ?></td>
                                                        <td><?= $IntervalEachAround ?></td>
                                                        <td><?= $AroundNumber ?></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-offset-1 col-md-10">
                                            <div class="col-md-2">
                                                จุดขายตั๋ว
                                            </div>
                                            <div class="col-md-10 text-left">

                                                <?php for ($n = 0; $n < 6; $n++) { ?>
                                                    <div class="col-xs-6 col-md-4">
                                                        จุดดขายตัวที่ <?= $n ?>
                                                    </div>
                                                <?php } ?>

                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <br>
                                            <div class="text-right">
                                                <?php
                                                $view_detail = array(
                                                    'type' => "button",
                                                    'class' => "btn btn-primary",
                                                    'data-toggle' => "tooltip",
                                                    'data-placement' => "top",
                                                    'title' => "ตารางเวลา " . $type_name . " " . $route_name,
                                                );
                                                $point_price = array(
                                                    'type' => "button",
                                                    'class' => "btn btn-primary",
                                                    'data-toggle' => "tooltip",
                                                    'data-placement' => "top",
                                                    'title' => "จุดจอดและอัตราค่าโดยสาร " . $type_name . " " . $route_name,
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
                                                    'title' => "ลบ เส้นทาง " . $type_name . " " . $route_name,
                                                );
                                                echo anchor('route/detail/' . $rcode . '/' . $vtid, '<i class="fa fa-clock-o" ></i>  ตารางเวลาเดินรถ', $view_detail);
                                                echo '  ';
                                                echo anchor('route/station/' . $rcode . '/' . $vtid, '<i class="fa fa-bus" ></i>  จุดจอดและอัตราค่าโดยสาร', $point_price);
                                                echo '  ';
                                                echo anchor('route/edit/' . $rcode . '/' . $vtid, '<i class="fa fa-pencil"></i>', $edit);
                                                echo ' ';
                                                echo anchor('route/delete/' . $rcode . '/' . $vtid, '<i class="fa fa-trash-o" ></i>', $delete);
                                                ?>                                                
                                            </div>
                                        </div>
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

