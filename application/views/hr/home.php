<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnHR").addClass("active");

        $('#myTab a[href="#tabEmployees"]').tab('show') // Select tab by name

    });
</script>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Library</a></li>
                <li class="active">Data</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>ระบบบริหารงานบุคคล <small>Subtext</small></h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist" id="myTab">
                <li><a href="#tabEmployees" role="tab" data-toggle="tab">พนักงาน</a></li>
                <li><a href="#tabGenaralInfo" role="tab" data-toggle="tab">ข้อมูลหลัก</a></li>
                <li><a href="#tabHistroyWork" role="tab" data-toggle="tab">ประวัติการทำงาน</a></li>
                <li><a href="#tabRolePermission" role="tab" data-toggle="tab">กำหนดสิทธิ์การเข้าใช้</a></li>
                <li><a href="#tabUserName" role="tab" data-toggle="tab">ข้อมูลการเข้าใช้ระบบ</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">

                <div class="tab-pane" id="tabEmployees">
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-search"></i>&nbsp;&nbsp;ค้นหาพนักงาน</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tabGenaralInfo">
                    <br>
                    <div class="row">
                        <div class="col-sm-6" >
                            <div id="panalPositions" class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">ตำแหน่งงาน</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>ชื่อตำแหน่ง</th>
                                                        <th>จำนวนพนักงาน</th>
                                                        <th></th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            ชื่อตำแหน่งงาน
                                                        </td>
                                                        <td>
                                                            10
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#frm_position"><i class="fa fa-pencil"></i></button>
                                                            <a class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-sm-12">
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#frm_position">
                                                <i class="fa fa-plus"></i>&nbsp;&nbsp;เพิ่มตำแหน่งงาน
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6" >
                            <div id="panalPositions" class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">คำนำหน้าชื่อ</h3>
                                </div>
                                <div class="panel-body">
                                    Panel content
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tabHistroyWork">
                    <br>
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1" >
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-search"></i>&nbsp;ค้นหา</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12" >
                                            <form id="frm_search_emp_history" class="form-horizontal" role="form">
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">รหัสพนักงาน</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="" placeholder="รหัสพนักงาน">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputPassword3" class="col-sm-3 control-label">เลขประจำตัวประชาชน</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="" placeholder="เลขประจำตัวประชาชน">
                                                    </div>
                                                </div> 
                                                <div class="form-group">
                                                    <div class="col-sm-offset-3 col-sm-8">
                                                        <div class="col-sm-6">
                                                            <label for="" class="col-sm-2 control-label">ตั้งแต่</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" id="" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label for="" class="col-sm-2 control-label">ถึง</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" id="" placeholder="">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div> 
                                                <div class="form-group">
                                                    <div class="col-sm-offset-3 col-sm-8 text-center">
                                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i>&nbsp;ค้นหา</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php for ($i = 0; $i < 3; $i++) { ?>
                                        <h3>ชื่อตำแหน่งงาน <?= $i ?></h3>

                                        <div class="panel-group" id="accordion<?= $i ?>">
                                            <div class="panel panel-default">
                                                <?php for ($j = 0; $j < 3; $j++) { ?>
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a data-toggle="collapse" data-parent="#accordion<?= $i ?>" href="#collapse<?= $i . "-" . $j ?>">
                                                                Collapsible Group Item #<?= $i . "-" . $j ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse<?= $i . "-" . $j ?>" class="panel-collapse collapse">
                                                        <div class="panel-body">
                                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                                        </div>
                                                    </div>                                                                                      
                                                <?php } ?>
                                            </div>  
                                        </div>

                                    <?php } ?>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tabRolePermission">
                    <div class="row">
                        <div class="col-sm-12" >

                        </div>

                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
<hr>
<!-- Modal frm_position -->
<div class="modal fade" id="frm_position" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <p>เพิ่มตำแหน่งงาน</p>
                    <p>แก้ไขแหน่งงาน</p>
                </h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <label for="InputPositionName">ชื่อตำแหน่งงาน</label>
                        <input type="text" class="form-control" id="PositionName" placeholder="ชื่อตำแหน่งงาน">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>