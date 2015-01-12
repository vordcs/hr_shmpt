<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnReport").addClass("active");
    });
</script>
<div class="container-fluid">    
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
                Panel content
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        
    </div>    
</div>