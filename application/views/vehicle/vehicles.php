<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnVehicle").addClass("active");
    });
    function remove_vehicle(VID) {
        alert(VID);
    }
</script>
<?php

function DateDiff($strDate1, $strDate2) {
    $date1 = date_create($strDate1);
    $date2 = date_create($strDate2);
    $interval = date_diff($date1, $date2);

    $result = ' ';
    $y = $interval->format('%y');
    $m = $interval->format('%m');
    $d = $interval->format('%d');

    if ($interval->format('%R') == '-')
        $result = '<font color="error">';

    if ($y != '0')
        $result .=$y . ' ปี ';
    if ($m != '0')
        $result .=$m . ' เดือน ';
    if ($d != '0')
        $result .=$d . ' วัน ';

    if ($interval->format('%R') == '-')
        $result .= '</font>';

    return $result;
}
?>
<div class="container">  
    <div class="row animated fadeInDown">
        <div class="col-md-12">
            <div class="page-header">
                <h3>
                    <?php echo $page_title; ?>                   
                    <font color="#777777">
                    <span style="font-size: 23px; line-height: 23.399999618530273px;"><?php echo $page_title_small; ?></span>                
                    </font>
                </h3> 
            </div>           
        </div>
    </div> 
    <div class="row animated fadeInUp">  
        <div class="col-md-12">
            <div id="panalSearchVehicle" class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search"></i>&nbsp;ค้นหารถ</h3>
                </div>
                <div class="panel-body">
                    <?php echo $form['form']; ?>
                    <div class="col-md-12"> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">เส้นทาง</label>
                                <?php echo $form['RCode']; ?> 
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">ประเภทรถ</label>
                            <?php echo $form['VTID']; ?>
                        </div>
                        <div class="col-md-2">                            
                            <label class="control-label" >เบอร์รถ</label>
                            <?php echo $form['VCode']; ?>                            
                        </div>
                        <div class="col-md-2">                                
                            <div class="form-group">
                                <label class="control-label" for="">ทะเบียนรถ</label>
                                <?php echo $form['NumberPlate']; ?>
                            </div>
                        </div> 
                        <div class="col-md-3">                                
                            <label class="control-label" for="">สถานะรถ</label>
                            <input type="text" class="form-control" placeholder="สถานะรถ">
                        </div> 
                        <br>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i>&nbsp;&nbsp;ค้นหา&nbsp;&nbsp;</button>
                        </div>
                    </div>                  
                    <?php echo form_close(); ?>                    
                </div>
            </div>
        </div>
    </div>
    <?php if ($strtitle != '') { ?>
        <div class="row animated pulse">
            <div class="col-md-12">
                <p class="lead">
                    <?php echo $strtitle; ?>
                </p>
            </div>
        </div>
    <?php } ?>   
    <?php if (count($route) <= 0) { ?>
        <div class="row animated fadeInUp">
            <div class="col-md-12">
                <div class="well" style="padding-bottom: 100px;padding-top: 100px;">
                    <p class="lead text-center">ไม่พบข้อมูล</p>
                </div>
            </div>        
        </div>
        <?php
    } else {
        foreach ($vehicle_types as $type) {
            $vtid = $type['VTID'];
            $vt_name = $type['VTDescription'];
            $num_vehicle_in_type = 0;
            foreach ($route as $r) {
                if ($r['VTID'] == $vtid) {
                    $num_vehicle_in_type++;
                }
            }
            ?>
            <div class="row animated fadeInDown">           
                <h3>
                    <?= $type['VTDescription'] ?>
                </h3>
            </div>


            <?php
            if ($num_vehicle_in_type <= 0) {
                ?>

                <div class="col-md-12">
                    <div class="well" style="padding-bottom: 100px;padding-top: 100px;">
                        <p class="lead text-center">ไม่พบข้อมูลกรุณา เพิ่มข้อมูล เส้นทาง <?= $vt_name ?></p>
                    </div>
                </div>        

                <?php
            } else {
                foreach ($route as $r) {
                    if ($vtid == $r['VTID']) {
                        $vtid = $r['VTID'];
                        $rcode = $r['RCode'];
                        $source = $r['RSource'];
                        $destination = $r['RDestination'];
                        $schedule_type = $r["ScheduleType"];
                        $route_name = "$vt_name เส้นทาง " . $rcode . ' ' . ' ' . $source . ' - ' . $destination;
//                        count vehicle in in route
                        $num_vehicle = 0;
                        foreach ($vehicles as $v) {
                            $vid = $v['VID'];
                            if ($vtid == $v['VTID'] && $rcode == $v['RCode']) {
                                $num_vehicle++;
                            }
                        }
                        ?>   
                        <div class="row animated fadeInUp">
                            <div class="col-md-12">          
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title text-primary">
                                            <?php echo $route_name; ?>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <?php if ($num_vehicle > 0) { ?>
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10%" rowspan="2">เบอร์รถ</th>
                                                        <th style="width: 10%" rowspan="2">ทะเบียนรถ</th>
                                                        <th style="width: 20%" rowspan="2">พนักงานขับรถ</th>
                                                        <th style="width: 20%" colspan="2">วันคงเหลือ</th>
                                                        <th style="width: 10%" rowspan="2"></th>
                                                    </tr>
                                                    <tr>
                                                        <th>ทะเบียน</th>
                                                        <th>ประกันเละพรบ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($vehicles as $v) {
                                                        $vid = $v['VID'];
                                                        $driver_name = $v['Title'] . $v['FirstName'] . ' ' . $v['LastName'];
                                                        $mobile = ($v['MobilePhone'] != NULL) ? 'เบอร์โทร ' . $v['MobilePhone'] : '';
                                                        if ($vtid == $v['VTID'] && $rcode == $v['RCode']) {
                                                            $date_expire = "-";
                                                            $policy_end = "-";
                                                            if ($v['DateExpire'] != NULL || $v['DateExpire'] != '') {
                                                                $date_expire = DateDiff($this->m_datetime->getDateToday(), $v['DateExpire']);
                                                            }
                                                            if ($v['PolicyEnd'] != NULL || $v['PolicyEnd'] != '') {
                                                                $policy_end = DateDiff($this->m_datetime->getDateToday(), $v['PolicyEnd']);
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td class="text-center"><?= $v['VCode']; ?></td>
                                                                <td class="text-center"><?= $v['NumberPlate']; ?></td>
                                                                <td><?= $driver_name . '<br/>' . $mobile ?></td>
                                                                <td class="text-center"><?php echo $date_expire; ?></td>
                                                                <td class="text-center"><?php echo $policy_end; ?></td>
                                                                <td class="text-center">
                                                                    <?php
                                                                    $edit = array(
                                                                        'type' => "button",
                                                                        'class' => "btn btn-warning btn-sm",
                                                                        'data-toggle' => "tooltip",
                                                                        'data-placement' => "top",
                                                                        'title' => "แก้ไขข้อมูล",
                                                                    );
                                                                    echo anchor('vehicle/edit/' . $rcode . '/' . $vtid . '/' . $vid, '<i class="fa fa-pencil"></i>', $edit) . ' ';

                                                                    $delete = array(
                                                                        'type' => "button",
                                                                        'class' => "btn  btn-danger btn-sm",
                                                                        'data-id' => "2",
                                                                        'data-title' => "ลบรถ" . $v['VCode'] . " หรือไม่",
                                                                        'data-sub_title' => "ดำเนินการลบ ",
                                                                        'data-info' => " รถ " . $v['VCode'],
                                                                        'data-toggle' => "modal",
                                                                        'data-target' => "#confirm",
                                                                        'data-href' => "vehicle/delete/$vid",
                                                                    );
                                                                    echo anchor('#', '<i class="fa fa-trash-o"></i>', $delete);
                                                                    ?>
                                                                </td>
                                                            </tr>  
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        <?php } ?>
                                        <div class="col-md-12">
                                            <a href="<?= base_url('vehicle/add/') . '/' . $r['RCode'] . '/' . $r['VTID'] ?>" class="btn btn-success pull-right"><i class="fa fa-plus fa-fw"></i>&nbsp;&nbsp;เพิ่ม <?php echo $type['VTDescription'] . ' ' . $route_name ?></a>
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
    }
    ?>  

</div>