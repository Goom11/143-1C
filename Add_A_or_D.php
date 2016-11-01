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
<form action="./Add_A_or_D.php" method="GET">
    <select name="pType">
        <option> Actor </option>
        <option> Director </option>
    </select>
    First Name <input type="text" name="fname" size="20">
    Last Name <input type="text" name="lname" size="20">
    Male <input type="radio" name="sex" value="Male" checked>
    Female <input type="radio" name="sex" value="Female">
    Date of Birth <input type="date" name="dob">
    Date of Death (may be blank) <input type="date" name="dod">
    <input type="submit" value="Add">
</form>

<?php
    // Get all the fields
    $pType = $_GET["pType"];
    $fname = $_GET["fname"];
    $lname = $_GET["lname"];
    $sex = $_GET["sex"];
    $dob = $_GET["dob"];
    $dod = $_GET["dod"];

    // Ensure that fields are completed
    if ($pType && $fname && $lname && $sex && $dob ) {

        // Input error checking
        if (strlen($fname) > 30) {
            print "<h3> $fname is invalid. First names must be less than 30 characters. </h3>";
            exit();
        }
        if (strlen($lname) > 30) {
            print "<h3> $lname is invalid. First names must be less than 30 characters. </h3>";
            exit();
        }

        if ($pType != "Actor" && $pType != "Director") {
            print "<h3> $pType is invalid. Person type must either be Actor or Director.</h3>";
            exit();
        }
        if ($sex != "Male" && $sex != "Female") {
            print "<h3> $sex is invalid. Sex must either be male or female.</h3>";
            exit();
        }
        if (!(bool)strtotime($dob)) {
            print "<h3> $dob is in an invalid date format.</h3>";
            exit();
        }

        if (($dod && !(bool)strtotime($dod))) {
            print "<h3> $dod is in an invalid date format.</h3>";
            exit();
        }

        // If $dod doesn't exist, makes it null.
        if (!$dod) {
             $dod = null;
        }

        $db = new mysqli('localhost', 'cs143', '', 'CS143');
        if($db->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        $id = nextPersonID();

        $actorInsert = "INSERT INTO $pType VALUES (?, ?, ?, ?, ?, ?)";
        $actorInsert = $db->prepare($actorInsert);
        $actorInsert->bind_param("isssss", $id, $lname, $fname, $sex, $dob, $dod);
        var_dump($actorInsert);
        if (!$actorInsert->execute()) {
            print $actorInsert->error;
        }
        else {
            print "<h3>Insert of $pType $fname $lname successful.</h3>";
        }

        $db->close();
        $actorInsert->free_result();
    }

    else if (!empty($_GET)) {
        if (!$pType) {
            print "<h3> Must input type of person.</h3>";
        }
        if (!$fname) {
            print "<h3> Must first name of person.</h3>";
        }
        if (!$lname) {
            print "<h3> Must last name of person.</h3>";
        }
        if (!$sex) {
            print "<h3> Must input sex of person.</h3>";
        }
        if (!$dob) {
            print "<h3> Must input the date of birth.</h3>";
        }
    }



?>

</body>
</html>