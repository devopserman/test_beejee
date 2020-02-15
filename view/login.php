<?php
session_start();

require_once str_replace("/", "//", $_SERVER['DOCUMENT_ROOT']) . '/controller/ControllerClass.php';

$controller = new ControllerClass();
$form_check = $controller->SendAdminForm();

if ($_SESSION['is_admin'] == 1) {
    header("Refresh: 0;url=index.php");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Авторизация</title>
    <link href="<?= $url_to_cite ?>/view/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $url_to_cite ?>/view/css/style.css" rel="stylesheet">

</head>

<body>
<main role="main" class="container m_t_20">
    <?php
		require_once str_replace("/", "//", $_SERVER['DOCUMENT_ROOT']) . '/view/inc/menu.inc.php';
    ?>
    <div class="row justify-content-md-center">
        <div class="col-md-5">
            <h2>Авторизация</h2>
            <form method="post" action="login.php">
                <div class="form-group">
                    <label for="exampleInputEmail1">Пользователь</label>
                    <input type="text" class="form-control" placeholder="Введите пользователя" name="login">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Пароль</label>
                    <input type="password" class="form-control" placeholder="Введите пароль" name="password">
                </div>
                <input type="hidden" name="send_form" value="1">
                <button type="submit" class="btn btn-primary">Авторизоваться</button>
            </form>
            <?php
            if (strlen($form_check) > 1) {
                echo $form_check;
            }
            ?>
        </div>
    </div>

</main>

<script src="<?= $url_to_cite ?>/view/js/bootstrap.min.js"></script>
</body>
</html>

