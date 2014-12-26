<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnHR").addClass("active");
        
        $("select[name='RCode']").change(function () {
            $('#form_search_seller').submit();
        });
    });
</script>

<div class="container">
    <div class="row">      
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
    <div class="row">   
        <div class="col-md-12">
            <ul class="nav nav-pills nav-justified">
                <li><a href="<?= base_url('hr/') ?>">หน้าหลัก</a></li>
                <li><a href="<?= base_url('employee/') ?>">พนักงาน</a></li>
                <li class="active"><a href="<?= base_url('hr/seller') ?>">พนักงานขายตั๋ว</a></li>
                <li><a href="<?= base_url('hr/master_data/') ?>">ข้อมูลหลัก</a></li>
                <li><a href="<?= base_url('hr/work_history/') ?>" >ประวัติการทำงาน</a></li>
                <li><a href="<?= base_url('hr/role_permission/') ?>" >กำหนดสิทธิ์การเข้าใช้</a></li>
                <li><a href="<?= base_url('hr/username/') ?>" >ข้อมูลการเข้าใช้ระบบ</a></li>    
            </ul>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row"> 
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-search">&nbsp;ค้นหา</i></h3>
            </div>
            <div class="panel-body">
                <?= $form['form'] ?>
                <div class="col-md-6 col-md-offset-3">
                    <!--                    <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">ประเภทรถ</label>
                    <?php $form['VTID'] ?>
                                            </div>                      
                                        </div>-->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">เส้นทาง</label>
                            <?= $form['RCode'] ?>
                        </div>                      
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i>&nbsp;&nbsp;ค้นหา</button>
                    </div>

                </div>
                <?= form_close() ?>
            </div>            
        </div>
    </div>   
    <div class="row"> 
        <?php
        if (count($sellers) <= 0) {
            echo '<div class="col-md-12">
                    <div class="well" style="padding-bottom: 100px;padding-top: 100px;">
                        <p class="lead text-center">ไม่พบข้อมูล</p>
                    </div>
                </div>';
        } else {
            ?>
            <div class="col-md-12">
                <h4>ข้อมูลพนักงานขายตั๋ว</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>

                            <th style="width: 15%">รหัสพนักงาน</th>
                            <th style="width: 25%">ชื่อ-นามสกุล</th> 
                            <th style="width: 10%">ประเภทรถ</th>
                            <th style="width: 30%">เส้นทาง</th>
                            <th style="width: 20%">จุดจอด</th>
                            <th style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($epmployees_seller as $emp) {
                            $EID = $emp['EID'];
                            $name = $emp['FirstName'];
                            $row_spam = 3;
                            $num_sale = 0;
                            foreach ($sellers as $s) {
                                if ($EID == $s['EID']) {
                                    $num_sale++;
//                                $row_spam++;
                                }
                            }
                            ?>
                            <tr>                           
                                <td rowspan="<?= $row_spam ?>" class="text-center"><?= $EID ?></td>                            
                                <td rowspan="<?= $row_spam ?>" colspan=""><?= $name ?></td> 
                            </tr>
                            <?php
                            foreach ($vehicle_types as $type) {
                                $vtid = $type['VTID'];
                                $vt_name = $type['VTDescription'];

                                $route_name = '-';
                                $station_name = '-';
                                $add = array(
                                    'class' => "btn btn-success btn-sm",
                                );
                                $col_span = 0;
                                $action = anchor("hr/seller/add/$EID/NULL/$vtid", '<i class="fa fa-plus"></i>', $add);

                                foreach ($sellers as $seller) {
                                    if ($EID == $seller['EID'] && $vtid == $seller['VTID']) {
                                        $seller_id = $seller['SellerID'];
                                        $rcode = $seller['RCode'];
                                        $source = $seller['RSource'];
                                        $destination = $seller['RDestination'];
                                        $route_name = "$rcode  $source - $destination";
                                        $station_name = $seller['StationName'];
                                        $col_span = 0;
                                        $delete = array(
                                            'type' => "button",
                                            'class' => "btn  btn-danger btn-sm",
                                            'data-id' => "2",
                                            'data-title' => "ลบพนักงานขายตั๋วโดยสาร",
                                            'data-sub_title' => "$name  ",
                                            'data-info' => " จุดจอด $station_name",
                                            'data-toggle' => "modal",
                                            'data-target' => "#confirm",
                                            'data-href' => "seller/delete/$seller_id",
                                        );
                                        $action = anchor('#', '<i class="fa fa-times"></i>', $delete);
                                    }
                                }
                                ?>
                                <tr>
                                    <td class="text-center"><?= $vt_name ?></td>  
                                    <td class="text-center"><?= $route_name ?></td>
                                    <td class="text-center"><?= $station_name ?></td>
                                    <td class="text-center"><?= $action ?></td>
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
        ?>

    </div>

</div>
