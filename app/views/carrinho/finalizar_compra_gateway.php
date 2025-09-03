<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['cliente_id'])) {
    header("Location: ../pedidos/index.php");
    exit();
} else {
    header("Location: ../login/?origem=carrinho");
    exit();
}

?>