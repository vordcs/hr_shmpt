<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnCost").addClass("active");

        $(".th-footer-bottom").addClass("hidden");

        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'yyyy-m-d'
        });
    });

</script>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="page-header">
                <h3><?= $page_title ?></h3>
            </div>
        </div>
        <div class="col-md-6 col-md-offset-3">            
            <?php echo $form['form']; ?>    
            <div class="form-group <?= (form_error('CostDate')) ? 'has-error' : '' ?>">
                <label for="" class="col-sm-2 control-label">วันที่</label>
                <div class="col-sm-4">
                    <?php echo $form['CostDate']; ?>
                </div>
                <div class="col-sm-4">
                    <?php echo form_error('CostDate', '<font color="error">', '</font>'); ?>
                </div>
            </div>
            <div class="form-group <?= (form_error('CostDetailID')) ? 'has-error' : '' ?>">
                <label for="" class="col-sm-2 control-label">รายการ</label>
                <div class="col-sm-6">
                    <?php echo $form['CostDetailID']; ?>
                </div>
                <div class="col-sm-4">
                    <?php echo form_error('CostDetailID', '<font color="error">', '</font>'); ?>
                </div>
            </div>
            <div class="form-group hidden <?= (form_error('VTID')) ? 'has-error' : '' ?>">
                <label for="" class="col-sm-2 control-label">ประเภทรถ</label>
                <div class="col-sm-5">
                    <?php echo $form['VTID']; ?>
                </div>
                <div class="col-sm-3">
                    <?php echo form_error('VTID', '<font color="error">', '</font>'); ?>
                </div>

            </div>
            <div class="form-group <?= (form_error('VCode')) ? 'has-error' : '' ?>">  
                <label for="" class="col-sm-2 control-label">เบอร์รถ</label>
                <div class="col-sm-3">
                    <?php echo $form['VCode']; ?>
                </div> 
                <div class="col-sm-4">
                    <?php echo form_error('VCode', '<font color="error">', '</font>'); ?>
                </div>
            </div>
            <div class="form-group <?= (form_error('CostValue')) ? 'has-error' : '' ?>">
                <label for="" class="col-sm-2 control-label">จำนวน</label>
                <div class="col-sm-4">
                    <?php echo $form['CostValue']; ?>
                </div>
                <div class="col-sm-1">
                    <span class="text-left">บาท</span>                            
                </div>
                <div class="col-sm-4">
                    <?php echo form_error('CostValue', '<font color="error">', '</font>'); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">หมายเหตุ</label>
                <div class="col-sm-8">
                    <?php echo $form['CostNote']; ?>
                </div>
            </div>
            <br>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 ">
                    <?php
                    $cancle = array(
                        'type' => "button",
                        'class' => "btn btn-danger",
                    );
                    ?>
                    <?php echo anchor('cost/', '<i class="fa fa-times" ></i>&nbsp;ยกเลิก', $cancle); ?>                                        
                    <button type="submit" class="btn btn-success"><span class="fa fa-save">&nbsp;&nbsp;บันทึก</span></button>
                </div> 
            </div>
            <?php echo form_close(); ?>     
        </div>
    </div>  
</div>


