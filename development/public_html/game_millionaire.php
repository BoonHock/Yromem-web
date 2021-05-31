<?php

require_once '../db/topic.php';

if (empty($_GET['tid']) || empty($_GET['chapter'])) {
    header('Location: /');
    exit;
}

$tid = $_GET['tid'];
// $arr_chapter = array_map('intval', explode(',', $_GET['chapter']));

if (!($topic = get_topic($tid))) {
    header('Location: 404.html');
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo $topic['topic_name']; ?></title>
    <meta name="description" content="Making learning fun and exciting." />
    <meta name="og:title" content="Yromem - <?php echo $topic['topic_name']; ?>" />
    <meta name="og:description" content="Making learning fun and exciting." />
    <meta name="og:image" content="img/logo_round.png" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-MTEPBP5X9W"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-MTEPBP5X9W');
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Zetta&display=swap" rel="stylesheet">

    <link href="css/main_millionaire.css" rel="stylesheet">

    <style>
        /* PAYOUT STYLES */

        .icon_container {
            display: inline-block;
            position: relative;
            text-align: center;
            margin: 0 2em;
        }

        .lifeline {
            display: inline-flex;
            border: 2px solid white;
            border-radius: 50%;
            width: 60px;
            height: 30px;
            align-items: center;
            justify-content: center;
            margin-left: 5px;
            margin-right: 5px;
            background-color: #040526;
            /* background-image: linear-gradient(#2c2f4e, #663694, #2c2f4e); */
        }

        @media (min-width: 768px) {
            .lifeline {
                width: 100px;
                height: 50px;
            }
        }

        .lifeline_button:not(.lifeline_used):hover {
            cursor: pointer;
            background-color: #e3901f;
            color: black;
        }

        .lifeline_button.lifeline_used {
            position: relative;
            border-color: #263f56;
        }

        .lifeline_used::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background-color: black;
            opacity: 0.7;
            border-radius: 50%;
        }

        .lifeline_icon {
            font-size: 1.2em;
            vertical-align: middle;
        }

        @media (min-width: 768px) {
            .lifeline_icon {
                font-size: 2.5em;
            }
        }

        #payout_table {
            width: auto;
            font-size: 0.9em;
            font-weight: 700;
            font-family: 'Lexend Zetta', sans-serif;
        }

        @media (min-width: 768px) {
            #payout_table {
                font-size: 1.3em;
            }
        }

        #payout_table td {
            padding: 0.1em 0.5em;
            color: #e3901f;
        }

        #payout_table .active td {
            background-color: #e3901f;
            color: #000000;
        }

        #payout_table td:first-child {
            text-align: right;
        }

        #payout_table tr .material-icons {
            visibility: hidden;
            vertical-align: middle;
        }

        #payout_table tr.active .material-icons,
        #payout_table tr.answered .material-icons {
            visibility: visible;
        }
    </style>
    <style>
        /* GAME STYLES */

        #question_text {
            display: flex;
            align-items: center;
            background-color: #040526;
            border: 2px solid #f6f6f6;
            padding: 0.7em;
        }

        #question_text,
        .answer_button {
            font-size: 0.9em;
        }

        .answer_button.long_answer {
            font-size: 0.8em;
        }

        @media (min-width: 768px) {
            #question_text {
                margin-left: 1em;
                margin-right: 1em;
                padding: 2em;
                font-size: 1.2em;
                min-height: 150px;
            }

            #question_text,
            .answer_button {
                font-size: 1em;
            }
        }

        .answer_button:before {
            content: "•";
            margin-right: 1em;
        }

        .answer_button>.answer_alphabet {
            color: #e9be48;
        }

        .answer_button.lifeline_5050_hide {
            cursor: default;
        }

        .answer_button.lifeline_5050_hide {
            animation: fade_out_answer 0.2s linear;
            animation-fill-mode: forwards;
        }

        @keyframes fade_out_answer {
            100% {
                opacity: 0;
            }
        }

        html {
            position: relative;
        }

        #navigate_to_payout_overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1;
        }
    </style>
    <style>
        /* https://css-tricks.com/how-to-create-an-animated-countdown-timer-with-html-css-and-javascript/ */
        #countdown_timer {
            display: inline-block;
        }

        .base-timer {
            position: relative;
            width: 60px;
            height: 60px;
        }

        .base-timer__label {
            position: absolute;
            width: 60px;
            height: 60px;
            top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1em;
        }

        @media (min-width: 768px) {
            .base-timer {
                width: 100px;
                height: 100px;
            }

            .base-timer__label {
                width: 100px;
                height: 100px;
                font-size: 1.5em;
            }
        }

        .base-timer__svg {
            transform: scaleX(-1);
        }

        .base-timer__circle {
            fill: #131c21;
            stroke: none;
        }

        .base-timer__path-elapsed {
            stroke-width: 7px;
            stroke: transparent;
        }

        .base-timer__path-remaining {
            stroke-width: 7px;
            stroke-linecap: round;
            transform: rotate(90deg);
            transform-origin: center;
            transition: 1s linear all;
            fill-rule: nonzero;
            stroke: currentColor;
        }

        .base-timer__path-remaining.green {
            color: #28a745;
        }

        .base-timer__path-remaining.orange {
            color: #ffc107;
        }

        .base-timer__path-remaining.red {
            color: #dc3545;
        }
    </style>
    <style>
        #millionaire_win {
            z-index: 99;
        }

        #millionaire_win,
        #win_banner_container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        #win_banner {
            display: block;
            background-color: red;
            width: 100%;
            text-align: center;
            font-size: 2.5em;
            padding-top: 1.5em;
            padding-bottom: 1.5em;
            /* -webkit-text-stroke: 0.01px black; */
            font-weight: 900;
            background: radial-gradient(#8a5fb3, #3a579c, #4f1881);
        }

        #close_win_banner {
            position: absolute;
            bottom: 50px;
            left: 0;
            right: 0;
            z-index: 1;
        }
    </style>
</head>

<body>
    <div id="navigate_to_payout_overlay" class="d-none"></div>
    <div id="game_title_container" class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center page_header"><?php echo $topic['topic_name']; ?></h1>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-12">
                <p><?php echo $topic['description'] ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div id="start_game" class="hexagon_container hexagon_nav mx-auto">START</div>
            </div>
        </div>
    </div>
    <div id="game_container" class="container" style="display:none">
        <div id="payout_layout">
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <div class="icon_container lifeline" data-lifeline="5050">
                        <span class="material-icons lifeline_icon">flaky</span>
                    </div>
                    <div class="icon_container lifeline" data-lifeline="switch">
                        <span class="material-icons lifeline_icon"> wifi_protected_setup</span>
                    </div>
                    <div class="icon_container lifeline" data-lifeline="timer_off">
                        <span class="material-icons lifeline_icon"> timer_off</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="payout_table" class="table table-borderless mx-auto">
                        <tbody>
                            <tr>
                                <td>15</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 1 MILLION</td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 500,000</td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 250,000</td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 125,000</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 64,000</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 32,000</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 16,000</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 8,000</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 4,000</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 2,000</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 1,000</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 500</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 300</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 200</td>
                            </tr>
                            <!-- <tr class="active">
                                    <td>2</td>
                                    <td>
                                        <span class="material-icons">circle</span>
                                    </td>
                                    <td>$ 200</td>
                                </tr> -->
                            <tr>
                                <td>1</td>
                                <td><span class="material-icons">circle</span></td>
                                <td>$ 100</td>
                            </tr>
                            <!-- <tr class="answered">
                                    <td>1</td>
                                    <td>
                                        <span class="material-icons">circle</span>
                                    </td>
                                    <td>$ 100</td>
                                </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p id="loading" class="text-center michroma_font larger_text mt-4 flashing">Loading...</p>
                    <div id="continue_game" class="hexagon_container hexagon_nav mx-auto d-none">CONTINUE</div>
                    <div id="new_game" class="hexagon_container hexagon_nav mx-auto d-none game_over_button">
                        NEW GAME
                    </div>
                    <a class="hexagon_container hexagon_nav mx-auto d-none game_over_button" href="topic.php?tid=<?php echo $_GET['tid'] ?>">
                        MAIN MENU
                    </a>
                </div>
            </div>
        </div>
        <div id="game_layout" style="display: none">
            <div class="row mb-1">
                <div class="col-12 text-center">
                    <div id="countdown_timer"></div>
                </div>
            </div>
            <div class="row mb-3 question_div">
                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2">
                    <div id="question_text">
                        <p class="m-0"></p>
                    </div>
                </div>
            </div>
            <div class="row mb-2 text-center">
                <div class="col-12 game_lifeline">
                    <div class="icon_container lifeline lifeline_button" data-lifeline="5050">
                        <span class="material-icons lifeline_icon">flaky</span>
                    </div>
                    <div class="icon_container lifeline lifeline_button" data-lifeline="switch">
                        <span class="material-icons lifeline_icon"> wifi_protected_setup</span>
                    </div>
                    <div class="icon_container lifeline lifeline_button" data-lifeline="timer_off">
                        <span class="material-icons lifeline_icon"> timer_off</span>
                    </div>
                </div>
            </div>
            <div class="row question_div">
                <div class="col-12 col-md-6">
                    <div class="hexagon_container answer_button ml-md-auto mr-md-0 mx-auto" id="answer0">
                        <span class="answer_alphabet">A:</span>&nbsp;&nbsp;<span class="answer_text">answer</span>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="hexagon_container answer_button mr-md-auto ml-md-0 mx-auto" id="answer1">
                        <span class="answer_alphabet">B:</span>&nbsp;&nbsp;<span class="answer_text">answer</span>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="hexagon_container answer_button ml-md-auto mr-md-0 mx-auto" id="answer2">
                        <span class="answer_alphabet">C:</span>&nbsp;&nbsp;<span class="answer_text">answer</span>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="hexagon_container answer_button mr-md-auto ml-md-0 mx-auto" id="answer3">
                        <span class="answer_alphabet">D:</span>&nbsp;&nbsp;<span class="answer_text">answer</span>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <div id="click_to_continue" class="text-center michroma_font flashing d-none">
                        Click to continue...
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="millionaire_win" class="d-none">
        <div id="win_banner_container" class="vertical_align_content">
            <div id="win_banner" class="michroma_font">MILLIONAIRE</div>
        </div>
        <div id="close_win_banner" class="hexagon_container hexagon_nav mx-auto">CLOSE</div>
    </div>
    <div id="btn_music" class="music_on">
        <span id="music_on" class="material-icons">music_note</span>
        <span id="music_off" class="material-icons">music_off</span>
        <div class="d-none">
            <audio src="audio/main_theme.mp3" preload="auto"></audio>
            <audio src="audio/lets_play.mp3" preload="auto"></audio>
            <audio src="audio/1_5.mp3" loop preload="auto"></audio>
            <audio src="audio/6_10.mp3" loop preload="auto"></audio>
            <audio src="audio/11.mp3" loop preload="auto"></audio>
            <audio src="audio/12_13.mp3" loop preload="auto"></audio>
            <audio src="audio/14.mp3" loop preload="auto"></audio>
            <audio src="audio/15.mp3" loop preload="auto"></audio>
            <audio src="audio/correct_answer.mp3" preload="auto"></audio>
            <audio src="audio/wrong_answer.mp3" preload="auto"></audio>
            <audio src="audio/short_intro.mp3" preload="auto"></audio>
        </div>
    </div>

    <!-- preload images -->
    <img src="img/text_hexagon_selected.svg" class="d-none">
    <img src="img/text_hexagon_correct.svg" class="d-none">

    <script src="js/ajax.js"></script>
    <script src="js/timer.js"></script>
    <script src="js/game.js"></script>
    <script src="js/cookie.js"></script>
    <script>
        const TIME_LIMIT_SECONDS = 30;
        const SOUND_COOKIE_NAME = 'sound_on';

        // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Math/random
        function getRandomIntInclusive(min, max) {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min + 1) + min); //The maximum is inclusive and the minimum is inclusive
        }

        function lifeline_5050(Question) {
            var answer_text = Question.a0;

            var answer_no = parseInt($(".answer_text").filter(function() {
                return $(this).html() === Question.a0;
            }).parent().attr('id').replace('answer', ''));

            var remove1;
            var remove2;

            do {
                remove1 = getRandomIntInclusive(0, 3)
                remove2 = getRandomIntInclusive(0, 3)
            }
            while (remove1 == answer_no || remove2 == answer_no || remove1 == remove2);

            // $('#answer' + remove1 + ',#answer' + remove2).animate({ opacity: 0 }, 200);
            $('#answer' + remove1 + ',#answer' + remove2)
                .addClass('lifeline_5050_hide');
        }

        function lifeline_switch(Question) {
            $('.question_div').fadeOut(function() {
                change_question(Question);
                $('.question_div').fadeIn();
            });
        }

        function change_question(Question) {
            var answers = shuffleArray([Question.a0, Question.a1, Question.a2, Question.a3]);
            $('#question_text > p').html(Question.question);
            reset_game_layout();

            for (var i = 0; i < answers.length; i++) {
                $('#answer' + i + '>.answer_text').html(answers[i]);

                // if answer text too long, need to shrink font size
                if (answers[i].length > 65) {
                    $('#answer' + i).addClass('long_answer');
                }
            }
        }

        function update_payout_table(question_index) {
            // clear
            $('#payout_table tr').removeClass('answered active');

            // 15 questions. 0-index so minus 1.
            // minus 1 again for current question index (0-index also)
            // so if current first question, 15-1-0 = 14th tr
            $('#payout_table tr:eq(' + (15 - 1 - question_index) + ')').addClass('active');

            // all previous questions as answered
            for (var i = 0; i < question_index; i++) {
                $('#payout_table tr:eq(' + (15 - 1 - i) + ')').addClass('answered').removeClass('active');
            }
        }

        function show_game(Question, timer) {
            change_question(Question);
            $('#payout_layout').fadeOut(function() {
                $('#game_layout').fadeIn(
                    function() {
                        timer.startTimer();
                    }
                )
            });
        }

        function show_payout(is_correct, question_index) {
            $(document).off('click');

            // question 15 means win d
            if (is_correct && question_index < 15) {
                update_payout_table(question_index)
            } else {
                if (!is_correct) {
                    // if question answered wrongly. highlight previous reward
                    update_payout_table(question_index - 1);
                }

                $('#continue_game').addClass('d-none');
                $('.game_over_button').removeClass('d-none');
            }
            $('#game_layout').fadeOut(function() {
                $('#navigate_to_payout_overlay, #click_to_continue').addClass('d-none');
                $('#payout_layout').fadeIn()
            });
        }

        function reset_game_layout() {
            $('.answer_button')
                .removeClass('selected correct lifeline_5050_hide');

            $('.long_answer').removeClass('long_answer');
        }

        function play_music(filename_wo_extension, require_reset, onEnd) {
            if ($('#btn_music').hasClass('music_off')) {
                return;
            }

            var playing_audio = null;

            $('#btn_music audio').each(function(index, elem) {
                if (!elem.pause) {
                    playing_audio = $(elem);
                }
            });
            var audio = $('#btn_music audio[src="audio/' + filename_wo_extension + '.mp3"]');

            if (audio == playing_audio) {
                return;
            }

            pause_all_music();

            if (audio.length > 0) {
                if (require_reset) {
                    audio[0].currentTime = 0;
                }
                audio[0].play();

                if (onEnd != null) {
                    audio.on('ended', function() {
                        onEnd();
                    });
                }
            }
        }

        function pause_all_music() {
            $('#btn_music audio').each(function(index, elem) {
                $(elem).off('ended');
                elem.pause();
            });
        }

        function play_music_based_on_question_index(question_index, require_reset) {
            if (question_index < 5) {
                play_music('1_5', require_reset);
            } else if (question_index < 10) {
                play_music('6_10', require_reset);
            } else if (question_index < 11) {
                play_music('11', require_reset);
            } else if (question_index < 13) {
                play_music('12_13', require_reset);
            } else if (question_index < 14) {
                play_music('14', require_reset);
            } else if (question_index == 14) {
                play_music('15', require_reset);
            }
        }

        $(document).ready(function() {
            var sound_cookie = get_cookie(SOUND_COOKIE_NAME);

            if (sound_cookie === null) {
                $('#btn_music').removeClass('music_off').addClass('music_on');
            } else {
                $('#btn_music').removeClass('music_on').addClass('music_off');
            }

            $('audio').each(function(index, elem) {
                // lower the volume. default is full volume which is very loud
                elem.volume = 0.5;
            });

            var used_lifeline = [];
            var CurrentQuestion;
            var question_index = 0;
            var questions = [];
            var spare_question;
            var timer;

            const urlParams = new URLSearchParams(window.location.search);
            const topic_id = urlParams.get('tid');
            const chapter_ids = urlParams.get('chapter').split(',');

            $('#start_game').on('click', function() {
                reset_game();
                play_music('short_intro', true, function() {
                    play_music('1_5', true);
                });
                $('#game_title_container').fadeOut(function() {
                    $('#game_container').fadeIn(function() {
                        var data = {
                            topic_id: topic_id,
                            chapter_ids: chapter_ids,
                            limit: 16
                        };

                        doAjax("get",
                            "ajax.php", {
                                get_questions_for_game: JSON.stringify(data)
                            },
                            function() {},
                            function(response) {
                                questions = JSON.parse(response);
                                spare_question = questions.pop();

                                $('#loading').addClass('d-none');
                                $('#continue_game').removeClass('d-none');
                            });
                    });
                });
            });

            $('#continue_game').on('click', function() {
                if (question_index == 0) {
                    play_music('lets_play', true, function() {
                        play_music('1_5', true);
                    });
                } else {
                    play_music_based_on_question_index(question_index);
                }

                if (questions.length == 0) {
                    alert('No questions for this topic...');
                } else {
                    CurrentQuestion = questions[question_index];
                    CurrentQuestion.answered = false;

                    timer = new CountdownTimer(TIME_LIMIT_SECONDS, function() {
                        end_question();
                    });

                    show_game(CurrentQuestion, timer);
                }
            });

            $("#new_game").on('click', function() {
                play_music('main_theme', true);
                $('#game_container').fadeOut(function() {
                    $('#game_title_container').fadeIn();
                });
            });

            $('.answer_button').on('click', function() {
                timer.stopTimer();
                var selected_answer = $(this);

                // if current question already answered or 
                // is hidden by 50:50 lifeline, do not respond
                if (CurrentQuestion.answered === true ||
                    selected_answer.hasClass('lifeline_5050_hide')) {
                    return;
                }

                selected_answer.addClass('selected');

                end_question();
            });

            $('.game_lifeline > .lifeline_button').on('click', function() {
                var data_lifeline = $(this).attr('data-lifeline');

                // already used
                if (used_lifeline.includes(data_lifeline)) {
                    return;
                }

                $('[data-lifeline="' + data_lifeline + '"]').addClass('lifeline_used');
                used_lifeline.push(data_lifeline);

                switch (data_lifeline) {
                    case "5050":
                        lifeline_5050(CurrentQuestion);
                        break;
                    case "switch":
                        questions[question_index] = spare_question;
                        CurrentQuestion = spare_question;
                        lifeline_switch(CurrentQuestion);
                        break;
                    case "timer_off":
                        timer.stopTimer();
                        break;
                }
            });

            $('#close_win_banner').on('click', function(e) {
                $('#millionaire_win').addClass('d-none');
            });

            function end_question() {
                CurrentQuestion.answered = true;
                $('#navigate_to_payout_overlay').removeClass('d-none');

                setTimeout(() => {
                    var is_correct = false;

                    $('.answer_button').each(function(index) {
                        if ($(this).children('.answer_text').html() == CurrentQuestion.a0) {
                            // highlight correct answer
                            $(this).addClass('correct');
                            if ($(this).hasClass('selected')) {
                                // answer correct
                                is_correct = true;
                                question_index++;
                            }
                        }
                    });

                    if (is_correct) {
                        if (question_index == 15) {
                            $('#millionaire_win').removeClass('d-none');
                            play_music('main_theme', true);
                        } else {
                            play_music('correct_answer',
                                true,
                                function() {
                                    play_music_based_on_question_index(question_index);
                                })
                        }
                    } else {
                        play_music('wrong_answer', true)
                    }

                    setTimeout(() => {
                        // show overlay so that all items behind blocked and answer buttons won't show hover pattern
                        $('#click_to_continue').removeClass('d-none');

                        $(document).on('click', function() {
                            if ($('#millionaire_win').hasClass('d-none')) {
                                // if millionaire banner showing, dont navigate to payout.
                                // let user manually close that first
                                show_payout(is_correct, question_index);
                            }
                        });
                    }, 2000);

                }, 1000);
            }

            function reset_game() {
                used_lifeline = [];
                CurrentQuestion = null;
                question_index = 0;
                $('#continue_game').addClass('d-none');
                $('.game_over_button').addClass('d-none');
                $('#loading').removeClass('d-none');
                $('.lifeline_used').removeClass('lifeline_used');

                update_payout_table(question_index);
            }

            $('#btn_music').on('click', function() {
                var btn_music = $(this);
                if (btn_music.hasClass('music_on')) {
                    set_cookie(SOUND_COOKIE_NAME, 'off', 365);
                    btn_music.removeClass('music_on').addClass('music_off');
                    pause_all_music();
                } else {
                    erase_cookie(SOUND_COOKIE_NAME);
                    btn_music.removeClass('music_off').addClass('music_on');
                    play_music_based_on_question_index(question_index);

                }
            });
        });
    </script>
</body>

</html>