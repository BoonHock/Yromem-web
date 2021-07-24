<?php

require_once '../php/conn.php';

function get_topic($tid)
{
    global $conn;

    $q_topic = $conn->prepare('SELECT * FROM topic WHERE topic_id=(?)');
    $q_topic->execute([$tid]);

    return $q_topic->fetch(PDO::FETCH_ASSOC);
}
function get_topics()
{
    global $conn;

    $q_topic = $conn->prepare('SELECT * FROM topic');
    $q_topic->execute([]);

    return $q_topic->fetchAll(PDO::FETCH_ASSOC);
}
