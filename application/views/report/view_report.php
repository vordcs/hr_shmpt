<script>
    $(function () {
        $("#btnExport").click(function () {
            $("#table2excel").table2excel({
                exclude: ".noExl",
                name: "Excel Document Name"
            });
        });
    });
</script>
<style>
    .container {
        width: 100%;
    }
    body {
        padding: 0 !important;
    }
    .table-container {
        visibility: hidden;
    }
    .table-container.show-table {
        visibility: visible;
    }
    .line2 {
        font-size: 0.8em;
        font-weight: normal;
    }
    .dataTables_wrapper {
        border: 1px solid #dddddd;
    }
    .dataTables_wrapper td.highlighted {
        background: #d9edf7;
    }
    .dataTables_wrapper th.highlighted,
    .dataTables_wrapper .DTFC_LeftBodyWrapper td.highlighted {
        background: #ccc;
    }
    .dataTables_wrapper th,
    .dataTables_wrapper td {
        border-radius: 0px !important;
    }
    .dataTables_wrapper table {
        margin-bottom: 0;
        border-radius: 0px !important;
    }
    .dataTables_wrapper .DTFC_LeftWrapper td,
    .dataTables_wrapper .DTFC_LeftWrapper th {
        text-align: right;
    }
    .dataTables_wrapper .DTFC_LeftBodyWrapper {
        border-right: 1px solid #dddddd;
        box-shadow: 4px 0px 6px -2px #aaaaaa;
        z-index: 100;
    }
    .dataTables_wrapper .dataTables_scrollHead {
        box-shadow: 0px 4px 6px -2px #aaaaaa;
        z-index: 100;
    }
    .dataTables_wrapper .dataTables_scrollHead thead th,
    .dataTables_wrapper .dataTables_scrollBody td {
        text-align: center;
    }
    .dataTables_wrapper .DTFC_LeftBodyWrapper td,
    .dataTables_wrapper .dataTables_scrollHeadInner th {
        cursor: pointer;
    }
    .dataTables_wrapper .DTFC_LeftBodyWrapper td:hover,
    .dataTables_wrapper .dataTables_scrollHeadInner th:hover {
        background: #ccc;
    }
    .dataTables_wrapper .dataTables_scrollBody > * {
        -webkit-transform: translateZ(0px);
    }
</style>

<div class="container" style="margin-top: 10px;">
    <div class="row">     
        <div class="col-md-12">
            <?php
            $cancle = array(
                'class' => 'btn btn-danger pull-right',
            );
            echo anchor('report/', '<i class="fa fa-times"></i>', $cancle);
            ?>
            <legend class="text-center">
                รายงานประจำเดือน <strong><?= $page_title ?></strong>
                <br> 
                เส้นทาง <strong><?= $page_title_small ?></strong>                            
            </legend>
            <button id="btnExport">Export</button>
        </div>        
    </div>
</div>
<?php
$num_vehicle = count($vehicles);
$num_sale_station = count($stations_sale_ticket);
$num_income = 0;
$num_outcome = 0;

foreach ($cost_types as $ct) {
    $ctid = $ct['CostTypeID'];
    foreach ($cost_detail as $cd) {
        $detail = $cd['CostDetail'];
        if ($ctid == $cd['CostTypeID'] && $ctid == '1') {
            //รายรับ
            $num_income++;
        }
        if ($ctid == $cd['CostTypeID'] && $ctid != '1') {
            //รายจ่าย
            $num_outcome++;
        }
    }
}
?>
<div class="container avails-grid">
    <p class="loading alert">Loading table...</p>
    <div class="table-container">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-condensed table-bordered table-hover" id="table2excel">
            <thead>            
                <tr>
                    <th rowspan="2" style="width: 100px">เบอร์รถ</th>                
                    <?php
                    $n = 0;
                    foreach ($vehicles as $vehicle) {
                        $bg_class = '';
                        if ($n % 2 == 0) {
                            $bg_class = 'active';
                        }
                        $n++;

                        $vcode = $vehicle['VCode'];
                        $width = 100 / $num_vehicle;
                        $col_span = $num_sale_station + $num_income + $num_outcome + 1;
                        echo "<th class=\"$bg_class\" colspan=\"$col_span\" style=\"width: $width%\">$vcode</th>";
                    }
                    ?>
                </tr>
                <tr> 

                    <?php
                    $n = 0;
                    foreach ($vehicles as $vehicle) {
                        $width = 80 / 2;
                        $bg_class = '';
                        if ($n % 2 == 0) {
                            $bg_class = 'active';
                        }
                        $n++;
                        ?>
                        <th class="<?= $bg_class ?>" style="width: <?= $width ?>%" colspan="<?= $num_sale_station + $num_income ?>">รายรับ</th>
                        <th class="<?= $bg_class ?>" style="width: <?= $width ?>%" colspan="<?= $num_outcome ?>">รายจ่าย</th>
                        <th class="<?= $bg_class ?>" style="width: 500px" rowspan="2">คงเหลือ</th>  
                        <?php
                    }
                    ?>
                </tr> 
                <tr>                    
                    <th>วันที่</th>                                 
                    <?php
                    $n = 0;
                    foreach ($vehicles as $vehicle) {
                        $bg_class = '';
                        if ($n % 2 == 0) {
                            $bg_class = 'active';
                        }
                        $n++;
                        /*
                         * รายรับ
                         */
                        foreach ($stations_sale_ticket as $sale_ticket) {
                            $StationName = $sale_ticket['StationName'];
                            echo "<th class=\"$bg_class\">";
                            echo "$StationName";
                            echo "</th>";
                        }
                        foreach ($cost_types as $ct) {
                            $ctid = $ct['CostTypeID'];
                            foreach ($cost_detail as $cd) {
                                $detail = $cd['CostDetail'];
                                if ($ctid == $cd['CostTypeID'] && $ctid == '1') {

                                    echo "<th class=\"$bg_class\" >$detail</th>";
                                }
                            }
                        }
                        /*
                         * รายจ่าย
                         */
                        foreach ($cost_types as $ct) {
                            $ctid = $ct['CostTypeID'];
                            foreach ($cost_detail as $cd) {
                                $detail = $cd['CostDetail'];
                                if ($ctid == $cd['CostTypeID'] && $ctid != '1') {
                                    echo " <th class=\"$bg_class\" style=\"width: 50px\">$detail</th> ";
                                }
                            }
                        }
                    }
                    ?>                    
                </tr>
            </thead>
            <tbody>
                <?php
                for ($day = 1; $day <= (int) $number_day; $day++) {
                    $year_th = $year + 543;
                    $str_date = "$day/$mounth/$year_th";

                    $date = NULL;
                    $d = new DateTime("$year-$mounth-$day");
                    $date .= ($d->format('Y')) . '-';
                    $date .= ($d->format('m')) . '-';
                    $date .= $d->format('d');
                    ?>
                    <tr>
                        <td><?= $str_date ?></td>                                             
                        <?php
                        $n = 0;
                        $income = 0;
                        $outcome = 0;
                        foreach ($vehicles as $vehicle) {
                            $vid = $vehicle['VID'];

                            $bg_class = '';
                            if ($n % 2 == 0) {
                                $bg_class = 'active';
                            }
                            $n++;
                            /*
                             * รายรับแต่ละสถานี เเละ รายรับที่ได้มาจากตารางข้อมูลค่าใช้จ่าย
                             * รถแต่ละคัน ในแต่่ละวัน 
                             */
//                            จุดขายคั๋ว
                            foreach ($stations_sale_ticket as $sale_ticket) {
                                $SID = $sale_ticket['SID'];
                                echo "<td class=\"$bg_class\">&nbsp;</td>";
                            }
//                            รายรับรายทางหรืออื่น
                            $ctid = '1';
                            foreach ($cost_detail as $cd) {
                                $cdid = $cd['CostDetailID'];
                                if ($ctid == $cd['CostTypeID']) {
                                    $value = 0;
                                    foreach ($cost as $c) {
                                        if ($ctid == $c['CostTypeID'] && $cdid == $c['CostDetailID'] && $vid == $c['VID'] && $date == $c['CostDate']) {
                                            $temp = $c['CostValue'];
                                            $value += $temp;
                                            $income += $temp;
                                        }
                                    }
                                    ?>
                                    <td class="text-center <?= $bg_class ?>"><?= $value != 0 ? $value : '&nbsp;' ?></td>                                                       
                                    <?php
                                }
                            }
                            /*
                             * รายจ่าย
                             */
                            $ctid = '2';
                            foreach ($cost_detail as $cd) {
                                $cdid = $cd['CostDetailID'];
                                if ($ctid == $cd['CostTypeID']) {
                                    $value = 0;
                                    foreach ($cost as $c) {
                                        if ($ctid == $c['CostTypeID'] && $cdid == $c['CostDetailID'] && $vid == $c['VID'] && $date == $c['CostDate']) {
                                            $temp = $c['CostValue'];
                                            $value += $temp;
                                            $outcome+=$temp;
                                        }
                                    }
                                    ?>
                                    <td class="text-center <?= $bg_class ?>"><?= $value != 0 ? $value : '' ?></td>                                                       
                                    <?php
                                }
                            }
                            ?>
                            <!--คงเหลือ-->
                            <td class="text-center <?= $bg_class ?>"> <?php echo number_format(($income - $outcome), 1, '.', ''); ?></td>

                            <?php
                            $income = 0;
                            $outcome = 0;
                        }
                        ?>

                    </tr>
                    <?php
                }
                ?>                
            </tbody>           
        </table>        
    </div>         
</div>


