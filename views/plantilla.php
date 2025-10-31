<?php
session_start();
?>

<!doctype html>
<html lang="sp">

<head>
    <meta charset="utf-8" />
    <title>Ghangi Notificaciones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Ghangi POS" name="description" />
    <meta content="Ghangi Soluciones" name="author" />

    <?php include "views/modules/html/css-link.php"; ?>

    <?php include "views/modules/html/js-script.php"; ?>

</head>

<body data-sidebar="dark" data-keep-enlarged="false" class="vertical-collpsed">

    <?php
    if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {
        echo '<div id="layout-wrapper">';
        include "views/modules/html/menu.php";
        include "views/modules/html/header.php";

        if (isset($_GET["url"])) {
            if (
                $_GET["url"] == "usuarios" ||
                $_GET["url"] == "inicio" ||
                $_GET["url"] == "proveedores" ||
                $_GET["url"] == "sucursales" ||
                $_GET["url"] == "categorias" ||
                $_GET["url"] == "promociones" ||
                $_GET["url"] == "clientes" ||
                $_GET["url"] == "productos" ||
                $_GET["url"] == "pedidos" ||
                $_GET["url"] == "compras" ||
                $_GET["url"] == "cortes_caja" ||
                $_GET["url"] == "metas_tacos" ||
                $_GET["url"] == "pagos" ||
                $_GET["url"] == "gastos" ||
                $_GET["url"] == "404" ||
                $_GET["url"] == "login" ||
                $_GET["url"] == "salir"
            ) {
                include "views/modules/" . $_GET['url'] . ".php";
            } else {
                include "views/modules/html/404.php";
            }
        } else {
            include "views/modules/inicio/dashboard.php";
        }

        include "views/modules/html/footer.php";
        echo '</div>';
    } else {
        include "views/modules/login.php";
    }
    ?>

    <?php include "views/modules/html/js_modules.php"; ?>


</body>

</html>