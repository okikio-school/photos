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

        # If the `user_id` cookie is set and the post request has `userB` set, then add user as a friend
        if (isset($_COOKIE["user_id"]) && isset($_POST["userB"])) {
            include_once "partials/add-friend.php";
        }

        # Show `Remove Friend` button if user is signed in
        $btn = "";
        if (isset($_COOKIE["user_id"])):
            $btn = '<button class="btn already-friends" type="submit">Remove Friend</button>';
        endif;

        # If the `email` is set display the friends list corrosponding to that email
        if (isset($_GET["email"])) {
            try {
                # Create database connection
                $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                # Sanitize email
                $email = $pdo->quote($_GET['email']);

                # Check if the email matches from `users` table
                $sql = "SELECT * FROM users WHERE email = " . $email;
                $statement = $pdo->query($sql);
                
                # If there is a user that has that email in the `users` table then display their friends
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

                    # Make sure userA has friends or that someone else is friends with userA
                    $sql = "SELECT * FROM friends WHERE userA = " . $userA['id'] . " OR userB = " . $userA['id'];
                    $statement = $pdo->query($sql);

                    # Get all the friends of the current user
                    if ($statement->fetch()):
                        echo '<div class="grid">';

                        # Display all users that userA has added as friends 
                        $sql = "SELECT * FROM friends WHERE userA = " . $userA['id'];
                        $statement = $pdo->query($sql);
                        $result = $statement->fetch();

                        if ($result):
                            do {
                                $sql = "SELECT * FROM users WHERE id = " . $result['userB'];
                                $statement2 = $pdo->query($sql);
                                
                                if ($user = $statement2->fetch()):
                                    echo '<div>
                                        <h3 class="username">
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

                        # Display all users that have added userA as their friend
                        $sql = "SELECT * FROM friends WHERE userB = " . $userA['id'];
                        $statement = $pdo->query($sql);
                        $result = $statement->fetch();

                        if ($result):
                            do {
                                $sql = "SELECT * FROM users WHERE id = " . $result['userA'];
                                $statement2 = $pdo->query($sql);
                                
                                if ($user = $statement2->fetch()):
                                    echo '<div>
                                        <h3 class="username">
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

                # Santize input
                $user_id = $pdo->quote($_COOKIE["user_id"]);
                
                # Make sure userA (signed in user) has friends or that someone else is friends with them
                $sql = "SELECT * FROM friends WHERE userA = " . $user_id . " OR userB = " . $user_id;
                $statement = $pdo->query($sql);

                if ($statement->fetch()):
                    echo '<div class="grid">';
                    
                    # Select all users that userA (signed in user) has added as friends 
                    $sql = "SELECT * FROM friends WHERE userA = " . $user_id;
                    $statement = $pdo->query($sql);
                    $result = $statement->fetch();

                    if ($result):
                        do {
                            # Check if the user id from the friend (`userB`) is present in `users` table
                            $sql = "SELECT * FROM users WHERE id = " . $result["userB"]; 
                            $statement2 = $pdo->query($sql);
                            
                            # If the it is, display all friends of userA (signed in user)
                            if ($user = $statement2->fetch()):
                                echo '<div>
                                    <h3 class="username">
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

                    # Select all users that userB (signed in user) has added as friends
                    $sql = "SELECT * FROM friends WHERE userB = " . $user_id;
                    $statement = $pdo->query($sql);
                    $result = $statement->fetch();

                    if ($result):
                        do {
                            # Check if the user id from the friend (`userA`) is present in `users` table
                            $sql = "SELECT * FROM users WHERE id = " . $result["userA"]; 
                            $statement2 = $pdo->query($sql);
                            
                            # If the it is, display all friends of userB (signed in user)
                            if ($user = $statement2->fetch()):
                                echo '<div>
                                    <h3 class="username">
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
</body>

</html>