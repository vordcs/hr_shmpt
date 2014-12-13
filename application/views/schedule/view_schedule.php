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
            <h3>ตารางเดินรถ&nbsp;
                <small>วันที่ <?= $date ?></small>
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
        <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">ไปขอนแก่น</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10%;">รอบที่</th>
                                <th style="width: 45%;">รถ</th>
                                <th style="width: 45%;">เวลา</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $round = 10;
                            $start = "06:00:00";
                            $duration = "01:00:00";

                            for ($i = 1; $i <= $round; $i++) {
                                ?>                            
                                <tr>
                                    <td><?= $i ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="text-right"><a class="btn btn-success btn-sm"><i class="fa fa-plus"></i>&nbsp;เพิ่มเวลา</a></td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">ไปมุกดาหาร</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10%;">รอบที่</th>
                                <th style="width: 45%;">รถ</th>
                                <th style="width: 45%;">เวลา</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="text-right"><a class="btn btn-success btn-sm"><i class="fa fa-plus"></i>&nbsp;เพิ่มเวลา</a></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"></h3>
                </div>
                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
</div>