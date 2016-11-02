<?php

include 'Helpers.php';

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
    <link rel="stylesheet" type="text/css" href="./style.css">
</head>

<body bgcolor=white>
<a href="search.php"><h1><?php print "$title"; ?></h1></a>

<nav>
    <a href="./search.php">Search</a>
    <a href="./Add_A_or_D.php">Add Actor or Director</a>
    <a href="./Add_M.php">Add Movie</a>
    <a href="./Add_A_M_relation.php">Add Actor/Movie Relation</a>
    <a href="./Add_D_M_relation.php">Add Director/Movie Relation</a>
</nav>

<div class="content">
    <hr>
<?php
if ($actorInformation === "failed") {
    print '<h2>Invalid Identifier</h2>';
} else if (!empty($actorInformation)) {
?>
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

<?php
if ($movieRoleInformation === "failed") {
} else if (!empty($movieRoleInformation)) {
?>
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
    foreach($movieRoleInformation as $mid => $row) {
        print '<tr>';
        foreach ($row as $info) {
            print '<td>';
            print "<a href='Show_M.php?identifier=$mid'>";
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

    </div>
</body>
</html>
