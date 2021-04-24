<?php

require_once 'conn.php';

$json = file_get_contents("yromem.json");
$json_decode = json_decode($json);

$topic_name = "";

foreach ($json_decode as $key => $val) {
    if ($key === "topic") {
        $topic_name = $val;
        break;
    }
}

$q_get_topic_id = $conn->prepare('SELECT topic FROM topic WHERE name=(?)');
$q_insert_topic = $conn->prepare('INSERT INTO topic (name) VALUES(?)');
$q_delete_question = $conn->prepare("DELETE FROM question WHERE topic_id=(?);");
$q_insert_question = $conn->prepare("INSERT INTO question (question,a0,a1,a2,a3,topic_id) VALUES(?,?,?,?,?,?);");

$q_get_topic_id->execute([$topic_name]);


if ($topic_id = $q_get_topic_id->fetch(PDO::FETCH_COLUMN, 1)) {
    // if topic already exists in db, delete questions and re-insert
    $q_delete_question->execute([$topic_id]);
} else {
    // if no topic in database, insert
    $q_insert_topic->execute([$topic_name]);
    $topic_id = $conn->lastInsertId();
}

foreach ($json_decode as $key => $val) {
    if ($key === "topic") {
        continue;
    }

    $question = $val->q;
    $a0 = $val->a0;
    $a1 = $val->a1;
    $a2 = $val->a2;
    $a3 = $val->a3;

    $q_insert_question->execute([$question, $a0, $a1, $a2, $a3, $topic_id]);
}
