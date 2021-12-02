<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

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
                <h1 class="font-serif"> Login </h1>
            </header>

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
                        $sql = "SELECT * FROM users WHERE id = " . trim($user_id);
                        $result = $pdo->query($sql);
                        
                        # If the user_id is present in the `users` table then the iser is already logged in
                        if ($result = $result->fetch()):
                            echo "You are already logged in " . $result["name"];
                        else:
                            echo "This user doesn't exist would you like to try loggining again.";
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
                        $sql = "SELECT * FROM users WHERE email = " . trim($email) . " AND password = " . trim($password);
                        $result = $pdo->query($sql);
                        
                        # If the email and password are present in the `users` table then the login info is valid
                        if ($result = $result->fetch()):
                            setcookie("user_id", $result["id"], time() + 80 * 80 * 24);
                            # echo "Welcome " . $result["name"];
                                
                            # Redirect to profile page
                            header("Location: ./profile");
                            die();
                        else:
                            echo "Either the email and/or the password is wrong.";
                        endif;

                        # Disconnect from database
                        $pdo = null;
                    } catch (PDOException $e) {
                        die($e->getMessage());
                    }
                }
            ?>
            
            <!-- Using post so login information is not present in the URL -->
            <form method="post">
                <fieldset>
                    <input type="email" name="email" required />
                    <label for="email">Email</label>
                </fieldset>

                <fieldset>
                    <input type="password" name="password" required />
                    <label for="password">Password</label>
                </fieldset>

                <button type="submit">
                    Login
                </button>
            </form>

            <div class="register-container-login">
                <small> Need a new account? <a href="./signup"> Sign Up. </a></small>
            </div>
        </div>
    </main>

    <?php include_once "partials/scripts.php"; ?>
</body>

</html>