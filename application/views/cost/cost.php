<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnCost").addClass("active");

        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'yyyy-m-d'
        });

        $('#ModalFormCost').on('show.bs.modal', function (e) {
            var title = $(e.relatedTarget).data('title');

            $('.modal-title').html('<strong>' + title + '</strong>');

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
        <div class="col-md-12 text-center hidden">   
            <?php
            $income = array(
                'type' => "button",
                'class' => "btn btn-info btn-lg",
            );
            $outcome = array(
                'type' => "button",
                'class' => "btn btn-warning btn-lg",
            );

            echo anchor('cost/add/1', '<span class="fa fa-plus-square">&nbsp;&nbsp;รายรับ</span>', $income) . '  ';
            echo anchor('cost/add/2', '<span class="fa fa-minus-square">&nbsp;&nbsp;รายจ่าย</span>', $outcome);
            ?> 
        </div>
    </div>   
    <div class="row hidden">
        <div class="col-md-12 text-center">
            <div class="btn-group">
                <?php
                foreach ($vehicle_types as $vt) {
                    $vt_id = $vt['VTID'];
                    $vt_name = $vt['VTDescription'];
                    ?>
                    <a href="#vt_<?= $vt_id ?>" class="btn btn-default btn-lg" ><?= $vt_name ?></a>                   
                <?php } ?> 
            </div>
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
                                            <p class="lead">  
                                                <?php
                                                echo "ค่าใช้จ่ายประจำวันที่ $date </small>";
                                                $view = array(
                                                    'class' => "btn btn-link pull-right",
                                                );
                                               echo anchor("cost/view/$rcode/$vtid","ดูรายละเอียด...",$view);
                                                ?>                                               
                                            </p>
                                        </div>
                                        <div class="col-md-12">                                            
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">เบอร์รถ</th>
                                                        <th colspan="<?= $num_sale_point ?>" class="info">เวลา</th>
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
                                                        <!--เวลา-->
                                                        <?php
                                                        foreach ($stations as $s) {
                                                            if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == '1') {
                                                                $station_name = $s['StationName'];
                                                                ?>
                                                                <th class="info"><?= $station_name ?></th>
                                                                <?php
                                                            }
                                                        }
                                                        ?>                                                                                                              
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
                                                                foreach ($stations as $s) {
                                                                    if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == '1') {
                                                                        $station_name = $s['StationName'];
                                                                        ?>
                                                                        <td class="info"><?= $station_name ?></td>
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
                                                                        <td class="success"><?= $station_name ?></td>
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


