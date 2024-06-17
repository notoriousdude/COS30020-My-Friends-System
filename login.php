<?php
    session_start();
    require_once 'settings.php'; 

    $email_error = '';
    $password_error = '';
    $email = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if email is empty
        if (empty($email)) {
            $email_error = 'Email is required.';
        } else {
            // Validate email
            $sql = "SELECT * FROM friends WHERE friend_email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (!$user) {
                $email_error = 'Email is not registered.';
            }
        }

        // Check if password is empty
        if (empty($password)) {
            $password_error = 'Password is required.';
        // Compares the entered password with the password from the database
        } elseif ($user && $password != $user['password']) {
            $password_error = 'Invalid password.';
        }

        // If there are no errors, set the session variable and redirect to friendlist page
        if (empty($email_error) && empty($password_error)) {
            $_SESSION['email'] = $email;
            header('Location: friendlist.php');
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
    <title>Log in page</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;700&family=Roboto:wght@400;500;700&family=Rubik:wght@400;500;700&family=Ubuntu:ital,wght@0,400;0,500;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <nav class="main-nav">
        <ul class="nav-list">
            <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="signup.php" class="nav-link">Sign-up</a></li>
            <li class="nav-item"><a href="login.php" class="nav-link active">Log-in</a></li>
            <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
        </ul>
    </nav>
    <h1 class="main-header">My Friend System</h1>
    <h1 class="sub-header">Log In Page</h1>

    <form method="post" action="login.php" class="login-form">
        <label class="form-label">Email address:</label>
        <input type="email" name="email" class="form-input" value= "<?php echo isset($email) ? $email : ''; ?>"><br>
        <?php if (!empty($email_error)) echo "<div class='error-message'>$email_error</div>"; ?><br>

        <label class="form-label">Password:</label>
        <input type="password" name="password" class="form-input"><br>
        <?php if (!empty($password_error)) echo "<div class='error-message'>$password_error</div>"; ?><br>

        <input type="submit" value="Log In" class="form-submit">
        <input type="reset" value="Clear" class="form-reset">
    </form>    

    <p class="home-link">Don't have an account? <a href="signup.php">Sign up</a></p>
    <p class="login-link"><a href="index.php"><i class="fa fa-home"></i>Return to Home Page</a></p>

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