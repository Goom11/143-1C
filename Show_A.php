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

function actorInformation($identifier) {
    if ($identifier) {
        $query = "SELECT * FROM Actor WHERE id = " . $identifier . ';';
        $queryResult = runQuery($query);
        if ($queryResult === "failed") {
            return "failed";
        }
        $queryRow = $queryResult->fetch_assoc();
        $actorInformation = array(
            "name" => $queryRow["first"] . " " . $queryRow["last"],
            "sex" => $queryRow["sex"],
            "dob" => $queryRow["dob"],
            "dod" => $queryRow["dod"],
        );
        $queryResult->free();
        return $actorInformation;
    }
}

$actorInformation = actorInformation($_GET["identifier"]);
?>

<html>
<head>
    <?php $title = "IMDB: I(ncomplete and Dated) Movie DB" ?>
    <title><?php print "$title"; ?></title>
</head>

<body bgcolor=white>
<h1><?php print "$title"; ?></h1>

<?php if (!empty($actorInformation)) { ?>
    <h2>Actor Information</h2>
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