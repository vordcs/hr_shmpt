
<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnHR").addClass("active");

        // Add the "focus" value to class attribute
        $('ul.radio li').focusin(function () {
            $(this).addClass('focus');
        }
        );

        // Remove the "focus" value to class attribute
        $('ul.radio li').focusout(function () {
            $(this).removeClass('focus');
        }
        );


    });
</script>
<div class="container">
    <div class="row animated">      
        <div class="col-md-12 ">
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
    <div class="row animated">   
        <div class="col-md-12">
            <ul class="nav nav-pills nav-justified">
                <li><a href="<?= base_url('hr/home') ?>">หน้าหลัก</a></li>
                <li><a href="<?= base_url('hr/employee/') ?>">พนักงาน</a></li>
                <li class="active"><a href="<?= base_url('hr/seller/') ?>">พนักงานขายตั๋ว</a></li>
                <li><a href="<?= base_url('hr/permission/') ?>" >กำหนดสิทธิ์การเข้าใช้</a></li>
                <li><a href="<?= base_url('hr/loguser/') ?>" >ข้อมูลการเข้าใช้ระบบ</a></li>    
            </ul>
        </div>
    </div>
</div>
<?php
echo $form['form'];
$RouteName = $form['RouteName'];
?>
<div class="container" style="padding-top: 1% ;padding-bottom: 5%;">    
    <div class="col-md-12 text-center">
        <?= $form['RCode'] ?>
        <?= $form['VTID'] ?>
        <legend class="lead" style="padding-bottom: 1%;"><strong><?= $form['RouteName'] ?></strong></legend>
    </div>
    <div class="col-md-12 <?= (validation_errors() == NULL) ? 'hidden' : '' ?>">
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>คำแนะนำ !</strong> กรณากรอกข้อมูล
        </div>
    </div>
    <div class="col-md-6" >  
        <h5 class="" >ข้อมูลพนักงานขายตั๋ว</h5> 
        <span style="font-size: 16pt;">
            <?= $form['EID'] ?>
        </span>
    </div>
    <div class="col-md-6">
        <div class="col-md-12">
            <h5 class="" >ข้อมูลจุดจอด</h5> 
            <fieldset class="radiogroup">    
                <?php
                foreach ($form['stations'] as $station) {
                    $SID = $station['SID'];
                    $StationName = $station['StationName'];
                    ?>
                    <ul class="radio">
                        <li>
                            <?= $SID ?>
                            <label>
                                <?= $StationName ?>
                            </label
                        </li>   
                    </ul>
                <?php } ?>
            </fieldset>    
        </div>
        <div class="form-group">                        
            <label for="">หมายเหตุ</label>
            <?php echo $form['SellerNote'] ?>
        </div>
        <div class="col-md-12 text-center" style="margin-top: 5%;">                   
            <?php
            $cancle = array(
                'type' => "button",
                'class' => "btn btn-lg btn-danger",
            );
            $save = array(
                'class' => "btn btn-lg btn-success",
                'type' => "submit",
                'data-id' => "5",
                'data-title' => " เพิ่ม พนักงานขายตั๋ว ",
                'data-sub_title' => "$RouteName",
                'data-info' => "",
                'data-content' => "",
                'data-toggle' => "modal",
                'data-target' => "#confirm",
                'data-form_id' => "form_seller",
            );
            echo anchor('hr/seller', '<i class="fa fa-times" ></i>&nbsp;ยกเลิก', $cancle) . '  ';
            echo anchor("#", '<span class="fa fa-save">&nbsp;&nbsp;บันทึก</span>', $save);
            ?> 
        </div>
    </div>
</div>
<?= form_close() ?>
