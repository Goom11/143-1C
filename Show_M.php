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

$movieInformation = movieInformation($_GET["identifier"]);
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