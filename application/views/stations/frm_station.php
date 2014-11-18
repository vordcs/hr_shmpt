<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");
        $(".th-footer-bottom").addClass("hidden");

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
        newCell.innerHTML = '<div class="checkbox"><input type="checkbox" name="IsSaleTicket[]"></div>';

        //*** Column 2 ***//
        newCell = newRow.insertCell(1);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("class", "text-center");
        newCell.innerHTML = "<INPUT TYPE=\"TEXT\" CLASS=\"form-control\" NAME=\"StationName[]\">";

        //*** Column 3 ***//
        newCell = newRow.insertCell(2);
        newCell.id = newCell.uniqueID;
        newCell.setAttribute("class", "text-center");
        newCell.innerHTML = "<INPUT TYPE=\"TEXT\" CLASS=\"form-control\" NAME=\"TravelTime[]\">";

        //*** Column 4 ***//
        newCell = newRow.insertCell(3);
        newCell.id = newCell.uniqueID;
        newCell.innerHTML = '<a class="btn btn-danger btn-sm" onClick="RemoveRow(this)"><i class="fa fa-minus"></i></a>';


    }
    function RemoveRow(row)
    {
        var r = row.parentNode.parentNode.rowIndex;
        document.getElementById('tableStation').deleteRow(r);
        console.log(r);
    }
    function showMe(box) {

        var chboxs = document.getElementsByName("c1");
        var vis = "none";
        for (var i = 0; i < chboxs.length; i++) {
            if (chboxs[i].checked) {
                vis = "block";
                break;
            }
        }
        document.getElementById(box).style.display = vis;


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
            <div id="div1" style="display:none">
                <table border=1 id="t1">
                    <tr>
                        <td>i am here!</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-12">            

            <input type="checkbox" name="c1" onclick="showMe('div1')">Show Hide Checkbox

        </div>
        <div class="col-md-8 col-md-offset-2">
            <table id="tableStation" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" style="width:15%;">จุดขายตั๋ว</th>
                        <th class="text-center" style="width:50%;">จุดจอด</th>
                        <th class="text-center" style="width:20%;">เวลาที่ใช้</th>
                        <th class="text-center" style="width:10%"></th>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <div class="checkbox">
                                <input type="checkbox" checked="true" name="IsSaleTicket[]" value="<?= $route_detail['RSource'] ?>" disabled> 
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control" disabled name="StationName[]" value="<?= $route_detail['RSource'] ?>"> 
                        </td> 
                        <td></td>
                    </tr>
                </thead>
                <tbody id="tableBodyStation">
                    <tr>
                        <td class="text-center">
                            <div class="checkbox">
                                <input type="checkbox" name="salepoint[]" onclick="ShowItemp('TravelTime1')" value=""> 
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control"   value=""> 
                        </td>
                        <td>
                            <input type="text" class="form-control" name="TravelTime" id="TravelTime1"  value="" style="display: none"> 
                        </td>
                        <td>
                            <a class="btn btn-danger btn-sm" onClick="RemoveRow(this)"><i class="fa fa-minus"></i></a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-center">
                            <div class="checkbox">
                                <input type="checkbox" checked="true" name="IsSaleTicket[]" value="<?= $route_detail['RDestination'] ?>" disabled> 
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control" disabled="" name="StationName[]" value="<?= $route_detail['RDestination'] ?>"> 
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <a name="btnAddSource"  id="btnAddSource" class="btn btn-block" onClick="CreateNewRow();"><i class="fa fa-plus"></i>&nbsp;ต้นทาง</a>
                        </td>
                    </tr>               
                </tfoot>
            </table>
        </div>
    </div>
</div>