<?php

require_once '../php/conn.php';

$json = file_get_contents("yromem.json");
$json_decode = json_decode($json);

$topic_id = 0;
$topic_name = "";

foreach ($json_decode as $key => $val) {
    if ($key === "topic") {
        $topic_name = $val;
        break;
    }
}

$q_get_topic_id = $conn->prepare('SELECT topic_id FROM topic WHERE topic_name=(?)');
$q_insert_topic = $conn->prepare('INSERT INTO topic (topic_name,description) VALUES(?,?)');
$q_insert_chapter = $conn->prepare('INSERT INTO chapter(chapter_name, description, topic_id) VALUES(?,?,?)');
$q_delete_question = $conn->prepare(
    'DELETE FROM question WHERE chapter_id IN (SELECT chapter_id FROM chapter WHERE topic_id=(?)); '
);
$q_delete_chapter = $conn->prepare('DELETE FROM chapter WHERE topic_id=(?);');
$q_insert_question = $conn->prepare("INSERT INTO question (question,a0,a1,a2,a3,explanation,chapter_id) VALUES(?,?,?,?,?,?,?);");

$q_get_topic_id->execute([$topic_name]);
$r_get_topic_id = $q_get_topic_id->fetchAll(PDO::FETCH_COLUMN, 0);

if (count($r_get_topic_id) > 0) {
    // if topic already exists in db, delete chapters and questions and re-insert
    $topic_id = $r_get_topic_id[0];
    $q_delete_question->execute([$topic_id]);
    $q_delete_chapter->execute([$topic_id]);
} else {
    // if no topic in database, insert
    $q_insert_topic->execute([$topic_name, '']);
    $topic_id = $conn->lastInsertId();
}

$added_chapters = [];
$chapter_id = 0;

foreach ($json_decode as $key => $val) {
    if ($key === "topic") {
        continue;
    }
    $chapter_title = trim($val->chapter_title);
    $description = $val->level . ' Chapter ' . $val->chapter;

    if ($chapter_title === '') {
        continue;
    }

    if (!in_array($chapter_title, $added_chapters)) {
        array_push($added_chapters, $chapter_title);
        $q_insert_chapter->execute([$chapter_title, $description, $topic_id]);
        $chapter_id = $conn->lastInsertId();
    }

    $question = $val->q;
    $a0 = $val->a0;
    $a1 = $val->a1;
    $a2 = $val->a2;
    $a3 = $val->a3;
    $explanation = $val->explanation;

    $q_insert_question->execute([$question, $a0, $a1, $a2, $a3, $explanation, $chapter_id]);
}
