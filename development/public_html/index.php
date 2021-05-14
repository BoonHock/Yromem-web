<?php

require_once '../db/topic.php';

$r_topics = get_topics();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Yromem</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://kit.fontawesome.com/c55d52918e.js" crossorigin="anonymous"></script>

    <link href="css/main.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
</head>

<body>
    <?php include('nav.html'); ?>
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <h4 class="section_title font-weight-bold font-italic mb-4 d-flex">SPM</h4>
                <?php foreach ($r_topics as $topic) { ?>
                    <a class="card border-primary mb-3" href="topic.php?tid=<?php echo $topic['topic_id'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $topic['topic_name'] ?></h5>
                            <p class="card-text"><?php echo $topic['description'] ?></p>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>