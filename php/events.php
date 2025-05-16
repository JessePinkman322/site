<?php
include 'connect.php';
$sql = "SELECT * FROM events";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='event-card'>";
        echo "<h3>" . $row["title"] . "</h3>";
        echo "<p><strong>Дата:</strong> " . $row["date"] . "</p>";
        echo "<p>" . $row["description"] . "</p>";
        echo "<p><strong>Место:</strong> Онлайн (Zoom)</p>";
        echo "<p><strong>Время:</strong> 18:00–20:00</p>";
        echo "</div>";
    }
}
$conn->close();
?>