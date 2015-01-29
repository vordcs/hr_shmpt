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

<style> 
    .connected, .sortable, .exclude, .handles {
        margin: auto;
        padding: 0;
        width: 100%;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .sortable.grid {
        overflow: hidden;
    }
    .connected li, .sortable li, .exclude li, .handles li {
        text-align: center;
        list-style: none;
        border: 1px solid #CCC;
        background: #F6F6F6;
        font-family: "Tahoma";
        /*color: #1C94C4;*/
        margin: 5px;
        padding: 5px;
        height: 35px;        
    }
    .handles span {
        cursor: move;
    }
    li.disabled {
        opacity: 0.7;
    }  
    .sortable.grid li {
        line-height: 80px;
        float: left;
        width: 96%;
        height: 100px;
        text-align: center;
    }
    li.highlight {
        background: #FEE25F;
    }
    #connected {
        width: 440px;
        overflow: hidden;
        margin: auto;
    }
    .connected {
        float: left;
    }
    .connected.no2 {
        float: right;
    }
    li.sortable-placeholder {
        border: 1px dashed #CCC;
        background: none;
    }
</style>
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

    <?= $form_open ?>

    <div class="row">
        <div class="col-md-4">
            <div class="widget">
                <div class="widget-header">
                    <i class="fa">จอดอยู่ที่</i> <?= $source ?>
                    <span></span>
                </div>
                <div class="widget-content">
                    <input type="hidden" name="vid[]" value="new_line">
                    <ul id="sortable4" class="connected sortable grid">
                        <li>Item 1<input type="hidden" name="vid[]" value="1"></li>
                        <li>Item 2<input type="hidden" name="vid[]" value="2"></li>
                        <li>Item 3<input type="hidden" name="vid[]" value="3"></li>
                        <li>Item 4<input type="hidden" name="vid[]" value="4"></li>
                        <li>Item 5<input type="hidden" name="vid[]" value="5"></li>
                        <li>Item 6<input type="hidden" name="vid[]" value="6"></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="widget">
                <div class="widget-header">
                    <i class="fa">จอดอยู่ที่</i> <?= $desination ?>
                    <span></span>
                </div>
                <div class="widget-content"> 
                    <input type="hidden" name="vid[]" value="new_line">
                    <ul id="sortable5" class="connected sortable grid">
                        <li>Item 7<input type="hidden" name="vid[]" value="7"></li>
                        <li>Item 8<input type="hidden" name="vid[]" value="8"></li>
                        <li>Item 9<input type="hidden" name="vid[]" value="9"></li>
                        <li>Item 10<input type="hidden" name="vid[]" value="10"></li>
                        <li>Item 11<input type="hidden" name="vid[]" value="11"></li>
                        <li>Item 12<input type="hidden" name="vid[]" value="12"></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="widget">
                <div class="widget-header">
                    <i class="fa">รถเสีย/รถที่ไม่ต้องจัดคิว</i>
                    <span></span>
                </div>
                <div class="widget-content"> 
                    <input type="hidden" name="vid[]" value="new_line">
                    <ul id="sortable6" class="connected sortable grid">
                        <li>Item 13<input type="hidden" name="vid[]" value="13"></li>
                        <li>Item 14<input type="hidden" name="vid[]" value="14"></li>
                        <li>Item 15<input type="hidden" name="vid[]" value="15"></li>
                        <li>Item 16<input type="hidden" name="vid[]" value="16"></li>
                        <li>Item 17<input type="hidden" name="vid[]" value="17"></li>
                        <li>Item 18<input type="hidden" name="vid[]" value="18"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-center panel panel-default" style="padding: 20px;">
            <?= anchor('schedule', '<span class="btn btn-lg btn-danger">ย้อนกลับ</span>') ?>
            <button class="btn btn-lg btn-success" type="submit">บันทึก</button>
        </div>
    </div>
    <?= $form_close ?>
</div>
<?php echo js('jquery.sortable.js?v=' . $version); ?>
<script>
    $(function () {
        $('.sortable').sortable();
        $('.handles').sortable({
            handle: 'span'
        });
        $('.connected').sortable({
            connectWith: '.connected'
        });
        $('.exclude').sortable({
            items: ':not(.disabled)'
        });
    });
</script>
