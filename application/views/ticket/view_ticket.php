<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnTicket").addClass("active");

        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'yyyy-m-d'
        });

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
                <br>
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
                <?= $form_search['form_open'] ?>
                <div class="col-md-10 col-md-offset-1">    
                    <div class="col-md-12">
                        <div class="col-md-3">                       
                            <?= $form_search['Date'] ?> 
                        </div>
                        <div class="col-md-4">                         
                            <?= $form_search['EID'] ?>
                        </div>
                        <div class="col-md-3">                            
                            <?= $form_search['SID'] ?>
                        </div>
                        <div class="col-md-2">                            
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search fa-lg"></i>&nbsp;แสดงข้อมูล</button>
                        </div>
                    </div>
                     <div class="col-md-12 text-center" style="padding-top: 3%">
                            <h4>
                                <?= $date ?>
                            </h4>            
                        </div>
                </div>
                <?= $form_search['form_close'] ?>
            </div>
        </div>
    </div>
</div>
<div class="container" style="padding-bottom: 2%;"> 
    <div class="row">

    </div>
    <?php
    foreach ($data as $route) {
        $RCode = $route['RCode'];
        $VTID = $route['VTID'];
        $RouteName = $route['RouteName'];
        ?>
        <div class="row"> 
            <legend><?= $RouteName ?></legend>
            <div class="col-md-12">
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <?php
                        foreach ($route['schedules']as $schedule) {
                            $TSID = $schedule['TSID'];
                            $TimeDepart = $schedule['TimeDepart'];
                            $class_tab = '';
                            if ($TSID == $this->session->flashdata('TSID')) {
                                $class_tab = 'active';
                            }
                            ?>
                            <li role="<?= $TSID ?>" class="<?= $class_tab ?>">
                                <a href="#<?= $TSID ?>" aria-controls="<?= $TSID ?>" role="tab" data-toggle="tab"><?= $TimeDepart ?></a>
                            </li>                                        
                        <?php } ?>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php
                        foreach ($route['schedules']as $schedule) {
                            $TSID = $schedule['TSID'];
                            $TimeDepart = $schedule['TimeDepart'];
                            $NumberTicket = $schedule['NumberTicket'];
                            $class_content = '';
                            if ($TSID == $this->session->flashdata('TSID')) {
                                $class_content = 'active';
                            }
                            ?>
                            <div role="tabpanel" class="tab-pane <?= $class_content ?>" id="<?= $TSID ?>">
                                <div class="col-md-12 text-right hidden">
                                    <h3>
                                        <small>รวม&nbsp;:&nbsp;</small><?= $NumberTicket ?>
                                    </h3>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">ข้อมูลเวลา</h3>
                                        </div>     
                                        <!-- Table -->
                                        <table class="table table-border">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50%;">สถานี</th>
                                                    <th style="width: 20%;">เวลาถึง</th>
                                                    <th style="width: 30%;">เวลาออก</th>                                                  
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($schedule['time_schedules'] as $time_schedule) {
                                                    $num_checkins = count($time_schedule['checkins']);
                                                    $StationName = $time_schedule['StationName']
                                                    ?>
                                                    <tr>
                                                        <td  class="text-center text"><?= $StationName ?></td>
                                                        <td  class="text-center"><?= $time_schedule['TimeDepart'] ?></td>
                                                        <td>
                                                            <?php
                                                            foreach ($time_schedule['checkins'] as $checkin) {
                                                                $TimeCheckIn = date('H:i', strtotime($checkin['TimeCheckIn']));
                                                                ?>                                                                
                                                                <div class="btn btn-default btn-block"
                                                                     data-container="body" 
                                                                     data-toggle="popover" 
                                                                     data-placement="top"
                                                                     data-content="<?= ($checkin['SellerNote'] == NULL) ? $StationName : $checkin['SellerNote'] ?>">
                                                                         <?= $TimeCheckIn ?>
                                                                </div>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">ข้อมูลพนักงานขายตั๋ว</h3>
                                        </div>                                       
                                        <table class="table table-striped table-border">
                                            <thead>
                                                <tr>
                                                    <th style="width: 15%">รหัสพนักงาน</th>
                                                    <th style="width: 30%">ชื่อ-นามสกุล</th>
                                                    <th style="width: 25%">สถานี</th>
                                                    <th style="width: 10%">จำนวน</th>
                                                    <th style="width: 20%">เป็นเงิน</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $net = 0;
                                                foreach ($schedule['sellers'] as $seller) {
                                                    $total = $seller['Total'];
                                                    $net+=$total;
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?= $seller['EID'] ?></td>
                                                        <td class=""><?= $seller['SellerName'] ?></td>
                                                        <td class="text-center"><?= $seller['StationName'] ?></td>
                                                        <td class="text-right text"><?= $seller['NumberTicket'] ?></td>
                                                        <td class="text-right"><?= number_format($total) ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr class="active">
                                                    <td colspan="3" class="text-right text">รวม</td>
                                                    <td class="text-right"><strong><?= $NumberTicket ?></strong></td>
                                                    <td class="text-right lead text"><strong><?= number_format($net) ?></strong></td>
                                                </tr>
                                            </tfoot>                                        
                                        </table>
                                    </div>    
                                </div>
                                <div class="col-md-12" style="padding-top: 2%">

                                    <div class="panel panel-default">
                                        <!-- Default panel contents -->
                                        <div class="panel-heading">ข้อมูลตั๋วโดยสาร</div>                                       

                                        <!-- Table -->
                                        <table class="table table-hover table-striped table-border">                                        
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%">สถานะ</th>                                                   
                                                    <th style="width: 10%">ที่นั่ง</th>
                                                    <th style="width: 25%">ต้นทาง</th>
                                                    <th style="width: 25%">ปลายทาง</th>  
                                                    <th style="width: 10%">ราคา</th>
                                                    <th style="width: 10%" >เวลาขาย</th>
                                                    <th style="width: 15%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($schedule['tickets'] as $ticket) {
                                                    $TicketID = $ticket['TicketID'];
                                                    $SouceID = $ticket['SourceID'];
                                                    $SourceName = $ticket['SourceName'];
                                                    $DestinationName = $ticket['DestinationName'];
                                                    $NumberPrint = $ticket['NumberPrint'];
                                                    $PriceSeat = $ticket['PriceSeat'];
                                                    $EID = $ticket['EID'];
                                                    if ($NumberPrint == 0) {
                                                        $status = '<i class="fa fa-ticket fa-lg" style="color: #FC6E51"></i>';
                                                    } else {
                                                        $status = '<i class="fa fa-ticket fa-lg" style="color: #48CFAD"></i>';
                                                    }
                                                    $delete = array(
                                                        'class' => "btn btn-danger btn-sm",
                                                        'type' => "button",
                                                        'data-id' => "2",
                                                        'data-title' => "ลบ ตั๋วโดยสาร ",
                                                        'data-sub_title' => "$SourceName",
                                                        'data-info' => "$DestinationName",
                                                        'data-content' => "$RouteName",
                                                        'data-toggle' => "modal",
                                                        'data-target' => "#confirm",
                                                        'data-href' => "ticket/delete/$TicketID",
                                                    );
                                                    $action = anchor('#', '<i class="fa fa-trash-o"></i>', $delete);
                                                    $IsDiscount = "ราคาเต็ม";
                                                    if($ticket['IsDiscount'] == "1"){
                                                        $IsDiscount = "ราคาลด";
                                                    }
                                                    
                                                    ?>
                                                    <tr id="<?= $ticket['EID'] ?>">
                                                        <td class="text-center"><?= $status ?></td>                                                        
                                                        <td class="text-center"><?= $ticket['Seat'] ?></td>
                                                        <td class="text-center"><strong><?= $SourceName ?></strong></td>
                                                        <td class="text-center"><strong><?= $ticket['DestinationName'] ?></strong></td>
                                                        <td class="text-center text" data-container="body" data-toggle="popover" data-placement="right" data-content="<?=$IsDiscount?>">
                                                            <strong><?= $PriceSeat ?></strong>
                                                        </td>
                                                        <td class="text-center">
                                                            <div data-toggle="popover"  
                                                                 data-placement="top" 
                                                                 title="<?= $ticket['SellerName'] ?>"
                                                                 data-content="<?= $ticket['SellerStation'] ?>"
                                                                 >
                                                                     <?= $ticket['TimeSale'] ?>
                                                            </div>                                                          
                                                        </td>
                                                        <td class="text-center"><?= $action ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </div>   
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-md-12 text-center">
            <?php
            $cancle = array(
                'type' => "button",
                'class' => "btn btn-danger btn-lg",
            );
            echo anchor('ticket/', '<i class="fa fa-times" ></i>&nbsp;กลับ', $cancle);
            ?>
        </div>
    </div>
</div>
