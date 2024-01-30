<?php

include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                createRecord();
                break;
            case 'update':
                updateRecord();
                break;
            case 'delete':
                deleteRecord();
                break;
            default:
                echo "Invalid action.";
        }
    }
}

// Function to create a new record
function createRecord() {
    // Check if the required fields are set
    if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {
        $name = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        try {
            // Open the SQLite database file
            $pdo = new PDO("sqlite:users.db");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insert user data into the 'users' table
            $insertDataSql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $insertStmt = $pdo->prepare($insertDataSql);
            $insertStmt->bindParam(':username', $name);
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':password', $password);
            $insertStmt->execute();

            echo "User added successfully.";
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        } finally {
            // Close the database connection
            $pdo = null;
        }
    } else {
        echo "Username, email, and password are required.";
    }
}

// Function to update an existing record
function updateRecord() {
    // Check if the required fields are set
    if (isset($_POST['id'], $_POST['new_username'], $_POST['new_password'])) {
        $userIdToUpdate = $_POST['id'];
        $newUsername = $_POST['new_username'];
        $newPassword = $_POST['new_password'];

        try {
            // Open the SQLite database file
            $pdo = new PDO("sqlite:users.db");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Update user data in the 'users' table
            $updateDataSql = "UPDATE users SET username = :new_username, password = :new_password WHERE id = :id";
            $updateStmt = $pdo->prepare($updateDataSql);
            $updateStmt->bindParam(':new_username', $newUsername);
            $updateStmt->bindParam(':new_password', $newPassword);
            $updateStmt->bindParam(':id', $userIdToUpdate);
            $updateStmt->execute();

            echo "Record with ID $userIdToUpdate updated successfully.";
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        } finally {
            // Close the database connection
            $pdo = null;
        }
    } else {
        echo "ID, new username, and new password are required for update.";
    }
}

// Function to delete a record
function deleteRecord() {
    // Check if the required fields are set
    if (isset($_POST['id'])) {
        $userIdToDelete = $_POST['id'];

        try {
            // Open the SQLite database file
            $pdo = new PDO("sqlite:users.db");
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
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- bootstrap cdn  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"
        integrity="sha512-b2QcS5SsA8tZodcDtGRELiGv5SaKSk1vDHDaQRda0htPYWZ6046lr3kJ5bAAQdpV2mmA/4v0wQF9MyU6/pDIAg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <h2 class="mb-4">Sign Up</h2>
            <div class="col-md-6">
                <form action="" method="post">
                    <div class="form-group">
                        <label for=""> Username</label>
                        <input type="text" placeholder="Enter your username" name="username" id="username"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for=""> E-mail</label>
                        <input type="text" placeholder="Enter your email" name="email" id="email"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for=""> Password</label>
                        <input type="text" placeholder="Enter your Password" name="password" id="password"
                            class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="action" value="create">Sign Up</button>
                </form>
            </div>
        </div>
        <div class="row justify-content-center mt-5">
            <h2>Users</h2>
            <div class="col-md-6">
                <?php
                try {
                    // Open the SQLite database file
                    $pdo = new PDO("sqlite:users.db");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Retrieve data from the 'users' table
                    $selectDataSql = "SELECT * FROM users";
                    $stmt = $pdo->query($selectDataSql);

                    // Display the retrieved data
                    echo "<ul>";
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
                            <button type='submit' name='action' value='delete'>Delete</button>
                        </form></li>";
                    }
                    echo "</ul>";

                } catch (PDOException $e) {
                    die("Error: " . $e->getMessage());
                } finally {
                    // Close the database connection
                    $pdo = null;
                }
                
                ?>
            </div>
        </div>
    </div>

    <!-- javascripts  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"
        integrity="sha512-WW8/jxkELe2CAiE4LvQfwm1rajOS8PHasCCx+knHG0gBHt8EXxS6T6tJRTGuDQVnluuAvMxWF4j8SNFDKceLFg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>
