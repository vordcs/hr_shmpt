<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");

    });
</script>

<?php
?>

<div class="container">
    <br>
    <div class="row">        
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
    <div class="row">  
        <div class="col-md-12">
            <?php
//            $temp_stations = $stations;
//            $number_station_ = count($temp_stations) - 1;
//            for ($i = 0; $i < $number_station_; $i++) {
//                $source = array_shift($temp_stations);
//                echo "<strong>" . $source['StationName'] . "</strong>";
//                echo '<br>';
//                foreach ($temp_stations as $s) {
//                    echo $s['StationName'];
//                    echo '<br>';
//                }
//                echo '-------------------------------';
//                echo '<br>';
//            }
            ?>            
        </div>
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">อัตราค่าโดยสาร</h3>
                </div>
                <div class="panel-body">
                    <?php
                    if ($vtid == 1) {
                        $type_price = array();
                        $rowspan = 3;
                        $colspan = 2;
                    } else {
                        $type_price = array('รถแอร์', 'รถพัดลม');
                        $rowspan = 4;
                        $colspan = 4;
                    }
//                    $type_price = array('รถแอร์', 'รถพัดลม');
//                    $colspan = 4;
//                    $rowspan = 4;
                    ?>
                    <table id="tableStation" class="table table-hover table-bordered">
                        <thead>
                            <tr>                                    
                                <th rowspan="<?= $rowspan ?>" class="text-center" style="width:30%;">ต้นทาง</th>
                                <th rowspan="<?= $rowspan ?>" class="text-center" style="width:30%;">ปลายทาง</th>
                                <th colspan="<?= $colspan ?>" class="text-center" style="width:30%;">ค่าโดยสาร</th>

                            </tr>
                            <?php
                            if (count($type_price) > 0) {
                                echo '<tr>';
                                foreach ($type_price as $value) {
                                    ?>
                                <th colspan="2" class="text-center" style="width:30%;"><?= $value ?></th>       
                                <?php
                            }
                            echo '</tr>';
                        }
                        ?>
                        <tr> 
                            <?php
                            $n = 1;
                            if (count($type_price) > 0) {
                                $n = count($type_price);
                            }
                            for ($i = 1; $i <= $n; $i++) {
                                ?>
                                <th class="text-center" style="width:10%;">เต็ม</th>
                                <th class="text-center" style="width:10%;">ลด</th>   
                                <?php
                            }
                            ?>
                        </tr>
                        </thead>
                        <tbody id="tableBodyStation">

                            <?php
                            $number_station = count($stations) - 1;
                            for ($i = 0; $i < $number_station; $i++) {
                                $s = array_shift($stations);
                                $source = $s['StationName'];
                                $rowspan = count($stations) + 1;
                                ?>
                                <tr>
                                    <td rowspan="<?= $rowspan ?>" class="text-center">
                                        <span class="lead">
                                            <?= $source ?>
                                        </span>
                                        
                                    </td>
                                </tr>
                                <?php
                                foreach ($stations as $s) {
                                    $destination = $s['StationName'];
                                    ?>  
                                    <tr>                                      
                                        <td>
                                            <label><?= $destination ?></label>                                            
                                        </td>
                                        <?php for ($j = 0; $j < $colspan; $j++) { ?>
                                            <td>
                                                <input type="text" class="form-control"> 
                                            </td> 
                                        <?php } ?>
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
        <div class="col-md-12 text-center">            
            <a  href="javascript:window.history.go(-1);" class="btn btn-link pull-left" ><span class="fa fa-backward"> เพิ่มจุดจอด</span></a>
            <a  href="javascript:window.history.go(-2);" class="btn btn-danger btn-lg" ><span class="fa fa-times">ยกเลิก</span></a>
            <button type="submit" class="btn btn-success  btn-lg" id="btn_save" ><i class="fa fa-save"></i>&nbsp;บันทึก</button>   
        </div>
    </div>
    <br>
</div>

