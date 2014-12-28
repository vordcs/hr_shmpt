<div class="container">
    <div class="row">      
        <div class="col-md-12">
            <div class="page-header">        
                <h3>
                    <?php echo $page_title; ?>                   
                    <font color="#777777">
                    <span style="font-size: 23px; line-height: 23.399999618530273px;"><?php echo $page_title_small; ?></span>                
                    </font>
                </h3>        
            </div>
        </div>
    </div>
    <div class="row">  
        <div class="col-md-12">
            <?php
            foreach ($route_detail as $rd) {
                $rid = $rd['RID'];
                $rcode = $rd['RCode'];
                $vtid = $rd['VTID'];
                $start_point = $rd['StartPoint'];
                $route_time = $rd['Time'];

                //นับจำนวนสถานี
                $num_station = 0;
                $num_sale_station = 0;
                foreach ($stations as $s) {
                    if ($rcode == $s['RCode'] && $vtid == $s['VTID']) {
                        $num_station ++;
                        if ($s['IsSaleTicket'] == '1') {
                            $num_sale_station ++;
                        }
                    }
                }
                ?>
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <?php
                            if ($start_point == "S") {
                                //start point s
                                for ($i = 0; $i <= $num_station; $i++) {
                                    foreach ($stations as $s) {
                                        if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == '1' && $s['Seq'] == $i) {
                                            $station_name = $s['StationName'];
                                            $last_seq_station = $s['Seq'];
                                            $pre_string = "";
                                            if ($s['Seq'] == 1) {
                                                $pre_string = "ออกจากสถานีขนส่ง "; //$pre_string, $station_name;
                                            }
                                            $width = 80 / $num_sale_station;
                                            echo "<th style=\"width: $width%\"> $pre_string $station_name</th>";
                                        }
                                    }
                                }
                            } else {
                                //start point D
                                for ($i = $num_station; $i >= 0; $i--) {
                                    foreach ($stations as $s) {
                                        if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == '1' && $s['Seq'] == $i) {
                                            $station_name = $s['StationName'];
                                            $last_seq_station = $s['Seq'];
                                            $pre_string = "";
                                            if ($s['Seq'] == $num_station) {
                                                $pre_string = "ออกจากสถานีขนส่ง "; //$pre_string, $station_name;
                                            }
                                            $width = 80 / $num_sale_station;
                                            echo "<th style=\"width: $width%\">$pre_string $station_name</th>";
                                        }
                                    }
                                }
                            }
                            ?>
                        <th style="width: 20%">รถเบอร์</th>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($schedules as $schedule) {
                                if ($rid == $schedule['RID']) {
                                    $seq_no_schedule = $schedule['SeqNo'];
                                    $start_time = $schedule['TimeDepart'];
                                    $vid = $schedule['VID'];
                                    $vcode = $schedule['VCode'];
                                    ?>
                                    <tr>                                       
                                        <?php
                                        foreach ($stations as $s) {
                                            if ($rcode == $s['RCode'] && $vtid == $s['VTID'] && $s['IsSaleTicket'] == '1') {
                                                $station_name = $s['StationName'];
                                                $travel_time = $s['TravelTime'];
                                                $temp = 0;
                                                if ($s['Seq'] == '1') {
                                                    //สถานีต้นทาง
                                                    $time_depart = strtotime($start_time);
                                                } elseif ($s['Seq'] == $num_station) {
                                                    //สถานีปลายทาง
                                                    $time_depart = strtotime("+$route_time minutes", strtotime($start_time));
                                                } else {
                                                    $temp+=$travel_time;
                                                    $time_depart = strtotime("+$temp minutes", strtotime($start_time));
                                                }
                                                $time_depart = date('H:i', $time_depart);
                                                $now = date('H:i');
                                                $class='';
                                                if ($now > $time_depart) {
                                                    $class ='success';
                                                }
                                                echo "";
                                                ?>
                                                <td class="text-center <?=$class?>"><?= $time_depart ?></td>   
                                                <?php
                                            }
                                        }

                                        if ($vcode == '') {
                                            echo "<td class=\"text-center\"> - </td>";
                                        } else {
                                            echo "<td class=\"text-center\"><strong>$vcode</strong>  </td>";
                                        }
                                        ?>


                                    </tr> 
                                    <?php
                                }
                            }
                            ?>   
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>


</div>