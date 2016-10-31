<html>
<head>
    <?php $title = "IMDB: I(ncomplete and Dated) Movie DB" ?>
    <title><?php print "$title"; ?></title>
</head>


<body bgcolor=white>
<h1><?php print "$title"; ?></h1>

<h2>Add new Movie</h2>
<form action="./Add_movie.php" method="GET">
    Title <input type="text" name="title" size="20">
    </br>
    Company <input type="text" name="company" size="20">
    </br>
    Year <input type="text" name="year" size="20">
    </br>
    MPAA Rating <input type="text" name="mpaa" size="20">
    </br>
    <input type="submit" value="Add">
</form>

<?php


?>

</body>
</html>

