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
    <?= $data['form'] ?>
    <?php if (validation_errors() != NULL) { ?>
        <div class="row animated pulse">
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>ผิดพลาด ! </strong>
                กรุณากรุณากรอกข้อมูลให้ครบ
            </div>
        </div>
    <?php } ?>
    <div class="row">       
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
                        $num_destination = count($rate['DestinationStations']) + 1;
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
                                <td class="text-center <?= (form_error("Price[$source_id][$desination_id]")) ? 'has-error' : '' ?>"><?= $desination['Price'] ?></td>
                                <td class="text-center <?= (form_error("PriceDicount[$source_id][$desination_id]")) ? 'has-error' : '' ?>"><?= $desination['PriceDicount'] ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>  
        <div class="col-md-12 text-center">   
            <?php
            $save = array(
                'id' => "btn_save",
                'name' => "btn_save",
                'type' => "submit",
                'class' => "btn btn-success btn-lg",
                'value' => 'save',
                'content' => '<i class="fa fa-save"></i>&nbsp;บันทึก'
            );
            echo form_button($save);
            ?>
        </div> 

    </div> 
    <?= form_close() ?>
    <div class="row">
        <div class="col-md-12">   
            <ul class="pager">
                <li class="previous">
                    <?php
                    if ($previous_page != '') {
                        echo anchor($previous_page, '<span class="fa fa-angle-double-left"></span> กลับ');
                    }
                    ?>                               
                </li>
                <li class="next">
                    <?php
                    if ($next_page != '') {
                        echo anchor($next_page, 'ต่อไป <span class="fa fa-angle-double-right"></span>');
                    }
                    ?>                                 
                </li>
            </ul>
        </div>
    </div>
</div>

