<?php
declare(strict_types=1);

require_once 'functions.php';

session_unset();
session_destroy();
removeUserCookie();

header('Location: login.php');
exit;
