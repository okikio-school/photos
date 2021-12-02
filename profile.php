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

        # If the `email`, so, just continue
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
                    echo '<header>';
                    echo '<h1>' . $user["name"] . '</h1>';
                    echo '<h3>' . $user["email"] . '</h3>';
                    echo '</header>';

                    # Check if the user_id  `users` table
                    $sql = "SELECT * FROM images WHERE `user_id` = " . $user["id"];
                    $result = $pdo->query($sql);
                    
                    # If the user_id is present in the `users` table then the iser is already logged in
                    if ($result->fetch()):
                        echo '<div class="grid">\n';
                        while ($result = $result->fetch()) {
                        echo '<img
                            src="' . $result["url"] . '"
                            alt="' . $result["description"] . '" loading="lazy">\n';
                        }
                        echo '</div>\n';
                    else:
                        echo '<div align="center">No images yet.</div>';
                    endif;
                else:
                    echo '<div align="center">Can\'t find user profile.</div>';
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
                if ($user = $result->fetch()):
                    # Check if the user_id from the cookie is already present in the `users` table
                    $sql = "SELECT * FROM images WHERE user_id = " . $user_id;
                    $result = $pdo->query($sql);
                        
                    # If the user_id is present in the `users` table then the iser is already logged in
                    if ($result->fetch()):
                        echo '<header>';
                        echo '<h1>' . $user["name"] . '</h1>';
                        echo '<h3>' . $user["email"] . '</h3>';
                        echo '</header>';

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
                    echo '<div align="center">Can\'t find user profile.</div>';
                endif;

                # Disconnect from database
                $pdo = null;
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        } else {
            echo '<div align="center">Can\'t find user profile, you need to sign in or specifiy a specific user profile.</div>';
        }
      ?>
    </div>
  </main>

  <?php include_once "partials/scripts.php"; ?>
</body>

</html>