<script>
    jQuery(document).ready(function($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnCandidate").addClass("active");
    });
</script>

<div class="container">
    <div class="row">        

        <div class="page-header">
            <h1>ผู้สมัครงาน&nbsp;
                <small>รอการอนุมัติ</small>
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-right"> 
           <a href="<?= base_url('candidate/add') ?>" class="btn btn-lg btn-success">เพิ่มผู้สมัครงาน</a>
        </div>
    </div>
    <div class="row animated bounceInUp">
        <div class="col-md-12 text-left">
            <div class="panel panel-info" style="margin: 10px 0px 4px 4px">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="glyphicon glyphicon-search"></span>ค้นหา</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form role="form">
                                <div class="form-group">
                                    <label class="control-label" for="exampleInputEmail1">ชื่อ</label>
                                    <input class="form-control" id="exampleInputEmail1" placeholder="Enter email" type="email">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form role="form">
                                <div class="form-group">
                                    <label class="control-label" for="exampleInputEmail1">ตำแหน่ง</label>
                                    <input class="form-control" id="exampleInputEmail1" placeholder="Enter email" type="email">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <a class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-search"></span>ค้นหา</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row animated bounceInLeft">
        <div class="col-md-12">
            <h2>ทั้งหมด</h2>
        </div>
    </div>
    <div class="row animated bounceInUp">
        <div class="col-md-12" style="">
            <table class="table table-hover" style="margin:15px 0px 4px 4px;">
                <thead>
                    <tr>
                        <th>เลขประจำตัวประชาชน</th>
                        <th>คำนำหน้าชื่อ</th>
                        <th>ชื่อ</th>
                        <th>นามสกุล</th>
                        <th>ตำแหน่งที่สมัคร</th>
                        <th>วันที่สมัคร</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td draggable="true">@mdo</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Larry</td>
                        <td>the Bird</td>
                        <td>@twitter</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
