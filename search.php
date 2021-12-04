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

        # If `search` in part of a get request, continue
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
            try {
                # Create database connection
                $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                # Sanitize search query
                $query = $pdo->quote($_GET['search']);
                echo '<h2>Search results for ' . $query . '</h2>';
                echo '<br>';
                echo '<h3 class="font-600">Images</h3>';
                echo '<br>';

                # Check if there are image info. that match the search query
                $sql = 'SELECT * FROM images WHERE name LIKE "%' . $_GET['search'] . '%" OR description LIKE "%' . $_GET['search'] . '%"';
                $statement = $pdo->query($sql);
                
                if ($result = $statement->fetch()):
                    echo '<div class="grid">';
                    do {
                      # Get user info. for image
                      $sql = "SELECT * FROM users WHERE id = " . $result["user_id"];
                      $statement2 = $pdo->query($sql);
                      $user = $statement2->fetch();

                      # Display images with user info.
                      images($result["name"], $result["url"], $result["description"], $user["name"], $user["email"]);
                    } while ($result = $statement->fetch());
                    echo '</div>';
                else:
                    echo '<div class="text-center" align="center">Can\'t find images for ' . $query . '</div>';
                endif;

                echo '<br>';
                echo '<h3 class="font-600">Users</h3>';
                echo '<br>';

                # Check if there are user info. that match the search query
                $sql = 'SELECT * FROM users WHERE name LIKE "%' . $_GET['search'] . '%" OR email LIKE "%' . $_GET['search'] . '%"';
                $statement = $pdo->query($sql);
                
                if ($result = $statement->fetch()):
                    echo '<div class="grid">';
                    do {
                      echo '<div>
                        <h4 class="username">
                            <a href="./profile?email=' . $result["email"] . '">' . $result["name"] . '</a>
                        </h4>
                        <br>
                        <p>' . $result["email"] . '</p>
                      </div>';
                    } while ($result = $statement->fetch());
                    echo '</div>';
                else:
                    echo '<div class="text-center" align="center">Can\'t find users for ' . $query . '</div>';
                endif;
                
                # Disconnect from database
                $pdo = null;
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        } else {
            echo '<div align="center">No images with that name.</div>';
        }
      ?>
    </div>
  </main>
</body>

</html>