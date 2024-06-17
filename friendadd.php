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

    $userId = $user['friend_id']; // Retrieve the user's ID
    $numOfFriends = $user['num_of_friends']; // Retrieve the number of friends of the user

    // Pagination settings
    $limit = 5; // Number of names per page
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Current page number
    $start = ($page - 1) * $limit; // Starting point for the query

    // Retrieve all users from the friends table who are not friends of the current user, and number of mutual friends
    $sql = "SELECT friends.*, 
            (SELECT COUNT(*) FROM myfriends WHERE (friend_id1 = friends.friend_id AND friend_id2 IN (SELECT friend_id2 FROM myfriends WHERE friend_id1 = ?)) 
            OR (friend_id2 = friends.friend_id AND friend_id1 IN (SELECT friend_id2 FROM myfriends WHERE friend_id1 = ?))) AS mutual_friends 
            FROM friends WHERE friend_id != ? AND friend_id NOT IN (SELECT friend_id2 FROM myfriends WHERE friend_id1 = ? UNION SELECT friend_id1 FROM myfriends WHERE friend_id2 = ?) LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiiii", $userId, $userId, $userId, $userId, $userId, $start, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $friends = $result->fetch_all(MYSQLI_ASSOC);

    // Get the total number of pages
    $sql = "SELECT COUNT(*) FROM friends WHERE friend_id != ? AND friend_id NOT IN (SELECT friend_id2 FROM myfriends WHERE friend_id1 = ? UNION SELECT friend_id1 FROM myfriends WHERE friend_id2 = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $userId, $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalFriends = $result->fetch_assoc()["COUNT(*)"];
    $pages = ceil($totalFriends / $limit);

    // Add friend function
    function addFriend($friendId)
    {
        require_once 'settings.php';
        global $conn, $userId, $numOfFriends;
        // Add the friend to the myfriends table
        $sql = "INSERT INTO myfriends (friend_id1, friend_id2) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $friendId);
        $stmt->execute();

        // Update the number of friends of the logged in user
        $numOfFriends++;
        $sql = "UPDATE friends SET num_of_friends = ? WHERE friend_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $numOfFriends, $userId);
        $stmt->execute();

        // Get the number of friends of the friend
        $sql = "SELECT num_of_friends FROM friends WHERE friend_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $friendId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $numOfFriends2 = $row["num_of_friends"];

        // Update the number of friends of the friend
        $numOfFriends2++;
        $sql = "UPDATE friends SET num_of_friends = ? WHERE friend_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $numOfFriends2, $friendId);
        $stmt->execute();
    }

    // Add friend button
    if (isset($_POST["friend_id"])) {
        addFriend($_POST["friend_id"]);
        
        // keep the current page after adding a friend
        header("Location: friendadd.php?page={$_POST['page']}");
        exit();
    }

    // Retrieve total number of friends from the myfriends table for the current user
    $sql = "SELECT COUNT(*) as total_friends FROM myfriends WHERE friend_id1 = ? OR friend_id2 = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalFriends = $row['total_friends'];

    // Close the database connection
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend Add</title>
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
    <h2 class="sub-header"><?php echo $user['profile_name']; ?>'s Add Friend Page</h2>
    <p class="info">Total number of friends is <?php echo $totalFriends; ?></p>

    <table class="friend-table">
        <tr>
            <th>Profile Name</th>
            <th>Mutual Friends</th>
            <th>Action</th>
        </tr>
        <?php foreach ($friends as $friend): ?>
            <tr>
                <td><?php echo $friend['profile_name']; ?></td>
                <td><?php echo $friend['mutual_friends']; ?> mutual friend(s)</td>
                <td>
                    <form action="friendadd.php" method="post">
                        <input type="hidden" name="page" value="<?php echo $page; ?>">
                        <input type="hidden" name="friend_id" value="<?php echo $friend['friend_id']; ?>">
                        <input class="button" type="submit" value="Add as friend">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="friendadd.php?page=<?php echo $page - 1; ?>">Previous</a>
        <?php endif; ?>

        <?php if ($page < $pages): ?>
            <a href="friendadd.php?page=<?php echo $page + 1; ?>">Next</a>
        <?php endif; ?>
    </div>

    <div class="button-container">
        <a class="button1" href="friendlist.php">Friend List</a>
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