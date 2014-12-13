<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");
        $(".th-footer-bottom").addClass("hidden");
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
    <?php if (validation_errors() != NULL) { ?>
        <div class="row animated pulse">
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>ผิดพลาด ! </strong>
                กรุณากรุณากรอกข้อมูลให้ครบ
            </div>
        </div>
    <?php } ?>
    <div class="row animated fadeIn"> 
        <?php echo $form['form'] ?>
        <div class="col-md-12">
            <?php //echo validation_errors(); ?>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">อัตราค่าโดยสาร</h3>
                </div>                
                <table id="tableStation" class="table table-hover table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th rowspan="2"  class="text-center" style="width:30%;">ต้นทาง</th>
                            <th rowspan="2" class="text-center" style="width:30%;">ปลายทาง</th>
                            <th colspan="2" class="text-center" style="width:30%;">ค่าโดยสาร</th>
                        </tr>
                        <tr>
                            <th class="text-center" style="width:10%;">เต็ม</th>
                            <th class="text-center" style="width:10%;">ลด</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $source = $form['Source'];
                        for ($i = 0; $i < count($source); $i++) {
                            $destination = $form['Destination'][$i];
                            $num_destination = count($destination);
                            ?>
                            <tr>
                                <td rowspan="<?= $num_destination + 1 ?>" class="text-center">
                                    <span class="text">
                                        <?= $source[$i] ?>
                                    </span>                                        
                                </td>
                            </tr>
                            <?php
                            for ($j = 0; $j < $num_destination; $j++) {
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <label><?php echo $destination[$j] ?></label>
                                    </td>   
                                    <?php
                                    for ($k = 0; $k < 2; $k++) {
                                        $input_price = $form['Price'];
                                        ?>
                                        <td class="<?= (form_error("Price[$i][$j][$k]")) ? 'has-error' : '' ?>">
                                            <?= $input_price[$i][$j][$k] ?>
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
        <div class="col-md-12 text-center">   
            <?php
            $cancle = array(
                'type' => "button",
                'class' => "btn btn-danger btn-lg",
            );
            $save = array(
                'id' => "btn_save",
                'name' => "btn_save",
                'type' => "submit",
                'class' => "btn btn-success btn-lg",
                'value' => 'save',
                'content' => '<i class="fa fa-save"></i>&nbsp;บันทึก'
            );
            echo anchor('route/', '<i class="fa fa-times" ></i>&nbsp;ยกเลิก', $cancle) . ' ';
            echo form_button($save);
            ?>
        </div>  
        <div class="col-md-12">   
            <ul class="pager">
                <li class="previous">
                    <?php
                    if ($previous_page != '') {
                        echo anchor($previous_page, '<span class="fa fa-angle-double-left"></span> ข้อมูลจุดจอดรถ');
                    }
                    ?>                               
                </li>
                <li class="next">
                    <?php
                    if ($next_page != '') {
                        echo anchor($next_page, 'ข้าม <span class="fa fa-angle-double-right"></span>');
                    }
                    ?>                                 
                </li>
            </ul>
        </div>
        <?php echo form_close(); ?>
    </div>
    <div class="row hidden">
        <div class="col-md-12">
            <ul class="pager">
                <li class="previous"><a href="#">Previous</a></li>
                <li class="next"><a href="#">Next</a></li>
            </ul>
        </div>
    </div>
    <br>
</div>

