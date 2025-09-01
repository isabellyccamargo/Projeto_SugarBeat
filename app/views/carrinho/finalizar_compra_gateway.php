<?php

session_start();

if (isset($_SESSION['cliente_id'])) {
    header("Location: finalizar_compra.php");
    exit();
} else {
    header("Location: ../login/?origem=carrinho");
    exit();
}

?>