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
      <h1 class="text-center font-700">You're now Signed out!</h1>      
      <?php 
          include_once "partials/db.php";
          if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_COOKIE['user_id'])) {
            unset($_COOKIE['user_id']);
            setcookie('user_id', '', time() - 3600, './'); // empty value and old timestamp
            
            # Redirect to profile page
            header("Location: ./signout");
            die();
          }
      ?>
    </div>
  </main>

  <?php include_once "partials/scripts.php"; ?>
</body>

</html>