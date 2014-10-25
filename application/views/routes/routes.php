<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");
    });
</script>

<div class="container">
    <div class="row">        
        <div class="page-header">
            <h1>เส้นทางเดินรถ&nbsp;
                <small></small>
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">


        </div>
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search"></i>&nbsp;&nbsp;ค้นหา</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-8 col-md-offset-2">                        
                        <form id="frm_search_route" class="form-horizontal" role="form">
                            <div class="col-md-6">
                                <label for="" class="col-sm-2 control-label">ต้นทาง</label>
                                <input type="text" class="form-control" placeholder="">
                            </div>
                            <div class="col-md-6">                                
                                <label for="">ปลายทาง</label>
                                <input type="text" class="form-control" placeholder="">                                
                            </div>                           
                            <div class="col-md-12">
                                <br>
                                <label for="" class="col-sm-2 control-label">ประเภทรถ</label>
                                <div class="col-sm-10">
                                    <div class="checkbox"> 
                                        <label class="checkbox-inline">
                                            <input type="checkbox" id="" value="option1"> ทั้งหมด
                                        </label>
                                        <?php
//                                        for ($i = 0; $i < 3; $i++) { 
                                        foreach ($vehicles_type as $v_type) {
                                            ?>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="" value="<?= $v_type['VTID'] ?>"> <?= $v_type['VTDescription'] ?>
                                            </label>
                                        <?php } ?>
                                    </div>    
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-default">ค้นหา</button>
                            </div>
                        </form>          
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php
        $time = date('H:i');
        $i = 0;
//        for ($i = 5; $i >= 1; $i--) {
        foreach ($vehicles_type as $v_type) {
            $add = array(
                'type' => "button",
                'class' => "btn badge-success btn-lg pull-right",                
            );
            ?>
            <div class="col-md-12">
                <div class="col-xs-8">
                    <h3 class=""><?= $v_type['VTDescription'] ?></h3> 
                </div>
                <div class="col-xs-4">
                    <?php echo anchor('route/add/'.$v_type['VTID'], '<i class="fa fa-plus"></i>&nbsp;เพิ่มเส้นทาง&nbsp' . $v_type['VTDescription'], $add); ?>
                </div>
            </div>
            <?php for ($j = 1; $j <= 2; $j++) { ?>
                <div class="col-md-offset-1 col-sm-10">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">สาย XXX<?= $j ?> ขอนแก่น มุกดาหาร</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-offset-1 col-md-10">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width: 20%"></th>
                                                <th>เที่ยวแรก</th>
                                                <th>เที่ยวสุดท้าย</th>
                                                <th>ออกทุกๆ</th>
                                                <th>เวลาทั้งหมด</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>ไป มุกดาหาร</td>
                                                <td><?= $time ?></td>
                                                <td><?= $time ?></td>
                                                <td><?= $time ?></td>
                                                <td><?= $time ?></td>
                                            </tr>
                                            <tr>
                                                <td>ไป ขอนแก่น</td>
                                                <td><?= $time ?></td>
                                                <td><?= $time ?></td>
                                                <td><?= $time ?></td>
                                                <td><?= $time ?></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-offset-1 col-md-10">
                                    <div class="col-md-2">
                                        จุดขายตั๋ว
                                    </div>
                                    <div class="col-md-10 text-left">

                                        <?php for ($n = 0; $n < 6; $n++) { ?>
                                            <div class="col-xs-6 col-md-4">
                                                จุดดขายตัวที่ <?= $n ?>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="text-right">
                                        <a class="btn btn-info" data-toggle="tooltip" data-placement="left" title="แก้ไข"><i class="fa fa-pencil" ></i></a>
                                        &nbsp;
                                        <a class="btn btn-info" data-toggle="tooltip" data-placement="left" title="ตารางเวลาเดินรถ"><i class="fa fa-list-ol" ></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>       
                    </div>
                </div>
                <?php
            } $i++;
        }
        ?>
    </div>
</div>

