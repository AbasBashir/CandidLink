
<header>

    <div class="brand-logo">
        <a href="index.php"><img src="images/CandidLink.png" alt=""></a>
    </div>

    <div class="header-content">
        <ul><li><a href="index.php">Home</a></li></ul>
        <?php if (!empty($_SESSION['info'])) {?>

            <ul><li><a href="profile.php">Profile</a></li></ul>
            <ul><li><a href="logout.php">Logout</a></li></ul>

        <?php } else {?>

            <ul><li><a href="login.php">Login</a></li></ul>
            <ul><li><a href="signup.php">Signup</a></li></ul>

        <?php } ?>
    </div>

</header>