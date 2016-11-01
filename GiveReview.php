<?php

include 'Helpers.php';

function getMovieTitle($movieID) {
    if ($movieID) {
        $query = "SELECT title FROM Movie WHERE id = " . $movieID . ";";
        $queryResult = runQuery($query);
        if ($queryResult === "failed") {
            return "failed";
        }
        $queryRows = getAllRows($queryResult);
        if (count($queryRows) < 1) {
            return array();
        }
        return $queryRows[0]["title"];
    }
    return array();
}

$movieID = $_GET["MovieID"];
$viewer = $_GET["viewer"];
$score = $_GET["score"];
$comment = $_GET["comment"];

$movieTitle = getMovieTitle($movieID);

$formIsUnfilled = !$viewer && !$score && !$comment;
$formIsCompletelyFilled = $viewer && $score && $comment;

?>

<html>
<head>
    <?php $title = "IMDB: I(ncomplete and Dated) Movie DB" ?>
    <title><?php print "$title"; ?></title>
</head>


<body bgcolor=white>
<h1><?php print "$title"; ?></h1>

<?php var_dump($movieTitle); ?>
<?php var_dump($formIsUnfilled); ?>

<?php
if ($movieID) {
?>
    <h2>Add new comment here:</h2>
    <form action="./GiveReview.php" method="GET">
        Movie Title: <select name="MovieID" id="ID">
            <?php
            print "<option value='$movieID'>$movieTitle</option>";
            ?>
        </select>
        </br>
        Name <input type="text" name="viewer" size="20">
        </br>
        Rating <select name="score" id="rating">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        </br>
        <textarea name="comment" rows="5" placeholder="no more than 500 characters"></textarea>
        <input type="submit" value="Add">
    </form>

<?php

    if (!$formIsUnfilled) {
        if (!$viewer) {
            print "<h3> Must input type your name.</h3>";
        }
        if (!$score) {
            print "<h3> Must input a score.</h3>";
        }
        if (!$comment) {
            print "<h3> Must input some comment.</h3>";
        }
    }

    if ($formIsCompletelyFilled) {
        if (strlen($viewer) > 20) {
            print "<h3> $viewer is invalid. Usernames must be less than 20 characters. </h3>";
            exit();
        }
        $scoreNumber = (int)$score;
        if ($scoreNumber < 0 || $scoreNumber > 5) {
            print "<h3> $score is invalid. Score must be within 0 and 5. </h3>";
            exit();
        }
        if (strlen($comment) > 500) {
            print "<h3> $comment is invalid. Comments must be less than 500 characters. </h3>";
            exit();
        }


        $db = new mysqli('localhost', 'cs143', '', 'CS143');
        if($db->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $query = "INSERT INTO Review VALUES (?, NOW(), ?, ?, ?)";
        $query = $db->prepare($query);
        $query->bind_param("siis", $viewer, $movieID, $scoreNumber, $comment);

        if (!$query->execute()) {
            print $query->error;
        }
        else {
            print "<h3>Insert of Review was successful.</h3>";
        }
    }

} else {
    print '<h2>Please go to search page to find a movie first and then leave a comment.</h2>';
}
?>

</body>
</html>
