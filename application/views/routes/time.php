
<!DOCTYPE HTML>
<html>
    <head>
        <?php $version = 1; ?>
        <?php echo js('pace.min.js?v=' . $version); ?>
        <?php echo css('bootstrap.css?v=' . $version); ?>
        <?php echo css('bootflat.min.css?v=' . $version); ?>
        <?php echo css('pace.css?v=' . $version); ?>
        <?php echo css('theme.css?v=' . $version); ?>
        <?php echo css('label.min.css?v=' . $version); ?>
        <?php echo css('segment.min.css?v=' . $version); ?>
        <?php echo css('font-awesome.css?v=' . $version); ?>
        <?php echo css('animate.css?v=' . $version); ?>
        <?php echo css('customCSS.css?v=' . $version); ?>
        <?php echo js('jquery.js?v=' . $version); ?>
        <?php echo js('bootstrap.js?v=' . $version); ?>
        <?php echo js('customJS.js?v=' . $version); ?>
        
        <?php echo css('bootstrap-combined.min.css?v=' . $version); ?>
        <?php echo css('bootstrap-datetimepicker.min.css?v=' . $version); ?>
    </head>
    <body>
        <div id="datetimepicker" class="input-append">
            <input class="form-control" type="text"></input>
            <span class="add-on">
                <i class="fa fa-clock-o"></i>
            </span>
        </div>

        
        <?php echo js('bootstrap-datetimepicker.min.js?v=' . $version); ?>

        <script type="text/javascript">
            $('#datetimepicker').datetimepicker({
                format: 'hh:mm:ss',
                pickDate: false
            });
        </script>
    </body>
</html>