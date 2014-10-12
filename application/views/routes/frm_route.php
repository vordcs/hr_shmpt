<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnRoute").addClass("active");
    });
</script>

<div class="container">
    <div class="row">        
        <div class="page-header">
            <h2>
                เพิ่มเส้นทางเดินรถ&nbsp;
                <small>รถตู้ รถบัส</small>
                <br>
                แก้ไขข้อมูลเส้นทาง : 234 ขอนแก่น มุกดาหาร
                <small>รถตู้ รถบัส</small>
            </h2>
        </div>
    </div>
    <form id="frm_route">
        <div class="row">
            <div class="col-md-4">
                <div class="row">                
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">ไป มุกดาหาร</h3>
                        </div>
                        <div class="panel-body">
                            Panel content
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">ไป ขอนแก่น</h3>
                        </div>
                        <div class="panel-body">
                            Panel content
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">จุดจอดและค่าโดยสาร</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:11%;">ขายตั๋ว</th>
                                    <th class="text-center" style="width:50%;">จุดจอด</th>
                                    <th class="text-center" style="width:20%;">ค่าโดยสาร(บาท)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox"> 
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
                        </table>
                        <div class="col-md-12">
                            <button class="btn btn-success btn-lg pull-right"><i class="fa fa-plus"></i>&nbsp;เพิ่มจุดจอด</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

