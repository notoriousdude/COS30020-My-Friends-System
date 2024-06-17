<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Friend System</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;700&family=Roboto:wght@400;500;700&family=Rubik:wght@400;500;700&family=Ubuntu:ital,wght@0,400;0,500;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <nav class="main-nav">
        <ul class="nav-list">
            <li class="nav-item"><a href="index.php" class="nav-link active">Home</a></li>
            <li class="nav-item"><a href="signup.php" class="nav-link">Sign-up</a></li>
            <li class="nav-item"><a href="login.php" class="nav-link">Log-in</a></li>
            <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
        </ul>
    </nav>
    <h1 class="main-header">My Friend System</h1>
    <p class="info"><strong class="info-label">Name:</strong> Duc Nhan Nguyen</p>
    <p class="info"><strong class="info-label">Student ID:</strong> 104180485</p>
    <p class="info"><strong class="info-label">Email:</strong> <a class="email" href="mailto:104180485@student.swin.edu.au">104180485@student.swin.edu.au</a></p>
    <p class="declaration">I declare that this assignment is my individual work. I have not worked collaboratively nor have I copied from any other student's work or from any other source.</p>

    <?php
        require_once 'settings.php';
        
        // Create friends table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS friends (
            friend_id INT AUTO_INCREMENT PRIMARY KEY,
            friend_email VARCHAR(50) NOT NULL,
            password VARCHAR(20) NOT NULL,
            profile_name VARCHAR(30) NOT NULL,
            date_started DATE NOT NULL,
            num_of_friends INT UNSIGNED
        )";
        if ($conn->query($sql) === TRUE) {
            echo "<p class='info'>Table 'friends' created successfully. </p>";
        } else {
            echo "<p class='info'>Error creating table: </p>" . $conn->error;
        }

        // Check if friends table is empty
        $sql = "SELECT COUNT(*) AS count FROM friends";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if ($row['count'] == 0) {
            // Insert records into friends table
            $sql = "INSERT INTO friends (friend_email, password, profile_name, date_started, num_of_friends) VALUES
                ('messi@gmail.com', 'password1', 'Lionel Messi', '2012-09-22', 4),
                ('ronaldo@gmail.com', 'password2', 'Cristiano Ronaldo', '2013-10-23', 4),
                ('neymar@gmail.com', 'password3', 'Neymar Jr', '2014-11-24', 4),
                ('mbappe@gmail.com', 'password4', 'Kylian Mbappe', '2015-12-25', 4),
                ('salah@gmail.com', 'password5', 'Mohamed Salah', '2016-01-26', 4),
                ('kane@gmail.com', 'password6', 'Harry Kane', '2017-02-27', 4),
                ('lewa@gmail.com', 'password7', 'Robert Lewandowski', '2018-03-28', 4),
                ('kdb@gmail.com', 'password8', 'Kevin De Bruyne', '2019-04-29', 4),
                ('vd@gmail.com', 'password9', 'Virgil van Dijk', '2020-05-30', 4),
                ('alisson@gmail.com', 'password10', 'Alisson Becker', '2021-06-30', 4)           
            ";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='info'>Records inserted successfully.</p><br>";
            } else {
                echo "<p class='info'>Error inserting records: </p>" . $conn->error;
            }
        }

        // Create myfriends table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS myfriends (
            friend_id1 INT NOT NULL,
            friend_id2 INT NOT NULL
        )";
        if ($conn->query($sql) === TRUE) {
            echo "<p class='info'>Table 'myfriends' created successfully. </p>";
        } else {
            echo "<p class='info'>Error creating table: </p>" . $conn->error;
        }

        // Check if myfriends table is empty
        $sql = "SELECT COUNT(*) AS count FROM myfriends";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if ($row['count'] == 0) {
            // Insert records into myfriends table
            $sql = "INSERT INTO myfriends (friend_id1, friend_id2) VALUES
                (1, 2),
                (2, 3),
                (3, 4),
                (4, 5),
                (5, 6),
                (6, 7),
                (7, 8),
                (8, 9),
                (9, 10),
                (10, 1),
                (1, 3),
                (2, 4),
                (3, 5),
                (4, 6),
                (5, 7),
                (6, 8),
                (7, 9),
                (8, 10),
                (9, 1),
                (10, 2)
            ";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='info'>Records inserted successfully.</p><br>";
            } else {
                echo "<p class='info'>Error inserting records: </p>" . $conn->error;
            }
        }

        // Close the database connection
        $conn->close();
    ?>
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
