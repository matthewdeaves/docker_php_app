<?php
$mysqli = new mysqli("mysql","app","password","example");

if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}

$results = mysqli_query($mysqli, "SELECT username FROM users");

if ($results) {
    while ($row = $results->fetch_assoc()) {
        echo $row['username'] . "<br>";
    }
} else {
    echo "No results found.";
}
