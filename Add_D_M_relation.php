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
<h2>Add a new Director/Movie Relation</h2>
<form action="./Add_D_M_relation.php" method="GET">
    <p> Select Movie
    <select name="movieID">
        <?php
        $queryResult = runQuery("SELECT * FROM Movie;");
        $queryRows = getAllRows($queryResult);
        $queryResult->free();

        foreach ($queryRows as $movie) {
            $id = $movie["id"];
            $title = $movie["title"];
            $year = $movie["year"];
            print "<option value='$id'> $title ($year) </option>";
        }
        ?>
    </select> </p>
    <p> Select Director
    <select name="directorID">
        <?php
        $queryResult = runQuery("SELECT * FROM Director;");
        $queryRows = getAllRows($queryResult);
        $queryResult->free();

        foreach ($queryRows as $director) {
            $id = $director["id"];
            $first = $director["first"];
            $last = $director["last"];
            $dob = $director["dob"];
            print "<option value='$id'> $first $last ($dob) </option>";
        }
        ?>
    </select> </p>
    <input type="submit" value="Add">
</form>

<?php
// Get all the fields
$did = $_GET["directorID"];
$mid = $_GET["movieID"];
$role = $_GET["role"];

// Ensure that fields are completed
if ($did && $mid) {

    // Establish database connection
    $db = new mysqli('localhost', 'cs143', '', 'CS143');
    if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    // Insert the movie and genre tuples into their respective tables
    $movieDirectorInsert = "INSERT INTO MovieDirector VALUES (?, ?)";
    $movieDirectorInsert = $db->prepare($movieDirectorInsert);
    $movieDirectorInsert->bind_param("ii", $mid, $did);

    if (!$movieDirectorInsert->execute()) {
        print "Error inserting MovieDirector relation: ";
        print $movieDirectorInsert->error;
        exit();
    }
    else {
        print "<h3>Insert of relation successful.</h3>";
    }

    $db->close();
    $movieDirectorInsert->free_result();
}

else if (!empty($_GET)) {
    if (!$did) {
        print "<h3> Must input actor id.</h3>";
    }
    if (!$mid) {
        print "<h3> Must input movie id.</h3>";
    }
}



?>
</div>
</body>
</html>