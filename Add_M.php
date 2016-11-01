<?php
include "Helpers.php";
?>

<html>
<head>
    <?php $title = "IMDB: I(ncomplete and Dated) Movie DB" ?>
    <title><?php print "$title"; ?></title>
</head>


<body bgcolor=white>
<h1><?php print "$title"; ?></h1>

<h2>Add a new Actor or Director</h2>
<form action="./Add_M.php" method="GET">
    Title <input type="text" name="title" size="20">
    Company <input type="text" name="company" size="20">
    Year <input type="number" name="year" size="20">
    MPAA Rating
    <select name="rating">
        <option> G </option>
        <option> PG </option>
        <option> PG-13 </option>
        <option> R </option>
        <option> NC-17 </option>
        <option> Unrated </option>
    </select>
    Genre <input type="text" name="genre">
    <input type="submit" value="Add">
</form>

<?php
// Get all the fields
$title = $_GET["title"];
$company = $_GET["company"];
$year = $_GET["year"];
$rating = $_GET["rating"];
$genre = $_GET["genre"];

// Ensure that fields are completed
if ($title && $company && $year && $rating && $genre) {

    // Input error checking
    if (strlen($title) > 100) {
        print "<h3> $title is an invalid title. Titles must be less than or equal to 100 characters. </h3>";
        exit();
    }

    if (strlen($rating) > 10) {
        print "<h3> $rating is an invalid MPAA rating. MPAA ratings must be less than or equal to 10 characters. </h3>";
        exit();
    }

    if (strlen($company) > 10) {
        print "<h3> $company is an invalid company. Company names must be less than or equal to 10 characters. </h3>";
        exit();
    }


    // Establish database connection
    $db = new mysqli('localhost', 'cs143', '', 'CS143');
    if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    // Get the next consecutive movie ID
    $id = nextMovieId();

    // Insert the movie and genre tuples into their respective tables
    $movieInsert = "INSERT INTO Movie VALUES (?, ?, ?, ?, ?)";
    $movieInsert = $db->prepare($movieInsert);
    $movieInsert->bind_param("isiss", $id, $title, $year, $rating, $company);

    if (!$movieInsert->execute()) {
        print "Error inserting movie data: ";
        print $movieInsert->error;
        exit();
    }

    $genreInsert = "INSERT INTO MovieGenre VALUES (?, ?)";
    $genreInsert = $db->prepare($genreInsert);
    $genreInsert->bind_param("is", $id, $genre);

    if (!$genreInsert->execute()) {
        print "Error inserting movie genre: ";
        print $genreInsert->error;
        exit();
    }
    else {
        print "<h3>Insert of $title successful.</h3>";
    }



}

else if (!empty($_GET)) {
    if (!$title) {
        print "<h3> Must input movie title.</h3>";
    }
    if (!$company) {
        print "<h3> Must input name of production company.</h3>";
    }
    if (!$year) {
        print "<h3> Must input year.</h3>";
    }
    if (!$rating) {
        print "<h3> Must input MPAA rating.</h3>";
    }
    if (!$genre) {
        print "<h3> Must input genre.</h3>";
    }
}



?>

</body>
</html>