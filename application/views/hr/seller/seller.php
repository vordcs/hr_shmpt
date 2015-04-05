<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnHR").addClass("active");

        $("select[name='RCode']").change(function () {
            $('#form_search_seller').submit();
        });
    });
</script>

<div class="container" style="margin-bottom: 20px;">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h2>ระบบบริหารงานบุคคล <small style="color: #777777;">พนักงานขายตั๋ว</small></h2>
            </div>
        </div>
    </div>
    <div class="row">   
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
<div class="container" style="padding-top: 3% ;"> 
    <?= $form['form'] ?>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="col-md-4">           
                <?=$form['VTID']?>
            </div>
            <div class="col-md-6">   
                <?=$form['RCode']?>
            </div>
            <div class="col-md-2">                
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i>&nbsp;แสดงผล</button>
            </div>   
        </div>
    </div>
    <?= form_close() ?>
</div>
<div class="container">
    <div class="row">
        <p class="lead">
            ข้อมูลพนักงานขายตั๋ว
        </p>
        <?php
        foreach ($data_seller as $type) {
            $VTID = $type['VTID'];
            ?>
            <div class="col-md-12">  
                <legend class="lead"><?= $type['VTName'] ?></legend>
            </div>
            <?php
            foreach ($type['routes'] as $route) {
                $RCode = $route['RCode'];
                $add = array(
                    'class' => "btn btn-success pull-right",
                );
                $RouteName = $route['RouteName'];
                ?>
                <div class="col-md-12">
                    <div class="widget">
                        <div class="widget-header">
                            <?= $RouteName ?>
                        </div>
                        <div class="widget-content">   
                            <div class="col-md-12" style="padding-bottom: 1%;">
                                <?php
                                echo anchor("hr/seller/add/$RCode/$VTID", '<i class="fa fa-plus"></i>&nbsp;พนักงานขายตั๋ว', $add);
                                ?>
                            </div>
                            <?php
                            $num_station = count($route['stations']);
                            if ($num_station == 1) {
                                $num_col = "6 col-md-offset-3";
                            } elseif ($num_station == 2) {
                                $num_col = "6";
                            } else {
                                $num_col = "4";
                            }
                            foreach ($route['stations'] as $station) {
                                $StationName = $station['StationName'];
                                ?>
                                <div class="col-md-<?= $num_col ?>">
                                    <div class="panel panel-default">
                                        <!-- Default panel contents -->
                                        <div class="panel-heading"><?= $station['StationName'] ?></div> 
                                        <!-- Table -->
                                        <table class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width: 30%">รหัสพนักงาน</th>
                                                    <th style="width: 55%">ชื่อ-นามสกุล</th>
                                                    <th style="width: 15%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($station['sellers'] as $seller) {
                                                    $SellerID = $seller['SellerID'];
                                                    $EID = $seller['EID'];
                                                    $Title = $seller['Title'];
                                                    $FirstName = $seller['FirstName'];
                                                    $LastName = $seller['LastName'];
                                                    $SellerNote = $seller['SellerNote'];
                                                    $FullName = "$Title$FirstName $LastName";
                                                    if ($SellerNote != NULL) {
                                                        $FullName .=" ($SellerNote)";
                                                    }

                                                    $delete = array(
                                                        'class' => "btn btn-danger btn-sm",
                                                        'type' => "button",
                                                        'data-id' => "2",
                                                        'data-title' => "คุณต้องการ ลบ พนักงานขายตั๋ว ",
                                                        'data-sub_title' => "$FullName",
                                                        'data-info' => "$StationName",
                                                        'data-content' => "$RouteName",
                                                        'data-toggle' => "modal",
                                                        'data-target' => "#confirm",
                                                        'data-href' => "seller/delete/$SellerID",
                                                    );
                                                    $action = anchor('#', '<i class="fa fa-trash-o"></i>', $delete);
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?= $EID ?></td>
                                                        <td class="text-left text"><?= $FullName ?></td>
                                                        <td class="text-center"><?= $action ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

