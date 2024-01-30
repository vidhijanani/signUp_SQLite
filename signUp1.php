<?php

include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["btn"])) {
        $username = $_POST["username"];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $users[] = ['name' => $username, 'email' => $email, 'password' => $password];
    }
}

try {
    $pdo = new PDO('sqlite:vidhi.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $SQL =
        "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        email TEXT NOT NULL,
        password TEXT NOT NULL
    )";

    $pdo->exec($SQL);

    echo "Table 'users' created successfully.";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
} finally {
    $pdo = null;
}

//create

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["btn"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {

        $pdo = new PDO("sqlite:vidhi.db");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $insertData = "INSERT INTO users (username, email,password) VALUES (:username, :email, :password)";
        $insertstamement = $pdo->prepare($insertData);
        $insertstamement->bindParam(':username', $username);
        $insertstamement->bindParam(':email', $email);
        $insertstamement->bindParam(':password', $password);
        $insertstamement->execute();
    } catch (PDOException $e) {
        die('' . $e->getMessage());
    }
}

// read 

try {
    $pdo = new PDO("sqlite:vidhi.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $selectDataSql = "SELECT * FROM users";
    $stmt = $pdo->query($selectDataSql);

    echo "<p>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>ID: {$row['id']}, Username: {$row['username']}, Email: {$row['email']}
                        <form action='' method='post' style='display:inline;'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <input type='text' name='new_username' placeholder='New Username' required>
                            <input type='text' name='new_password' placeholder='New Password' required>
                            <button type='submit' name='action' value='update'>Update</button>
                        </form>
                        <form action='' method='post' style='display:inline;'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <button type='submit' name='action' value='delete'>Delete</button>;
                        </form>
                        </li>";
    }
    echo "</p>";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
} finally {
    $pdo = null;
}

// UPDATE 
   if (isset($_POST['id'], $_POST['new_username'], $_POST['new_password'])) {
        $userIdToUpdate = $_POST['id'];
        $newUsername = $_POST['new_username'];
        $newPassword = $_POST['new_password'];   

    try{
    $pdo = new PDO("sqlite:vidhi.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     $updateDataSql = "UPDATE users SET username = :new_username, password = :new_password WHERE id = :id";
            $updateStmt = $pdo->prepare($updateDataSql);
            $updateStmt->bindParam(':new_username', $newUsername);
            $updateStmt->bindParam(':new_password', $newPassword);
            $updateStmt->bindParam(':id', $userIdToUpdate);
            $updateStmt->execute();

            echo "Record with ID $userIdToUpdate updated successfully.";
       
    }catch (PDOException $e) {
    die("Error: " . $e->getMessage());
} finally {
    $pdo = null;
}
   }

//    delete 

 if (isset($_POST['id'])) {
        $userIdToDelete = $_POST['id'];

        try {
            // Open the SQLite database file
            $pdo = new PDO("sqlite:vidhi.db");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Delete user data from the 'users' table
            $deleteDataSql = "DELETE FROM users WHERE id = :id";
            $deleteStmt = $pdo->prepare($deleteDataSql);
            $deleteStmt->bindParam(':id', $userIdToDelete);
            $deleteStmt->execute();

            echo "Record with ID $userIdToDelete deleted successfully.";
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        } finally {
            // Close the database connection
            $pdo = null;
        }
    } else {
        echo "ID is required for delete.";
    }


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- bootstrap cdn  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" integrity="sha512-b2QcS5SsA8tZodcDtGRELiGv5SaKSk1vDHDaQRda0htPYWZ6046lr3kJ5bAAQdpV2mmA/4v0wQF9MyU6/pDIAg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <h2 class="mb-4">Sign Up</h2>
            <div class="col-md-6">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="username"> Username</label>
                        <input type="text" placeholder="Enter your full name" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="username"> Email</label>
                        <input type="text" placeholder="Enter your E-mail" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="username"> Password</label>
                        <input type="text" placeholder="Eg-User@123" name="password" id="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary" name="btn"> Submit</button>

                </form>
            </div>
        </div>
    </div>
    <!-- javascripts  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js" integrity="sha512-WW8/jxkELe2CAiE4LvQfwm1rajOS8PHasCCx+knHG0gBHt8EXxS6T6tJRTGuDQVnluuAvMxWF4j8SNFDKceLFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>