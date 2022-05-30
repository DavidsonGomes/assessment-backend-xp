<?php
ob_start();
date_default_timezone_set('America/Sao_Paulo');

require __DIR__ . "/vendor/autoload.php";

require __DIR__. "/source/Boot/Routes.php";

ob_end_flush();
