<html>
    <head>
        <style>
            body {width:100%;}
            .q1, .q2, .q3, .q4 {overflow:hidden; display:block; float:left;}
            .q1 {width:50px; height: 30px;}
            .q2 {width:300px; height: 30px;}
            .q3 {width:50px; height: 100px;}
            .q4 {width:300px; height: 100px; overflow:auto;}

            .q2 .firstCol, .q3 thead, .q4 thead, .q4 .firstCol {display:none;}
            .q2 td {background-color:#999;}
            .q3 td {background-color:#999;}
            .container {width:9999px;}

        </style>

        <script src="http://code.jquery.com/jquery-1.7.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.q4').bind('scroll', fnscroll);
                $('.q2').html($('.q4').html());
                $('.q3').html($('.q4').html());
            });

            function fnscroll() {

                $('.q2').scrollLeft($('.q4').scrollLeft());
                $('.q3').scrollTop($('.q4').scrollTop());


            }

        </script>
    </head>


    <body>
        <div class="q1"><div class="container"></div></div>
        <div class="q2"><div class="container"></div></div>
        <div class="q3"><div class="container"></div></div>
        <div class="q4">
            <div class="container">
                <table>
                    <thead>
                        <tr>
                            <td class="firstCol"></td>
                            <td>Col</td>
                            <td>Col</td>
                            <td>Col</td>
                            <td>Col</td>
                            <td>Col</td>
                            <td>Col</td>
                            <td>Col</td>
                            <td>Col</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($c = 0; $c < 50; $c++) { ?>
                            <tr>
                                <td class="firstCol">Row</td>
                                <td>this is some content</td>
                                <td>hello world!<br/>This is good</td>
                                <td>Row</td>
                                <td>adjfljaf oj eoaifj </td>
                                <td>ajsdlfja oije</td>
                                <td>alsdfjaoj f</td>
                                <td>jadfioj</td>
                                <td>jalsdjf oai</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>