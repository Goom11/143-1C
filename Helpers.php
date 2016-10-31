<?php
// If you call this method
//   make sure to free the result after you are done with it!!
function runQuery($query) {
    $db = new mysqli('localhost', 'cs143', '', 'CS143');
    if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    $rv = "failed";
    if (!($rs = $db->query($query))) {
        $rv = "failed";
    } else {
        $rv = $rs;
    }
    $db->close();
    return $rv;
}

function getAllRows($queryResult) {
    $rows = [];
    while ($row = $queryResult->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

?>
