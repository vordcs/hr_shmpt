<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnSchedule").addClass("active");
    });
</script>
<div class="container">
    <div class="row">        
        <div class="page-header">
            <h3>ตารางเวลาเดินรถ&nbsp;
                <small></small>
            </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

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
    <?php
    foreach ($vehicles_type as $v_type) {
        ?>
        <div class="row">      
            <div class="col-md-12">                
                <h3><?= $v_type['VTDescription'] ?></h3>                
                <?php
                if (count($route) <= 0) {
                    ?>

                    <?php
                } else {
                    foreach ($route as $r) {
                        if ($r['VTID'] == $v_type['VTID']) {
                            $vtid = $r['VTID'];
                            $type_name = $v_type['VTDescription'];
                            $rcode = $r['RCode'];
                            $source = $r['RSource'];
                            $destination = $r['RDestination'];
                            $route_name = '' . $rcode . ' ' . ' ' . $source . ' - ' . $destination;
                            ?>
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?= $route_name ?></h3>
                                </div>
                                <div class="panel-body">
                                    <?php
                                    foreach ($route_detail as $rd) {
                                        $source = $rd['RSource'];
                                        $destination = $rd['RDestination'];
                                        $start_point = $rd['StartPoint'];
                                        if ($rcode == $rd["RCode"] && $vtid == $rd["VTID"]) {
                                            ?>
                                            <div class="col-md-6">
                                                <span class="lead"> 
                                                    <i>ไป</i>&nbsp</strong>
                                                    <strong><?php echo $destination; ?></strong>
                                                </span> 
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 20%">รอบที่</th>
                                                            <th style="width: 40%">เวลา</th>
                                                            <th style="width: 40%">รถเบอร์</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="active">
                                                            <td class="text-center">1</td>
                                                            <td class="text-center">7:00</td>
                                                            <td class="text-center">123-1</td>
                                                        </tr>
                                                        <tr class="success">
                                                            <td class="text-center">2</td>
                                                            <td class="text-center">7:30</td>
                                                            <td class="text-center">123-2</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <br>
                                                <div class="col-md-12">
                                                    <a href="<?= base_url('schedule/view') ?>" class="btn btn-block"><i class="fa fa-list-ol"></i>&nbsp;ตารางเวลา</a>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>
        </div>
    <?php } ?>

</div>

