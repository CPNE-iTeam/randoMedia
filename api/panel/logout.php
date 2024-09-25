<?php
session_start();
session_destroy(); //SESSION GOES KABOOMM
header('Location: ../../panel/');
exit;
?>
