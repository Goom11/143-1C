<?php

include 'Helpers.php';

function movieInformation($identifier) {
    if ($identifier) {
        $query = "SELECT * FROM Movie WHERE id = " . $identifier . ';';
        $queryResult = runQuery($query);
        if ($queryResult === "failed") {
            return "failed";
        }
        $queryRows = getAllRows($queryResult);
        if (count($queryRows) < 1) {
            return array();
        }
        $queryRow = $queryRows[0];
        $movieInformation = array(
            "title" => $queryRow["title"],
            "year" => $queryRow["year"],
            "rating" => $queryRow["rating"],
            "company" => $queryRow["company"],
        );
        $queryResult->free();
        return $movieInformation;
    }
}

function actorsInMovie($identifier) {
    if ($identifier) {
        $query =
            "SELECT * FROM MovieActor MA, Actor A WHERE MA.aid = A.id " .
            "AND MA.mid = " . $identifier;
        $queryResult = runQuery($query);
        if ($queryResult === "failed") {
            return "failed";
        }
        $queryRows = getAllRows($queryResult);
        $actorsInMovie = array();
        foreach ($queryRows as $actorRole) {
            $actorsInMovie[$actorRole["aid"]] = array(
                "name" => $actorRole["first"] . " " . $actorRole["last"],
                "role" => $actorRole["role"],
            );
        }

        return $actorsInMovie;
    }
    return array();
}

$movieInformation = movieInformation($_GET["identifier"]);
$actorsInMovie = actorsInMovie($_GET["identifier"]);
?>

<html>
<head>
    <?php $title = "IMDB: I(ncomplete and Dated) Movie DB" ?>
    <title><?php print "$title"; ?></title>
</head>

<body bgcolor=white>
<h1><?php print "$title"; ?></h1>

<?php
if ($movieInformation === "failed") {
    print '<h2>Invalid Identifier</h2>';
} else if (!empty($movieInformation)) {
?>
    <h2>Movie Information</h2>
    <table border="1" cellpadding="2" cellspacing="1">
        <thead>
        <tr>
            <td>Title</td>
            <td>Year</td>
            <td>Rating</td>
            <td>Company</td>
        </tr>
        </thead>

        <tbody>
        <tr>
            <?php
            foreach($movieInformation as $info) {
                print '<td>';
                print $info;
                print '</td>';
            }
            ?>
        </tr>
        </tbody>
    </table>
    <br><hr>
<?php } ?>




<?php
if ($actorsInMovie === "failed") {
    print '<h2>Invalid Identifier</h2>';
} else if (!empty($actorsInMovie)) {
    ?>
    <h2>Actors In Movie:</h2>
    <table border="1" cellpadding="2" cellspacing="1">
        <thead>
        <tr>
            <td>Name</td>
            <td>Role</td>
        </tr>
        </thead>

        <tbody>
        <?php
        foreach($actorsInMovie as $aid => $row) {
            print '<tr>';
            foreach ($row as $info) {
                print '<td>';
                print "<a href='Show_A.php?identifier=$aid'>";
                print $info;
                print '</a>';
                print '</td>';
            }
            print '</tr>';
        }
        ?>
        </tbody>
    </table>
    <br><hr>
<?php } ?>

<h2>Search Actors and Movies</h2>
<form action="./search.php" method="GET">
    <input type="text" name="query" size="40" <br>
    <input type="submit" value="Submit">
</form>


</body>
</html>