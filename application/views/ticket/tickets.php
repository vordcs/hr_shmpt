<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnTicket").addClass("active");

    });
    $(function () {
        $('[data-toggle="popover"]').popover({
            html: true,
            trigger: 'hover'
        });
    });
</script>
<div class="container">    
    <div class="row">        
        <div class="page-header">        
            <h3>
                <?php echo $page_title; ?>
                &nbsp;<small>:</small>&nbsp;
                <font color="#777777">
                <span style="font-size: 23px; line-height: 23.399999618530273px;"><?php echo $page_title_small; ?></span>                
                </font>
            </h3>        
        </div>
    </div>
    <div class="row">        
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-search"></i>&nbsp;ค้นหา</h3>
            </div>
            <div class="panel-body">
                <div class="col-md-8 col-md-offset-2">
                    <?= $form_search['form_open'] ?>
                    <div class="col-md-3">                            
                        <?= $form_search['VTID'] ?>
                    </div>
                    <div class="col-md-6">                            
                        <?= $form_search['RCode'] ?>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search fa-lg"></i>&nbsp;แสดงข้อมูล</button>
                    </div>
                    <?= $form_search['form_close'] ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container"> 
    <div class="row"> 
        <?php
        $class = '';
        if (count($data) == 1) {
            $class = 'col-md-offset-3';
        }
        foreach ($data as $type) {
            $VTID = $type['VTID'];
            $sum_net = 0;
            
            ?>   

            <div class="col-md-6 <?= $class ?>">
                <div class="widget">
                    <div class="widget-header">
                        <?= $type['VTName'] ?>
                    </div>
                    <div class="widget-content">
                        <table class="table table-hover">
                            <thead>
                                <tr>                                                                  
                                    <th style="width: 60%">สถานี</th> 
                                    <th style="width: 20%">ยอดขาย</th>  
                                    <th style="width: 20%"></th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($type['routes'] as $route) {
                                    $RouteName = $route['RouteName'];
                                    $RCode = $route['RCode'];
                                    $view_by_route = array(
                                        'type' => "button",
                                        'class' => "btn",
                                    );
                                    ?>
                                    <tr class="active">
                                        <td colspan="2" class="">
                                            <strong>
                                                <?= $RouteName ?>
                                            </strong>
                                        </td>
                                        <td class="text-center">
                                            <div data-placement="top"
                                                 data-toggle="popover"                                           
                                                 title="ข้อมูลขายตั๋วโดยสาร" 
                                                 data-content="<?= $RouteName ?>">  
                                                     <?= anchor("ticket/view/$RCode/$VTID/", '<i class="fa fa-ticket"></i>&nbsp;ทั้งหมด', $view_by_route) ?>  
                                            </div>                                        
                                        </td>
                                    </tr>
                                    <?php
                                    $sum = 0;
                                    foreach ($route['stations'] as $station) {
                                        $SID = $station['SID'];
                                        $StationName = $station['StationName'];
                                        $SaleTotal = $station['SaleTotal'];
                                        $sum+=$SaleTotal;
                                        $view = array(
                                            'type' => "button",
                                            'class' => "btn btn-link",
                                            'data-container' => "body",
                                            'data-toggle' => "popover",
                                            'data-placement' => "top",
                                            'data-content' => "$StationName"
                                        );
                                        ?>
                                        <tr>                                                                                  
                                            <td class="text-center text"><?= $StationName ?></td>
                                            <td class="text-right text"><?= number_format($SaleTotal) ?></td>  
                                            <td class="text-center">
                                                <?= anchor("ticket/view/$RCode/$VTID/$SID/", '<i class="fa fa-ticket fa-lg"></i>', $view) ?>                                       
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr class="info">
                                        <td class="text-right">รวม</td>
                                        <td class="text-right text"><strong><?= number_format($sum) ?></strong></td>
                                        <td></td>
                                    </tr>
                                <?php } ?>
                            </tbody>                          
                        </table>
                    </div>
                </div>  
            </div>
            <?php
        }
        ?>
    </div> 
</div>



