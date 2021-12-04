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
              
              # Select all images from the `images` table
              $sql = "SELECT * FROM images";
              $statement = $pdo->query($sql);
              
              # If there are images display, them, otherwise display that there are no image
              if ($result = $statement->fetch()):
                echo '<div class="grid">';
                do {
                  // Select users who have the resulting `user_id` from the images table
                  $sql = "SELECT * FROM users WHERE id = " . $result["user_id"];
                  $statement2 = $pdo->query($sql);
                  $user = $statement2->fetch();

                  // Display images with user info. 
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
</body>

</html>