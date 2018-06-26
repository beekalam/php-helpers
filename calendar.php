<?php require_once "inc.php"; ?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

    <title>..</title>

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php
            if (isset($_GET['year']) && isset($_GET['month'])) {
                $year  = $_GET['year'];
                $month = $_GET['month'];
                $cal   = new Fa_Calendar($year, $month);
                build_links($month, $year);
            } else {
                $local_time = time();
                $year       = tr_num(jdate('Y', $local_time), 'en');
                $month      = tr_num(jdate('m', $local_time), 'en');
                $cal        = new Fa_Calendar($year, $month);
                $adjusted_date = $cal->adjusted_date;

                build_links($month,$year);
            }
            echo $cal->gen();

            ?>
        </div>
    </div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"
        integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T"
        crossorigin="anonymous"></script>
<script>

    $("document").ready(function () {
        $('[data-spy="scroll"]').each(function () {
            // var $spy = $(this).scrollspy('refresh')
            $(this).css('background', 'green');
        })
    });
</script>
</body>
</html>