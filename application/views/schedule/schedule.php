<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnSchedule").addClass("active");
    });
</script>
<div class="container">
    <div class="row">        
        <div class="page-header">
            <h1>ตารางเดินรถ&nbsp;
                <small>วันที่ </small>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search"></i>&nbsp;ค้นหา</h3>
                </div>
                <div class="panel-body">
                    <form id="frm_search_schedule" class="">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="col-md-4">
                                <div class="form-search search-only">
                                    <i class="fa fa-calendar search-icon"></i>
                                    <input type="text" class="form-control search-query">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-search search-only">
                                    <i class="fa fa-share-alt search-icon"></i>
                                    <input type="text" class="form-control search-query">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="checkbox">
                                    <input type="checkbox" id="flat-checkbox-1">
                                    <label for="flat-checkbox-1">รถบัส</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="checkbox">
                                    <input type="checkbox" id="flat-checkbox-1">
                                    <label for="flat-checkbox-1">รถตู้</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <a class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;ค้นหา</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>     
    </div>
    <?php for ($i = 1; $i <= 3; $i++) { ?>
        <div class="row">      
            <div class="col-md-12">                
                <h3>ประเภทรถ <?= $i ?></h3>
                <?php for ($j = 1; $j <= 3; $j++) { ?>
                    <div class="col-md-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">234 ขอนแก่น-อำนาจเจริญ</h3>
                            </div>
                            <div class="panel-body">

                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th rowspan="2"></th>
                                            <th colspan="2">จำนวนเที่ยว</th>
                                        </tr>
                                        <tr>
                                            <th>ทั้งหมด</th>
                                            <th>คงเหลือ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>ไปอำนาจเจริญ</td>
                                            <td></td>
                                            <td></td>
                                        </tr>   
                                        <tr>
                                            <td>ไปขอนแก่น</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    <a href="<?= base_url('schedule/view') ?>" class="btn btn-block"><i class="fa fa-list-ol"></i>&nbsp;ตารางเวลา</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>