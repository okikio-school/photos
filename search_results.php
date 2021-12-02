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

          try {
              echo '<h1>Search Results</h1>';
              
              # Create database connection
              $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              
              # Check if the user_id from the cookie is already present in the `users` table
              $sql = "SELECT * FROM images";
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
                  echo '<div align="center">No images...</div>';
              endif;

              # Disconnect from database
              $pdo = null;
          } catch (PDOException $e) {
              die($e->getMessage());
          }
      ?>
    </div>
  </main>

  <?php include_once "partials/scripts.php"; ?>
</body>

</html>