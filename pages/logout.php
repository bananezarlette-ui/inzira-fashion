<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';
session_destroy();
header('Location: ' . base('index.php'));
exit;
