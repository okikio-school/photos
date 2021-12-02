<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>

    <?php include_once "partials/meta.php"; ?>
    <link rel="stylesheet" type="text/css" href="styles/signup.css">
</head>

<body>
    <!-- Navbar partial -->
    <!-- I got tired of copying and pasting the code for the navbar -->
    <?php include_once "partials/navbar.php"; ?>

    <main class="signup">
        <div class="container">
            <header>
                <h1 class="font-serif"> Join us </h1>
                <p> Sign up to get into the art community </p>
            </header>

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
                            $sql = "SELECT id, email, password FROM users WHERE email = " . trim($email) . " AND password = " . trim($password);
                            $result = $pdo->query($sql);
                            
                            # If the email and password are present in the `users` table then warn the user
                            # Otherwise create a new row (using the info. from the form) and insert it into the `users` table
                            if ($result = $result->fetch()):
                                echo "That email is already in use, please try again with a different email.\n";
                            else:
                                $sql = "INSERT INTO users (name, age, email, password) VALUES (?, ?, ?, ?)";

                                # Begin Transaction
                                $pdo->beginTransaction();

                                $statement = $pdo->prepare($sql);

                                # Use user info. from the form in sql query
                                $statement->bindValue(1, $_POST['name']);
                                $statement->bindValue(2, $_POST['age']);

                                $statement->bindValue(3, $_POST['email']);
                                $statement->bindValue(4, $_POST['password']);

                                # Run prepared sql query
                                $statement->execute();

                                # Set the user_id cookie
                                setcookie("user_id", $pdo->lastInsertId(), time() + 80 * 80 * 24);

                                # Commit Transaction
                                $pdo->commit();

                                # Garbage collect the statement
                                $statement = null;
                                
                                # Redirect to profile page
                                header("Location: ./profile?email=" . trim($email));
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
            
            #registering account
            <!-- Using post so signup information is not present in the URL -->
            <form method="post">
                <fieldset>
                    <input type="text" name="name" required />
                    <label for="name">Name</label>
                </fieldset>
                
                <fieldset>
                    <input type="email" name="email" required />
                    <label for="email">Email</label>
                </fieldset>

                <fieldset>
                    <input type="password" name="password" required />
                    <label for="password">Password</label>
                </fieldset>

                <fieldset>
                    <input type="number" name="age" required />
                    <label for="age">Age</label>
                </fieldset>

                <button type="submit">
                    Register
                </button>
            </form>

            <div class="register-container-login">
                <small> Already have an account? <a href="./login"> Login </a></small>
            </div>
        </div>
    </main>

    <?php include_once "partials/scripts.php"; ?>
</body>

</html>
