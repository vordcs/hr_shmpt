<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnReport").addClass("active");
    });
</script>

<div class="container">    
    <div class="row-fluid">        
        <div class="page-header">        
            <h3>
                <?php echo $page_title; ?>                
                <font color="#777777">
                <span style="font-size: 23px; line-height: 23.399999618530273px;"><?php echo $page_title_small; ?></span>                
                </font>
            </h3>        
        </div>
    </div>
    <div class="row-fluid">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-search"></i>&nbsp;ค้นหา
            </div>
            <div class="panel-body">
                <div class="col-md-6 col-md-offset-3">
                    <form>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">ประเภทรถ</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="">เส้นทาง</label>
                                <input type="email" class="form-control" id="" placeholder="Enter">
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-default"><i class=" fa fa-search"></i>&nbsp;ค้นหา</button>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row-fluid">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-bus"></i>&nbsp;ข้อมูลเส้นทางเดินรถ
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10%">ประเภทรถ</th>
                        <th style="width: 20%">รหัสเส้นทาง</th>
                        <th style="width: 20%">ต้นทาง-ปลายทาง</th>                   
                        <th style="width: 20%"></th>
                    </tr>
                </thead>
                <tbody>
                <div class="tab-content">
                    <?php
                    foreach ($routes as $r) {
                        $rcode = $r['RCode'];
                        $vtid = $r['VTID'];
                        $vt_name = $r['VTDescription'];
                        $route_name = $r['RSource'] . ' - ' . $r['RDestination'];
                        $id = $vtid . '_' . $rcode;
                        ?>                       
                        <tr>
                            <td class="text-center">
                                <?= $vt_name ?>
                            </td>                                  
                            <td class="text-center">
                                <?= $rcode ?>
                            </td>
                            <td class="text-left">
                                <?= $route_name ?>
                            </td>
                            <td class="text-center">
                                <?php
                                $view = array(
                                    'class' => 'btn btn-primary',
                                );
                                echo anchor("report/view/$rcode/$vtid", '<i class="fa fa-eye"></i>&nbsp;ดูรายงาน', $view);
                                ?>                                        
                            </td>
                        </tr>  
                        <?php
                    }
                    ?>
                </div>
                </tbody>
            </table>            
        </div>
    </div>
</div>

