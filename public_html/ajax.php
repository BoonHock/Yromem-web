<?php


require_once '../php/conn.php';

if (isset($_GET["topic"])) {
    $query = $conn->prepare("SELECT topic, name FROM topic");
    $query->execute([]);
    echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
} elseif (!empty($_GET['start_game'])) {
    // get 16 questions. 15 for game, 1 for switch lifeline
    $query = $conn->prepare("SELECT
        question.question_id,
        question.question,
        question.a0,
        question.a1,
        question.a2,
        question.a3,
        question.explanation
    FROM question

    JOIN topic
    ON question.topic_id = topic.topic

    WHERE question.topic_id = (?)
    ORDER BY RAND()
    LIMIT 16");
    $query->execute([$_GET['start_game']]);
    echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
}
