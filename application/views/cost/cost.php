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
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search "></i>&nbsp;ค้นหา</h3>
                </div>
                <div class="panel-body" style="min-height: 100px;">
                    Panel content
                    <?php
                    $rs = $this->session->flashdata('tab_active');
                    echo $rs;
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">  
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
        <div class="col-md-12 text-center">   
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
            <p class="lead"><strong><?php echo $vt_name; ?></strong></p>
            <hr>
        </div>        
        <div class="col-md-12">         
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
                        if ($vt_id == $vtid) {
                            $idtab = $vtid . '_' . $rcode;
                            ?>  
                            <div class="tab-pane fade in <?= $active_id != NULL && $active_id == $idtab ? 'active' : '' ?> " id="<?= $idtab ?>">                                    
                                <div class="row">
                                    <div class="col-md-12">
                                        <p><?= $route_name ?></p>
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">เบอร์รถ</th>
                                                    <th colspan="4" class="info">เวลา</th>
                                                    <?php
                                                    foreach ($cost_types as $ct) {
                                                        $num_detail = count_cost_detail($cost_detail, $ct['CostTypeID']);
                                                        $type_name = $ct['CostTypeName'];
                                                        ?>
                                                        <th colspan="<?= $num_detail ?>" class="<?= $ct['CostTypeID'] == 1 ? 'success' : 'warning' ?>"><?= $type_name ?></th>                                                       
                                                    <?php } ?>
                                                    <th rowspan="2">คงเหลือ</th>
                                                </tr>
                                                <tr>
                                                    <!--เวลา-->
                                                    <th class="info">จุดลงจอดที่ 1</th>
                                                    <th class="info">จุดลงจอดที่ 2</th>
                                                    <th class="info">จุดลงจอดที่ 3</th>
                                                    <th class="info">จุดลงจอดที่ 3</th>
                                                    <!--รายจ่าย--><!--รายรับ-->
                                                    <?php
                                                    foreach ($cost_types as $ct) {
                                                        $ctid = $ct['CostTypeID'];
                                                        foreach ($cost_detail as $cd) {
                                                            $detail = $cd['CostDetail'];
                                                            if ($ctid == $cd['CostTypeID']) {
                                                                ?>
                                                                <th class="<?= $ctid == '1' ? 'success' : 'warning' ?>"><?= $detail ?></th>                                                       
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
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>                                                                
                                                            <td></td>
                                                            <!--รายรับ-->
                                                            <?php
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
                                                                    <td class="text-center <?= $ctid == '1' ? 'success' : 'warning' ?>"><?= $value != 0 ? $value : '' ?></td>                                                       
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
            <?php ?>
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


