<script>
    jQuery(document).ready(function($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnVehicle").addClass("active");
    });
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li>
                    <a href="#">Home</a>
                </li>
                <li>
                    <a href="#">Library</a>
                </li>
                <li class="active">Data</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>ระบบจัดการรถ
                    <font color="#777777">
                    <span style="font-size: 23px; line-height: 23.399999618530273px;">รถแต่ละเส้นทาง</span>
                    </font>
                </h1>
            </div>

        </div>
    </div> 
    <div class="row">  
        <div class="col-sm-10 col-sm-offset-1">
            <div id="panalSearchVehicle" class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search"></i>&nbsp;ค้นหารถ</h3>
                </div>
                <div class="panel-body">
                    <form role="form">
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-4">
                                <label class="control-label" for="">ประเภทรถ</label>
                                <select class="form-control">
                                    <option>ทั้งหมด</option>                           
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label" for="">เส้นทาง</label>
                                <select class="form-control">
                                    <option>ทั้งหมด</option>                           
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-5">
                                <label class="control-label" for="">เบอร์รถ</label>
                                <input class="form-control" id=""  placeholder="" type="text">
                            </div>              
                            <div class="col-sm-5">
                                <label class="control-label" for="exampleInputEmail1">ทะเบียนรถ</label>
                                <input class="form-control" id=""  placeholder="" type="text">
                            </div>              
                            <div class="col-sm-1"></div>
                        </div>
                        <br>
                        <div class="row text-center">
                            <button type="submit" class="btn btn-default">ค้นหา</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 well">
            <h1>รถตู้</h1>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title text-primary">สาย 237 ขอนแก่น - กาฬสินธุ์</h3>

                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>เบอร์รถ</th>
                                <th>ทะเบียนรถ</th>
                                <th>พนักงานขับรถ</th>
                                <th>สถานะ</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                                <td>
                                    <a class="btn btn-info btn-xs" draggable="true"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger btn-xs" draggable="true"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>   
                             <tr>
                                <td>2</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                                <td>
                                    <a class="btn btn-info btn-xs" draggable="true"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger btn-xs" draggable="true"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="<?= base_url('vehicle/add') ?>" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus fa-fw"></i>&nbsp;&nbsp;รถ</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> 
    <div class="row">
        <div class="col-md-12 well">
            <h1>รถบัส</h1>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title text-primary">สาย 237 ขอนแก่น - กาฬสินธุ์</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>เบอร์รถ</th>
                                <th>ทะเบียนรถ</th>
                                <th>พนักงานขับรถ</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>                            
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="<?= base_url('vehicle/add') ?>" class="btn btn-sm btn-success"><i class="fa fa-plus fa-fw"></i>&nbsp;&nbsp;รถ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>