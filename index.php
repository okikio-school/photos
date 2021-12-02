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
      <h1>Grid</h1>      
      <?php 
          include_once "./db.php";

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
                  echo '<img
                    src="' . $result["url"] . '"
                    alt="' . $result["description"] . '" loading="lazy">';
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
      <!-- <div class="grid">    
        <img
          src="https://res.cloudinary.com/css-tricks/image/upload/f_auto,q_auto/v1568814785/photostream-photos/DSC05466_kwlv0n.jpg"
          alt="A Toyota Previa covered in graffiti" loading="lazy">
        <img
          src="https://res.cloudinary.com/css-tricks/image/upload/f_auto,q_auto/v1568814785/photostream-photos/DSC05621_zgtcco.jpg"
          alt="Interesting living room light through a window" loading="lazy">
        <img
          src="https://res.cloudinary.com/css-tricks/image/upload/f_auto,q_auto/v1568814785/photostream-photos/DSC05513_gfbiwi.jpg"
          alt="Sara on a red bike" loading="lazy">
        <img
          src="https://res.cloudinary.com/css-tricks/image/upload/f_auto,q_auto/v1568814785/photostream-photos/DSC05588_nb0dma.jpg"
          alt="XOXO venue in between talks" loading="lazy">
        <img
          src="https://res.cloudinary.com/css-tricks/image/upload/f_auto,q_auto/v1568814785/photostream-photos/DSC05459_ziuomy.jpg"
          alt="Trees lit by green light during dusk" loading="lazy">
        <img
          src="https://res.cloudinary.com/css-tricks/image/upload/f_auto,q_auto/v1568814785/photostream-photos/DSC05586_oj8jfo.jpg"
          alt="Portrait of Justin Pervorse" loading="lazy">
        <img
          src="https://res.cloudinary.com/css-tricks/image/upload/f_auto,q_auto/v1568814785/photostream-photos/DSC05465_dtkwef.jpg"
          alt="Empty bike racks outside a hotel" loading="lazy">
        <img
          src="https://res.cloudinary.com/css-tricks/image/upload/f_auto,q_auto/v1568814785/photostream-photos/DSC05626_ytsf3j.jpg"
          alt="Heavy rain on an intersection" loading="lazy">
        <img
          src="https://res.cloudinary.com/css-tricks/image/upload/f_auto,q_auto/v1568814785/photostream-photos/DSC05449_l9kukz.jpg"
          alt="Payam Rajabi eating peanut chicken" loading="lazy">
        <img
          src="https://res.cloudinary.com/css-tricks/image/upload/f_auto,q_auto/v1568814785/photostream-photos/DSC05544_aczrb9.jpg"
          alt="Portland skyline sunset" loading="lazy">
        <img
          src="https://res.cloudinary.com/css-tricks/image/upload/f_auto,q_auto/v1568814785/photostream-photos/DSC05447_mvffor.jpg"
          alt="Interior at Nong's" loading="lazy">
        <img
          src="https://res.cloudinary.com/css-tricks/image/upload/f_auto,q_auto/v1568814784/photostream-photos/DSC05501_yirmq8.jpg"
          alt="A kimchi hotdog on a plate" loading="lazy"> 
      </div> -->
    </div>
  </main>

  <?php include_once "partials/scripts.php"; ?>
</body>

</html>