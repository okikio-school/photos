<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>

    <?php include_once "partials/meta.php"; ?>
    <link rel="stylesheet" type="text/css" href="styles/signup.css">
</head>

<body>
    <!-- Navbar partial -->
    <!-- I got tired of copying and pasting the code for the navbar -->
    <?php include_once "partials/navbar.php"; ?>

    <main class="signup">
        <div class="container">
            <header>
                <h1 class="font-serif"> Upload image </h1>
            </header>

            <?php 
                include_once "./db.php";

                # Only run if the form is submitted and a user is signed in
                if (isset($_COOKIE["user_id"]) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"]) && isset($_POST["url"])) {
                    try {
                        # Create database connection
                        $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                        try {
                            # Insert the image info. into the `images` table
                            $sql = "INSERT INTO images (name, description, url, likes, user_id) VALUES (:name, :description, :url, :likes, :user_id)";

                            # Begin Transaction
                            $pdo->beginTransaction();

                            $statement = $pdo->prepare($sql);

                            # Use image info. from the form in sql query
                            $statement->bindValue(':name', $_POST['name']);
                            $statement->bindValue(':description', $_POST['description']);

                            $statement->bindValue(':url', $_POST['url']);
                            $statement->bindValue(':likes', $_POST['likes']);

                            $statement->bindValue(':user_id', $_COOKIE["user_id"]);

                            # Run prepared sql query
                            $statement->execute();

                            # Commit Transaction
                            $pdo->commit();

                            # Garbage collect the statement
                            $statement = null;

                            # Success
                            echo "<h2 class=\"text-center success\">Survey Completed Successfully.</h2>";
                        } catch (PDOException $e) {
                            $pdo->rollback();
                            throw $e;
                        }

                        # Disconnect from database
                        $pdo = null;
                    } catch (PDOException $e) {
                        die($e->getMessage());
                    }
                }
            ?>
            
            <!-- Using post so signup information is not present in the URL -->
            <form>
                <fieldset>
                    <input type="text" name="name" required />
                    <label for="name">Name</label>
                </fieldset>
                
                <fieldset>
                    <input type="text" name="description" required />
                    <label for="description">Description</label>
                </fieldset>

                <fieldset>
                    <input type="text" name="tags" />
                    <label for="tags">Tags</label>
                </fieldset>

                <button id="upload_widget"  class="cloudinary-button">
                    Upload Image
                </button>
            </form>
        </div>
    </main>

    <?php include_once "partials/scripts.php"; ?>
</body>

</html>