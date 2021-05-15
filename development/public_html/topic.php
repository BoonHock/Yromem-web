<?php

// require_once '../php/conn.php';
require_once '../db/topic.php';
require_once '../db/chapter.php';

if (empty($_GET['tid'])) {
    header('location: /');
    exit;
}

$tid = $_GET['tid'];

if (!is_numeric($tid)) {
    // topic id must be numeric
    header('location: /404.html');
    exit;
}

if ($topic = get_topic($tid)) {
    // topic ok
} else {
    // no such topic
    header('location: /404.html');
    exit;
}

$r_chapter = get_chapters($tid);

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
    <link href="css/topic.css" rel="stylesheet">
</head>

<body>
    <?php include('../includes/nav.html'); ?>
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <h2 class="d-inline-block mr-3"><?php echo $topic['topic_name'] ?></h2>
                    <button type="button" class="btn btn-success btn-sm" id="play_btn">
                        <i class="fas fa-play mr-3"></i>Play
                    </button>
                    <div class="small text-danger ml-1 d-none" id="no_chapter_selected">
                        Please select at least one chapter
                    </div>
                </div>
                <p class="lead"><?php echo $topic['description'] ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled ul_chapter">
                    <li class="mb-2">
                        <label for="select_all_chapter" class="w-100 check_label">
                            <input type="checkbox" id="select_all_chapter" class="fa_check_input d-none">
                            <span class="d-flex">
                                <span><i class="fas fa-check-circle mr-3"></i></span>
                                <span class="d-flex flex-column">
                                    <span>Select all chapters</span>
                                </span>
                            </span>
                        </label>
                    </li>
                    <?php foreach ($r_chapter as $chapter) {
                        $cid = $chapter['chapter_id']; ?>
                        <li class="mb-2 ml-3">
                            <label for="c<?php echo $cid; ?>" class="w-100 check_label">
                                <input type="checkbox" id="c<?php echo $cid; ?>" value="<?php echo $cid; ?>" class="fa_check_input chk_chapter d-none">
                                <span class="d-flex">
                                    <span class="align-self-center"><i class="fas fa-check-circle mr-3"></i></span>
                                    <span class="d-flex align-self-center flex-column">
                                        <span><?php echo $chapter['chapter_name'] ?></span>
                                        <span class="d-block small"><?php echo $chapter['description'] ?></span>
                                    </span>
                                </span>
                            </label>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal_game" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Choose a game mode</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-deck mb-3">
                        <a class="card game_mode_card" data-mode="standard" href="game.php?mode=standard&tid=<?php echo $tid; ?>">
                            <div class="card-body">
                                <h5 class="card-title">Standard</h5>
                                <p class="card-text">
                                    Answer questions with no time limit.
                                </p>
                            </div>
                        </a>
                        <a class="card game_mode_card" data-mode="sprint" href="game.php?mode=sprint&tid=<?php echo $tid; ?>">
                            <div class="card-body">
                                <h5 class="card-title">Sprint</h5>
                                <p class="card-text">
                                    Answer as many questions as possible within one minute!
                                </p>
                            </div>
                        </a>
                        <a class="card game_mode_card" data-mode="millionaire" href="game_millionaire.html?tid=<?php echo $tid; ?>">
                            <div class="card-body">
                                <h5 class="card-title">Millionaire</h5>
                                <p class="card-text">
                                    Answer 15 questions correctly to win ONE MILLION DOLLARS! The money is not real though...
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var topic_id = <?php echo $topic['topic_id']; ?>;

        $(document).ready(function() {
            var select_all_chapter = $('#select_all_chapter');
            var chk_chapters = $('.chk_chapter');

            select_all_chapter.on('change', function() {
                var checked = this.checked;
                chk_chapters.each(function(index, elem) {
                    elem.checked = checked;
                });
            });

            chk_chapters.on('change', function() {
                if (!this.checked) {
                    select_all_chapter[0].checked = false;
                } else {
                    var has_unchecked = false;
                    chk_chapters.each(function(index, elem) {
                        if (!elem.checked) has_unchecked = true
                    });
                    select_all_chapter[0].checked = !has_unchecked;
                }
            });

            $('#play_btn').on('click', function() {
                var has_checked_chapter = false;

                chk_chapters.each(function(index, elem) {
                    if (elem.checked) has_checked_chapter = true;
                });

                if (!has_checked_chapter) {
                    $('#no_chapter_selected').removeClass('d-none');
                } else {
                    var selected_chapters = [];

                    chk_chapters.each(function(index, elem) {
                        if (elem.checked) {
                            selected_chapters.push(parseInt($(this).val()));
                        }
                    });

                    var chapter_id_join = selected_chapters.join(",");
                    $('a[data-mode]').val(function(index, value) {
                        var game_mode = $(this).attr('data-mode');

                        if (game_mode === 'millionaire') {
                            $(this).attr('href', encodeURI('game_millionaire.html?tid=' + topic_id + '&chapter=' + chapter_id_join));
                        } else {
                            $(this).attr('href', encodeURI('game.php?tid=' + topic_id + '&chapter=' + chapter_id_join + '&mode=' + $(this).attr('data-mode')));
                        }
                    });
                    $('#modal_game').modal();
                }
            });
        });
    </script>
</body>

</html>