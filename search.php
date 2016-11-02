<?php
    function buildClause($strarr) {
        $last = end($strarr);
        $clause = "";

        foreach ($strarr as $cur) {
            if ($cur == $last) {
                $clause = $clause . " " . $cur;
            } else {
                $clause = $clause . " " . $cur . " AND";
            }
        }
        return $clause;
    };
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
<h2>Search Actors and Movies</h2>
<form action="./search.php" method="GET">
    <input type="text" name="query" size="40" <br>
    <input type="submit" value="Submit">
</form>

<?php

$query = $_GET["query"];
if (!empty($_GET)) {

    $db = new mysqli('localhost', 'cs143', '', 'CS143');
    if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    // Deal with multiple space characters. Also applies set semantics to query tokens.
    $multipleSpaces = preg_match('/  /', $query) || ($query == " ");
    $query_tokens = explode(" ", $query);
    $query_tokens = array_unique($query_tokens);
    if ($multipleSpaces) {
        array_push($query_tokens, " ");
    }

    // Escape the tokens and then build the appropriate constraints from the tokens
    $actorParameters = array_map(function($str) use ($db) {
        $escaped_str = $db->real_escape_string($str);
        return "((first LIKE '%$escaped_str%') OR (last LIKE '%$escaped_str%'))";
    }, $query_tokens);

    $movieParameters = array_map(function($str) use ($db) {
        $escaped_str = $db->real_escape_string($str);
        return "(title LIKE '%$escaped_str%')";
    }, $query_tokens);


    $actorQuery = "SELECT * FROM Actor WHERE" . buildClause($actorParameters);
    $movieQuery = "SELECT * FROM Movie WHERE" . buildClause($movieParameters);

    print "<br><hr>";

    if (!($rs = $db->query($actorQuery))) {
        $errmsg = $db->error;
        print "Query failed: $errmsg <br/>";
    } else {
        print "<h2> Actor Results </h2> <br/>";
        print '<table border="1" cellpadding="2" cellspacing="1">';
        $fields = $rs->fetch_fields();
        print '<tr align="center">';
        foreach ($fields as $column) {
            print '<td><b>';
            print $column->name;
            print '</b></td>';
        }
        print '</tr>';
        while($row = $rs->fetch_assoc()) {
            $id = $row['id'];
            print '<tr align=center>';
            foreach($row as $_ => $value) {
                print '<td>';
                print "<a href='Show_A.php?identifier=$id'>";
                print $value;
                print '</a>';
                print '</td>';
            }
            print '</tr>';
        }
        print '</table>';
        print "<br><hr>";
    }

    $rs->free();

    if (!($rs = $db->query($movieQuery))) {
        $errmsg = $db->error;
        print "Query failed: $errmsg <br />";
    } else {
        print "<h2> Movie Results </h2> <br/>";
        print '<table border="1" cellpadding="2" cellspacing="1">';
        $fields = $rs->fetch_fields();
        print '<tr align="center">';
        foreach ($fields as $column) {
            print '<td><b>';
            print $column->name;
            print '</b></td>';
        }
        print '</tr>';
        while($row = $rs->fetch_assoc()) {
            $mid = $row["id"];
            print '<tr align=center>';
            foreach($row as $_ => $value) {
                print '<td>';
                print "<a href='Show_M.php?identifier=$mid'>";
                print $value;
                print '</a>';
                print '</td>';
            }
            print '</tr>';
        }
        print '</table>';
    }

    $rs->free();
    $db->close();


}


?>

    </div>
</body>
</html>