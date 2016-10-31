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
        $rs->free();
    } else {
        $rv = $rs;
    }
    $db->close();
    return $rv;
}

function movieInformation($identifier) {
    if ($identifier) {
        $query = "SELECT * FROM Movie WHERE id = " . $identifier . ';';
        $queryResult = runQuery($query);
        if ($queryResult === "failed") {
            return "failed";
        }
        $queryRow = $queryResult->fetch_assoc();
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

$movieInformation = movieInformation($_GET["identifier"]);
?>

<html>
<head>
    <?php $title = "IMDB: I(ncomplete and Dated) Movie DB" ?>
    <title><?php print "$title"; ?></title>
</head>

<body bgcolor=white>
<h1><?php print "$title"; ?></h1>

<?php if (!empty($movieInformation)) { ?>
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

/*
    <td>Tom Hanks</td>
    <td>Male</td>
    <td>1956-07-09</td>
    <td>Still Alive</td>
*/

$identifier = $_GET["identifier"];
?>

<h2>Search Actors and Movies</h2>
<form action="./search.php" method="GET">
    <input type="text" name="query" size="40" <br>
    <input type="submit" value="Submit">
</form>


</body>
</html>