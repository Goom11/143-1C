<html>
<head>
<?php $title = "CS143 Project 1B Demo" ?>
<title><?php print "$title"; ?></title>
</head>

<body bgcolor=white>
<h1><?php print "$title"; ?></h1>

<form action="./query.php" method="GET">
    <textarea name="query" cols="60" rows="8"></textarea><br>
    <input type="submit" value="Submit">
</form>

<?php

$query = $_GET["query"];
if ($query) {
    $db = new mysqli('localhost', 'cs143', '', 'CS143');
    if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    if (!($rs = $db->query($query))) {
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
    }

    $rs->free();
    $db->close();
}
?>

</body>
</html>