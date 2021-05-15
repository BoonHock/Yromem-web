<?php

require_once '../php/conn.php';

function get_questions_for_game($topic_id, $chapter_ids, int $limit, $exclude_qid)
{
    global $conn;

    // make sure all elemes are int
    $chapter_ids = array_map(function ($val) {
        return (int) $val;
    }, $chapter_ids);

    $chapter_ids = implode("','", $chapter_ids);

    $query = "SELECT
        q.question_id, 
        q.question,
        q.a0,
        q.a1,
        q.a2,
        q.a3,
        q.explanation
    FROM question q
    JOIN chapter c
    ON q.chapter_id = c.chapter_id
    WHERE q.chapter_id IN ('$chapter_ids')
    AND c.topic_id = (?)";

    if (!empty($exclude_qid)) {
        $exclude_qid = array_map(function ($val) {
            return (int) $val;
        }, $exclude_qid);

        $exclude_qid = implode("','", $exclude_qid);
        $query .= " AND q.question_id NOT IN ('$exclude_qid')";
    }

    $query .= " ORDER BY RAND() LIMIT $limit";
    $q = $conn->prepare($query);

    $q->execute([$topic_id]);
    return $q->fetchAll(PDO::FETCH_ASSOC);
}
