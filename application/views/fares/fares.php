<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");
        $(".th-footer-bottom").addClass("hidden");
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
</div>
<div class="container">    
    <div class="row well" style="padding-bottom: 2%;"> 
        <?= $data['form'] ?>
        <div class="col-md-6 col-md-offset-3">
            <?= validation_errors() ?>            
            <?= $data['RCode'] ?>
            <?= $data['VTID'] ?>
            <div class="col-md-3 text-right">
                <label for="">ต้นทาง</label>
            </div>
            <div class="col-md-6">
                <?= $data['stations'] ?>
            </div>
            <div class="col-md-3 text-center">
                <?php
                $search = array(
                    'id' => "btn_save",
                    'name' => "btn_save",
                    'type' => "submit",
                    'class' => "btn",
                    'value' => 'save',
                    'content' => '<i class="fa fa-search"></i>&nbsp;แสดงข้อมูล'
                );
                echo form_button($search);
                ?>
            </div>
        </div> 
        <?= form_close() ?>
    </div> 
    <div class="row" style="padding-top: 2%;">  
        <div class="col-md-12">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 40%" rowspan="2">ต้นทาง</th>
                        <th style="width: 40%" rowspan="2">ปลายทาง</th>
                        <th style="width: 20%" colspan="2">ค่าโดยสาร</th>
                    </tr>
                    <tr>
                        <th style="width: 10%">เต็ม</th>
                        <th style="width: 10%">ลด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data['data'] as $rate) {
                        $source_id = $rate['SourceID'];
                        $source_name = $rate['SourceName'];
                        $num_destination = count($rate['DestinationStations']) + 2;
                        ?>
                        <tr>
                            <td class="text text-center" rowspan="<?= $num_destination ?>"><?= $source_name ?></td>                            
                        </tr>
                        <?php
                        foreach ($rate['DestinationStations'] as $desination) {
                            $desination_id = $desination['DestinationID'];
                            $desination_name = $desination['DestinationName'];
                            ?>
                            <tr>
                                <td class="text-center text"><?= $desination_name ?></td>
                                <td class="text-center"><strong><?= $desination['Price'] ?></strong></td>
                                <td class="text-center"><strong><?= $desination['PriceDicount'] ?></strong></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td colspan="3" class="text-center">
                                <?= $rate['Action'] ?> 
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

