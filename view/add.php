<?php
session_start();

require_once str_replace("/", "//", $_SERVER['DOCUMENT_ROOT']) . '/controller/ControllerClass.php';

$controller = new ControllerClass();
$form_check = $controller->SendForm('new');
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Добавить запись</title>
    <link href="<?= $url_to_cite ?>/view/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $url_to_cite ?>/view/css/style.css" rel="stylesheet">

</head>

<body>

<main role="main" class="container m_t_20">
    <?php
		require_once str_replace("/", "//", $_SERVER['DOCUMENT_ROOT']) . '/view/inc/menu.inc.php';
    ?>
    <div class="row">
        <div class="col-md-12">
            <h2>Добавить запись</h2>
            <form method="post" action="add.php">
                <div class="form-group">
                    <label for="exampleInputEmail1">Имя</label>
                    <input type="text" class="form-control" placeholder="Введите имя" name="name">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">E-mail адрес</label>
                    <input type="text" class="form-control" placeholder="Введите e-mail" name="email">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Текст</label>
                    <input type="text" class="form-control" placeholder="Введите текст задачи" name="text">
                </div>
                <input type="hidden" name="send_form" value="1">
                <button type="submit" class="btn btn-primary">Создать</button>
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

