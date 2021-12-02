<!DOCTYPE html>
<html>

<head>
  <title>Art Gallery</title>

  <?php include_once "partials/meta.php"; ?>
</head>

<body>
  <?php include_once "partials/navbar.php"; ?>

  <main>
    <div class="container">          
      <?php 
        include_once "./db.php";

        # If the `email` , so, just continue
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["email"])) {
            try {
                # Create database connection
                $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                # Santize `email` input
                $email = $pdo->quote($_GET["email"]);
                
                # Check if the user_id from the cookie is already present in the `users` table
                $sql = "SELECT * FROM users WHERE email = " . $email;
                $result = $pdo->query($sql);
                
                # If the user_id is present in the `users` table then the iser is already logged in
                if ($user = $result->fetch()):
                    # Check if the user_id  `users` table
                    $sql = "SELECT * FROM images WHERE user_id = " . $user["id"];
                    $result = $pdo->query($sql);
                    
                    # If the user_id is present in the `users` table then the iser is already logged in
                    if ($result->fetch()):
                        echo '<h1>' . $user["name"] . '</h1>';

                        echo '<div class="grid">\n';
                        while ($result = $result->fetch()) {
                        echo '<img
                            src="' . $result["url"] . '"
                            alt="' . $result["description"] . '" loading="lazy">\n';
                        }
                        echo '</div>\n';
                    else:
                        echo '<div align="center">Can\'t find user profile.</div>';
                    endif;
                else:
                    echo '<a class="signup-btn" href="./signup">Sign Up / Log in</a>';
                endif;
                

                # Disconnect from database
                $pdo = null;
            } catch (PDOException $e) {
                die($e->getMessage());
            }

        } 
        
        # If the `user_id` cookie is set the user is already logged in, so, just continue
        else if (isset($_COOKIE["user_id"])) {
            try {
                # Create database connection
                $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                # Santize `user_id` input
                $user_id = $pdo->quote($_COOKIE["user_id"]);
                
                # Check if the user_id from the cookie is already present in the `users` table
                $sql = "SELECT * FROM users WHERE id = " . $user_id;
                $result = $pdo->query($sql);
                
                # If the user_id is present in the `users` table then the iser is already logged in
                if ($result = $result->fetch()):
                    echo '<a class="signup-btn" href="./profile?email=' . $result["email"]  . '">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.754 14a2.249 2.249 0 0 1 2.25 2.249v.918a2.75 2.75 0 0 1-.513 1.599C17.945 20.929 15.42 22 12 22c-3.422 0-5.945-1.072-7.487-3.237a2.75 2.75 0 0 1-.51-1.595v-.92a2.249 2.249 0 0 1 2.249-2.25h11.501ZM12 2.004a5 5 0 1 1 0 10 5 5 0 0 1 0-10Z" fill="currentColor"/></svg>' . 
                        $result["name"] . 
                    '</a>';
                else:
                    echo '<a class="signup-btn" href="./signup">Sign Up / Log in</a>';
                endif;

                # Disconnect from database
                $pdo = null;
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        } else {
            echo '<a class="signup-btn" href="./signup">Sign Up / Log in</a>';
        }
      ?>
    </div>
  </main>

  <?php include_once "partials/scripts.php"; ?>
</body>

</html>