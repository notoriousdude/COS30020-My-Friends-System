<?php
    session_start();
    require_once 'settings.php'; 

    // Check if user is logged in
    if (!isset($_SESSION['email'])) {
        header('Location: login.php');
        exit;
    }

    // Retrieve user data from the database
    $sql = "SELECT * FROM friends WHERE friend_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $_SESSION['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Retrieve friends from the database
    $sql = "SELECT f.* FROM friends f 
            JOIN myfriends mf ON (f.friend_id = mf.friend_id2 OR f.friend_id = mf.friend_id1) 
            WHERE (mf.friend_id1 = ? OR mf.friend_id2 = ?) 
            AND f.friend_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii', $user['friend_id'], $user['friend_id'], $user['friend_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $friends = $result->fetch_all(MYSQLI_ASSOC);

    function deleteFriend($friendId) {
        global $conn;

        // Delete friend from the database
        $sql = "DELETE FROM myfriends WHERE (friend_id2 = ? AND friend_id1 = (SELECT friend_id FROM friends WHERE friend_email = ?)) OR (friend_id1 = ? AND friend_id2 = (SELECT friend_id FROM friends WHERE friend_email = ?))";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isis', $friendId, $_SESSION['email'], $friendId, $_SESSION['email']);
        $stmt->execute();
    }

    // Unfriend button
    if (isset($_POST["unfriend"])) {
        deleteFriend($_POST["friendId"]);

        // Redirect to the friendlist page
        header("Location: friendlist.php");
        exit();
    }

    // Close the database connection
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend List</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;700&family=Roboto:wght@400;500;700&family=Rubik:wght@400;500;700&family=Ubuntu:ital,wght@0,400;0,500;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <nav class="main-nav">
        <ul class="nav-list">
            <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="signup.php" class="nav-link">Sign-up</a></li>
            <li class="nav-item"><a href="login.php" class="nav-link">Log-in</a></li>
            <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
        </ul>
    </nav>

    <h1 class="main-header">My Friend System</h1>
    <h2 class="sub-header"><?php echo $user['profile_name']; ?>'s Friend List Page</h2>
    <p class="info">Total number of friends is <?php echo count($friends); ?></p>

    <table class="friend-table">
        <tr>
            <th>Profile Name</th>
            <th>Action</th>
        </tr>
        <?php foreach ($friends as $friend): ?>
            <tr>
                <td><?php echo $friend['profile_name']; ?></td>
                <td>
                    <form method="POST" action="friendlist.php">
                        <input type="hidden" name="friendId" value="<?php echo $friend['friend_id']; ?>">
                        <button class="button" type="submit" name="unfriend">Unfriend</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="button-container">
        <a class="button1" href="friendadd.php">Add Friends</a>
        <a class="button1" href="logout.php">Log Out</a>
    </div>

    <footer>
        <div class="f1">
            <h3>Assignment 2</h3>
            <i class="fa fa-address-book"></i>
        </div>
        <div class="f2">
            <p>COPYRIGHT &copy;2024</p>
        </div>
    </footer>
</body>
</html>