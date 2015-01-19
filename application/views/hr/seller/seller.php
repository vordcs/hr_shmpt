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
                            <th colspan="2" style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($sort as $emp) {
                            $flag_tr = FALSE;
                            $EID = $emp['EID'];
                            $name = $emp['Title'] . $emp['FirstName'] . ' ' . $emp['LastName'];
                            $num_sell_1 = count($emp['sell_1']);
                            $num_sell_2 = count($emp['sell_2']);
                            $row_spam = $num_sell_1 + $num_sell_2 + 1;

                            if ($flag_tr == FALSE) {
                                echo '<tr>';
                                $flag_tr = TRUE;
                            }

                            echo '<td rowspan="' . $row_spam . '" class="text-center">' . $EID . '</td>';
                            echo '<td rowspan="' . $row_spam . '" colspan="">' . $name . '</td>';

                            foreach ($vehicle_types as $type) {
                                $vtid = $type['VTID'];
                                $vt_name = $type['VTDescription'];

                                $route_name = '-';
                                $station_name = '-';
                                $add = array(
                                    'class' => "btn btn-success btn-sm",
                                );
                                $col_span = 0;
                                $action_add = anchor("hr/seller/add/$EID/NULL/$vtid", '<i class="fa fa-plus"></i>', $add);
                                $action_delete = '';

                                if ($vtid == '1') {
                                    $flag_first = TRUE;
                                    for ($i = 0; $i < $num_sell_1; $i++) {
                                        $seller = (isset($emp['sell_1'][$i])) ? $emp['sell_1'][$i] : NULL;
                                        $row_v = count($emp['sell_1']);
                                        if ($seller['EID'] != NULL && $seller['EID'] == $EID) {
                                            if ($flag_tr == FALSE && $flag_first == FALSE) {
                                                echo '<tr>';
                                                $flag_tr = TRUE;
                                            }
                                            if ($flag_first) {
                                                echo '<tr>';
                                                echo '<td rowspan="' . $row_v . '" class="text-center">' . $vt_name . '</td>';
                                                $flag_tr = TRUE;
                                            }
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
                                            $action_delete = anchor('#', '<i class="fa fa-times"></i>', $delete);

                                            echo '<td class="text-center">' . $route_name . '</td>';
                                            echo '<td class="text-center">' . $station_name . '</td>';
                                            echo '<td class="text-center">' . $action_delete . '</td>';
                                            if ($flag_first) {
                                                echo '<td rowspan="' . $row_v . '" class="text-center">' . $action_add . '</td>';
                                                $flag_first = FALSE;
                                            }
                                            if ($flag_tr) {
                                                echo '</tr>';
                                                $flag_tr = FALSE;
                                            }
                                        } else {
                                            echo '<tr>';
                                            echo '<td class="text-center">' . $vt_name . '</td>';
                                            echo '<td class="text-center"> - </td>';
                                            echo '<td class="text-center"> - </td>';
                                            echo '<td class="text-center"> - </td>';
                                            echo '<td class="text-center">' . $action_add . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                } else if ($vtid == '2') {
                                    $flag_first = TRUE;
                                    for ($i = 0; $i < $num_sell_2; $i++) {
                                        $seller = (isset($emp['sell_2'][$i])) ? $emp['sell_2'][$i] : NULL;
                                        $row_v = count($emp['sell_2']);
                                        if ($seller['EID'] != NULL && $seller['EID'] == $EID) {
                                            if ($flag_tr == FALSE && $flag_first == FALSE) {
                                                echo '<tr>';
                                                $flag_tr = TRUE;
                                            }
                                            if ($flag_first) {
                                                echo '<tr>';
                                                echo '<td rowspan="' . $row_v . '" class="text-center">' . $vt_name . '</td>';
                                                $flag_tr = TRUE;
                                            }
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
                                            $action_delete = anchor('#', '<i class="fa fa-times"></i>', $delete);

                                            echo '<td class="text-center">' . $route_name . '</td>';
                                            echo '<td class="text-center">' . $station_name . '</td>';
                                            echo '<td class="text-center">' . $action_delete . '</td>';
                                            if ($flag_first) {
                                                echo '<td rowspan="' . $row_v . '" class="text-center">' . $action_add . '</td>';
                                                $flag_first = FALSE;
                                            }
                                            if ($flag_tr) {
                                                echo '</tr>';
                                                $flag_tr = FALSE;
                                            }
                                        } else {
                                            echo '<tr>';
                                            echo '<td class="text-center">' . $vt_name . '</td>';
                                            echo '<td class="text-center"> - </td>';
                                            echo '<td class="text-center"> - </td>';
                                            echo '<td class="text-center"> - </td>';
                                            echo '<td class="text-center">' . $action_add . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                }
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
