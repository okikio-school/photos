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
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["q"])) {
            try {
                # Create database connection
                $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                echo '<h1>Search results for ' . $query . '</h1>';

                # Search query
                $query = $pdo->quote($_GET['q']);

                # Check if the user_id `images` table
                $sql = "SELECT * FROM images WHERE name = " . $query;
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
                    echo '<div align="center">Can\'t find images for \"' . $query . '\"</div>';
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

  <?php include_once "partials/scripts.php"; ?>
</body>

</html>