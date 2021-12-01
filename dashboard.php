
<?php 
    include_once "./db.php";

    try {
        # Create database connection
        $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        # Count the number of users that have submitted the survey
        $sql = "SELECT COUNT('user_id') AS NUMBER_OF_USERS from usersinfo";
        $result = $pdo->query($sql);

        echo "<li>";
        echo "<label>How many users submitted the rating form?</label>";
        while($row = $result->fetch()):
            echo $row["NUMBER_OF_USERS"] . " people(s) <br>";
        endwhile;
        echo "</li>";

        # Get the average of each rating from the `ratinginfo` table
        $sql = "SELECT AVG(food_rate), AVG(menu_rate), AVG(service_rate), AVG(atmosphere_rate) from ratinginfo";
        $result = $pdo->query($sql);

        echo "<li>";
        echo "<label>The average rate of each category.</label>";
        echo "<ul>";
        while($row = $result->fetch()):
            printf("<li>Average food rating: <strong class=\"accent\">%d</strong> </li>", $row["AVG(food_rate)"]);
            printf("<li>Average menu rating: <strong class=\"accent\">%d</strong> </li>", $row["AVG(menu_rate)"]);
            printf("<li>Average service rating: <strong class=\"accent\">%d</strong> </li>", $row["AVG(service_rate)"]);
            printf("<li>Average atmosphere rating: <strong class=\"accent\">%d</strong> </li>", $row["AVG(atmosphere_rate)"]);
        endwhile;
        echo "</ul>";
        echo "</li>";

        # Disconnect from database
        $pdo = null;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
?>