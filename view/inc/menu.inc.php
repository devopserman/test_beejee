<div class="row">
    <div class="col-md-12">
        <?php
        if ($_SESSION['is_admin'] == 1) {

            ?>
            <a href="logout.php" class="btn btn-info my-2 my-sm-0 float-right">Выход из профиля <?=$_SESSION['login']?></a>
            <?php

        } else {
            if (basename($_SERVER['PHP_SELF']) != "login.php") {
                ?>
                <a href="login.php" class="btn btn-success my-2 my-sm-0 float-right">Авторизация</a>
                <?php
            }
        }
        ?>
        <?php
        if (basename($_SERVER['PHP_SELF']) != "add.php") {
            ?>
            <a href="add.php" class="btn btn-outline-success my-2 my-sm-0 float-right m_r_10">Создать задачу</a>
            <?php
        }
        ?>
        <?php
        if (basename($_SERVER['PHP_SELF']) != "index.php") {
            ?>
            <a href="index.php" class="btn btn-outline-success my-2 my-sm-0 float-left"><< На главную</a>
            <?php
        }
        ?>

    </div>
</div>