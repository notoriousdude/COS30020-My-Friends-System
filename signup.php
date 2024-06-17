<?php
    session_start();
    require_once 'settings.php';

    $email_error = '';
    $profile_name_error = '';
    $password_error = '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $profile_name = isset($_POST['profile_name']) ? $_POST['profile_name'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_error = 'Email must be valid in format and cannot be blank.';
        } else {
            $sql = "SELECT * FROM friends WHERE friend_email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $email_error = 'Email already exists.';
            }
        }

        // Validate profile name
        if (empty($profile_name) || !ctype_alpha(str_replace(' ', '', $profile_name))) {
            $profile_name_error = 'Profile name must contain only letters and cannot be blank.';
        }

        // Validate password
        if ($password !== $confirm_password || !ctype_alnum($password)) {
            $password_error = 'Passwords must match and contain only letters and numbers.';
        }

        // If no errors, insert into database and redirect
        if (empty($email_error) && empty($profile_name_error) && empty($password_error)) {
            $sql = "INSERT INTO friends (friend_email, profile_name, password, date_started, num_of_friends) VALUES (?, ?, ?, NOW(), 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $email, $profile_name, $password);
            $stmt->execute();

            $_SESSION['email'] = $email; // Set session variable
            header('Location: friendadd.php'); // Redirect to Friend Add page
            exit;
        }
    }

    // Close the database connection
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up page</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;700&family=Roboto:wght@400;500;700&family=Rubik:wght@400;500;700&family=Ubuntu:ital,wght@0,400;0,500;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <nav class="main-nav">
        <ul class="nav-list">
            <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="signup.php" class="nav-link active">Sign-up</a></li>
            <li class="nav-item"><a href="login.php" class="nav-link">Log-in</a></li>
            <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
        </ul>
    </nav>
    <h1 class="main-header">My Friend System</h1>
    <h1 class="sub-header">Registration Page</h1>

    <form method="post" action="signup.php" class="registration-form">
        <label class="form-label">Email address:</label>
        <input type="email" name="email" class="form-input" value="<?php echo isset($email) ? $email:''; ?>"><br>
        <?php if (!empty($email_error)) echo "<div class='error-message'>$email_error</div>"; ?><br>

        <label class="form-label">Profile name:</label>
        <input type="text" name="profile_name" class="form-input" value="<?php echo isset($profile_name) ? $profile_name:''; ?>"><br>
        <?php if (!empty($profile_name_error)) echo "<div class='error-message'>$profile_name_error</div>"; ?><br>

        <label class="form-label">Password:</label>
        <input type="password" name="password" class="form-input"><br>
        <?php if (!empty($password_error)) echo "<div class='error-message'>$password_error</div>"; ?><br>

        <label class="form-label">Confirm password:</label>
        <input type="password" name="confirm_password" class="form-input"><br>

        <input type="submit" value="Register" class="form-submit">
        <input type="reset" value="Clear" class="form-reset">
    </form>

    <p class="login-link">Already have an account? <a href="login.php">Log In</a></p>
    <p class="home-link"><a href="index.php"><i class="fa fa-home"></i>Return to Home Page</a></p>
    
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