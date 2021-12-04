<?php 
    include_once "partials/db.php";

    try {
        # Create database connection
        $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        # Santize input
        $User_A = $pdo->quote($_COOKIE["user_id"]);
        $User_B = $pdo->quote($_POST["userB"]);
        
        if ($User_A !== $User_B): 
            # Check if the user_id from the cookie is already present in the `friends` table
            $sql = "SELECT * FROM friends WHERE userA = " . $User_A . " AND userB = " . $User_B;
            $statement = $pdo->query($sql);
            
            # Depending on whether the cookie user_id is present in the `friends` table then toggle between deleting and creating the relation between userA and userB 
            if ($result = $statement->fetch()):
                $sql = "DELETE FROM friends WHERE userA = " . $User_A . " AND userB = " . $User_B;
            else:
                $sql = "INSERT INTO friends (userA, userB) VALUES (?, ?)";
            endif;

            # Begin Transaction
            $pdo->beginTransaction();

            $statement = $pdo->prepare($sql);

            # Use user info. 
            $statement->bindValue(1, $_COOKIE["user_id"]);
            $statement->bindValue(2, $_POST["userB"]);

            # Run prepared sql query
            $statement->execute();

            # Commit Transaction
            $pdo->commit();
        endif;

        # Garbage collect the statement
        $statement = null;

        # Disconnect from database
        $pdo = null;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
?>