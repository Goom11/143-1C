<html>
<head>
    <?php $title = "IMDB: I(ncomplete and Dated) Movie DB" ?>
    <title><?php print "$title"; ?></title>
</head>

<body bgcolor=white>
<h1><?php print "$title"; ?></h1>

<?php

$query = $_GET["query"];
if ($query) {

    $db = new mysqli('localhost', 'cs143', '', 'CS143');
    if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    $query_tokens = explode(" ", $query);

    // Escape the tokens and then build the appropriate constraints from the tokens
    $actorParameters = array_map(function($str) use ($db) {
        $escaped_str = $db->real_escape_string($str);
        return "((first LIKE '%$escaped_str%') OR (last LIKE '%$escaped_str%'))";
    }, $query_tokens);

    $movieParameters = array_map(function($str) use ($db) {
        $escaped_str = $db->real_escape_string($str);
        return "(title LIKE '%$escaped_str%')";
    }, $query_tokens);

    var_dump($actorParameters);
    var_dump($query_tokens);

    $actorQuery = "SELECT * FROM Actor WHERE" . buildClause($actorParameters);
    $movieQuery = "SELECT * FROM Movie WHERE" . buildClause($movieParameters);



    if (!($rs = $db->query($actorQuery))) {
        $errmsg = $db->error;
        print "Query failed: $errmsg <br />";
    } else {
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
            print '<tr align=center>';
            foreach($row as $_ => $value) {
                print '<td>';
                print $value;
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
            print '<tr align=center>';
            foreach($row as $_ => $value) {
                print '<td>';
                print $value;
                print '</td>';
            }
            print '</tr>';
        }
        print '</table>';
        print "<br><hr>";
    }

    $rs->free();
    $db->close();


} ?>

<h2>Search Actors and Movies</h2>
    <form action="./search.php" method="GET">
        <input type="text" name="query" size="40" <br>
        <input type="submit" value="Submit">
    </form>


</body>
</html>