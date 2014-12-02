<script language="javascript">
    $(document).ready(function () {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");

        $(".th-footer-bottom").addClass("hidden");
    });
    function preview(start_point) {
//        alert('preview -> '+start_point);

        var table_body = '#table_body' + start_point;
        var start_time = $('#StartTime' + start_point);
        var interval_time = $('#IntervalEachAround' + start_point);
        var num_around = $('#AroundNumber' + start_point);
        var panal = $('#panal' + start_point);

        var html = '';
        if (start_time.val() === '') {
            start_time.focus();
            return;
        }
        if (interval_time.val() === '') {
            interval_time.focus();
            return;
        }
        if (num_around.val() === '') {
            num_around.focus();
            return;
        }
        if (start_time.val() !== '' || interval_time.val() !== '' || num_around.val() !== '') {
            panal.removeClass('hidden');
            $('#info').hide();
            $('html, body').animate({
                scrollTop: panal.offset().top - 70
            }, 1000);
            for (var i = 0; i < num_around.val(); i++) {
                var interval = interval_time.val() * i;
                var time = addMinutes(start_time.val(), interval);
                html += '<tr>';
                html += '<td class="text-center">';
                html += i + 1;
                html += '</td>';
                html += '<td class="text-center">';
                html += time;
                html += '</td>';
                html += '</tr>';
            }
        }
        $(table_body).html(html);
    }
    function addMinutes(time, minsToAdd) {
        function z(n) {
            return (n < 10 ? '0' : '') + n;
        }
        ;
        var bits = time.split(':');
        var mins = bits[0] * 60 + +bits[1] + +minsToAdd;
        return z(mins % (24 * 60) / 60 | 0) + ':' + z(mins % 60);
    }
</script>

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
        <div class="row">
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4>ผิดพลาด ! <small>กรุณากรุณากรอกข้อมูลให้ครบ</small></h4>
            </div>
        </div>
    <?php } ?>
    <div class="row animated bounceInUp">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">ข้อมูลตารางเวลาเดินรถ</h3>
            </div>                
            <div class="panel-body"> 
                <div class="col-md-6">                    
                    <?php echo $form['form']; ?>

                    <?php
                    $i = 0;
                    foreach ($route_detail as $rd) {
                        $source = $rd['RSource'];
                        $destination = $rd['RDestination'];
                        $start_point = $rd['StartPoint'];
                        $start_time = $rd['StartTime'];
                        $interval = $rd['IntervalEachAround'];
                        $around = $rd['AroundNumber'];
                        $class = 'hidden';
                        if ($start_time != '' && $interval != '' && $around != '') {
                            $class = '';
                        }
                        ?>
                        <div class="panel">
                            <div class="panel-heading">
                                <span class="lead"> 
                                    <strong><?php echo $source; ?></strong>
                                    <small><i>&nbsp;ไป&nbsp;</i></small>
                                    <strong><?php echo $destination; ?></strong>
                                </span> 
                            </div>
                            <div class="panel-body">
                                <div class="form-group <?= (form_error("StartTime[$i]")) ? 'has-error' : '' ?>">
                                    <label for="" class="col-sm-4 control-label">เวลาเที่ยวแรก</label>
                                    <div class="col-sm-4">
                                        <?php echo form_input($form['StartTime'][$i]); ?>
                                    </div>
                                </div>
                                <div class="form-group <?= (form_error("AroundNumber[$i]")) ? 'has-error' : '' ?>">
                                    <label for="" class="col-sm-4 control-label">ระยะห่าง</label>
                                    <div class="col-sm-3">
                                        <?php echo form_input($form['IntervalEachAround'][$i]); ?>
                                    </div>
                                    <div class="col-sm-1">
                                        นาที
                                    </div>
                                </div>
                                <div class="form-group <?= (form_error("AroundNumber[$i]")) ? 'has-error' : '' ?>">
                                    <label for="" class="col-sm-4 control-label">จำนวน</label>
                                    <div class="col-sm-3">
                                        <?php echo form_input($form['AroundNumber'][$i]); ?>
                                    </div>
                                    <div class="col-sm-3">
                                        เที่ยว/วัน
                                    </div>
                                </div> 
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-default btn-sm" onclick="preview('<?= $start_point ?>')">
                                        <span class="fa fa-th-list ">&nbsp;ดูตารางเวลา</span>
                                    </button>
                                </div>
                            </div> 

                        </div>

                        <?php
                        $i++;
                    }
                    ?>                   
                    <div class="col-md-12">
                        <a href="javascript:window.history.go(-1);" class="btn btn-link btn-sm pull-left"><span class="fa fa-backward">&nbsp;ข้อมูลเส้นทาง</span></a>
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
                    <?php echo form_close(); ?>  
                </div>
                <div class="col-md-6">
                    <?php
                    $i = 0;
                    foreach ($route_detail as $rd) {
                        $source = $rd['RSource'];
                        $destination = $rd['RDestination'];
                        $start_point = $rd['StartPoint'];
                        $start_time = $rd['StartTime'];
                        $interval = $rd['IntervalEachAround'];
                        $around = $rd['AroundNumber'];
                        $class = 'hidden';
                        if ($start_time != '' && $interval != '' && $around != '') {
                            $class = '';
                        }
                        ?>    
                        <div class="col-md-6">
                            <div class="panel <?= $class ?>" id="panal<?= $start_point ?>">
                                <div class="panel-heading text-center">
                                    <span class="">
                                        <strong><?php echo $source; ?></strong>
                                        &nbsp;<i class="fa fa-arrow-right"></i>&nbsp;
                                        <strong><?php echo $destination; ?></strong>
                                    </span>
                                </div>
                                <table id="table<?= $start_point ?>" class="table table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%">ลำดับ</th>
                                            <th style="width: 80%">เวลา</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_body<?= $start_point ?>">
                                        <?php
                                        if ($start_time != '' && $interval != '' && $around != '') {
                                            for ($j = 0; $j < $around; $j++) {
                                                $time = strtotime($start_time) + $interval * 60 * $j;
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?= $j + 1 ?></td>
                                                    <td class="text-center"><?= date('H:i', $time); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>                           
                                <?php
                                $i++;
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>   
                    <?php if ($class == "hidden") {
                        ?>
                        <div class="well" id="info" style="min-height: 550px; padding-bottom: 100px;padding-top: 250px;">
                            <p class="text-center">ไม่พบข้อมูล</p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
    $('.timepicker').timepicker({
        defaultTime: false,
        minuteStep: 5,
        showInputs: false,
        showMeridian: false,
        disableFocus: true
    });
</script>