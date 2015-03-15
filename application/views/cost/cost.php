<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnCost").addClass("active");

        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'yyyy-m-d'
        });

    });

</script>
<?php

function count_cost_detail($data, $ctid) {
    $n = 0;

    foreach ($data as $value) {
        if ($ctid == $value['CostTypeID']) {
            $n ++;
        }
    }
    return $n;
}

function count_itemp($data_array, $str, $con) {
    $n = 0;
    foreach ($data_array as $value) {
        if ($con == $value[$str]) {
            $n++;
        }
    }
    return $n;
}
?>
<div class="container-fluid">
    <div class="row">      
        <div class="col-md-12">
            <div class="page-header">
                <?php
                $date = $this->m_datetime->DateThaiToDay();
                ?>
                <h2>รายรับ-รายจ่าย <small>ประจำวันที่ <?= $date ?></small></h2>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <br>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search "></i>&nbsp;ค้นหา</h3>
                </div>
                <div class="panel-body" style="min-height: 100px;">                
                    <div class="row">
                        <?php echo $form['form']; ?>
                        <div class="col-md-10 col-md-offset-1">
                            <div class="col-md-3">                            
                                <div class="form-group">
                                    <label for="">ประเภทรถ</label>
                                    <?php echo $form['VTID']; ?>                                       
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">เส้นทาง</label>
                                    <?php echo $form['RCode']; ?> 
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">เบอร์รถ</label>
                                    <?php echo $form['VCode']; ?>
                                </div>
                            </div>

                        </div>                       
                        <div class="col-md-12 text-center">   
                            <br>
                            <button type="submit" class="btn btn-default">ค้นหา</button>   
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <br>
                    <?php
                    $rs = $this->session->flashdata('tab_active');
                    echo $rs;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($strtitle != '') { ?>
        <div class="row">
            <div class="col-md-12">
                <p class="lead">
                    <?php echo $strtitle; ?>
                </p>
            </div>
        </div>
    <?php } ?>  

</div>

<!--All tab of cost-->
<div class="container-fluid">
    <?php foreach ($all_cost as $row_vtid) { ?>
        <div class="row">
            <div class="col-md-12">
                <h3><?= $row_vtid['VTDescription'] ?></h3>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php if (count($row_vtid['line']) > 0) { ?>
                    <div class="panel">
                        <!--start tab bar-->
                        <ul class="nav nav-tabs nav-justified">
                            <?php
                            $flag_first_tab = TRUE;
                            foreach ($row_vtid['line'] as $row_line) {
                                $tab_id = $row_vtid['VTID'] . '__' . $row_line['RCode'];
                                $set_active = NULL;
                                if ($flag_first_tab) {
                                    $flag_first_tab = FALSE;
                                    $set_active = 'active';
                                }
                                ?>
                                <li class="<?= $set_active ?>"><a href="#<?= $tab_id ?>" data-toggle="tab"><?= $row_line['tab_title'] ?></a></li>
                            <?php } ?>
                        </ul>
                        <!--end tab bar-->
                        <!--start tab content-->
                        <div class="tab-content">
                            <?php
                            $flag_first_tab = TRUE;
                            foreach ($row_vtid['line'] as $row_line) {
                                $tab_id = $row_vtid['VTID'] . '__' . $row_line['RCode'];
                                $set_active = NULL;
                                if ($flag_first_tab) {
                                    $flag_first_tab = FALSE;
                                    $set_active = 'active';
                                }
                                ?>
                                <div class="tab-pane <?= $set_active ?>" id="<?= $tab_id ?>">                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="lead text-center">ค่าใช้จ่ายประจำวันที่ <?= $row_line['date'] ?></p>
                                        </div>
                                        <div class="col-md-12">                                            
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="th-blue-jeans"><?= $row_line['thead']['carnum'] ?></th>
                                                        <th colspan="2" class="th-sunflower">จำนวนเที่ยว</th>
                                                        <th colspan="<?= count($row_line['thead']['income']) ?>" class="th-dark-gray">รายรับ</th>
                                                        <th colspan="<?= count($row_line['thead']['charge']) ?>">รายจ่าย</th>
                                                        <th rowspan="2" class="th-grass"><?= $row_line['thead']['balance'] ?></th>
                                                    </tr>
                                                    <tr>
                                                        <th class="th-sunflower"><em>ไป</em> <?= $row_line['thead']['frequencies'][0] ?></th>
                                                        <th class="th-sunflower"><em>ไป</em> <?= $row_line['thead']['frequencies'][1] ?></th>
                                                        <?php foreach ($row_line['thead']['income'] as $row_income) { ?>
                                                        <th class="th-dark-gray"><?= $row_income ?></th>
                                                        <?php } ?>
                                                        <?php foreach ($row_line['thead']['charge'] as $row_charge) { ?>
                                                            <th><?= $row_charge ?></th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($row_line['tbody'] as $row_body) { ?>
                                                        <tr>
                                                            <td class="text-center td-blue-jeans"><?= $row_body['carnum'] ?></td>
                                                            <td class="text-center td-sunflower"><?= $row_body['f_station'] ?></td>
                                                            <td class="text-center td-sunflower"><?= $row_body['l_station'] ?></td>
                                                            <?php foreach ($row_body['income']as $row_income) { ?>
                                                                <td class="text-right td-dark-gray"><?= $row_income ?></td>
                                                            <?php } ?>
                                                            <?php foreach ($row_body['outcome']as $row_outcome) { ?>
                                                                <td class="text-right"><?= $row_outcome ?></td>
                                                            <?php } ?>
                                                            <td class="text-right td-grass"><?= $row_body['balance'] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>   
                                        </div>
                                    </div>
                                </div> 
                            <?php } ?>
                        </div>
                        <!--end tab content-->

                    </div>
                <?php } else {//End (count($row_vtid['line']) > 0)   ?>
                    <div class="panel" style="min-height: 100px;">
                        <p class="lead text-center">ไม่พบข้อมูล</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
<!--End All tab of cost-->

<div class="container-fluid" style="min-height: 500px;">  
    <div class="row">
        <div class="col-md-12 text-center hidden">
            <?php
            $income = array(
                'type' => "button",
                'class' => "btn btn-info btn-lg",
                'data-title' => "รายรับ",
                'data-toggle' => "modal",
                'data-target' => "#ModalFormCost",
                'data-href' => 'Slides/delete/',
            );
            $outcome = array(
                'type' => "button",
                'class' => "btn btn-warning btn-lg",
                'data-title' => "รายจ่าย",
                'data-toggle' => "modal",
                'data-target' => "#ModalFormCost",
                'data-href' => 'Slides/delete/',
            );

            echo anchor('#', '<span class="fa fa-plus-square">&nbsp;&nbsp;รายรับ</span>', $income) . '  ';
            echo anchor('#', '<span class="fa fa-minus-square">&nbsp;&nbsp;รายจ่าย</span>', $outcome);
            ?>                   
        </div>      
    </div>   

    <?php
    $active_id = $this->session->flashdata('tab_active');
    foreach ($vehicle_types as $vt) {
        $vt_id = $vt['VTID'];
        $vt_name = $vt['VTDescription'];
        ?>
        <div class="col-md-12" id="vt_<?= $vt_id ?>">                         
            <p class="lead"><strong><?php echo $vt_name; ?></strong>   
                <span class="pull-right">      
                    <?php
                    $income = array(
                        'type' => "button",
                        'class' => "btn btn-info",
                        'data-toggle' => "tooltip",
                        'data-placement' => "top",
                        'title' => "เพิ่มรายจ่าย " . $vt_name,
                    );
                    $outcome = array(
                        'type' => "button",
                        'class' => "btn btn-warning",
                        'data-toggle' => "tooltip",
                        'data-placement' => "top",
                        'title' => "เพิ่มรายจ่าย " . $vt_name,
                    );

                    echo anchor('cost/add/1/' . $vt_id, '<span class="fa fa-plus">&nbsp;&nbsp;รายรับ ' . $vt_name . '</span>', $income) . '  ';
                    echo anchor('cost/add/2/' . $vt_id, '<span class="fa fa-minus">&nbsp;&nbsp;รายจ่าย ' . $vt_name . '</span>', $outcome);
                    ?>
                </span>  
            </p> 
            <hr>
        </div>        
        <div class="col-md-12">  
            <?php
            $n = count_itemp($routes, 'VTID', $vt_id);
            if ($n == 0) {
                ?>
                <div class="well" style="padding-bottom: 100px;padding-top: 100px;">
                    <p class="lead text-center">ไม่พบข้อมูล</p>
                </div>
            <?php } else { ?>
                <div class="panel">
                    <ul id="RouteTab" class="nav nav-tabs nav-justified" >
                        <?php
                        foreach ($routes as $r) {
                            $rcode = $r['RCode'];
                            $vtid = $r['VTID'];
                            $route_name = $rcode . ' ' . $r['RSource'] . ' - ' . $r['RDestination'];
                            $idtab = $vtid . '_' . $rcode;
                            if ($vt_id == $vtid) {
                                ?>                    
                                <li class="<?= $active_id != NULL && $active_id == $idtab ? 'active' : '' ?> "><a href="#<?= $idtab ?>" data-toggle="tab"><?= $route_name ?></a></li>
                                <?php
                            }
                        }
                        ?>                        
                    </ul>
                    <div id="RouteTabContent" class="tab-content">
                        <?php
                        foreach ($routes as $r) {
                            $rcode = $r['RCode'];
                            $vtid = $r['VTID'];
                            $route_name = $rcode . ' ' . $r['RSource'] . ' - ' . $r['RDestination'];
                            $num_sale_point = 0;

                            foreach ($stations as $s) {
                                if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == '1') {
                                    $num_sale_point++;
                                }
                            }

                            if ($vt_id == $vtid) {
                                $idtab = $vtid . '_' . $rcode;
                                ?>  
                                <div class="tab-pane fade in <?= $active_id != NULL && $active_id == $idtab ? 'active' : '' ?> " id="<?= $idtab ?>">                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="lead text-center">  
                                                <?php
                                                echo "ค่าใช้จ่ายประจำวันที่ $date </small>";
                                                $view = array(
                                                    'class' => "btn btn-link pull-right",
                                                );
                                                echo anchor("cost/view/$rcode/$vtid", "ดูรายละเอียด...", $view);
                                                ?>                                               
                                            </p>
                                        </div>
                                        <div class="col-md-12">                                            
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">เบอร์รถ</th>
                                                        <th colspan="2" class="info">จำนวนเที่ยว</th>
                                                        <th colspan="<?= $num_sale_point + 1 ?>" class="success">รายรับ</th>
                                                        <?php
                                                        foreach ($cost_types as $ct) {
                                                            $ctid = $ct['CostTypeID'];
                                                            $num_detail = count_cost_detail($cost_detail, $ct['CostTypeID']);
                                                            $type_name = $ct['CostTypeName'];
                                                            if ($ctid != '1') {
                                                                ?>
                                                                <th colspan="<?= $num_detail ?>" class="<?= $ct['CostTypeID'] == 1 ? 'success' : 'warning' ?>"><?= $type_name ?></th>                                                       
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        <th rowspan="2">คงเหลือ</th>
                                                    </tr>
                                                    <tr>
                                                        <!--จำนวนเที่ยว-->
                                                        <?php
                                                        //ค้าหาสถานีเเรกและสุดท้าย                                                        
                                                        $n = 0;
                                                        $station_in_route = array();
                                                        foreach ($stations as $station) {
                                                            if ($rcode == $station['RCode'] && $vtid == $station['VTID']) {
                                                                $station_in_route[$n] = $station;
                                                                $n++;
                                                            }
                                                        }
                                                        $first_station_name = $station_in_route[0]['StationName'];
                                                        $first_station_id = $station_in_route[0]['SID'];
                                                        $last_station_name = end($station_in_route)['StationName'];
                                                        $last_station_id = end($station_in_route)['SID'];
                                                        $num_station = count($station_in_route);
                                                        ?>
                                                        <th class="info"><i>ไป</i>&nbsp;<?= $first_station_name ?></th>
                                                        <th class="info"><i>ไป</i>&nbsp;<?= $last_station_name ?></th>
                                                        <!--รายรับ-->
                                                        <?php
                                                        foreach ($stations as $s) {
                                                            if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == '1') {
                                                                $station_name = $s['StationName'];
                                                                ?>
                                                                <th class="success"><?= $station_name ?></th>
                                                                <?php
                                                            }
                                                        }
                                                        foreach ($cost_types as $ct) {
                                                            $ctid = $ct['CostTypeID'];
                                                            foreach ($cost_detail as $cd) {
                                                                $detail = $cd['CostDetail'];
                                                                if ($ctid == $cd['CostTypeID'] && $ctid == '1') {
                                                                    ?>
                                                                    <th class="success"><?= $detail ?></th>                                                       
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?> 
                                                        <!--รายจ่าย-->
                                                        <?php
                                                        foreach ($cost_types as $ct) {
                                                            $ctid = $ct['CostTypeID'];
                                                            foreach ($cost_detail as $cd) {
                                                                $detail = $cd['CostDetail'];
                                                                if ($ctid == $cd['CostTypeID'] && $ctid != '1') {
                                                                    ?>
                                                                    <th class="warning"><?= $detail ?></th>                                                       
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>                                                  
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($vehicles as $v) {
                                                        $income = 0;
                                                        $outcome = 0;
                                                        if ($v['RCode'] == $rcode && $v['VTID'] == $vtid) {
                                                            $vid = $v['VID'];
                                                            ?>
                                                            <tr>
                                                                <!--เบอร์รถ-->
                                                                <td class="text-center"><?= $v['VCode'] ?></td>
                                                                <!--เวลา-->
                                                                <?php
                                                                foreach ($route_details as $rd) {
                                                                    $rid = $rd['RID'];
                                                                    if ($rcode == $rd['RCode'] && $vtid == $rd['VTID']) {
                                                                        $start_point = $rd['StartPoint'];
                                                                        $num_round = 0;
                                                                        foreach ($schedules as $schedule) {
                                                                            if ($rid == $schedule['RID'] && $vid == $schedule['VID']) {
                                                                                $num_round++;
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <td class="text-center info"><?= $num_round ?></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>

                                                                <!--รายรับ-->
                                                                <?php
//                                                                รายรับแต่ละจุดขายตั๋ว
                                                                foreach ($stations as $s) {
                                                                    if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == '1') {
                                                                        $station_name = $s['StationName'];
                                                                        ?>
                                                                        <td class="success">&nbsp;</td>
                                                                        <?php
                                                                    }
                                                                }
//                                                                รายรับรายทาง
                                                                $ctid = '1';
                                                                foreach ($cost_detail as $cd) {
                                                                    $cdid = $cd['CostDetailID'];
                                                                    if ($ctid == $cd['CostTypeID']) {
                                                                        $value = 0;
                                                                        foreach ($cost as $c) {
                                                                            if ($ctid == $c['CostTypeID'] && $cdid == $c['CostDetailID'] && $vid == $c['VID']) {
                                                                                $temp = $c['CostValue'];
                                                                                $value += $temp;
                                                                                $income += $temp;
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <td class="text-center success"><?= $value != 0 ? $value : '' ?></td>                                                       
                                                                        <?php
                                                                    }
                                                                }
                                                                ?> 
                                                                <!--รายจ่าย-->
                                                                <?php
                                                                $ctid = '2';
                                                                foreach ($cost_detail as $cd) {
                                                                    $cdid = $cd['CostDetailID'];
                                                                    if ($ctid == $cd['CostTypeID']) {
                                                                        $value = 0;
                                                                        foreach ($cost as $c) {
                                                                            if ($ctid == $c['CostTypeID'] && $cdid == $c['CostDetailID'] && $vid == $c['VID']) {
                                                                                $temp = $c['CostValue'];
                                                                                $value += $temp;
                                                                                $outcome+=$temp;
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <td class="text-center <?= $ctid == '1' ? 'success' : 'warning' ?>"><?= $value != 0 ? $value : '' ?></td>                                                       
                                                                        <?php
                                                                    }
                                                                }
                                                                ?> 
                                                                <!--คงเหลือ-->
                                                                <td class="text-center"> <?php echo number_format(($income - $outcome), 2, '.', ''); ?></td>

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

                                <?php
                            }
                        }
                        ?>  

                    </div>

                </div>
            <?php } ?>
        </div>  
    <?php } ?>
</div>

<!-- Modal รายจ่าย-->
<div class="modal fade" id="ModalFormCost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">...</h4>
            </div>
            <form class="form-horizontal" role="form">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">วันที่</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control datepicker" id="" placeholder="" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">เบอร์รถ</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="" placeholder="" required="">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">จำนวน</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="" placeholder="" required="">
                        </div>
                        <div class="col-sm-2">
                            <span class="text-left">บาท</span>                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">หมายเหตุ</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-times">&nbsp;&nbsp;ยกเลิก</span></button>                  
                    <button type="submit" class="btn btn-info"><span class="fa fa-save">&nbsp;&nbsp;Save changes</span></button>
                </div> 
            </form>
        </div>
    </div>
</div>


