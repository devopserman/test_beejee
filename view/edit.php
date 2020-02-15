<?php
session_start();

require_once str_replace("/", "//", $_SERVER['DOCUMENT_ROOT']) . '/controller/ControllerClass.php';

$controller = new ControllerClass();

$controller->is_logged();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Refresh: 0;url=index.php");
} else {
    $id = intval($_GET['id']);
}

$this_task = $controller->getTaskForEdit($id);
if ($this_task == 0) {
    echo "Ошибка получения задачи для редактирования<br>";
    die();
}

$form_check = $controller->SendForm('edit');

$_SESSION['old_text'] = $this_task['text'];

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Редактировать запись</title>
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
            <h2>Редактировать запись</h2>
            <form method="post" action="edit.php?id=<?=$this_task['id']?>">
                <div class="form-group">
                    <label for="exampleInputEmail1">Имя</label>
                    <input type="text" class="form-control" placeholder="Введите имя" name="name" value="<?=$this_task['name']?>">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">E-mail адрес</label>
                    <input type="email" class="form-control" placeholder="Введите e-mail" name="email" value="<?=$this_task['email']?>">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Текст</label>
                    <input type="text" class="form-control" placeholder="Введите текст задачи" name="text" value="<?=$this_task['text']?>">
                </div>
                <div class="form-check m_b_20">
                    <input type="checkbox" class="form-check-input" name="checked" <?php if ($this_task['checked'] == 1) {echo 'checked';} ?>>
                    <label class="form-check-label" for="exampleCheck1">Отметка о выполнении</label>
                </div>
                <input type="hidden" name="send_form" value="1">
                <input type="hidden" name="id" value="<?=$this_task['id']?>">
                <input type="hidden" name="old" value="<?=$this_task['id']?>">
                <button type="submit" class="btn btn-primary">Редактировать</button>
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

