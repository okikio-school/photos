<?php 
    include_once "partials/db.php";

    # Only run if the form is submitted and a user is signed in
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["image_id"]) && isset($_POST["likes"])) {
        try {
            # Create database connection
            $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
            try {
                # Update the image info. in the `images` table, add the new value to likes
                $sql = "UPDATE images SET likes=:likes WHERE id=:id";
                

                # Begin Transaction
                $pdo->beginTransaction();
                $statement = $pdo->prepare($sql);

                # Increment likes
                $likes = $_POST['likes'] + 1;

                # Use image info. from the form in sql query
                $statement->bindValue(':id', $_POST['image_id']);
                $statement->bindValue(':likes', $_POST['likes'] + 1);

                # Run prepared sql query
                $statement->execute();

                # Commit Transaction
                $pdo->commit();

                # Garbage collect the statement
                $statement = null;

                # Success
                echo "<h2 class=\"text-center success\">Survey Completed Successfully.</h2>";
            } catch (PDOException $e) {
                $pdo->rollback();
                throw $e;
            }

            # Disconnect from database
            $pdo = null;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
?>