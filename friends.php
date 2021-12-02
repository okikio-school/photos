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

        # If the `user_id` cookie is set the user is already logged in, so, just continue
        if (isset($_COOKIE["user_id"]) && isset($_POST["userB"])) {
            include_once "partials/add-friend.php";
        }

        $btn = "";
        if (isset($_COOKIE["user_id"])):
            $btn = '<button class="btn already-friends" type="submit">Remove Friend</button>';
        endif;

        # If the `email`, so, just continue
        if (isset($_GET["email"])) {
            try {
                # Create database connection
                $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                # Sanitize email
                $email = $pdo->quote($_GET['email']);

                # Check if the user_id `images` table
                $sql = "SELECT * FROM users WHERE email = " . $email;
                $statement = $pdo->query($sql);
                
                # If the user_id is present in the `users` table then the iser is already logged in
                if ($userA = $statement->fetch()):
                    echo '<a href="./profile?email=' . $userA['email'] . '" class="back-to-profile">
                        <div>
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M10.295 19.716a1 1 0 0 0 1.404-1.425l-5.37-5.29h13.67a1 1 0 1 0 0-2H6.336L11.7 5.714a1 1 0 0 0-1.404-1.424l-6.924 6.822a1.25 1.25 0 0 0 0 1.78l6.924 6.823Z" fill="currentColor"/></svg>
                            <h4 class="font-500">Go to Profile</h4>
                        </div>
                    </a>';
                    echo '<br>';
                    echo '<br>';
                    echo '<br>';

                    # Check if the user_id `images` table
                    $sql = "SELECT * FROM friends WHERE userA = " . $userA['id'] . " OR userB = " . $userA['id'];
                    $statement = $pdo->query($sql);

                    if ($statement->fetch()):
                        echo '<div class="grid">';
                        # Check if the user_id from the cookie is already present in the `users` table
                        $sql = "SELECT * FROM friends WHERE userA = " . $userA['id'];
                        $statement = $pdo->query($sql);
                        $result = $statement->fetch();

                        if ($result):
                            do {
                                # Check if the user_id from the cookie is already present in the `users` table
                                $sql = "SELECT * FROM users WHERE id = " . $result['userB'];
                                $statement2 = $pdo->query($sql);
                                
                                # If the user_id is present in the `users` table then the iser is already logged in
                                if ($user = $statement2->fetch()):
                                    echo '<div>
                                        <h3>
                                            <a href="./profile?email=' . $user["email"] . '">' . $user["name"] . '</a>
                                        </h3>
                                        <br>
                                        <p>' . $user["email"] . '</p>
                                        <br>
                                        <form class="profile-actions" method="POST"> 
                                            <a class="btn" href="./friends?email=' . $user["email"] . '">Friends</a>
                                            <input type="hidden" name="userB" value="' . $user['id'] . '" />' .
                                            $btn . 
                                        '</form>
                                    </div>';
                                else:
                                    echo '<br><div class="text-center" align="center">Can\'t find friends.</div>';
                                endif;
                            } while ($result = $statement->fetch());
                        endif;

                        # Check if the user_id from the cookie is already present in the `users` table
                        $sql = "SELECT * FROM friends WHERE userB = " . $userA['id'];
                        $statement = $pdo->query($sql);
                        $result = $statement->fetch();

                        if ($result):
                            do {
                                # Check if the user_id from the cookie is already present in the `users` table
                                $sql = "SELECT * FROM users WHERE id = " . $result['userA'];
                                $statement2 = $pdo->query($sql);
                                
                                # If the user_id is present in the `users` table then the iser is already logged in
                                if ($user = $statement2->fetch()):
                                    echo '<div>
                                        <h3>
                                            <a href="./profile?email=' . $user["email"] . '">' . $user["name"] . '</a>
                                        </h3>
                                        <br>
                                        <p>' . $user["email"] . '</p>
                                        <br>
                                        <form class="profile-actions" method="POST"> 
                                            <a class="btn" href="./friends?email=' . $user["email"] . '">Friends</a>
                                            <input type="hidden" name="userB" value="' . $user['id'] . '" />' .
                                            $btn . 
                                        '</form>
                                    </div>';
                                else:
                                    echo '<br><div class="text-center" align="center">Can\'t find friends.</div>';
                                endif;
                            } while ($result = $statement->fetch());
                        endif;
                        echo '</div>';
                    else:
                        echo '<br><div class="text-center" align="center">No friends yet.</div>';
                    endif;
                else:
                    echo '<br><div class="text-center" align="center">Can\'t find users for ' . $email . '</div>';
                endif;
                
                # Disconnect from database
                $pdo = null;
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        } else if (isset($_COOKIE["user_id"])) {
            echo '<a href="./profile" class="back-to-profile">
                <div>
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M10.295 19.716a1 1 0 0 0 1.404-1.425l-5.37-5.29h13.67a1 1 0 1 0 0-2H6.336L11.7 5.714a1 1 0 0 0-1.404-1.424l-6.924 6.822a1.25 1.25 0 0 0 0 1.78l6.924 6.823Z" fill="currentColor"/></svg>
                    <h4 class="font-500">Back to Profile</h4>
                </div>
            </a>';
            echo '<br>';
            echo '<br>';
            echo '<br>';

            try {
                # Create database connection
                $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                # Santize `user_id` input
                $user_id = $pdo->quote($_COOKIE["user_id"]);
                
                # Check if the user_id from the cookie is already present in the `users` table
                $sql = "SELECT * FROM friends WHERE userA = " . $user_id . " OR userB = " . $user_id;
                $statement = $pdo->query($sql);

                # If the user_id is present in the `users` table then the iser is already logged in
                if ($statement->fetch()):
                    echo '<div class="grid">';

                    # Check if the user_id from the cookie is already present in the `users` table
                    $sql = "SELECT * FROM friends WHERE userA = " . $user_id;
                    $statement = $pdo->query($sql);
                    $result = $statement->fetch();

                    if ($result):
                        do {
                            # Check if the user_id from the cookie is already present in the `users` table
                            $sql = "SELECT * FROM users WHERE id = " . $result["userB"]; 
                            $statement2 = $pdo->query($sql);
                            
                            # If the user_id is present in the `users` table then the iser is already logged in
                            if ($user = $statement2->fetch()):
                                echo '<div>
                                    <h3>
                                        <a href="./profile?email=' . $user["email"] . '">' . $user["name"] . '</a>
                                    </h3>
                                    <br>
                                    <p>' . $user["email"] . '</p>
                                    <br>
                                    <form class="profile-actions" method="POST"> 
                                        <a class="btn" href="./friends?email=' . $user["email"] . '">Friends</a>
                                        <input type="hidden" name="userB" value="' . $user['id'] . '" />' .
                                        $btn . 
                                    '</form>
                                </div>';
                            else:
                                echo '<br><div class="text-center" align="center">Can\'t find friends.</div>';
                            endif;
                        } while ($result = $statement->fetch());
                    endif;

                    # Check if the user_id from the cookie is already present in the `users` table
                    $sql = "SELECT * FROM friends WHERE userB = " . $user_id;
                    $statement = $pdo->query($sql);
                    $result = $statement->fetch();

                    if ($result):
                        do {
                            # Check if the user_id from the cookie is already present in the `users` table
                            $sql = "SELECT * FROM users WHERE id = " . $result["userA"]; 
                            $statement2 = $pdo->query($sql);
                            
                            # If the user_id is present in the `users` table then the iser is already logged in
                            if ($user = $statement2->fetch()):
                                echo '<div>
                                    <h3>
                                        <a href="./profile?email=' . $user["email"] . '">' . $user["name"] . '</a>
                                    </h3>
                                    <br>
                                    <p>' . $user["email"] . '</p>
                                    <br>
                                    <form class="profile-actions" method="POST"> 
                                        <a class="btn" href="./friends?email=' . $user["email"] . '">Friends</a>
                                        <input type="hidden" name="userB" value="' . $user['id'] . '" />' .
                                        $btn . 
                                    '</form>
                                </div>';
                            else:
                                echo '<br><div class="text-center" align="center">Can\'t find friends.</div>';
                            endif;
                        } while ($result = $statement->fetch());
                    endif;
                    echo '</div>';
                else:
                    echo '<br><div class="text-center" align="center">No friends yet.</div>';
                endif;

                # Disconnect from database
                $pdo = null;
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        } else {
            echo '<br><div align="center">Can\'t find friends from user profile, you need to sign in or specifiy a specific user to check for their friends.</div>';
        }
      ?>
    </div>
  </main>

  <?php include_once "partials/scripts.php"; ?>
</body>

</html>