<?php 
setcookie('remember', '', time()-3600);
header('Location: index.php');
exit();
