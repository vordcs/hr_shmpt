<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords"
              content="thaihubhosting, hosting, host, high perfomance">
        <meta name="description"
              content="เว็บโฮสติ้งคุณภาพสูง ที่ได้รับการออกแบบติดตั้งและผู้แลจากผู้เชี่ยวชาญตลอด 24 ชั่วโมง">
        <meta name="author" content="CBNUKE">


        <title><?php echo $title; ?></title>
        <!-- Favicons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144"
              href="<?= asset_url() ?>img/apple-touch-icon-144-precomposed.png<?= '?v=' . $version ?>">
        <link rel="shortcut icon" href="<?= asset_url() ?>img/favicon.ico<?= '?v=' . $version ?>">
        <!-- Bootstrap core CSS ans JS -->
        <?php echo css('bootstrap.css?v=' . $version); ?>
        <?php echo css('bootflat.min.css?v=' . $version); ?>
        <?php echo css('font-awesome.css?v=' . $version); ?>
        <?php echo js('jquery.js?v=' . $version); ?>
        <?php echo js('bootstrap.js?v=' . $version); ?>

        
        <!-- report style -->       
        <?php echo js('report/jquery-migrate-1.2.1.min.js?v=' . $version); ?> 
        <?php echo js('report/jquery.scrollTo.js?v=' . $version); ?> 
        <?php echo js('report/jquery.dataTables.js?v=' . $version); ?> 
        <?php echo js('report/dataTables.scroller.js?v=' . $version); ?> 
        <?php echo js('report/FixedColumns.js?v=' . $version); ?> 
        <?php echo js('report/report.js?v=' . $version); ?> 

        <script type="text/javascript">
            $(window).scroll(function () {
                if ($(this).scrollTop() > 50) { //use `this`, not `document`                   
                    $(".pace-progress").css("margin-top", "58px");
                } else {                    
                    $(".pace-progress").css("margin-top", "91px");
                }
                if ($(this).scrollTop() > $(window).height() / 2) {
                    $('#scroll-top').removeClass('hidden');
                } else {
                    $('#scroll-top').addClass('hidden');
                }
            });
            jQuery(window).load(function () {
                $('.alert').delay(3000).fadeOut();
            });
        </script>
    </head>
    <body>    
        <?php
        if (isset($debug) && $debug != NULL) {
            echo '<div class="container" style="margin-top: 60px;">';
            print '<pre>';
            print_r($debug);
            print '</pre>';
            echo '</div>';
        }

        if (isset($alert) && $alert != NULL) {
            if ($alert['alert_mode'] == 'success') {
                echo '<div class="container alert alert-success animated pulse" style="margin-top: 60px;"><strong>สำเร็จ</strong> ';
            } elseif ($alert['alert_mode'] == 'warning') {
                echo '<div class="container alert alert-warning animated pulse" style="margin-top: 60px;"><strong>คำเตือน</strong> ';
            } elseif ($alert['alert_mode'] == 'danger') {
                echo '<div class="container alert alert-danger animated pulse" style="margin-top: 60px;"><strong>ผิดพลาด</strong> ';
            } else {
                echo '<div class="container alert alert-info animated pulse" style="margin-top: 60px;"><strong>เพิ่มเติม</strong> ';
            }
            echo $alert['alert_message'];
            echo '</div>';
        }

        if (isset($real_alert) && $real_alert != NULL) {
            if ($real_alert['alert_mode'] == 'success') {
                echo '<div class="container alert alert-success animated pulse" style="margin-top: 60px;"><strong>สำเร็จ</strong> ';
            } elseif ($real_alert['alert_mode'] == 'warning') {
                echo '<div class="container alert alert-warning animated pulse" style="margin-top: 60px;"><strong>คำเตือน</strong> ';
            } elseif ($real_alert['alert_mode'] == 'danger') {
                echo '<div class="container alert alert-danger animated pulse" style="margin-top: 60px;"><strong>ผิดพลาด</strong> ';
            } else {
                echo '<div class="container alert alert-info animated pulse" style="margin-top: 60px;"><strong>เพิ่มเติม</strong> ';
            }
            echo $real_alert['alert_message'];
            echo '</div>';
        }
        ?>