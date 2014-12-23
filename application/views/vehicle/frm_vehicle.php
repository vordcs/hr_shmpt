<style>
    #list-driver {                    
        max-height: 310px;
        overflow-y: scroll;                   
    }
</style>
<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnVehicle").addClass("active");

        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'yyyy-m-d'
        });
    });
</script>
<div class="container">    
    <div class="row animated fadeInDown">
        <div class="col-md-12">
            <div class="page-header">
                <h3>
                    <?php echo $page_title; ?>                   
                    <font color="#777777">
                    <span style="font-size: 23px; line-height: 23.399999618530273px;"><?php echo $page_title_small; ?></span>                
                    </font>
                </h3> 
            </div>           
        </div>     
    </div> 
    <?php if (validation_errors() != NULL) { ?>
        <div class="row animated pulse">
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4>ผิดพลาด ! <small>กรุณากรุณากรอกข้อมูลให้ครบ</small></h4>
            </div>
        </div>
    <?php } ?>
    <?= $form['form'] ?>
    <div class="row">
        <div class="col-md-6 animated  fadeInLeft">
            <div id="panalVehicleDetail" class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">ข้อมูลรถ</h3>
                </div>
                <div class="panel-body" style="min-height: 408px;">                   
                    <div class="col-sm-12">
                        <?php if ($VID != NULL) { ?>
                            <div class="form-group <?= (form_error('RCode')) ? 'has-error' : '' ?>">                                  
                                <label for="" class="col-sm-2 control-label">เส้นทาง</label>
                                <div class="col-sm-7">
                                    <?php echo $form['RCode'] ?>
                                </div>  
                                <div class="col-sm-3">
                                    <?php echo form_error('RCode', '<font color="error">', '</font>'); ?>
                                </div>
                            </div>
                            <div class="form-group <?= (form_error('VTID')) ? 'has-error' : '' ?>">                                  
                                <label for="" class="col-sm-4 control-label">ประเภทรถ</label>
                                <div class="col-sm-4">
                                    <?php echo $form['VTID'] ?>
                                </div>  
                                <div class="col-sm-4">
                                    <?php echo form_error('VTID', '<font color="error">', '</font>'); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group <?= (form_error('NumberPlate')) ? 'has-error' : '' ?>">                                  
                            <label for="" class="col-sm-4 control-label">เลขทะเบียน</label>
                            <div class="col-sm-4">
                                <?php echo $form['NumberPlate'] ?>
                            </div>  
                            <div class="col-sm-4">
                                <?php echo form_error('NumberPlate', '<font color="error">', '</font>'); ?>
                            </div>
                        </div>
                        <div class="form-group <?= (form_error('VCode')) ? 'has-error' : '' ?>">
                            <label for="" class="col-sm-4 control-label">เบอร์รถ</label>
                            <div class="col-sm-4">
                                <?php echo $form['VCode'] ?>
                                <span class="help-block">ตัวอย่าง <?= $RCode ?>-xx</span>
                            </div>
                            <div class="col-sm-4">
                                <?php echo form_error('VCode', '<font color="error">', '</font>'); ?>                                        
                            </div>                                  
                        </div>
                        <div class="form-group <?= (form_error('VColor')) ? 'has-error' : '' ?>">
                            <label for="" class="col-sm-4 control-label">สีรถ</label>
                            <div class="col-sm-4">
                                <?php echo $form['VColor'] ?>
                            </div>
                            <div class="col-sm-4">
                                <?php echo form_error('VColor', '<font color="error">', '</font>'); ?>
                            </div>
                        </div>
                        <div class="form-group <?= (form_error('VBrand')) ? 'has-error' : '' ?>">
                            <label for="" class="col-sm-4 control-label">ยี่ห้อ</label>
                            <div class="col-sm-4">
                                <?php echo $form['VBrand']; ?>                                        
                            </div>
                            <div class="col-sm-4">
                                <?php echo form_error('VBrand', '<font color="error">', '</font>'); ?>
                            </div>
                        </div>
                        <div class="form-group <?= (form_error('VSeat')) ? 'has-error' : '' ?>">
                            <label for="" class="col-sm-4 control-label">จำนวนที่นั่ง</label>
                            <div class="col-sm-2">
                                <?php echo $form['VSeat']; ?>
                            </div>
                            <div class="col-sm-2">
                                ที่
                            </div>
                            <div class="col-sm-4">
                                <?php echo form_error('VSeat', '<font color="error">', '</font>'); ?>
                            </div>
                        </div>
                        <div class="form-group <?= (form_error('VStatus')) ? 'has-error' : '' ?>">
                            <label for="" class="col-sm-4 control-label">สถานะรถ</label>
                            <div class="col-sm-5">
                                <?php echo $form['VStatus']; ?>                                        
                            </div>
                        </div>
                    </div>                         

                </div>
            </div>
        </div>
        <div class="col-md-6 animated fadeInRight">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">ข้อมูลพนักงานขับรถ</h3>
                </div>
                <div class="panel-body">                    
                    <div class="col-md-6">
                        <div class="text-center thumbnail">
                            <img src="http://placehold.it/200x200" class="img-responsive">
                            <div class="caption">
                                <h4>ชื่อพนักงานขับรถ</h4> 
                                <p>
                                    ใบอนุญาติขับขี่รถ เลขที่                                            
                                </p>
                                <p>
                                    <b>ท.2 ขก.00614/34 </b>
                                </p>
                                <p>
                                    หมดอายุ 18/08/57
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <h5>รายชื่อพนักงานขับรถ</h5>
                        <div id="list-driver"> 
                            <div class="list-group">
                                <?php
                                for ($x = 0; $x <= 30; $x++) {
                                    ?>                                        
                                    <a href="#" class="list-group-item">
                                        <h5 class="list-group-item-heading">นาย aaaaaa กกกกกกกก</h5>
                                        <p class="list-group-item-text">...</p>
                                    </a>                                        
                                <?php } ?>
                            </div>
                        </div>
                    </div>                                          
                </div>
            </div>
        </div> 
    </div>
    <div class="row animated fadeInUp">
        <div class="col-sm-12">
            <div id="panalInsurance" class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">ข้อมูลทะเบียน,ประกันและพรบ</h3>
                </div>
                <div class="panel-body">                       
                    <div class="row">
                        <div class="col-md-6">
                            <p class="lead">ทะเบียน</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="form-horizontal" role="form">
                                        <div class="form-group <?= (form_error('DateRegistered')) ? 'has-error' : '' ?>">
                                            <label for="" class="col-sm-3 control-label">วันที่ต่อทะเบียน</label>
                                            <div class="col-sm-5">
                                                <?php echo $form['DateRegistered']; ?>                                                    
                                            </div>
                                            <div class="col-sm-4">
                                                <?php echo form_error('DateRegistered', '<font color="error">', '</font>'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group <?= (form_error('DateExpire')) ? 'has-error' : '' ?>">
                                            <label for="" class="col-sm-3 control-label">วันหมดอายุ</label>
                                            <div class="col-sm-5">
                                                <?php echo $form['DateExpire']; ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <?php echo form_error('DateExpire', '<font color="error">', '</font>'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">หมายเหตุ</label>
                                            <div class="col-sm-8">
                                                <?php echo $form['VRNote']; ?>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="lead">ประกันและพรบ</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group <?= (form_error('InsuranceCompanyName')) ? 'has-error' : '' ?>">
                                        <label for="" class="col-sm-3 control-label">บริษัทประกัน</label>
                                        <div class="col-sm-8">
                                            <?php echo $form['InsuranceCompanyName']; ?>
                                        </div>
                                    </div>
                                    <div class="form-group <?= (form_error('PolicyType')) ? 'has-error' : '' ?>">
                                        <label for="" class="col-sm-3 control-label">ประเภทกรมธรรม์</label>
                                        <div class="col-sm-5">
                                            <?php echo $form['PolicyType']; ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <?php echo form_error('PolicyType', '<font color="error">', '</font>'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group <?= (form_error('PolicyStart')) ? 'has-error' : '' ?>">
                                        <label for="" class="col-sm-3 control-label">วันที่เริ่มกรมธรรม์</label>
                                        <div class="col-sm-4">
                                            <?php echo $form['PolicyStart']; ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <?php echo form_error('PolicyStart', '<font color="error">', '</font>'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group <?= (form_error('PolicyEnd')) ? 'has-error' : '' ?>">
                                        <label for="" class="col-sm-3 control-label">วันสิ้นสุดกรมธรรม์</label>
                                        <div class="col-sm-4">
                                            <?php echo $form['PolicyEnd']; ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <?php echo form_error('PolicyEnd', '<font color="error">', '</font>'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group <?= (form_error('PolicyNumber')) ? 'has-error' : '' ?>">
                                        <label for="" class="col-sm-3 control-label">เลขที่กรมธรรม์</label>
                                        <div class="col-sm-8">
                                            <?php echo $form['PolicyNumber']; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">หมายเหตุ</label>
                                        <div class="col-sm-8">
                                            <?php echo $form['ActNote']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                 

                </div>
            </div>  
        </div>
    </div>
    <div class="row animated fadeInUp">
        <div class="col-md-12 text-center">
            <a href="<?= base_url('vehicle/') ?>" class="btn btn-danger btn-lg"><i class="fa fa-times"></i>&nbsp;ยกเลิก</a>
            <button type="submit" class="btn btn-success btn-lg"><i class="fa fa-save"></i>&nbsp;บันทึก</button> 
        </div> 
    </div>
    <?php echo form_close(); ?>
</div>
<hr>