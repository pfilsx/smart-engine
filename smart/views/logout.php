<?php
session_start();
unset($_SESSION['login']);
session_write_close();
header('Location: '. \smart\Application::$instance->getBaseUrl().'/smart/login', true, 303);
exit;