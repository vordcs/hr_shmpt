<div class="modal fade bs-example-modal-sm" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-title">Confirm</h4>
            </div>
            <div class="modal-body" id="modal-body">
                <div class="col-md-12">
                    <div class="modal-body-title">

                    </div>                    
                </div>
                <div class="col-md-12">
                    <div class="modal-body-content">

                    </div>                    
                </div>
            </div>
            <div class="modal-footer" align="center">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_no"><i class="fa fa-times fa-lg"></i>&nbsp;ไม่</button>
                <a href="#" class="btn btn-danger danger" id="btn_delete" ><i class="fa fa-trash-o fa-lg"></i>&nbsp;ลบ</a>
                <a href="#" class="btn btn-primary yes " id="btn_yes" ><i class="fa fa-check fa-lg"></i>&nbsp;ใช่</a>
            </div>
        </div>
    </div>
</div>

<ul class="nav pull-right scroll-top hidden" style="z-index: 1;" id="scroll-top">
    <li><a href="#" id="top" style="background-color: transparent;"><i class="fa fa-arrow-circle-up fa-3x"></i></a></li>
</ul>
<footer class="th-footer hidden-print hidden">

</footer>
<div class="th-footer-bottom hidden">COPYRIGHT &copy; 2014</div>


<script>
    jQuery(document).ready(function ($) {
        $('#top').click(function () {
            $("html, body").animate({scrollTop: 0}, 500);
        });
    });
    $('#confirm').on('show.bs.modal', function (e) {
        var id = $(e.relatedTarget).data('id');
        var title = $(e.relatedTarget).data('title');
        var sub_title = $(e.relatedTarget).data('sub_title');
        var info = $(e.relatedTarget).data('info');
        var content = $(e.relatedTarget).data('content');
        var form = $(e.relatedTarget).data('form_id');
        var url = '<?= base_url() ?>' + $(e.relatedTarget).data('href');

        $('.modal-title').html('<i class="fa fa-info-circle fa-lg"></i> คุณต้องการ <strong>' + title + '</strong>');
        $('.modal-body-title').html('<strong>' + sub_title + '</strong> : ' + info + '');
        $('.modal-body-content').html(content);
        if (id === 1) //edit
        {
            $('#btn_yes').show();
            $('#btn_delete').hide();
            $(this).find('.yes').attr('href', $(e.relatedTarget).data('href'));

        } else if (id === 2) //delete 
        {
            $('#btn_yes').hide();
            $('#btn_delete').show();
            $(this).find('.danger').attr('href', url);

        }
        else if (id === 3) //cancle
        {
            $('#btn_yes').show();
            $('#btn_delete').hide();
            $(this).find('.yes').attr('href', $(e.relatedTarget).data('href'));

        } else if (id === 4) //active
        {
            $('#btn_yes').show();
            $('#btn_delete').hide();
            $(this).find('.yes').attr('href', $(e.relatedTarget).data('href'));

        } else if (id === 5)//submit form
        {
            $('#btn_yes').show();
            $('#btn_delete').hide();

            $("#btn_yes").on('click', function () {
                $('#' + form).submit();
            });
        }
    });
</script>

</body>
</html>