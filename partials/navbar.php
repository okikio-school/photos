  <nav>
    <div class="navbar">
      <div class="navbar-container">
        <!-- <img src="stoK.jpg" width="150" alt="Logo" /> --> 
        <?php
          $aboutClass = "";
          $homeClass = "";
          if ($_SERVER['REQUEST_URI'] == "/photos/") {
            $homeClass = 'active';
          } else if ($_SERVER['REQUEST_URI'] == "/photos/about") {
            $aboutClass = 'active';
          }

          echo '<a class="' . $homeClass . '" href="./">Home</a>';
          echo '<a class="' . $aboutClass . '" href="./about">About</a>';
        ?>

        <div class="flex-grow"></div>

        <form action="./search.php" method="GET" class="search">
          <input type="text" placeholder="Search..." name="search">
          <button type="submit" tabindex="-1">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M10 2.5a7.5 7.5 0 0 1 5.964 12.048l4.743 4.745a1 1 0 0 1-1.32 1.497l-.094-.083-4.745-4.743A7.5 7.5 0 1 1 10 2.5Zm0 2a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11Z"
                fill="currentColor" />
            </svg>
          </button>
        </form>   
          
        <?php 
            include_once "./db.php";

            # If the `user_id` cookie is set the user is already logged in, so, just continue
            if (isset($_COOKIE["user_id"])) {
                try {
                    # Create database connection
                    $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    # Santize `user_id` input
                    $user_id = $pdo->quote($_COOKIE["user_id"]);
                    
                    # Check if the user_id from the cookie is already present in the `users` table
                    $sql = "SELECT * FROM users WHERE id = " . $user_id;
                    $result = $pdo->query($sql);
                    
                    # If the user_id is present in the `users` table then the iser is already logged in
                    if ($result = $result->fetch()):
                        // ?email=' . $result["email"]  . '
                        echo '<a class="signup-btn" href="./profile">
                          <svg width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.754 14a2.249 2.249 0 0 1 2.25 2.249v.918a2.75 2.75 0 0 1-.513 1.599C17.945 20.929 15.42 22 12 22c-3.422 0-5.945-1.072-7.487-3.237a2.75 2.75 0 0 1-.51-1.595v-.92a2.249 2.249 0 0 1 2.249-2.25h11.501ZM12 2.004a5 5 0 1 1 0 10 5 5 0 0 1 0-10Z" fill="currentColor"/></svg>' .
                          '<span>' . $result["name"] . '</span>' . 
                        '</a>' .
                        '<a href="./upload_image" class="cloudinary-button" title="Upload files...">
                          <svg width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                              d="M5.25 3.495h13.498a.75.75 0 0 0 .101-1.493l-.101-.007H5.25a.75.75 0 0 0-.102 1.493l.102.007Zm6.633 18.498L12 22a1 1 0 0 0 .993-.884L13 21V8.41l3.294 3.292a1 1 0 0 0 1.32.083l.094-.083a1 1 0 0 0 .083-1.32l-.083-.094-4.997-4.997a1 1 0 0 0-1.32-.083l-.094.083-5.004 4.996a1 1 0 0 0 1.32 1.499l.094-.083L11 8.415V21a1 1 0 0 0 .883.993Z"
                              fill="currentColor" />
                          </svg>
                        </a>';
                    else:
                        echo '<a class="signup-btn" href="./signup">Sign Up / Log in</a>';
                    endif;

                    # Disconnect from database
                    $pdo = null;
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
            } else {
              echo '<a class="signup-btn" href="./signup">Sign Up / Log in</a>';
            }
        ?>
      </div>
    </div>
  </nav>