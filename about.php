<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About page</title>
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
            <li class="nav-item"><a href="about.php" class="nav-link active">About</a></li>
        </ul>
    </nav>

    <div class="about">
        <h1 class="main-header">My Friend System</h1>
        <h2 class="sub-header">About Page</h2>
        <p>- If there are not any mistakes, I believe I have completed all tasks of the assignment.</p>
        <p>- Basically, I have finished all required features, including 2 tasks in the extra challenge.</p>
        <p>- I first had trouble with the friend add function, however, after thorough research and endeavour, I was able to complete it.</p>
        <p>- Next time, I would like to utilize other PHP concepts like OOP for code optimizing.</p>
        <p>- I have applied some proper CSS to enhance the layout and make the website easy to navigate, exceeding the basic requirements of the assignment.</p>
        <h3>Discussion participated</h3>
        <figure>
            <img src="images/discussion.png" alt="discussion" height="380" width="1000">
        <figcaption><i>Reply to a question</i></figcaption>
        </figure>
    </div>

    <div class="button-container">
        <a class="button1" href="friendlist.php">Friend List</a>
        <a class="button1" href="friendadd.php">Add Friend</a>
        <a class="button1" href="index.php">Home Page</a>
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