<?php
session_start();

require_once 'config.php';
?>
<nav>
    <ul>
        <li style="margin: 0;">
            <a href="index.php"><img src="img/logo.png" height="95" width="85" alt="Visit Saudi Logo"></a>
        </li>
        <li><a href="destination.php">Destinations</a></li>
        <li><a href="gallery.php">Gallery</a></li>
    </ul>
    <ul class="right-lu">
        <?php if (isLoggedIn()): ?>
            <li class="boxed-li"><a href="getVisa.php">Get Visa</a></li>
            <li class="boxed-li"><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
        <?php else: ?>
            <li class="boxed-li"><a href="signIn.php">Sign Up/Log In</a></li>
        <?php endif; ?>
    </ul>
</nav>