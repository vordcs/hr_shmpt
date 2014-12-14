<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");

    });
</script>

<?php
?>

<div class="container-fluid">    
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
    <?php if (count($stations) <= 0) {
        ?>
        <div class="row" >
            <div class="col-md-12">                
                <a  href="javascript:window.history.go(-1);" class="btn btn-danger pull-right" ><span class="fa fa-times"> กลับ</span></a>
            </div>
            <div class="col-md-12 text-center">    
                <div style="min-height: 400px;padding-bottom: 100px;padding-top: 200px;">
                    <?php
                    $add_staion = array(
                        'type' => "button",
                        'class' => "btn btn-lg btn-info ",
                        'data-toggle' => "tooltip",
                        'data-placement' => "top",
                        'title' => $page_title . '  ' . $page_title_small,
                    );
                    echo anchor('station/add/' . $rcode . '/' . $vtid, '<i class="fa fa-bus" ></i>  จุดจอดและอัตราค่าโดยสาร', $add_staion);
                    ?>             
                    
                </div>          
            </div>
        </div>
    <?php } else { ?>
        <div class="row">    
            <div class="col-md-12 text-right">   

                <?php
                $edit_staion = array(
                    'type' => "button",
                    'class' => "btn btn-lg btn-success ",
                    'data-toggle' => "tooltip",
                    'data-placement' => "top",
                    'title' => $page_title . '  ' . $page_title_small,
                );
                echo anchor('station/edit/' . $rcode . '/' . $vtid, '<span class="fa fa-stop">  จุดจอดและอัตราค่าโดยสาร </span>', $edit_staion);
                ?>                 
                <a class="btn btn-success" ><span class="fa fa-money">  กำหนดค่าโดยสาร</span></a>
                <a href="" class="btn btn-danger pull-right" ><span class="fa fa-times"> กลับ</span></a>
                <a  href="javascript:window.history.go(-1);" class="btn btn-danger pull-right" ><span class="fa fa-times"> กลับ</span></a>
            </div> 
        </div>
        <br>
        <div class="row">  
            <div class="col-md-9">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">จุดจอดและค่าโดยสาร</h3>
                    </div>
                    <div class="panel-body">
                        <?php
                        if ($vtid == 1) {
                            $rowspan = 3;
                            $colspan = 2;
                        } else {
                            $rowspan = 4;
                            $colspan = 4;
                        }

                        $type_price = array('รถแอร์', 'รถพัดลม');

                        $colspan = 4;
                        $rowspan = 4;
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
                                <tr>                                   
                                    <td>
                                        <label>1234567899101121245</label>
                                        <!--<input type="text" class="form-control">--> 
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"> 
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"> 
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"> 
                                    </td>  
                                    <td class="text-center">
                                        <span class="text">150</span>
                                    </td>  
                                    <td class="text-center">
                                        <span class="text">152</span>
                                    </td>  
                                </tr>
                            </tbody>                     
                        </table>                      
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">จุดขายตั๋ว</h3>
                    </div>
                    <div class="panel-body">
                        <div class="checkbox">
                            <input type="checkbox"> 12444121
                        </div>                 
                    </div>
                </div>
            </div>
        </div>

    <?php }
    ?>


    <div class="row hidden" >
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">จุดขายตั๋ว</h3>
                </div>
                <div class="panel-body">
                    <div class="checkbox">
                        <input type="checkbox"> 12444121
                    </div>                 
                </div>
            </div>
        </div>
    </div>

    <br>
</div>

