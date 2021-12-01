<?php 
    include_once "./db.php";

    # Only run if the form is submitted and an email and phone number are present 
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["password"])) {
        try {
            # Create database connection
            $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                # Santize email and password input
                $email = $pdo->quote($_POST['email']);
                $password = $pdo->quote($_POST['password']);
                
                # Check if the email and password from the form submission are already present in the `users` table
                $sql = "SELECT id, email, password FROM users WHERE email = " . $email . " AND password = " . $password;
                $result = $pdo->query($sql);
                
                # If the email and password are present in the `users` table then warn the user
                # Otherwise create a new row (using the info. from the form) and insert it into the `users` table
                if ($result = $result->fetch()):
                    die("That email is already in use, please try again with a different email");
                else:
                    $sql = "INSERT INTO users (name, email, age, password) VALUES (:name, :email, :age, :password)";

                    # Begin Transaction
                    $pdo->beginTransaction();

                    $statement = $pdo->prepare($sql);

                    # Use user info. from the form in sql query
                    $statement->bindValue(':name', $_POST['name']);
                    $statement->bindValue(':age', $_POST['age']);

                    $statement->bindValue(':email', $_POST['email']);
                    $statement->bindValue(':passowrd', $_POST['password']);

                    # Run prepared sql query
                    $statement->execute();

                    # Set the user_id cookie
                    setcookie("user_id", $pdo->lastInsertId(), time() + 80 * 80 * 24);

                    # Commit Transaction
                    $pdo->commit();

                    # Garbage collect the statement
                    $statement = null;

                    # Success
                    echo "<h2 class=\"text-center success\">Survey Completed Successfully.</h2>";
                endif;
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