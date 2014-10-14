<!DOCTYPE html>
<html>
    <head>
        <?php $version = 1; ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php echo css('bootstrap.css?v=' . $version); ?>
        <?php echo css('bootstrap-timepicker.min.css?v=' . $version); ?>  
        <?php echo js('bootstrap-timepicker.min.js?v=' . $version); ?> 
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <?php echo js('jquery.js?v=' . $version); ?>
        <?php echo js('bootstrap.js?v=' . $version); ?>

    </head>
    <body>
        <div class="container">
            <hr>

            <div class="input-append bootstrap-timepicker">
                <input id="timepicker2" type="text" class="input-small">
                <span class="add-on">
                    <i class="icon-time"></i>
                </span>
            </div>
        </div>
        <script type="text/javascript">
            $('#timepicker2').timepicker({
                minuteStep: 1,
                template: 'modal',
                appendWidgetTo: 'body',
                showSeconds: true,
                showMeridian: false,
                defaultTime: false
            });
        </script>
    </body>
</html>