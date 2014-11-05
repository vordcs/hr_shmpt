<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnExpenditure").addClass("active");
    });

</script>
<br>
<div class="container-fluid">
    <div class="row">      
        <div class="col-md-12">
            <div class="page-header">
                <?php
                $date = $this->m_datetime->DateThaiToDay();
                ?>
                <h2>รายรับ-รายจ่าย <small>ประจำวันที่ <?= $date ?></small></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Button trigger modal -->
            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                Launch demo modal
            </button>      
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <br>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search"></i>&nbsp;ค้นหา</h3>
                </div>
                <div class="panel-body">
                    Panel content
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="btn-group">
                <?php
                foreach ($vehicle_types as $vt) {
                    $vt_id = $vt['VTID'];
                    $vt_name = $vt['VTDescription'];
                    ?>
                    <a href="#vt_<?= $vt_id ?>" class="btn btn-default btn-lg" ><?= $vt_name ?></a>                   
                <?php } ?> 
            </div>
        </div>
        <?php
        foreach ($vehicle_types as $vt) {
            $vt_id = $vt['VTID'];
            $vt_name = $vt['VTDescription'];
            ?>
            <div class="col-md-12" id="vt_<?= $vt_id ?>">                         
                <p class="lead"><strong><?php echo $vt_name; ?></strong></p>
                <hr>
            </div>        
            <div class="col-md-12">         
                <div class="panel">
                    <ul id="RouteTab" class="nav nav-tabs nav-justified" >
                        <?php
                        foreach ($routes as $r) {
                            $rcode = $r['RCode'];
                            $vtid = $r['VTID'];
                            $route_name = $rcode . ' ' . $r['RSource'] . ' - ' . $r['RDestination'];
                            if ($vt_id == $vtid) {
                                ?>                    
                                <li><a href="#<?= $vtid . '_' . $rcode ?>" data-toggle="tab"><?= $route_name ?></a></li>
                                <?php
                            }
                        }
                        ?>                        
                    </ul>
                    <div id="RouteTabContent" class="tab-content">
                        <?php
                        foreach ($routes as $r) {
                            $rcode = $r['RCode'];
                            $vtid = $r['VTID'];
                            $route_name = $rcode . ' ' . $r['RSource'] . ' - ' . $r['RDestination'];
                            if ($vt_id == $vtid) {
                                ?>  
                                <div class="tab-pane fade in" id="<?= $vtid . '_' . $rcode ?>">                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><?= $route_name ?></p>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">เบอร์รถ</th>
                                                        <th colspan="4" class="info">เวลา</th>
                                                        <th colspan="4" class="success">รายรับ</th>
                                                        <th colspan="4" class="danger">รายจ่าย</th>
                                                        <th rowspan="2">คงเหลือ</th>
                                                    </tr>
                                                    <tr>
                                                        <!--เวลา-->
                                                        <th class="info">จุดลงจอดที่ 1</th>
                                                        <th class="info">จุดลงจอดที่ 2</th>
                                                        <th class="info">จุดลงจอดที่ 3</th>
                                                        <th class="info">จุดลงจอดที่ 3</th>

                                                        <!--รายรับ-->
                                                        <th class="success">station 1</th>
                                                        <th class="success">station 2</th>
                                                        <th class="success">station 3</th>
                                                        <th class="success">station 4</th>

                                                        <!--รายจ่าย-->
                                                        <th class="danger">ค่าเที่ยว</th>
                                                        <th class="danger">ค่าก๊าซ</th>
                                                        <th class="danger">ค่าน้ำมัน</th>
                                                        <th class="danger">อื่นๆ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($vehicles as $v) {
                                                        if ($v['RCode'] == $rcode && $v['VTID'] == $vtid) {
                                                            ?>
                                                            <tr>
                                                                <!--เบอร์รถ-->
                                                                <td class="text-center"><?= $v['VCode'] ?></td>
                                                                <!--เวลา-->
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>                                                                
                                                                <td></td>
                                                                <!--รายรับ-->
                                                                <td>รายรับ</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>รายรับ</td>
                                                                <!--รายจ่าย-->
                                                                <td>รายจ่าย</td>
                                                                <td></td>
                                                                <td></td>                                                                
                                                                <td>รายจ่าย</td>  
                                                                <!--คงเหลือ-->
                                                                <td></td>
                                                                
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>   
                                        </div>
                                    </div>
                                </div> 

                                <?php
                            }
                        }
                        ?>  

                    </div>
                </div>
                <?php ?>
            </div>  
        <?php } ?>
    </div>

    <div class="row hidden">
        <div class="col-md-6">
            <div class="panel">
                <ul id="myTab1" class="nav nav-tabs nav-justified">
                    <li class="active"><a href="#home1" data-toggle="tab">Home</a></li>
                    <li><a href="#profile1" data-toggle="tab">Profile</a></li>
                    <li class="dropdown">
                        <a href="#" id="myTabDrop1-1" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
                            <li><a href="#dropdown1-1" tabindex="-1" data-toggle="tab">@fat</a></li>
                            <li><a href="#dropdown1-2" tabindex="-1" data-toggle="tab">@mdo</a></li>
                        </ul>
                    </li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade active in" id="home1">
                        <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica.</p>
                    </div>
                    <div class="tab-pane fade" id="profile1">
                        <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee.  </p>
                    </div>
                    <div class="tab-pane fade" id="dropdown1-1">
                        <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone...</p>
                    </div>
                    <div class="tab-pane fade" id="dropdown1-2">
                        <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche ... </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel">
                <div class="tabbable tabs-below">
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade active in" id="home2">
                            <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. </p>
                        </div>
                        <div class="tab-pane fade" id="profile2">
                            <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee.  </p>
                        </div>
                        <div class="tab-pane fade" id="dropdown2-1">
                            <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone...</p>
                        </div>
                        <div class="tab-pane fade" id="dropdown2-2">
                            <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche ... </p>
                        </div>
                    </div>
                    <ul id="myTab2" class="nav nav-tabs nav-justified">
                        <li class="active"><a href="#home2" data-toggle="tab">Home</a></li>
                        <li><a href="#profile2" data-toggle="tab">Profile</a></li>
                        <li class="dropdown dropup">
                            <a href="#" id="myTabDrop2-1" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
                                <li><a href="#dropdown2-1" tabindex="-1" data-toggle="tab">@fat</a></li>
                                <li><a href="#dropdown2-2" tabindex="-1" data-toggle="tab">@mdo</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row hidden">
        <div class="col-md-6">
            <div class="panel">
                <div class="tabbable tabs-left clearfix">
                    <ul id="myTab1" class="nav nav-tabs">
                        <li class="active"><a href="#home3" data-toggle="tab">Home</a></li>
                        <li><a href="#profile3" data-toggle="tab">Profile</a></li>
                        <li><a href="#myTabDrop3" data-toggle="tab">Dropdown</a></li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade active in" id="home3">
                            <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica.</p>
                        </div>
                        <div class="tab-pane fade" id="profile3">
                            <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee.  </p>
                        </div>
                        <div class="tab-pane fade" id="myTabDrop3">
                            <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel">
                <div class="tabbable tabs-right clearfix">
                    <ul id="myTab1" class="nav nav-tabs">
                        <li class="active"><a href="#home4" data-toggle="tab">Home</a></li>
                        <li><a href="#profile4" data-toggle="tab">Profile</a></li>
                        <li><a href="#myTabDrop4" data-toggle="tab">Dropdown</a></li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade active in" id="home4">
                            <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica.</p>
                        </div>
                        <div class="tab-pane fade" id="profile4">
                            <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee.  </p>
                        </div>
                        <div class="tab-pane fade" id="myTabDrop4">
                            <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>