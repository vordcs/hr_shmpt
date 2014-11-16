<script language="javascript">
    $(document).ready(function () {
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
        newCell.setAttribute("class", "text-center");
        newCell.innerHTML = "<DIV CLASS=\"checkbox\"><INPUT TYPE=\"checkbox\" CLASS=\"form-control\" NAME=\"flat-checkbox-" + intLine + "\"  ID=\"flat-checkbox-" + intLine + "\" VALUE=\"\"></DIV>";

        //*** Column 2 ***//
        newCell = newRow.insertCell(1);
        newCell.id = newCell.uniqueID;

        newCell.innerHTML = "<INPUT TYPE=\"TEXT\" CLASS=\"form-control\" NAME=\"Column2_" + intLine + "\" ID=\"Column2_" + intLine + "\"  VALUE=\"\">";
        //*** Column 2 ***//
        newCell = newRow.insertCell(2);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("colspan", "4");

    }

    function CreateDestination()
    {
        var intLine = $('#tableStation tbody tr').length;
        intLine++;
        var theTable = document.getElementById("tableBodyStation");
        var newRow = theTable.insertRow(theTable.rows.length);
        newRow.id = newRow.uniqueID;

        var newCell;

        //*** Column 1 ***//
        newCell = newRow.insertCell(0);

        //*** Column 2 ***//
        newCell = newRow.insertCell(1);

        //*** Column 3 ***//
        newCell = newRow.insertCell(2);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("className", "text-center");
        newCell.innerHTML = "<center><INPUT TYPE=\"TEXT\" CLASS=\"form-control\" NAME=\"Column3_" + intLine + "\"  ID=\"Column3_" + intLine + "\" VALUE=\"\"></center>";
        //*** Column 4 ***//
        newCell = newRow.insertCell(3);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("className", "css-name");
        newCell.innerHTML = "<center><INPUT TYPE=\"TEXT\" CLASS=\"form-control\" NAME=\"Column4_" + intLine + "\"  ID=\"Column4_" + intLine + "\" VALUE=\"\"></center>";
        //*** Column 5 ***//
        newCell = newRow.insertCell(4);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("className", "css-name");
        newCell.innerHTML = "<INPUT TYPE=\"TEXT\" CLASS=\"form-control\" NAME=\"Column6_" + intLine + "\"  ID=\"Column1_" + intLine + "\" VALUE=\"\">";

        //*** Column 6 ***//
        newCell = newRow.insertCell(5);
        newCell.id = newCell.uniqueID;
        newCell.innerHTML = '<a class="btn btn-danger btn-sm" onClick="RemoveDes()"><i class="fa fa-minus"</a>';

    }


    function RemoveDes()
    {
        var intLine = $('#tableStation tbody tr').length - 1;
        if (parseInt(intLine) >= 0)
        {
            theTable = document.getElementById("tableStation");
            theTableBody = theTable.tBodies[0];
            theTableBody.deleteRow(intLine);
        }
    }
</script>

<div class="container">

    <div class="row">        
        <div class="page-header">
            <h3>               
                <?php echo $page_title; ?>
            </h3>
        </div>
    </div>

    <div class="row">
        <form id="frm_route_detail" class="form-horizontal" >   
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">ไป มุกดาหาร</h3>
                    </div>
                    <div class="panel-body">                        
                        <div class="form-group has-feedback">
                            <label for="" class="col-sm-3 control-label">เวลาเที่ยวแรก</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="" placeholder="">
                                <span class="fa fa-clock-o form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">เวลาห่าง</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" id="" placeholder="">
                            </div>
                            <div class="col-sm-1">
                                <p>นาที</p> 
                            </div>                            
                            <label for="" class="col-sm-2 control-label">จำนวน</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" id="" placeholder="">
                            </div>
                            <div class="col-sm-2">
                                <p>เที่ยว / วัน</p>  
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">ไป ขอนแก่น</h3>
                    </div>
                    <div class="panel-body">

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">จุดจอดและค่าโดยสาร</h3>
                    </div>
                    <div class="panel-body">
                        <table id="tableStation" class="table table-hover">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center" style="width:11%;">ขายตั๋ว</th>
                                    <th rowspan="2" class="text-center" style="width:20%;">ต้นทาง</th>
                                    <th rowspan="2" class="text-center" style="width:20%;">ปลายทาง</th>
                                    <th colspan="2" class="text-center" style="width:20%;">ค่าโดยสาร</th>
                                    <th rowspan="2"></th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>

                                    <th class="text-center" style="width:10%;">เต็ม</th>
                                    <th class="text-center" style="width:10%;">ลด</th>

                                </tr>
                            </thead>
                            <tbody id="tableBodyStation">
                                <tr>
                                    <td class="text-center">
                                        <div class="checkbox">
                                            <input type="checkbox"> 
                                        </div>
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
                                    <td>
                                        <input type="text" class="form-control"> 
                                    </td>
                                    <td>
                                        <a class="btn btn-danger btn-sm"><i class="fa fa-minus"</a>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td>
                                        <a name="btnAddSource"  id="btnAddSource" class="btn btn-block" onClick="CreateNewRow();"><i class="fa fa-plus"></i>&nbsp;ต้นทาง</a>
                                    </td>
                                    <td>                                       
                                        <a name="btnAddDes" id="btnAddDes" class="btn btn-block" onClick="CreateDestination()"><i class="fa fa-plus"></i>&nbsp;ปลายทาง </a>
                                    </td>

                                </tr>
                            </tfoot>
                        </table>
                        <div class="col-md-12">
                            <a class="btn btn-danger btn-sm" onClick="RemoveDes()"><i class="fa fa-minus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

