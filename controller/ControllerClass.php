<?php

class ControllerClass
{
    public function getDB()
    {
        require_once str_replace("/", "//", $_SERVER['DOCUMENT_ROOT']) . '/model/DB.php';

        $mdb = DB::getInstance();

        return $mdb;
    }

    public function getAllTasks()
    {
        return $this->getDB()->selectAllTasks();
    }

    public function SendForm($marker)
    {
        if (isset($_POST['send_form']) && $_POST['send_form'] == 1) {
            $error = '';
			$prepare = [];
            if (isset($_POST['name']) && $_POST['name'] != "") {
                $prepare['name'] = $this->preparedDataForDB($_POST['name']);
            } else {
                $error .= "Поле \"Имя\" не должно быть пустым!<br>";
            }

            if (isset($_POST['email']) && $_POST['email'] != "") {
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $prepare['email'] = $this->preparedDataForDB($_POST['email']);
                } else {
                    $error .= "\"E-mail\" указан неверно!<br>";
                }
            } else {
                $error .= "Поле \"E-mail\" не должно быть пустым!<br>";
            }

            if (isset($_POST['text']) && $_POST['text'] != "") {
                $prepare['text'] = $this->preparedDataForDB($_POST['text']);
            } else {
                $error .= "Поле \"Текст\" не должно быть пустым!<br>";
            }

            if (strlen($error) == 0) {
                if ($marker == 'new') {
                    if (!$this->getDB()->addNewTask($prepare)) {
                        return "Ошибка добавления записи <br>";
                    } else {
                        header("Refresh: 2;url=index.php");
                        return $this->getDB()->SuccessMessage('Запись добавлена. Вы будете переправлены на главную страницу.');
                    }
                } elseif ($marker == 'edit') {
                    if (isset($_POST['id']) && $_POST['id'] != "") {
                        $id = (int)$_POST['id'];
						$prepare['id'] = $id;
					} else {
                        return $this->getDB()->ErrorMessage('Возникла ошибка!');
                    }
                    if ($_POST['checked'] == 'on') {
                        $this->getDB()->addCheckedMark($id);
                    }
                    if (strcmp($_SESSION['old_text'], $prepared['text'])) {
                        $this->getDB()->addModifiedMark($id);
                    }
                    if (!$this->getDB()->editTask($prepare)) {
                        return "Возникла ошибка. Данные не отредактированы. <br>";
                    } else {
                        header("Refresh: 2;url=edit.php?id=" . $id);
                        return $this->getDB()->SuccessMessage('Запись отредактирована. Вы будете переправлены на предыдущую страницу.');
                    }
                }
            } else {
                return $this->getDB()->ErrorMessage($error);
            }
        } else {
            return 0;
        }
    }

    public function preparedDataForDB($text)
    {
        $text = trim($text);
        $text = htmlspecialchars($text, ENT_QUOTES);
        $text = stripslashes($text);
        return $text;
    }

    public function SendAdminForm()
    {
        if (isset($_POST['send_form']) && $_POST['send_form'] == 1) {
            $error = '';
			$prepare = [];
            if (isset($_POST['login']) && $_POST['login'] != "") {
                $prepare['login'] = $this->preparedDataForDB($_POST['login']);
            } else {
                $error .= "Поле \"Пользователь\" не может быть пустым!<br>";
            }

            if (isset($_POST['password']) && $_POST['password'] != "") {
                $prepare['password'] = $this->preparedDataForDB($_POST['password']);
            } else {
                $error .= "Поле \"Пароль\" не может быть пустым!<br>";
            }

            if (strlen($error) == 0) {
                if ($this->getDB()->isAdminCheck($prepare)) {
                    return $this->getDB()->SuccessMessage('Авторизация прошла успешно');
                } else {
                    return $this->getDB()->ErrorMessage('Введены неверные  данные');
                }
            } else {
                return $this->getDB()->ErrorMessage($error);
            }
        }
    }

    public function getPageData($page_count, $name_of_sorting, $sign_of_sorting)
    {
        return $this->getDB()->selectTasksForPage($page_count, $name_of_sorting, $sign_of_sorting);
    }

    public function doPagination($page_count)
    {
        return $this->getDB()->insertPagination($page_count);
    }

    public function getTaskForEdit($id)
    {
        return $this->getDB()->selectTaskId($id);
    }

    public function is_logged()
    {
        if ($_SESSION['is_admin'] != 1) {
            echo "<div class='alert alert-danger align-content-center' role='alert'><h2>Недостаточно прав!</h2><a href='login.php' class='btn btn-outline-danger'>Авторизоваться</a></div>";
            die();
        }
    }

    public function getSortButton($field, $page){
        return $this->getDB()->genSortButton($field, $page);
    }

}
