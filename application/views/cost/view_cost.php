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
<div class="container">
    <div class="row animated fadeIn">        
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
    <div class="row animated fadeIn">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-search "></i>&nbsp;ค้นหา</h3>
            </div>
            <div class="panel-body" style="min-height: 100px;"> 
                <?php echo $form['form']; ?>           
                <div class="col-md-6 col-md-offset-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">วันที่</label>
                            <div class="col-sm-9">
                                <?php echo $form['DateForm']; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">ถึง</label>
                            <div class="col-sm-10">
                                <?php echo $form['DateTo']; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-center">   
                    <br>
                    <button type="submit" class="btn btn-default"><span class="fa fa-search">&nbsp;ค้นหา</span></button>   
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>        
    </div>
    <?php if ($strtitle != '') { ?>
        <div class="row animated fadeInUp">
            <div class="col-md-12">
                <p class="lead">
                    <?php echo $strtitle; ?>
                </p>
            </div>
        </div>
    <?php } ?> 
    <div class="row animated fadeIn">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil "></i>&nbsp;ข้อมูลค่าใช้จ่าย</h3>
            </div>            
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


                $idtab = $vtid . '_' . $rcode;
                ?>  

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
//                                 รายรับรายทาง
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


                <?php
            }
            ?> 

        </div>
    </div>

</div>