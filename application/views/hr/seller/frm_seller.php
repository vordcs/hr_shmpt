
<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnHR").addClass("active");
        var num_station = '<?= count($stations) ?>';
        if (num_station === '0') {
            $('#list_routes').addClass('animated fadeInUp col-md-offset-3');
        } else {
            $('#list_routes').removeClass('col-md-offset-3');
            $('#list_stations').removeClass('hidden').addClass('animated pulse');
        }

        // Add the "focus" value to class attribute
        $('ul.radio li').focusin(function () {
            $(this).addClass('focus');
        }
        );

        // Remove the "focus" value to class attribute
        $('ul.radio li').focusout(function () {
            $(this).removeClass('focus');
        }
        );


    });
</script>
<style type="text/css">
    .list-scholl {                    
        max-height: 200px;
        overflow-y: scroll;                   
    }   
</style>
<div class="container">
    <div class="row animated">      
        <div class="col-md-12 ">
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
    <div class="row animated">   
        <div class="col-md-12">
            <ul class="nav nav-pills nav-justified">
                <li><a href="<?= base_url('hr/home') ?>">หน้าหลัก</a></li>
                <li><a href="<?= base_url('hr/employee/') ?>">พนักงาน</a></li>
                <li class="active"><a href="<?= base_url('hr/seller/') ?>">พนักงานขายตั๋ว</a></li>
                <li><a href="<?= base_url('hr/permission/') ?>" >กำหนดสิทธิ์การเข้าใช้</a></li>
                <li><a href="<?= base_url('hr/loguser/') ?>" >ข้อมูลการเข้าใช้ระบบ</a></li>    
            </ul>
        </div>
    </div>
</div>
<div class="container">
    <br>
    <?php
    echo $form['form'];
    validation_errors();
    echo $form['EID'];
    echo $form['RCode'];
    echo $form['VTID'];
    ?>
    <div class="row">        
        <div class="col-md-12 animated">            
            <div class="widget">
                <div class="widget-header">
                    <i class="fa fa-user"></i>
                    <span>&nbsp;&nbsp;ข้อมูลพนักงาน</span>
                </div>
                <div class="widget-content">
                    <div class="col-md-1">
                        <img class="img-circle"
                             src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=50"
                             alt="User Pic">
                    </div>
                    <div class="col-md-11">
                        <strong><?= $employee['Title'] . $employee['FirstName'] . ' ' . $employee['LastName'] ?></strong><br>
                        <span class="text-muted">ระดับสิทธิ์การใช้งาน : <?= $employee['RoleLevel'] ?></span>
                        <button class="btn btn-primary pull-right" type="button" data-toggle="collapse" data-target="#emp_info" aria-expanded="false" aria-controls="collapseExample">
                            ข้อมูลพนักงาน
                        </button>
                    </div>
                </div>
            </div> 
        </div>       
        <div class="col-md-8 col-md-offset-2 collapse" id="emp_info">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">ข้อมูลพนักงาน</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-3">
                        <img class="img-circle"
                             src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=50"
                             alt="User Pic">
                    </div>
                    <div class="col-md-6">
                        <strong><?= $employee['Title'] . $employee['FirstName'] . ' ' . $employee['LastName'] ?></strong><br>
                        <table class="table table-condensed table-responsive table-user-information">
                            <tbody>
                                <tr>
                                    <td>ระดับสิทธิ์การใช้งาน</td>
                                    <td><?= $employee['RoleLevel'] ?></td>
                                </tr>
                                <tr>
                                    <td>รหัสประชาชน</td>
                                    <td><?= $employee['PersonalID'] ?></td>
                                </tr>
                                <tr>
                                    <td>อายุ</td>
                                    <td><?= $employee['Age'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel-footer">
                    <i class="fa fa-phone"></i>&nbsp;&nbsp;
                    <span class=""><?= $employee['MobilePhone'] ?></span>
                    <span class="pull-right">
                        <?= anchor('hr/employee/detail/' . $employee['EID'], '<i class="fa fa-search"></i>', array('class' => 'btn btn-primary btn-sm')) ?>
                        <?= anchor('hr/employee/edit/' . $employee['EID'], '<i class="fa fa-pencil"></i>', array('class' => 'btn btn-warning btn-sm')) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6" id="list_routes">
            <div class="widget">
                <div class="widget-header">
                    <i class="fa fa-search"></i>
                    <span>&nbsp;&nbsp;เส้นทาง</span>                   
                </div>
                <div class="widget-content">
                    <?php
                    foreach ($vehicle_types as $type) {
                        $vtid = $type['VTID'];
                        $vt_name = $type['VTDescription'];
                        $num_route = 0;
                        foreach ($routes as $r) {
                            if ($r['VTID'] == $vtid) {
                                $num_route++;
                            }
                        }
                        ?>
                        <div class="col-md-12">
                            <p class="lead"><?= $vt_name ?></p>
                            <?php
                            if ($num_route == 0) {
                                ?>
                                <div class="col-md-12">
                                    <div class="well" style="padding-bottom: 50px;padding-top: 50px;">
                                        <p class="lead text-center">ไม่พบข้อมูล</p>
                                    </div>
                                </div>    
                                <?php
                            } else {
                                ?>
                                <div class="list-scholl">
                                    <ul class="list-group">
                                        <?php
                                        foreach ($routes as $r) {
                                            $rcode = $r['RCode'];
                                            $source = $r['RSource'];
                                            $destination = $r['RDestination'];
                                            $schedule_type = $r["ScheduleType"];
                                            $route_name = "เส้นทาง " . $rcode . ' ' . ' ' . $source . ' - ' . $destination;
                                            if ($RCode == $r['RCode'] && $VTID == $r['VTID']) {
                                                $active = 'active';
                                            } else {
                                                $active = '';
                                            }
                                            if ($vtid == $r['VTID']) {
                                                $select_route = array(
                                                    'class' => "list-group-item $active",
                                                );
                                                echo anchor("hr/seller/add/$EID/$rcode/$vtid", $route_name, $select_route);
                                            }
                                        }
                                        ?>

                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                    }
                    ?>                    
                </div>                 
            </div>
        </div>
        <div class="col-md-6 hidden" id="list_stations">
            <div class="widget">
                <div class="widget-header">
                    <i class="fa fa-search"></i>
                    <span>&nbsp;&nbsp;จุดขายตั๋วโดยสาร</span>                   
                </div>
                <div class="widget-content">
                    <?php echo form_error('SID', '<font color="error">', '</font>'); ?>
                    <div class="col-md-12 <?= (form_error('SID')) ? 'has-error' : '' ?>">
                        <fieldset class="radiogroup">    
                            <?php
                            foreach ($stations as $station) {
                                $SID = $station['SID'];
                                $StationName = $station['StationName'];
                                ?>
                                <ul class="radio">
                                    <li>
                                        <input type="radio" name="SID" id="SID<?= $SID ?>" value="<?= $SID ?>" />
                                        <label for="SID<?= $SID ?>">
                                            <?= $StationName ?>
                                        </label
                                    </li>   
                                </ul>
                            <?php } ?>
                        </fieldset> 
                    </div>
                    <div class="form-group">                        
                        <label for="">หมายเหตุ</label>
                        <?php echo $form['SellerNote'] ?>
                    </div>
                    <div class="col-md-12 text-center" style="margin-top: 5%;">                   
                        <?php
                        $cancle = array(
                            'type' => "button",
                            'class' => "btn btn-lg btn-danger",
                        );
                        $save = array(
                            'type' => "submit",
                            'class' => "btn btn-lg btn-success",
                            'value' => '',
                            'content' => '<span class="fa fa-save">&nbsp;&nbsp;บันทึก</span>'
                        );
                        echo anchor('hr/seller', '<i class="fa fa-times" ></i>&nbsp;ยกเลิก', $cancle) . '  ';
                        echo form_button($save);
                        ?> 
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php echo form_close(); ?>     
</div>
