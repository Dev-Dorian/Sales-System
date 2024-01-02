<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        $_SESSION['admin'] = 0;
    }
?>