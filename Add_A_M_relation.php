<?php
include "Helpers.php";
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
<h2>Add a new Actor/Movie Relation</h2>
<form action="./Add_A_M_relation.php" method="GET">
    <p> Select Movie
    <select name="movieID">
        <?php
        $queryResult = runQuery("SELECT * FROM Movie;");
        $queryRows = getAllRows($queryResult);
        $queryResult->free();

        var_dump($queryResult);
        foreach ($queryRows as $movie) {
            $id = $movie["id"];
            $title = $movie["title"];
            $year = $movie["year"];
            print "<option value='$id'> $title ($year) </option>";
        }
        ?>
    </select> </p>
    <p> Select Actor
    <select name="actorID">
        <?php
        $queryResult = runQuery("SELECT * FROM Actor;");
        $queryRows = getAllRows($queryResult);
        $queryResult->free();

        var_dump($queryResult);
        foreach ($queryRows as $actor) {
            $id = $actor["id"];
            $first = $actor["first"];
            $last = $actor["last"];
            $dob = $actor["dob"];
            print "<option value='$id'> $first $last ($dob) </option>";
        }
        ?>
    </select> </p>
    <p> </p>Role <input type="text" name="role" size="60"> </p>
    <input type="submit" value="Add">
</form>

<?php
// Get all the fields
$aid = $_GET["actorID"];
$mid = $_GET["movieID"];
$role = $_GET["role"];

// Ensure that fields are completed
if ($aid && $mid && $role) {

    // Input error checking
    if (strlen($role) > 50) {
        print "<h3> $role is an invalid role. Roles must be less than or equal to 50 characters. </h3>";
        exit();
    }

    // Establish database connection
    $db = new mysqli('localhost', 'cs143', '', 'CS143');
    if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
    }


    // Insert the movie and genre tuples into their respective tables
    $movieActorInsert = "INSERT INTO MovieActor VALUES (?, ?, ?)";
    $movieActorInsert = $db->prepare($movieActorInsert);
    $movieActorInsert->bind_param("iis", $mid, $aid, $role);

    if (!$movieActorInsert->execute()) {
        print "Error inserting MovieActor relation: ";
        print $movieActorInsert->error;
        exit();
    }
    else {
        print "<h3>Insert of relation successful.</h3>";
    }

    $db->close();
    $movieActorInsert->free_result();
}

else if (!empty($_GET)) {
    if (!$aid) {
        print "<h3> Must input actor id.</h3>";
    }
    if (!$mid) {
        print "<h3> Must input movie id.</h3>";
    }
    if (!$role) {
        print "<h3> Must input role.</h3>";
    }
}



?>
</div>
</body>
</html>