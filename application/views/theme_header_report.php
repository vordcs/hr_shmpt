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
        <?php echo css('bootstrap2.3.2.css?v=' . $version); ?>
        <?php echo css('bootstrap-responsive2.3.2.css?v=' . $version); ?>
        <?php echo css('bootflat.min.css?v=' . $version); ?>
        <?php echo css('font-awesome.css?v=' . $version); ?>
        <?php echo js('jquery.js?v=' . $version); ?>
        <?php echo js('bootstrap.js?v=' . $version); ?>
        <?php echo js('jquery.table2excel.js?v=' . $version); ?> 

        <!-- report style -->       
        <?php echo js('report/jquery-migrate-1.2.1.min.js?v=' . $version); ?> 
        <?php echo js('report/jquery.scrollTo.js?v=' . $version); ?> 
        <?php echo js('report/jquery.dataTables.js?v=' . $version); ?> 
        <?php echo js('report/dataTables.scroller.js?v=' . $version); ?> 
        <?php echo js('report/FixedColumns.js?v=' . $version); ?> 
        <?php echo js('report/report.js?v=' . $version); ?> 
 
    </head>
    <body>    