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
    <meta name="description" content="Studying shouldn't be boring! Play quizzes in different modes to make your learning journey fun and enjoyable." />
    <meta name="og:title" content="Yromem - <?php echo $topic['topic_name']; ?>" />
    <meta name="og:description" content="Studying shouldn't be boring! Play quizzes in different modes to make your learning journey fun and enjoyable." />
    <meta name="og:image" content="img/favicon.ico" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

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
    <link href="css/game.css" rel="stylesheet">
    <link href="css/timer.css" rel="stylesheet">
    <script src="js/timer.js"></script>
</head>

<body class="pb-5">
    <?php include('../includes/nav.html'); ?>
    <div id="game_loading_spinner" class="spinner_container">
        <div class="mx-auto">
            <div class="spinner-grow"></div>
            <div class="spinner-grow"></div>
            <div class="spinner-grow"></div>
        </div>
    </div>
    <div id="game_layout" class="container">
        <div class="row mb-1">
            <div class="col-12 text-center">
                <div id="countdown_timer"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p>
                    <span id="question_index">1</span>
                    <span id="total_questions"> of <span id="total_questions_no">15</span></span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="jumbotron">
                    <div class="lead" id="question_text">Which substance is a covalent compound</div>
                </div>
            </div>
        </div>
        <div id="option_div" class="row">
            <div class="col-12 col-md-6">
                <div class="answer_button answer_button_layout ml-md-auto mr-md-0 mx-auto font-weight-bold" id="answer0" data-answer-option="0">
                    <span class="answer_text">answer</span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="answer_button answer_button_layout mr-md-auto ml-md-0 mx-auto font-weight-bold incorrect" id="answer1" data-answer-option="1">
                    <span class="answer_text">answer</span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="answer_button answer_button_layout ml-md-auto mr-md-0 mx-auto font-weight-bold" id="answer2" data-answer-option="2">
                    <span class="answer_text">answer</span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="answer_button answer_button_layout ml-md-auto mr-md-0 mx-auto font-weight-bold" id="answer3" data-answer-option="3">
                    <span class="answer_text">answer</span>
                </div>
            </div>
        </div>
        <div id="reveal_div" class="d-none">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div id="reveal_answer_text" class="answer_button_layout ml-md-auto mr-md-0 mx-auto font-weight-bold" data-answer-option="3">
                        <span class="answer_text">answer</span>
                    </div>
                </div>
            </div>
            <p class="lead mt-3 mb-4" id="reveal_answer_explanation">
                explanation explanationexplanation explanationexplanation explanationexplanation explanationexplanation
                explanationexplanation explanationexplanation explanationexplanation explanation
            </p>
            <button id="continue_button" class="btn btn-block btn-success pt-3 pb-3">
                Continue
            </button>
        </div>
    </div>
    <div class="container d-none" id="review_layout">
        <div class="row">
            <div class="col-12">
                <h4>Review</h4>
                <ol id="review_list">
                    <li data-template="review_list_item_template">
                        <p class="m-0 review_question">Which of the following does not affect the rate of reaction?</p>
                        <div class="review_list_answer">
                            <div data-template="review_answer_template" class="review_answer">
                                <p data-answer-option="0"><span class="answer_text">Volume of the solutionVolume of the
                                        solutionVolume of the
                                        solutionVolume of the solution</span></p>
                            </div>
                        </div>
                        <p class="jumbotron mb-0 review_explanation">
                            Volume of the solution only affects the quantity of product produced (MV/1000 = number
                            of moles)
                        </p>
                    </li>
                </ol>
                <a href="topic.php?tid=<?php echo $_GET['tid'] ?>" class="btn btn-info">Back to Menu</a>
            </div>
        </div>
    </div>
    <script src="js/ajax.js"></script>
    <script src="js/game.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (!urlParams.has("tid") || !urlParams.has("chapter")) {
            window.location.assign('/');
        }

        function reset_game_layout() {
            var classes_to_remove = ['answered', 'selected', 'incorrect'];

            $('#game_layout').find('.' + classes_to_remove.join(', .')).removeClass(classes_to_remove.join(' '));

            $('#option_div').removeClass('d-none');
            $('#reveal_div').addClass('d-none');
            $('#reveal_answer_text .answer_text').text('');
            $('#reveal_answer_explanation').text('');
        }

        function reset_review_list_item(li) {
            li.find('.review_question').text('');
            li.find('.answer_text').text('');
            li.find('.incorrect').removeClass('incorrect');
            li.find('.selected').removeClass('selected');

            li.find('[data-answer-option]').text('');
            li.find('.review_explanation').text('');
        }

        function change_question(Question, question_index) {
            $('#option_div').removeClass('answered');
            $('#question_text').html(Question.question);

            reset_game_layout();

            var answers = shuffleArray([Question.a0, Question.a1, Question.a2, Question.a3]);
            for (var i = 0; i < answers.length; i++) {
                $('#answer' + i + '>.answer_text').html(answers[i]);
            }

            $('#question_index').text(question_index + 1);
        }

        $(document).ready(function() {
            var timer;
            var is_sprint_mode = urlParams.has('mode') && urlParams.get('mode') === 'sprint';

            var review_list_item_template = $('[data-template="review_list_item_template"]')
                .clone().removeAttr('data-template');
            var review_answer_template = $('[data-template="review_answer_template"]')
                .clone().removeAttr('data-template');
            $('[data-template="review_answer_template"]').remove();
            $('[data-template="review_list_item_template"]').remove();

            var questions = [];
            var answered_correct_index = [];
            var CurrentQuestion;
            var question_index = 14;
            const topic_id = urlParams.get('tid');
            const chapter_ids = urlParams.get('chapter').split(',');

            start_game();

            function start_game() {
                var data = {
                    topic_id: topic_id,
                    chapter_ids: chapter_ids
                };

                question_index = 0; // reset

                get_questions_for_game(data, function() {
                    if (is_sprint_mode) {
                        $('#total_questions').remove();
                        timer = new CountdownTimer(60, function() {
                            game_ended();
                        });
                    } else {
                        $('#total_questions_no').text(questions.length);
                    }

                    CurrentQuestion = questions[question_index];

                    change_question(CurrentQuestion, question_index);

                    $('#game_loading_spinner').fadeOut(function() {
                        if (is_sprint_mode) {
                            timer.startTimer();
                        }
                    });
                });
            }

            function get_questions_for_game(data, callback) {
                doAjax("get",
                    "ajax.php", {
                        get_questions_for_game: JSON.stringify(data)
                    },
                    function() {},
                    function(response) {
                        questions = questions.concat(JSON.parse(response));

                        if (callback != null) {
                            callback();
                        }
                    });
            }

            $('.answer_button').on('click', function() {
                // if current question already answered do not respond
                if (CurrentQuestion.answered === true) {
                    return;
                }

                var selected_answer = $(this);
                var answer_text = selected_answer.children('.answer_text').text().toLowerCase();
                var data_answer_option;

                $('.answer_button').each(function(index) {
                    if ($(this).children('.answer_text').text() == CurrentQuestion.a0) {
                        data_answer_option = $(this).attr('data-answer-option');
                    }
                });

                selected_answer.addClass('selected');
                $('#option_div').addClass('answered');

                CurrentQuestion.answered = true;

                var is_correct = answer_text === CurrentQuestion.a0.toLowerCase();

                if (is_correct) {
                    answered_correct_index.push(question_index);
                }

                $('#reveal_answer_text .answer_text').html(CurrentQuestion.a0);
                $('#reveal_answer_text').attr('data-answer-option', data_answer_option);
                $('#reveal_answer_explanation').html(CurrentQuestion.explanation);

                $('#reveal_answer_text').addClass(is_correct ? 'selected' : 'incorrect');

                $('#option_div').addClass('d-none');
                $('#reveal_div').removeClass('d-none');

                update_review_layout(selected_answer.attr('data-answer-option'));
            });

            $('#continue_button').on('click', function() {
                if (question_index < questions.length - 1) {
                    CurrentQuestion = questions[++question_index];
                    change_question(CurrentQuestion, question_index);

                    if (is_sprint_mode && question_index === (questions.length - 5)) {
                        var qids = [];

                        questions.forEach(function(value, index) {
                            qids.push(value.question_id);
                        });
                        var data = {
                            topic_id: topic_id,
                            chapter_ids: chapter_ids,
                            exclude_qid: qids
                        };

                        get_questions_for_game(data);
                    }
                } else {
                    game_ended();
                }
            });

            function game_ended() {
                if (is_sprint_mode) {
                    timer.stopTimer();
                }
                $('#game_layout').remove();
                $('#review_layout').removeClass('d-none');
            }

            function update_review_layout() {
                var review_list = $('#review_list');

                var new_list_item = review_list_item_template.clone();
                new_list_item.removeAttr('data-template');

                reset_review_list_item(new_list_item);

                new_list_item.find('.review_question').html($('#question_text').html());

                var selected_answer = $('#option_div').find('.answer_button.selected');
                var selected_answer_dao = parseInt(selected_answer.attr('data-answer-option'));
                var correct_answer = $('#reveal_answer_text');
                var correct_answer_dao = parseInt(correct_answer.attr('data-answer-option'));

                ra_selected = review_answer_template.clone();
                ra_correct = review_answer_template.clone();

                var review_list_answer = new_list_item.find('.review_list_answer');

                ra_selected.addClass('selected');
                ra_selected.children()
                    .attr('data-answer-option', selected_answer_dao)
                    .children().html(selected_answer.children('.answer_text').html())


                ra_correct.addClass('incorrect');
                ra_correct.children()
                    .attr('data-answer-option', correct_answer_dao)
                    .children().html(correct_answer.children('.answer_text').html())

                if (selected_answer_dao < correct_answer_dao) {
                    review_list_answer.append(ra_selected);
                    if (selected_answer_dao != correct_answer_dao) {
                        review_list_answer.append(ra_correct);
                    }
                } else {
                    if (selected_answer_dao != correct_answer_dao) {
                        review_list_answer.append(ra_correct);
                    }
                    review_list_answer.append(ra_selected);
                }

                var explanation_div = new_list_item.find('.jumbotron');
                // if no explanation, remove jumbotron so that the styles not displayed
                if (CurrentQuestion.explanation.length == 0) {
                    explanation_div.remove();
                } else {
                    explanation_div.html(CurrentQuestion.explanation);
                }
                review_list.append(new_list_item);
            }
        });
    </script>
</body>

</html>