<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnSchedule").addClass("active");

        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'yyyy-m-d'
        });
    });
    function remove_schedule(schedule_id) {
        var elem = document.getElementById(schedule_id);
        elem.remove();
    }

</script>
<div class="container">
    <div class="row">        
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
<div class="container">
    <div class="row">
        <?php if (validation_errors() != NULL) { ?>
        <div class="row animated pulse">
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4>ผิดพลาด ! <small> ข้อมูลเวลา </small></h4>
            </div>
        </div>
    <?php } ?>
        <div class="col-md-8 col-md-offset-2">
            <div class="col-md-12">
                <div class="clock" id="clock">
                    <div id="Date"></div>
                    <ul id="time">
                        <li id="hours"> </li>
                        <li id="point">:</li>
                        <li id="min"> </li>
                        <li id="point">:</li>
                        <li id="sec"> </li>
                    </ul>
                </div>   
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?= $form['form'] ?>
            <div class="col-md-12">
                <div class="form-group  <?= (form_error('RID')) ? 'has-error' : '' ?>">
                    <label for="RID" class="control-label">เส้นทาง</label>
                    <?= $form['RID'] ?>
                    <?= $form['route_name'] ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group <?= (form_error('TimeDepart')) ? 'has-error' : '' ?>">  
                    <label for="" class="control-label">เวลา</label>                        
                    <?= $form['TimeDepart'] ?> 
                    <?php echo form_error('TimeDepart', '<font color="error">', '</font>'); ?> 
                </div>                   
            </div>
            <div class="col-md-6">
                <div class="form-group <?= (form_error('Date')) ? 'has-error' : '' ?>">    
                    <label for="" class="control-label">วันที่</label>
                    <?= $form['date_thai'] ?> 
                    <?= $form['Date'] ?>                    
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group <?= (form_error('ScheduleNote')) ? 'has-error' : '' ?>">                        
                    <label for="ScheduleNote" class="control-label">หมายเหตุ</label>
                    <?= $form['ScheduleNote'] ?>
                </div>    
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">คำแนะนำการเพิ่มเที่ยวรถ</h3>
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li>เที่ยวเเรกที่สามารถเพิ่มได้ห่างจากเวลาปัจจุบัน <strong>30 นาที</strong> เท่านั่น </li>
                            <li>ระบบจะทำการเลือกรถให้โดยอัตโนมัติ</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center" style="margin-top: 5%;">                   
                <?php
                $cancle = array(
                    'type' => "button",
                    'class' => "btn btn-lg btn-danger",
                );
                $save = array(
                    'type' => "submit",
                    'class' => "btn btn-lg btn-success",
                    'value' => '',
                    'content' => '<span class="fa fa-save">&nbsp;&nbsp;บันทึก</span>'
                );
                echo anchor('/', '<i class="fa fa-times" ></i>&nbsp;ยกเลิก', $cancle) . '  ';
                echo form_button($save);
                ?> 
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

