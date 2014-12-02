<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");
        $(".th-footer-bottom").addClass("hidden");

    });

    function CreateNewRow()
    {
        var intLine = $('#tableStation #tableBodyStation tr').length;

        var theTable = document.getElementById("tableBodyStation");
        var newRow = theTable.insertRow(theTable.rows.length);
        newRow.id = newRow.uniqueID;
        var newCell;

        var TravelTime_id = "TravelTime" + intLine;
        var StopTime_id = "StopTime" + intLine;
        //*** Column 1 ***//
        newCell = newRow.insertCell(0);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("class", "text-center");
        newCell.innerHTML = "<input type=\"checkbox\" name=\"IsSaleTicket[" + intLine + "]\" class=\"IsSaleTicket\" VALUE=\"" + intLine + "\" onclick=\"ShowItemp(\'" + intLine + "\')\">";
        console.log(newCell.innerHTML);
        //*** Column 2 ***//
        newCell = newRow.insertCell(1);
        newCell.id = newCell.uniqueID;
        newCell.innerHTML = "<INPUT TYPE=\"TEXT\" CLASS=\"form-control text-center\" NAME=\"StationName[]\" ID=\"StationName\"" + intLine + " placeholder=\"ชื่อจุดจอด\" VALUE =\"StationName" + intLine + "\">";

        //*** Column 3 ***//
        newCell = newRow.insertCell(2);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("class", "text-center");
        newCell.innerHTML = "<INPUT TYPE=\"TEXT\" CLASS=\"form-control text-center\" NAME=\"TravelTime[]\" ID=\"" + TravelTime_id + "\" VALUE =\"\" style=\"display: none;\">";
        console.log(newCell.innerHTML);

        //*** Column 3 ***//
        newCell = newRow.insertCell(3);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("class", "text-center");
        newCell.innerHTML = "<INPUT TYPE=\"TEXT\" CLASS=\"form-control text-center\" NAME=\"StoptTime[]\" ID=\"" + StopTime_id + "\" VALUE =\"\" style=\"display: none;\">";
        console.log(newCell.innerHTML);

        //*** Column 5 ***//
        newCell = newRow.insertCell(4);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("class", "text-center");
        newCell.innerHTML = '<a class="btn btn-danger btn-sm" onClick="RemoveRow(this)"><i class="fa fa-times"></i></a>';


    }
    function RemoveRow(row)
    {
        var r = row.parentNode.parentNode.rowIndex;
        document.getElementById('tableStation').deleteRow(r);
        console.log(r);
    }

    function ShowItemp(itemp_id) {
        var TravelTime_id = "TravelTime" + itemp_id;
        var StopTime_id = "StopTime" + itemp_id;

        var chboxs = document.getElementsByClassName("IsSaleTicket");
        var vis = 'none';
        for (var i = 0; i < chboxs.length; i++) {
            if (chboxs[i].checked && chboxs[i].value === itemp_id) {
                vis = 'block';
                break;
            }
        }
        document.getElementById(TravelTime_id).style.display = vis;
        document.getElementById(StopTime_id).style.display = vis;

    }
    function validateForm()
    {
        var chboxs = document.getElementsByClassName("IsSaleTicket");
        for (var i = 0; i < chboxs.length; i++) {
            if (chboxs[i].checked) {
                var t = document.getElementsById(chboxs[i].value);
                if (t.val() === '') {
                    console.log('Emty : ' + chboxs[i].value);
                }
            }
            console.log(chboxs[i].value);
        }

        return false;
    }
</script>

<?php ?>
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
    <div class="row">    

        <div class="col-md-12">
            <div class="col-md-6">

            </div>
            <div class="col-md-6">

            </div>
        </div>
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">ข้อมูลจุดจอดรถโดยสาร</h3>
                </div>
                <div class="panel-body">
                    <?php echo validation_errors(); ?>
                    <?php echo $form['form']; ?>                            
                    <table id="tableStation" class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center" style="width:15%;">จุดขายตั๋ว</th>
                                <th rowspan="2" class="text-center" style="width:50%;">จุดจอด</th>
                                <th colspan="2" class="text-center" style="width:20%;">ใช้เวลา(นาที)</th>
                                <th rowspan="2" class="text-center" style="width:10%"></th>
                            </tr>
                            <tr>
                                <th class="text-center" style="width:10%;">เดินทาง</th>
                                <th class="text-center" style="width:10%;">เวลาพัก</th>
                            </tr>
                            <tr class="active">
                                <td class="text-center">
                                    <input type="checkbox"  checked disabled="">  
                                </td>
                                <td>
                                    <?php echo $form['Source'] ?>                           
                                </td> 
                                <td colspan="3"></td>
                            </tr>
                        </thead>
                        <tbody id="tableBodyStation">    
                            <?php echo $form['station'] ?>   
                            <?php
                            if (count($form['StationName']) > 0) {
                                for ($j = 0; $j < count($form['StationName']); $j++) {
                                    $sale_point = $form['IsSaleTiket'][$j];
                                    $station_name = $form['StationName'][$j];
                                    $travel_time = $form['TravelTime'][$j];
                                    $stop_time = $form['StopTime'][$j];
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php echo $sale_point ?>
                                        </td>
                                        <td class=" <?= (form_error("StationName[$j]")) ? 'has-error' : '' ?>">
                                            <?php echo $station_name; ?>
                                        </td>
                                        <td class="text-center <?php echo (form_error("TravelTime[$j]")) ? 'has-error' : '' ?>">
                                            <?php echo $travel_time; ?>
                                        </td>
                                        <td class="text-center <?php echo (form_error("StopTime[$j]")) ? 'has-error' : ''    ?>">
                                            <?php  echo $stop_time; ?>
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-danger btn-sm" onClick="RemoveRow(this)"><i class="fa fa-times"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="active">
                                <td class="text-center">                           
                                    <input type="checkbox"  checked disabled="">   
                                </td>
                                <td>
                                    <?php echo $form['Destination'] ?>   
                                </td>   
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2">
                                    <a name="btnAddSource"  id="btnAddSource" class="btn btn-block btn-info" onClick="CreateNewRow();"><i class="fa fa-plus"></i>&nbsp;จุดจอด</a>
                                </td>
                            </tr>               
                        </tfoot>
                    </table>
                    <div class="col-md-12 text-center"> 
                        <a  href="javascript:window.history.go(-1);" class="btn btn-danger btn-lg" ><span class="fa fa-times"> ยกเลิก</span></a>   
                        <button type="submit" class="btn btn-success  btn-lg" id="btn_save" ><i class="fa fa-save"></i>&nbsp;บันทึก</button>   
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
//                            if (count($form['StationName']) > 0) {
//                                for ($j = 0; $j < count($form['StationName']); $j++) {
//                                    $sale_point = $form['IsSalePoint'][$j];
//                                    $station_name = $form['StationName'][$j];
//                                    $travel_time = $form['TravelTime'][$j];
?>
<!--                                    <tr>
                                        <td class="text-center">
<?php // echo $sale_point ?>
                                        </td>
                                        <td class="<? (form_error("StationName[$j]")) ? 'has-error' : '' ?>">
<?php // echo $station_name; ?>
                                        </td>
                                        <td class="<? (form_error("TravelTime[$j]")) ? 'has-error' : '' ?>">
<?php // echo $travel_time; ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-danger btn-sm" onClick="RemoveRow(this)"><i class="fa fa-minus"></i></a>
                                        </td>-->
<!--</tr>-->
<?php
//                                }
//                            }
?>