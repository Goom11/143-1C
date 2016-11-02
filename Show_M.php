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
        $queryResult->free();
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

function averageScore($identifier) {
    if ($identifier) {
        $query =
            "SELECT AVG(R.rating) FROM Movie M, Review R " .
            "WHERE M.id = R.mid AND M.id = " . $identifier . ";";
        $queryResult = runQuery($query);
        if ($queryResult === "failed") {
            return "failed";
        }
        $queryRows = getAllRows($queryResult);
        $queryResult->free();
        return $queryRows[0]["AVG(R.rating)"];
    }
    return array();
}

function getUserComments($identifier) {
    if ($identifier) {
        $query = "SELECT * FROM Review R WHERE R.mid = " . $identifier . ";";
        $queryResult = runQuery($query);
        if ($queryResult === "failed") {
            return "failed";
        }
        $queryRows = getAllRows($queryResult);
        $queryResult->free();
        return $queryRows;
    }
    return array();
}

$identifier = $_GET["identifier"];
$movieInformation = movieInformation($identifier);
$actorsInMovie = actorsInMovie($identifier);
$averageScore = averageScore($identifier);
$userComments = getUserComments($identifier);
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


<?php
if ($averageScore === "failed") {
    print 'No Average Review';
} else if (!empty($averageScore)) {
    print '<h2>Average Score: </h2>';
    print "The average score for this movie is: $averageScore";
}
?>

<?php
if ($userComments === "failed") {
    print '<h2>Invalid Identifier</h2>';
} else if (empty($userComments)) {
    print '<h2>There are no user reviews for this movie</h2>';
} else {
    print '<h2>User Reviews: </h2>';
    foreach ($userComments as $comment) {
        $name = $comment["name"];
        $rating = $comment["rating"];
        $commentMessage = $comment["comment"];
        $time = $comment["time"];
        print "<p><b>$name</b> rated this movie $rating out of 5 and said: \"$commentMessage\" at $time.</p>";
    }
}
?>

<?php
if ($movieInformation === "failed") {
} else if (empty($movieInformation)) {
} else {
    print "<h3><a href='GiveReview.php?MovieID=$identifier'>Leave Your Own Review For This Movie</a></h3>";
}
?>



<h2>Search Actors and Movies</h2>
<form action="./search.php" method="GET">
    <input type="text" name="query" size="40" <br>
    <input type="submit" value="Submit">
</form>

</div>
</body>
</html>