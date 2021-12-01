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
                $sql = "SELECT user_id, email, password FROM users WHERE email = " . $email . " AND password = " . $password;
                $result = $pdo->query($sql);
                
                # If the email and password are present in the `users` table then update that row with the information from the form
                # Otherwise create a new row (using the info. from the form) and insert it into the `users` table
                if ($result = $result->fetch()):
                    $sql = "UPDATE users SET name=:name, email=:email, age=:age, password=:password WHERE user_id=" . $result["user_id"];
                else:
                    $sql = "INSERT INTO users (first_name, last_name, email, phone) VALUES (:first_name, :last_name, :email, :phone)";
                endif;

                # Begin Transaction
                $pdo->beginTransaction();

                $statement = $pdo->prepare($sql);

                # Use user info. from the form in sql query
                $statement->bindValue(':name', $_POST['name']);
                $statement->bindValue(':age', $_POST['age'], );

                $statement->bindValue(':email', $_POST['email']);
                $statement->bindValue(':passowrd', $_POST['phone']);

                # Run prepared sql query
                $statement->execute();

                # Get the `user_id` from the inserted/updated row in table `users`
                if ($result) {
                    $user_id = $result["user_id"];
                } else { 
                    $user_id = $pdo->lastInsertId();
                }

                # Function for converting the "Very Good", "Good", etc... rating to actual number rating ranging from (1 - 5)
                function getRating (string $prop) {
                    global $_POST;
                    $rating = [
                        "Very Good" => 5,
                        "Good" => 4,
                        "Okay" => 3,
                        "Bad" => 2,
                        "Very Bad" => 1
                    ];

                    return empty($_POST[$prop]) ? 0 : $rating[$_POST[$prop]];
                }

                # Create a new prepared statement to insert the users rating info to the `ratingInfo` table
                $sql = "INSERT INTO ratingInfo (`user-ID`, food_rate, menu_rate, service_rate, atmosphere_rate, transcation_date, remarks, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $statement = $pdo->prepare($sql);

                # Set `user_id` 
                $statement->bindValue(1, $user_id);

                # Set ratings to numbers between (1 - 5)
                $statement->bindValue(2, getRating('food-quality'));
                $statement->bindValue(3, getRating('menu-variety'));
                $statement->bindValue(4, getRating('service-quality'));
                $statement->bindValue(5, getRating('atmosphere-quality'));

                # Set date
                $statement->bindValue(6, $_POST['date']);
                
                $statement->bindValue(7, 'I would give an overall rating of ' . $_POST['overall-rating']);
                $statement->bindValue(8, 0);

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