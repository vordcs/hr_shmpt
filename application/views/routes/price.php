<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");

    });

    function CreateNewRow()
    {
        var intLine = $('#tableStation tbody tr').length;
        intLine++;
        var theTable = document.getElementById("tableBodyStation");
        var newRow = theTable.insertRow(theTable.rows.length);
        newRow.id = newRow.uniqueID;

        var newCell;

        //*** Column 1 ***//
        newCell = newRow.insertCell(0);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("className", "text-center");
        newCell.innerHTML = "<center><INPUT TYPE=\"TEXT\" CLASS=\"form-control\" NAME=\"Column1_" + intLine + "\"  ID=\"Column3_" + intLine + "\" VALUE=\"\"></center>";
        //*** Column 2 ***//
        newCell = newRow.insertCell(1);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("className", "text-center");
        newCell.innerHTML = "<center><INPUT TYPE=\"TEXT\" CLASS=\"form-control\" NAME=\"Column2_" + intLine + "\"  ID=\"Column3_" + intLine + "\" VALUE=\"\"></center>";
        //*** Column 3 ***//
        newCell = newRow.insertCell(2);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("class", "");
        newCell.innerHTML = "<center><INPUT TYPE=\"TEXT\" CLASS=\"form-control\" NAME=\"Column3_" + intLine + "\"  ID=\"Column3_" + intLine + "\" VALUE=\"\"></center>";
        //*** Column 4 ***//
        newCell = newRow.insertCell(3);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("className", "css-name");
        newCell.innerHTML = "<center><INPUT TYPE=\"TEXT\" CLASS=\"form-control\" NAME=\"Column4_" + intLine + "\"  ID=\"Column4_" + intLine + "\" VALUE=\"\"></center>";

        //*** Column 5 ***//
        newCell = newRow.insertCell(4);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("class", "text-center");
        newCell.innerHTML = '<a class="btn btn-danger btn-sm" onClick="RemoveRow(this)"><i class="fa fa-minus"</a>';

    }
    function RemoveRow(row)
    {
        var r = row.parentNode.parentNode.rowIndex;
        document.getElementById('tableStation').deleteRow(r);
        console.log(r);
    }

</script>

<div class="container">
    <br>
    <div class="row">        
        <div class="page-header">
            <h3>
                <?php echo $page_title ?>
                <br>
                <font color="#777777">
                <span style="font-size: 23px; line-height: 23.399999618530273px;"><?php echo $page_title_small ?></span>                
                </font>
            </h3>
        </div>
    </div>
    <div class="row">    
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">จุดจอดและค่าโดยสาร</h3>
                </div>
                <div class="panel-body">
                    <table id="tableStation" class="table table-hover table-bordered">
                        <thead>
                            <tr>                                    
                                <th rowspan="2" class="text-center" style="width:30%;">ต้นทาง</th>
                                <th rowspan="2" class="text-center" style="width:30%;">ปลายทาง</th>
                                <th colspan="2" class="text-center" style="width:30%;">ค่าโดยสาร</th>
                                <th rowspan="2" style="width:10%;"></th>                                    
                            </tr>
                            <tr>
                                <th class="text-center" style="width:10%;">เต็ม</th>
                                <th class="text-center" style="width:10%;">ลด</th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyStation">
                            <tr>                                   
                                <td>
                                    <input type="text" class="form-control"> 
                                </td>
                                <td>
                                    <input type="text" class="form-control"> 
                                </td>
                                <td>
                                    <input type="text" class="form-control"> 
                                </td>
                                <td>
                                    <input type="text" class="form-control"> 
                                </td>                                    
                                <td class="text-center">
                                    <a class="btn btn-danger btn-sm" onclick="RemoveRow(this)" ><i class="fa fa-minus"></i></a>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>                                   
                                <td colspan="5" class="text-center">
                                    <a name="btnAddPoint"  id="btnAddPoint" class="btn btn-success" onClick="CreateNewRow();"><i class="fa fa-plus"></i>&nbsp;ต้นทาง</a>   
                                </td>
                            </tr>
                        </tfoot>
                    </table>                      
                </div>
            </div>
        </div>
    </div>
    <div class="row" >
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">จุดขายตั๋ว</h3>
                </div>
                <div class="panel-body">
                    <div class="checkbox">
                        <input type="checkbox"> 12444121
                    </div>                 
                </div>
            </div>
        </div>
    </div>
</div>

