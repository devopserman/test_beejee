<?php
session_start();

if (isset($_GET['page']) && $_GET['page'] != ''){
    if (is_numeric($_GET['page'])){
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
}

if (isset($_GET['field']) && $_GET['field'] != ''){
    if ($_GET['field'] == 'name' || $_GET['field'] == 'email' || $_GET['field'] == 'checked'){
        $field = $_GET['field'];
    } else {
        $field = 'name';
    }
}

if (isset($_GET['sign']) && $_GET['sign'] != ''){
    if ($_GET['sign'] == 'ASC' || $_GET['sign'] == 'DESC'){
        $sign = $_GET['sign'];
    } else {
        $sign = 'ASC';
    }
}

SetCookie("field", $field);
SetCookie("sign", $sign);

header("Refresh: 0;url=index.php?page=".$page);

