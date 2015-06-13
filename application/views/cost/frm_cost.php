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
        <div class="col-md-12">
            <div class="page-header">
                <h3><?= $page_title ?> <span class="badge badge-primary">ประจำวันที่ <?= $this->m_datetime->DateThai($date) ?></span></h3>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-info"></i>&nbsp;รายละเอียด</h3>
                </div>
                <div class="panel-body" style="min-height: 114px; padding: 30px;">                
                    <div class="row">
                        <div class="col-md-12">            
                            <?php echo $form_open; ?>    
                            <div class="form-group <?= (form_error('CostDetailID')) ? 'has-error' : '' ?>">
                                <label for="" class="col-sm-2 control-label">รายการ</label>
                                <div class="col-sm-6">
                                    <?php echo $form['CostDetailID']; ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php echo form_error('CostDetailID', '<font color="error">', '</font>'); ?>
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
                                <label for="" class="col-sm-2 control-label">เงินสด/เครดิต</label>
                                <label class="toggle" style="margin-top: 10px;">
                                    <input type="checkbox" checked="" name="IsCash">
                                    <span class="handle"></span>
                                </label>
                                <span class="label label-success">เงินสด สีเขียว , เครคิด สีเทา</span>
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

                            <?php echo $form_close; ?>     
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
