<?php
include_once('conn.php');
// Get the search query from the form
$query = $_GET['query'];

// Perform the full-text search query
$sql = "SELECT title,file_path FROM pdfs WHERE MATCH(content) AGAINST('$query' IN BOOLEAN MODE)";
$result = $conn->query($sql);
var_dump($result);die;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdfName = $row['title'];
        $pdfLink = $row['file_path'];
        echo "<a href='$pdfLink'>Download</a><br>";
    }
} else {
    echo "No results found.";
}
?>