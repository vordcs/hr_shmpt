<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");

        $(".th-footer-bottom").addClass("hidden");
    });
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h3><?php echo $page_title; ?></h3>
            </div>
        </div>
    </div>
       <?php if (validation_errors() != NULL) { ?>
        <div class="row animated bounceInDown">
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4>ผิดพลาด ! <small>กรุณากรุณากรอกข้อมูลให้ครบ</small></h4>
            </div>
        </div>
    <?php } ?>
    <div class="row animated bounceInUp "> 
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">ข้อมูลเส้นทาง</h3>
                </div>                
                <div class="panel-body"> 
                    <?php echo $form_route['form'] ?>   
                    <div class="col-md-8 col-md-offset-2">
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
                    </div>
                                
                    <div class="col-md-12 text-center"> 
                        <hr>                     
                        <?php
                        $cancle = array(
                            'type' => "button",
                            'class' => "btn btn-danger btn-lg",
                        );
                        $save = array(
                            'type' => "submit",
                            'class' => "btn btn-success btn-lg",
                            'content' => '<spand class="fa fa-save" >&nbsp;บันทึก</spand>'
                        );

                        $time = array(
                            'type' => "button",
                            'class' => "btn btn-link btn-sm",
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
                                    echo anchor($previous_page, '<span class="fa fa-angle-double-left"></span> กลับ');
                                }
                                ?>                               
                            </li>
                            <li class="next">
                                <?php
                                if ($next_page != '') {
                                    echo anchor($next_page, 'ต่อไป <span class="fa fa-angle-double-right"></span>');
                                }
                                ?>                                 
                            </li>
                        </ul>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>        
    </div>
</div>

