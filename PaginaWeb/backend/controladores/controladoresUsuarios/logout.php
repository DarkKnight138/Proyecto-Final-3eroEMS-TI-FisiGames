<?php
session_start();
session_unset();
session_destroy();
<<<<<<< Updated upstream
header('Location: login.php');
=======
header('Location: ../../login.php');
>>>>>>> Stashed changes
exit();
