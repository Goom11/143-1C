<?php

function getAllRows($queryResult) {
    $rows = [];
    while ($row = $queryResult->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

// If you call this method
//   make sure to free the result after you are done with it!!
function runQuery($query) {
    $db = new mysqli('localhost', 'cs143', '', 'CS143');
    if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    if (!($rs = $db->query($query))) {
        $rv = "failed";
        $rs->free();
    } else {
        $rv = $rs;
    }
    $db->close();
    return $rv;
}

function actorInformation($identifier) {
    if ($identifier) {
        $query = "SELECT * FROM Actor WHERE id = " . $identifier . ';';
        $queryResult = runQuery($query);
        if ($queryResult === "failed") {
            return "failed";
        }
        $queryRows = getAllRows($queryResult);
        if (count($queryRows) < 1) {
            return array();
        }
        $queryRow = $queryRows[0];
        $actorInformation = array(
            "name" => $queryRow["first"] . " " . $queryRow["last"],
            "sex" => $queryRow["sex"],
            "dob" => $queryRow["dob"],
            "dod" => $queryRow["dod"],
        );
        $queryResult->free();
        return $actorInformation;
    }
    return array();
}

function movieRoleInformation($identifier) {
    if ($identifier) {
        $query =
            "SELECT * FROM MovieActor MA, Movie M WHERE MA.mid = M.id AND" .
            " MA.aid = " . $identifier . ";";
        $queryResult = runQuery($query);
        if ($queryResult === "failed") {
            return "failed";
        }
        $queryRows = getAllRows($queryResult);
        $queryResult->free();

        $movieRoleInformation = array();
        foreach ($queryRows as $movieRole) {
            $movieRoleInformation[$movieRole["mid"]] = array(
                "role" => $movieRole["role"],
                "title" => $movieRole["title"],
            );
        }
        return $movieRoleInformation;
    }
    return array();
}

$actorInformation = actorInformation($_GET["identifier"]);

$movieRoleInformation = movieRoleInformation($_GET["identifier"]);

?>

<html>
<head>
<?php $title = "IMDB: I(ncomplete and Dated) Movie DB" ?>

<title><?php print "$title"; ?></title>
</head>

<body bgcolor=white>
<h1><?php print "$title"; ?></h1>

<?php if (!empty($actorInformation)) { ?>
<h2>Actor Information:</h2>
<table border="1" cellpadding="2" cellspacing="1">
    <thead>
    <tr>
        <td>Name</td>
        <td>Sex</td>
        <td>Date of Birth</td>
        <td>Date of Death</td>
    </tr>
    </thead>

    <tbody>
    <tr>
        <?php
        foreach($actorInformation as $info) {
            print '<td>';
            print $info;
            print '</td>';
        }
        ?>
    </tr>
    </tbody>
</table>
<?php } ?>

<?php if (!empty($movieRoleInformation)) { ?>
<h2>Actor's Movies and Role:</h2>
<table border="1" cellpadding="2" cellspacing="1">
    <thead>
    <tr>
        <td>Role</td>
        <td>Movie Title</td>
    </tr>
    </thead>

    <tbody>
    <?php
    foreach($movieRoleInformation as $id => $row) {
        print '<tr>';
        foreach ($row as $info) {
            print '<td>';
            print "<a href='Show_M.php?identifier=$id'>";
            print $info;
            print '</a>';
            print '</td>';
        }
        print '</tr>';
    }
    ?>
    </tbody>
</table>
<?php } ?>

<h2>Search Actors and Movies</h2>
<form action="./search.php" method="GET">
    <input type="text" name="query" size="40" <br>
    <input type="submit" value="Submit">
</form>

</body>
</html>
