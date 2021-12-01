<?php 
    include_once "./db.php";

    # If the `user_id` cookie is set the user is already logged in, so, just continue
    if (isset($_COOKIE["user_id"])) {
        try {
            # Create database connection
            $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            # Santize `user_id` input
            $user_id = $pdo->quote($_COOKIE["user_id"]);
            
            # Check if the user_id from the cookie is already present in the `users` table
            $sql = "SELECT * FROM users WHERE id = " . $user_id;
            $result = $pdo->query($sql);
            
            # If the user_id is present in the `users` table then print out `Welcome [...]`
            if ($result = $result->fetch()):
                echo "Welcome " . $result["name"] . "\n";
            else:
                echo "This user doesn't exist would you like to  " . $result["name"] . "\n";
            endif;

            # Disconnect from database
            $pdo = null;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    } 
    
    # Only run if the form is submitted and an email and password are present 
    else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["password"])) {
        try {
            # Create database connection
            $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            # Santize email and password input
            $email = $pdo->quote($_POST['email']);
            $password = $pdo->quote($_POST['password']);
            
            # Check if the email and password from the form submission are already present in the `users` table
            $sql = "SELECT * FROM users WHERE email = " . $email . " AND password = " . $password;
            $result = $pdo->query($sql);
            
            # If the email and password are present in the `users` table then print `Welcome [...]`
            if ($result = $result->fetch()):
                setcookie("user_id", $result["id"], time() + 80 * 80 * 24);
                echo "Welcome " . $result["name"] . "\n";
            else:
                echo "This user doesn't exist would you like to  " . $result["name"] . "\n";
            endif;

            # Disconnect from database
            $pdo = null;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
?>