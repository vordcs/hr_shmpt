<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");
    });
</script>

<div class="container">
    <br>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="">
                        <h3>
                            <?php echo $page_title; ?>
                            <small></small>
                        </h3>
                    </div>                    
                    <?php echo $form_route['form_route'] ?>
                    <p>Feel free to contact us for any issues you might have with our products.</p>
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>Oh snap!</strong> Change a few things up and try submitting again.
                    </div>
                    <div class="form-group <?= (form_error('RID')) ? 'has-error' : '' ?>">
                        <label class="col-sm-3 control-label">รหัสเส้นทาง</label>
                        <div class="col-sm-6">
                            <?php echo $form_route['RID']; ?>
                        </div>
                    </div>
                    <div class="form-group <?= (form_error('RSource')) ? 'has-error' : '' ?>">
                        <label class="col-sm-3 control-label">ต้นทาง</label>
                        <div class="col-sm-8">
                            <?php echo $form_route['RSource']; ?>
                        </div>
                    </div>
                    <div class="form-group <?= (form_error('RDestination')) ? 'has-error' : '' ?>">
                        <label class="col-sm-3 control-label">ปลายทาง</label>
                        <div class="col-sm-8">
                            <?php echo $form_route['RDestination']; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10 text-center">
                            <?php
                            $cancle = array(
                                'type' => "button",
                                'class' => "btn btn-danger",
                            );
                            ?>
                            <?php echo anchor('route/', '<i class="fa fa-times" ></i>&nbsp;ยกเลิก', $cancle); ?>
                            <button type="submit" class="btn btn-success  " id="btn_save" ><i class="fa fa-save"></i>&nbsp;บันทึกเส้นทาง</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>

            </div>

        </div>
    </div>
</div>

