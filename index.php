<?php
    session_start();
    $message = '';
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
    }
?>
<html>
    <head>
        <?php
            require_once(__DIR__ . '/gui/style.php');
            echo "
                <script type='text/javascript'>
                    function bodyLoaded() {
                        if ('$message') {
                            alert('$message');
                        }
                    }
                </script>";
            ?>
    </head>
    <body onload="bodyLoaded()">
        <?php
            require_once(__DIR__ . '/crud/user.php');
            require_once(__DIR__ . '/api/museums.php');
            require_once(__DIR__ . '/gui/museums.php');

            // If not logged in
            if (!amLoggedIn()) {
                echo '
                    <form name="login" method="post" action="api/login.php">
                        <div class="message"><?php if(isset($message) && $message != "") { echo $message; } ?></div>
                            Email: <input type="text" name="email"><br>
                            Password: <input type="password" name="password"><br>
                            <input type="hidden" name="action" value="auth">
                            <input type="submit" name="submit" value="Submit"><br>
                        </div>
                    </form>
                    <a href="register.php">New user? Click here to register</a>';
            } else {
                $userId = myUserId();
                $museumsCurating = getMuseumsCurating($userId);

                echo '<h2>Welcome, <i>' . getUser($userId)['email'] . '</i>!</h2>';

                echo museumSelectinForm();

                echo '
                    <form action="museums.php">
                        <input type="submit" value="View All Museums"/>
                    </form>';

                echo '
                    <form action="tickets.php">
                        <input type="submit" value="My Tickets"/>
                    </form>';

                echo '
                    <form action="reviews.php">
                        <input type="submit" value="My Reviews"/>
                    </form>';

                if ($museumsCurating) {
                    echo '
                        <form action="museums.php">
                            <input type="hidden" name="curator" value="true">
                            <input type="submit" value="My Museums"/>
                        </form>
                    ';
                }

                echo '
                    <form action="manage.php">
                        <input type="submit" value="Manage Account"/>
                    </form>';
            }
        ?>
    </body>
</html>
