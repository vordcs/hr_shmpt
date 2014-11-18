<script language="javascript">
    $(document).ready(function () {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");
    });
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $('.form_date').datetimepicker({
        language: 'fr',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    $('.form_time').datetimepicker({
        language: 'fr',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });
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
    <div class="row">
        <div class="bootstrap-timepicker pull-right">
            <input id="timepicker3" class="timepicker" type="text" class="input-small">
        </div>
 
        <script type="text/javascript">
            $('.timepicker').timepicker({
                minuteStep: 5,
                showInputs: false,
                showMeridian:false,
                disableFocus: true
            });
        </script>
    </div>
    <div class="row">  
        <div class="col-md-6">
            <div class="input-append bootstrap-timepicker">
                <input id="timepicker1" type="text" class="input-small">
                <span class="add-on"><i class="icon-time"></i></span>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">ไป มุกดาหาร</h3>
                </div>
                <div class="panel-body">                        
                    <div class="form-group has-feedback">
                        <label for="" class="col-sm-3 control-label">เวลาเที่ยวแรก</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="" placeholder="">
                            <span class="fa fa-clock-o form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">เวลาห่าง</label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" id="" placeholder="">
                        </div>
                        <div class="col-sm-1">
                            <p>นาที</p> 
                        </div>                            
                        <label for="" class="col-sm-2 control-label">จำนวน</label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" id="" placeholder="">
                        </div>
                        <div class="col-sm-2">
                            <p>เที่ยว / วัน</p>  
                        </div>
                    </div>                        
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">ไป ขอนแก่น</h3>
                </div>
                <div class="panel-body">

                </div>
            </div>
        </div>
        </form>
    </div>
</div>
