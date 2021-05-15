<?php

require_once '../php/conn.php';
require_once '../db/question.php';

if (isset($_GET["topic"])) {
    $query = $conn->prepare("SELECT topic_id, topic FROM topic");
    $query->execute([]);
    echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
} elseif (!empty($_GET['start_game'])) {
    // get 16 questions. 15 for game, 1 for switch lifeline
    $query = $conn->prepare("SELECT
        question.qid,
        question.question,
        question.a0,
        question.a1,
        question.a2,
        question.a3,
        question.explanation
    FROM question

    JOIN topic
    ON question.topic_id = topic.topic_id

    WHERE question.topic_id = (?)
    ORDER BY RAND()
    LIMIT 16");
    $query->execute([$_GET['start_game']]);
    echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
} elseif (!empty($_GET['get_questions_for_game'])) {
    $limit = 15;
    $obj = json_decode($_GET['get_questions_for_game']);
    $topic_id = $obj->topic_id;
    $chapter_ids = $obj->chapter_ids;
    $exclude_qid = [];

    if (!empty($obj->limit) && !is_nan($obj->limit)) {
        $limit = $_GET['limit'];
    }

    if (!empty($obj->exclude_qid)) {
        $exclude_qid = $obj->exclude_qid;
    }

    echo json_encode(get_questions_for_game($topic_id, $chapter_ids, $limit, $exclude_qid));
    // echo json_encode(get_questions_for_game($));
} elseif (!empty($_GET['get_question'])) {
    $q = $conn->prepare('SELECT * FROM question WHERE question_id=(?)');
    $q->execute([$_GET['get_question']]);

    echo json_encode($q->fetch(PDO::FETCH_ASSOC));
} elseif (!empty($_GET['get_question_by_topic'])) {
    $q = $conn->prepare('SELECT * FROM question WHERE topic_id=(?)');
    $q->execute([$_GET['get_question_by_topic']]);

    echo json_encode($q->fetchAll(PDO::FETCH_ASSOC));
} elseif (!empty($_POST['edit_question'])) {
    $data = json_decode($_POST['edit_question']);

    $qid = $data->qid;
    $topic_id = $data->topic_id;
    $question = $data->question;
    $a0 = $data->a0;
    $a1 = $data->a1;
    $a2 = $data->a2;
    $a3 = $data->a3;
    $explanation = $data->explanation;

    if ($qid === 0) {
        // add question
        add_question($conn, $topic_id, $question, $a0, $a1, $a2, $a3, $explanation);
    } else {
        // update question
        update_question($conn, $topic_id, $a0, $a1, $a2, $a3, $explanation, $qid);
    }

    echo 'OK';
} elseif (!empty($_POST['delete_question'])) {
    $q = $conn->prepare('DELETE FROM question WHERE question_id=(?);');
    $q->execute([$_POST['delete_question']]);
    echo 'OK';
}

function add_question(
    $conn,
    $topic_id,
    $question,
    $a0,
    $a1,
    $a2,
    $a3,
    $explanation
) {
    $q = $conn->prepare('INSERT INTO question(question,a0,a1,a2,a3,explanation,topic_id)
    VALUES (?,?,?,?,?,?,?);');
    $q->execute([$question, $a0, $a1, $a2, $a3, $explanation, $topic_id]);
}

function update_question(
    $conn,
    $question,
    $a0,
    $a1,
    $a2,
    $a3,
    $explanation,
    $qid
) {
    $q = $conn->prepare('UPDATE question SET
    question=(?), a0=(?), a1=(?), a2=(?), a3=(?), explanation=(?)
    WHERE question_id=(?)');
    $q->execute([$question, $a0, $a1, $a2, $a3, $explanation, $qid]);
}
