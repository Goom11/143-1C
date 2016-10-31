<html>
<head>
    <?php $title = "IMDB: I(ncomplete and Dated) Movie DB" ?>
    <title><?php print "$title"; ?></title>
</head>


<body bgcolor=white>
<h1><?php print "$title"; ?></h1>

<h2>Add a new Actor or Director</h2>
<form action="./Add_A_or_D.php" method="GET">
    <select name="pType">
        <option> Actor </option>
        <option> Director </option>
    </select>
    First Name <input type="text" name="fname" size="20">
    Last Name <input type="text" name="lname" size="20">
    Male <input type="radio" name="sex" value="male" checked>
    Female <input type="radio" name="sex" value="female">
    Date of Birth <input type="date" name="dob">
    Date of Death <input type="date" name="dod">
    <input type="submit" value="Add">
</form>

<?php


?>

</body>
</html>