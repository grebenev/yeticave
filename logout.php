<?php
    session_start ();
    unset($_SESSION['user']);
    $_SESSION = [];
    header("Location: /");
