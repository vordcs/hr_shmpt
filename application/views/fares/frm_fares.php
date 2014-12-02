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
        <?php echo $form['form'] ?>
        <div class="col-md-12">
            <?php //echo validation_errors(); ?>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">อัตราค่าโดยสาร</h3>
                </div>
                <div class="panel-body">
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
        </div>
        <div class="col-md-12 text-center">
            <a  href="javascript:window.history.go(-1);" class="btn btn-link pull-left" ><span class="fa fa-backward"> เพิ่มจุดจอด</span></a>

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
<?php echo form_close(); ?>
    </div>
    <br>
</div>

