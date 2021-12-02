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
      <h1>The Grid</h1>      
      <?php 
          include_once "partials/db.php";
          include_once "partials/images.php";

          try {
              # Create database connection
              $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              
              # Check if the user_id from the cookie is already present in the `users` table
              $sql = "SELECT * FROM images";
              $statement = $pdo->query($sql);
              
              # If the `images` table then the iser is already logged in
              if ($result = $statement->fetch()):
                echo '<div class="grid">';
                do {
                  // echo '<img
                  //   src="' . $result["url"] . '"
                  //   alt="' . $result["description"] . '" loading="lazy">';
                  $sql = "SELECT * FROM users WHERE id = " . $result["user_id"];
                  $statement2 = $pdo->query($sql);
                  $user = $statement2->fetch();
                  images($result["name"], $result["url"], $result["description"], $user["name"], $user["email"]);
                } while ($result = $statement->fetch());
                echo '</div>';
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