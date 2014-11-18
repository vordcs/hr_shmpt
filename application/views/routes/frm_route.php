<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");

        $(".th-footer-bottom").addClass("hidden");
    });
</script>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="page-header">
                <h3><?php echo $page_title; ?></h3>
            </div>
        </div>
    </div>
    <div class="row">        
        <div class="col-md-12" >
            <div class="panel panel-default">              
                <div class="panel-body">                                     
                    <?php echo $form_route['form'] ?>                                     
                    <div class="form-group <?= (form_error('RCode')) ? 'has-error' : '' ?>">
                        <label class="col-sm-3 control-label">รหัสเส้นทาง</label>
                        <div class="col-sm-3">
                            <?php echo $form_route['RCode']; ?>
                        </div>
                        <div class="col-sm-4">
                            <?php echo form_error('RCode', '<font color="error">', '</font>'); ?>
                        </div>
                    </div>
                    <div class="form-group <?= (form_error('VTID')) ? 'has-error' : '' ?>">
                        <label class="col-sm-3 control-label">ประเภทรถ</label>
                        <div class="col-sm-5">
                            <?php echo $form_route['VTID']; ?>
                        </div>
                        <div class="col-sm-4">
                            <?php echo form_error('VTID', '<font color="error">', '</font>'); ?>
                        </div>
                    </div>
                    <div class="form-group <?= (form_error('RSource')) ? 'has-error' : '' ?>">
                        <label class="col-sm-3 control-label">ต้นทาง</label>
                        <div class="col-sm-5">
                            <?php echo $form_route['RSource']; ?>
                        </div>
                        <div class="col-sm-4">
                            <?php echo form_error('RSource', '<font color="error">', '</font>'); ?>
                        </div>
                    </div>
                    <div class="form-group <?= (form_error('RDestination')) ? 'has-error' : '' ?>">
                        <label class="col-sm-3 control-label">ปลายทาง</label>
                        <div class="col-sm-5">
                            <?php echo $form_route['RDestination']; ?>
                        </div>
                        <div class="col-sm-4">
                            <?php echo form_error('RDestination', '<font color="error">', '</font>'); ?>
                        </div>
                    </div>
                    <div class="form-group <?= (form_error('Time')) ? 'has-error' : '' ?>">
                        <label class="col-sm-3 control-label">เวลาที่ใช้</label>
                        <div class="col-sm-2">
                            <?php echo $form_route['Time']; ?>                            
                        </div>
                        <div class="col-sm-1">
                            นาที
                        </div>
                        <div class="col-sm-3">
                            <?php echo form_error('Time', '<font color="error">', '</font>'); ?>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">                        
                        <div class="col-sm-offset-3 col-sm-10">
                            <?php
                            $cancle = array(
                                'type' => "button",
                                'class' => "btn btn-danger btn-lg",
                            );
                            ?>
                            <?php echo anchor('route/', '<i class="fa fa-times" ></i>&nbsp;ยกเลิก', $cancle); ?>
                            <button type="submit" class="btn btn-success  btn-lg" id="btn_save" ><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

