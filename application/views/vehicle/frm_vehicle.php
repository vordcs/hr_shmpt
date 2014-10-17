<style>
    #list-driver {                    
        max-height: 310px;
        overflow-y: scroll;                   
    }
</style>
<script>
    jQuery(document).ready(function ($) {
        $("#mainmenu ul li").removeAttr('class');
        $("#btnVehicle").addClass("active");

        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'dd/mm/yyyy'
        });
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
                <li class="active datepicker">Data</li>
            </ul>
            <div class="page-header">
                <h1>
                    <p>เพิ่มข้อมูล รถ เส้นทาง 237 ขอนแก่น - อำนาจเจริญ</p>
                    <p>แก้ไขข้อมูล รถเบอร์ XXXXXX เส้นทาง 237 ขอนแก่น - อำนาจเจริญ</p>     
                </h1>
            </div>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12">
            <div class="well">
                <input type="text" class="datepicker" value="" >
            </div>
            <div class="well">
                <input type="text" class="datepicker" value="" >
            </div>
        </div>
    </div>
    <div class="row"> 
        <form class="form-horizontal" role="form">
            <div class="row">
                <div class="col-md-6">
                    <div id="panalVehicleDetail" class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">ข้อมูลรถ</h3>
                        </div>
                        <div class="panel-body" style="min-height: 408px;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">                                  
                                        <label for="" class="col-sm-4 control-label">เลขทะเบียน</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputEmail3" placeholder="">
                                        </div>  
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">เบอร์รถ</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputEmail3" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">สีรถ</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputEmail3" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">ยี่ห้อ</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputEmail3" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">ประเภทรถ</label>
                                        <div class="col-sm-5">
                                            <select class="form-control">
                                                <option>เลือกประเภทรถ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">จำนวนที่นั่ง</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="" placeholder="">
                                        </div>
                                        <div class="col-sm-2">
                                            ที่
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">สถานะรถ</label>
                                        <div class="col-sm-5">
                                            <select class="form-control">
                                                <option>เลือกสถานะรถ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>                         
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">ข้อมูลพนักงานขับรถ</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center thumbnail">
                                        <img src="http://placehold.it/200x200" class="img-responsive">
                                        <div class="caption">
                                            <h4>ชื่อพนักงานขับรถ</h4> 
                                            <p>
                                                ใบอนุญาติขับขี่รถ เลขที่                                            
                                            </p>
                                            <p>
                                                <b>ท.2 ขก.00614/34 </b>
                                            </p>
                                            <p>
                                                หมดอายุ 18/08/57
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" >
                                    <h5>รายชื่อพนักงานขับรถ</h5>
                                    <div id="list-driver"> 
                                        <div class="list-group">
                                            <?php
                                            for ($x = 0; $x <= 30; $x++) {
                                                ?>                                        
                                                <a href="#" class="list-group-item">
                                                    <h5 class="list-group-item-heading">นาย aaaaaa กกกกกกกก</h5>
                                                    <p class="list-group-item-text">...</p>
                                                </a>                                        
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>                        
                        </div>
                    </div>
                </div> 
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div id="panalInsurance" class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">ข้อมูลทะเบียน,ประกันและพรบ</h3>
                        </div>
                        <div class="panel-body">                       
                            <div class="row">
                                <div class="col-md-6">
                                    <h3>ทะเบียน</h3>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" role="form">
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">วันที่ต่อทะเบียน</label>
                                                    <div class="col-sm-5">
                                                        <input type="text" class="form-control" id="" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">วันหมดอายุ</label>
                                                    <div class="col-sm-5">
                                                        <input type="text" class="form-control" id="" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="col-sm-3 control-label">หมายเหตุ</label>
                                                    <div class="col-sm-8">
                                                        <textarea class="form-control" rows="3"></textarea>
                                                    </div>
                                                </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3>ประกันและพรบ</h3>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">บริษัทประกัน</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="" placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">ประเภทกรมธรรม์</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="" placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">วันที่เริ่มกรมธรรม์</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control datepicker" id="" placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">วันสิ้นสุดกรมธรรม์</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="" placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">เลขที่กรมธรรม์</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="" placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-3 control-label">หมายเหตุ</label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                 

                        </div>
                    </div>  
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <a class="btn btn-primary">บันทึก</a>
                    <a href="<?= base_url('vehicle/') ?>" class="btn btn-danger"><i class="fa fa-times"></i>&nbsp;ยกเลิก</a>
                </div> 
            </div>
        </form>
    </div>
    <hr>
</div>