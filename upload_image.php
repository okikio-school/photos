<?php 
    include_once "./db.php";

    # Only run if the form is submitted and a user is signed in
    if (isset($_COOKIE["user_id"]) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"]) && isset($_POST["url"])) {
        try {
            # Create database connection
            $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
            try {
                # Insert the image info. into the `images` table
                $sql = "INSERT INTO images (name, description, url, likes, user_id) VALUES (:name, :description, :url, :likes, :user_id)";

                # Begin Transaction
                $pdo->beginTransaction();

                $statement = $pdo->prepare($sql);

                # Use image info. from the form in sql query
                $statement->bindValue(':name', $_POST['name']);
                $statement->bindValue(':description', $_POST['description']);

                $statement->bindValue(':url', $_POST['url']);
                $statement->bindValue(':likes', $_POST['likes']);

                $statement->bindValue(':user_id', $_COOKIE["user_id"]);

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