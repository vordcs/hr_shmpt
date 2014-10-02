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
        <?php echo js('pace.min.js?v=' . $version); ?>
        <?php echo css('bootstrap.css?v=' . $version); ?>
        <?php echo css('pace.css?v=' . $version); ?>
        <?php echo css('theme.css?v=' . $version); ?>
        <?php echo css('label.min.css?v=' . $version); ?>
        <?php echo css('segment.min.css?v=' . $version); ?>
        <?php echo css('font-awesome.css?v=' . $version); ?>
        <?php echo css('animate.css?v=' . $version); ?>
        <?php echo js('jquery.js?v=' . $version); ?>
        <?php echo js('bootstrap.js?v=' . $version); ?>

        <script type="text/javascript">
            $(window).scroll(function () {
                if ($(this).scrollTop() > 50) { //use `this`, not `document`
                    $('#top-nav').fadeOut();
                    $(".pace-progress").css("margin-top", "58px");
                } else {
                    $('#top-nav').fadeIn();
                    $(".pace-progress").css("margin-top", "104px");
                }
                if ($(this).scrollTop() > $(window).height() / 2) {
                    $('#scroll-top').removeClass('hidden');
                } else {
                    $('#scroll-top').addClass('hidden');
                }
            });
        </script>
    </head>
    <body>
        <!-- Fixed navbar -->
        <div class="navbar navbar-fixed-top sh-nav" role="navigation">
            <div id="top-nav" class="sh-top-nav">
                <h2 class="color-white" style="font-weight: bold; margin: 4px 0px 4px 4px;">บริษัทสหมิตรภาพ</h2>
                <p class="text-right" style="margin-top: -20px;">
                    <a id="popoverMail" href="#" data-content="ติดต่อกับผู้ให้บริการ ผ่าน support@thaihubhosting.com" rel="popover" data-placement="bottom" data-original-title="ติดต่อผ่านอีเมล์" data-trigger="hover"><i class="fa fa-envelope"></i> Support |</a>
                    <a id="popoverFacebook" class="hidden-xs" href="#" data-content="ติดต่อกับผู้ให้บริการ ผ่าน Facebookpage ThaiHubHosting" rel="popover" data-placement="bottom" data-original-title="ติดต่อผ่านเพจ" data-trigger="hover"> <i class="fa fa-facebook"></i> ThaiHubHosting |</a>
                    <a id="popoverMobile" href="#" data-content="ติดต่อกับผู้ให้บริการ ผ่านเบอร์ <?= lang('phone_number1') ?>" rel="popover" data-placement="bottom" data-original-title="ติดต่อผ่านสายตรง" data-trigger="hover"><i class="fa fa fa-mobile-phone"></i> <?= lang('phone_number1') ?></a>
                </p>
            </div>
            <div class="navbar-collapse collapse">
                <div class="subnavbar">
                    <div class="subnavbar-inner">
                        <div class="container">
                            <ul class="mainnav">
                                <li class="active"><a href="index.html"><i class="icon-dashboard"></i><span>Dashboard</span> </a> </li>
                                <li><a href="reports.html"><i class="icon-list-alt"></i><span>Reports</span> </a> </li>
                                <li><a href="guidely.html"><i class="icon-facetime-video"></i><span>App Tour</span> </a></li>
                                <li><a href="charts.html"><i class="icon-bar-chart"></i><span>Charts</span> </a> </li>
                                <li><a href="shortcodes.html"><i class="icon-code"></i><span>Shortcodes</span> </a> </li>
                                <li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-long-arrow-down"></i><span>Drops</span> <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="icons.html">Icons</a></li>
                                        <li><a href="faq.html">FAQ</a></li>
                                        <li><a href="pricing.html">Pricing Plans</a></li>
                                        <li><a href="login.html">Login</a></li>
                                        <li><a href="signup.html">Signup</a></li>
                                        <li><a href="error.html">404</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- /container --> 
                    </div>
                    <!-- /subnavbar-inner --> 
                </div>
            </div>
            <!--/.nav-collapse -->
        </div>

        <?php
        if (isset($debug)) {
            echo '<div class="container">';
            print '<pre>';
            print_r($debug);
            print '</pre>';
            echo '</div>';
        }

        if (isset($alert)) {
            echo '<div class="container">';
            echo $alert;
            echo '</div>';
        }
        ?>