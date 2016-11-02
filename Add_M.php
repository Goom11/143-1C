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
<h2>Add a new Movie</h2>
<form action="./Add_M.php" method="GET">
    <p> Title <input type="text" name="title" size="80"> </p>
    <p> Company <input type="text" name="company" size="20"> </p>
    <p> Year <input type="number" name="year" size="20"> </p>
    <p> MPAA Rating
    <select name="rating">
        <option> G </option>
        <option> PG </option>
        <option> PG-13 </option>
        <option> R </option>
        <option> NC-17 </option>
        <option> Unrated </option>
    </select> </p>
    <p>
        Genre:
        <input type="checkbox" name="genre[]" value="Action"> Action </input>
        <input type="checkbox" name="genre[]" value="Adult"> Adult </input>
        <input type="checkbox" name="genre[]" value="Adventure"> Adventure </input>
        <input type="checkbox" name="genre[]" value="Animation"> Animation </input>
        <input type="checkbox" name="genre[]" value="Comedy"> Comedy </input> <br>
        <input type="checkbox" name="genre[]" value="Crime"> Crime </input>
        <input type="checkbox" name="genre[]" value="Documentary"> Documentary </input>
        <input type="checkbox" name="genre[]" value="Drama"> Drama </input>
        <input type="checkbox" name="genre[]" value="Family"> Family </input>
        <input type="checkbox" name="genre[]" value="Fantasy"> Fantasy </input> <br>
        <input type="checkbox" name="genre[]" value="Horror"> Horror </input>
        <input type="checkbox" name="genre[]" value="Musical"> Musical </input>
        <input type="checkbox" name="genre[]" value="Mystery"> Mystery </input>
        <input type="checkbox" name="genre[]" value="Romance"> Romance </input>
        <input type="checkbox" name="genre[]" value="Sci-Fi"> Sci-Fi </input> <br>
        <input type="checkbox" name="genre[]" value="Short"> Short </input>
        <input type="checkbox" name="genre[]" value="Thriller"> Thriller </input>
        <input type="checkbox" name="genre[]" value="War"> War </input>
        <input type="checkbox" name="genre[]" value="Western"> Western </input>
    </p>
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

    var_dump($genre);

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

    foreach ($genre as $elem) {
        $genreInsert = "INSERT INTO MovieGenre VALUES (?, ?)";
        $genreInsert = $db->prepare($genreInsert);
        $genreInsert->bind_param("is", $id, $genre);

        if (!$genreInsert->execute()) {
            print "Error inserting movie genre: ";
            print $genreInsert->error;
            exit();
        }
        $genreInsert->free_result();
    }

    print "<h3>Insert of $title successful.</h3>";

    $db->close();
    $movieInsert->free_result();



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
</div>
</body>
</html>