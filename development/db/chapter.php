<?php

require_once '../php/conn.php';

function get_chapters($topic_id)
{
    global $conn;

    $q_chapter = $conn->prepare('SELECT * FROM `chapter` WHERE topic_id=(?)');
    $q_chapter->execute([$topic_id]);
    return $q_chapter->fetchAll(PDO::FETCH_ASSOC);
}
