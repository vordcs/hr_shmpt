<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnSchedule").addClass("active");

        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'yyyy-m-d'
        });
    });
</script>
<br>
<?php
$date = date('Y-m-d');
?>
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
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">ค้นหา</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-6 col-md-offset-3">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label">วันที่</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control datepicker" id="" placeholder="">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-default">Submit</button>
                            </div>                            
                        </form>  
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">ตารางเวลา</h3>
                </div>
                <div class="panel-body">
                    <?php
                    foreach ($route_detail as $rd) {
                        $rid = $rd['RID'];
                        $source = $rd['RSource'];
                        $destination = $rd['RDestination'];
                        $start_point = $rd['StartPoint'];
                        ?>
                        <div class="col-md-6 animated fadeInUp">
                            <span class="lead"> 
                                <i>ไป</i>&nbsp</strong>
                                <strong><?php echo $destination; ?></strong>
                            </span> 
                            <div class="col-md-10 col-md-offset-1">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%;">รอบที่</th>                                        
                                            <th style="width: 30%;">เวลาออก</th>
                                            <th style="width: 30%;">รถ</th>
                                            <th style="width: 20%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($schedule as $s) {
                                            $tsid = $s['TSID'];
                                            $seq_no = $s['SeqNo'];
                                            $time_depart = $s['TimeDepart'];
                                            if ($rid == $s['RID']) {
                                                ?>                            
                                                <tr>
                                                    <td class="text-center"><?= $seq_no ?></td>
                                                    <td class="text-center"><?= $time_depart ?></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right"><a class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;เพิ่มเวลา</a></td>
                                        </tr>
                                    </tfoot>
                                </table> 
                            </div>

                        </div>
                        <?php
                    }
                    ?>



                </div>
            </div>
        </div>   
    </div>
    <div class="row animated fadeInUp">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">ตารางเดินรถ</h3>
                </div>
                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
</div>