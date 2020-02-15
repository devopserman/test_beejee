<?php
session_start();

if (!isset($_COOKIE['field']) && !isset($_COOKIE['sign'])) {
    SetCookie("field", "name");
    SetCookie("sign", "ASC");
    header("Refresh: 0;url=index.php");
}

require_once str_replace("/", "//", $_SERVER['DOCUMENT_ROOT']) . '/controller/ControllerClass.php';

$controller = new ControllerClass();

(!isset($_GET['page']) || !is_numeric($_GET['page']) ? $page = 1 : $page = $_GET['page']);
$arr_tasks = $controller->getPageData($page, $_COOKIE['field'], $_COOKIE['sign']);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Задачник</title>
    <link href="<?= $url_to_cite ?>/view/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $url_to_cite ?>/view/css/style.css" rel="stylesheet">
    <link href="<?= $url_to_cite ?>/view/css/font-awesome.css" rel="stylesheet">

</head>

<body>
<main role="main" class="container m_t_20">
    <?php
		require_once str_replace("/", "//", $_SERVER['DOCUMENT_ROOT']) . '/view/inc/menu.inc.php';
    ?>
    <?php
    if (isset($arr_tasks)) {
    ?>
    <table class="table m_t_20">
        <thead class="thead-light">
        <tr>
            <th scope="col">Имя <?=$controller->getSortButton('name', $page)?>
            </th>
            <th scope="col">E-mail <?=$controller->getSortButton('email', $page)?></th>
            <th scope="col">Текст</th>
            <th scope="col">Статус <?=$controller->getSortButton('checked', $page)?></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($arr_tasks as $task) {
            ?>
            <tr>

                <th scope="row"><?= $task['name'] ?></th>
                <td><?= $task['email'] ?></td>
                <td><?= $task['text'] ?></td>
                <td>
                    <?php
                    if ($task['checked'] == 0) {
                        ?>
                        <span class="badge badge-danger">не выполнена</span>
                        <?php
                    } else {
                        ?>
                        <span class="badge badge-success">выполнена</span>
                        <?php 
                    }
                    if ($task['modified'] == 1) {
                        ?><br />
                        <span class="badge badge-light">отредактировано администратором</span>
                        <?php
                    }else{
						?><br />
                        <span class="badge badge-light"> </span>
                        <?php
					}
                    ?>
                </td>
                <td><?php
                    if ($_SESSION['is_admin'] == 1) {
                        ?>
                        <a href="edit.php?id=<?= $task['id'] ?>"
                           class="btn btn-outline-info btn-sm">редактировать</a>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
        } else {
            ?>
            <h2 class="align-content-center">Пусто</h2>
            <?php
        }
        ?>

        </tbody>
    </table>
    <?php
    $controller->doPagination($page);
    ?>
</main>

<script src="<?= $url_to_cite ?>/view/js/bootstrap.min.js"></script>
</body>
</html>

