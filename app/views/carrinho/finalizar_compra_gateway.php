<?php

session_start();

if (isset($_SESSION['cliente_id'])) {
    header("Location: ../pedido/index.php");
    exit();
} else {
    header("Location: ../login/?origem=carrinho");
    exit();
}

?>