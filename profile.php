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
        include_once "partials/db.php";
        include_once "partials/images.php";

        # If the `user_id` cookie is set and the post request has `userB` set, then add user as a friend
        if (isset($_COOKIE["user_id"]) && isset($_POST["userB"])) {
            include_once "partials/add-friend.php";
        }

        # If the `email` is set display the friends list corrosponding to that email
        if (isset($_GET["email"])) {
            try {
                # Create database connection
                $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                # Santize `email` input
                $email = $pdo->quote($_GET["email"]);
                
                # Check if the email matches from `users` table
                $sql = "SELECT * FROM users WHERE email = " . $email;
                $statement = $pdo->query($sql);
                
                # If there is a user that has that email in the `users` table then display their profile
                if ($user = $statement->fetch()):
                    echo '<header>';
                    echo '<h1>' . $user["name"] . '</h1>';
                    echo '<p>' . $user["email"] . '</p>';
                    echo '</header>';

                    if (isset($_COOKIE["user_id"])) {
                        $btnText = "";
                        $btnClass = "";

                        $User_A = $pdo->quote($_COOKIE["user_id"]);
                        $User_B = $pdo->quote($user['id']);
                        
                        # Check if user has any friends, if so, then display has "Already Friends" on profile, 
                        # otherwise, display "Add as Friend"
                        $sql = "SELECT * FROM friends WHERE userA = " . $User_A . " AND userB = " . $User_B;
                        $statement = $pdo->query($sql);

                        if ($result = $statement->fetch()):
                            $btnText = "Already Friends";
                            $btnClass = "already-friends";
                        else:
                            $btnText = "Add as Friend";
                        endif;

                        echo '<br>';
                        echo '<form class="profile-actions" method="POST"> 
                            <a class="btn" href="./friends?email=' . $user['email'] . '">Friends</a>
                            <input type="hidden" name="userB" value="' . $user['id'] . '" />
                            <button class="btn ' . $btnClass . '" type="submit">' . $btnText . '</button>
                        </form>';
                    }

                    echo '<br>';
                    echo '<br>';

                    # Select images that corrospond to the users id
                    $sql = "SELECT * FROM images WHERE `user_id` = " . $user["id"];
                    $statement = $pdo->query($sql);
                    
                    if ($result = $statement->fetch()):
                        echo '<div class="grid">';
                        do {
                            # Display images with user info.
                            images($result["name"], $result["url"], $result["description"], $user["name"], $user["email"]);
                        } while ($result = $statement->fetch());
                        echo '</div>';
                    else:
                        echo '<br><div class="text-center" align="center">No images yet.</div>';
                    endif;
                else:
                    echo '<br><div class="text-center" align="center">Can\'t find user profile.</div>';
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
                $statement = $pdo->query($sql);

                # If the user_id is present in the `users` table then the iser is already logged in
                if ($user = $statement->fetch()):
                    echo '<header>';
                    echo '<h1>' . $user["name"] . '</h1>';
                    echo '<p>' . $user["email"] . '</p>';
                    echo '</header>';
                    echo '<br>';
                    echo '<form class="profile-actions" action="./signout" method="POST"> 
                        <a class="btn" href="./friends">Friends</a>
                        <button class="btn" type="submit">Sign Out</button>
                    </form>';
                    echo '<br>';
                    echo '<br>';

                    # Select the images corrosponding to the `user_id`
                    $sql = "SELECT * FROM images WHERE user_id = " . $user_id;
                    $statement = $pdo->query($sql);

                    if ($result = $statement->fetch()):
                        echo '<div class="grid">';
                        do {
                            # Display images with user info.
                            images($result["name"], $result["url"], $result["description"], $user["name"], $user["email"]);
                        } while ($result = $statement->fetch());
                        echo '</div>';
                    else:
                        echo '<br><div class="text-center" align="center">No images yet.</div>';
                    endif;
                else:
                    echo '<br><div class="text-center" align="center">Can\'t find user profile.</div>';
                endif;

                # Disconnect from database
                $pdo = null;
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        } else {
            echo '<br><div align="center">Can\'t find user profile, you need to sign in or specifiy a specific user profile.</div>';
        }
      ?>
    </div>
  </main>

  <?php include_once "partials/scripts.php"; ?>
</body>

</html>