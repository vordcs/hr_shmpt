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

        //*** Column 1 ***//
        newCell = newRow.insertCell(0);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("class", "text-center");
        newCell.innerHTML = "<input type=\"checkbox\" name=\"IsSaleTicket[" + intLine + "]\" class=\"IsSaleTicket\" VALUE=\"" + TravelTime_id + "\" onclick=\"ShowItemp(\'" + TravelTime_id + "\')\">";
        console.log(newCell.innerHTML);
        //*** Column 2 ***//
        newCell = newRow.insertCell(1);
        newCell.id = newCell.uniqueID;
        newCell.innerHTML = "<INPUT TYPE=\"TEXT\" CLASS=\"form-control\" NAME=\"StationName[]\" ID=\"StationName\"" + intLine + " placeholder=\"ชื่อจุดจอด\" VALUE =\"StationName" + intLine + "\">";

        //*** Column 3 ***//
        newCell = newRow.insertCell(2);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("class", "text-center");
        newCell.innerHTML = "<INPUT TYPE=\"TEXT\" CLASS=\"form-control\" NAME=\"TravelTime[]\" ID=\"" + TravelTime_id + "\" VALUE =\"\" style=\"display: none;\">";
        console.log(newCell.innerHTML);
        //*** Column 4 ***//
        newCell = newRow.insertCell(3);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("class", "text-center");
        newCell.innerHTML = '<a class="btn btn-danger btn-sm" onClick="RemoveRow(this)"><i class="fa fa-minus"></i></a>';


    }
    function RemoveRow(row)
    {
        var r = row.parentNode.parentNode.rowIndex;
        document.getElementById('tableStation').deleteRow(r);
        console.log(r);
    }

    function ShowItemp(itemp_id) {

        var chboxs = document.getElementsByClassName("IsSaleTicket");
        var vis = 'none';
        for (var i = 0; i < chboxs.length; i++) {
            if (chboxs[i].checked && chboxs[i].value === itemp_id) {
                vis = 'block';
                break;
            }
        }
        document.getElementById(itemp_id).style.display = vis;

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
            <?php echo $form['form']; ?>
            <?php echo validation_errors(); ?>           
            <table id="tableStation" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" style="width:15%;">จุดขายตั๋ว</th>
                        <th class="text-center" style="width:50%;">จุดจอด</th>
                        <th class="text-center" style="width:20%;">เวลาที่ใช้ (นาที)</th>
                        <th class="text-center" style="width:10%"></th>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <input type="checkbox"  checked disabled="">  
                        </td>
                        <td>
                            <?php echo $form['Source'] ?>                           
                        </td> 
                        <td>
                            <input type="text" class="form-control"  value="" style="display: none;">
                        </td>
                    </tr>
                </thead>
                <tbody id="tableBodyStation">    
                    <?php echo $form['station'] ?>
<!--                    <tr>
                        <td class="text-center">                         
                            <input type="checkbox"  name="IsSaleTicket" onclick="ShowItemp('TravelTime1')" value="TravelTime1">                           
                        </td>
                        <td>
                            <input type="text" class="form-control" placeholder="ชื่อจุดจอด"  value=""> 
                        </td>
                        <td>
                            <input type="text" class="form-control" name="TravelTime[]" id="TravelTime1" value="TravelTime1" style="display: none;">
                        </td>
                        <td>
                            <a class="btn btn-danger btn-sm" onClick="RemoveRow(this)"><i class="fa fa-minus"></i></a>
                        </td>
                    </tr>-->
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-center">                           
                            <input type="checkbox"  checked disabled="">   
                        </td>
                        <td>
                            <?php echo $form['Destination'] ?>   
                        </td>
                        <td>
                            <input type="text" class="form-control" name=""  value="" style="display: none;">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <a name="btnAddSource"  id="btnAddSource" class="btn btn-sm" onClick="CreateNewRow();"><i class="fa fa-plus"></i>&nbsp;จุดจอด</a>
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