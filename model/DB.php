<?php

include_once str_replace("/", "//", $_SERVER['DOCUMENT_ROOT']) . '/config/config.php';

class DB
{
    private static $_instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

	private function __wakeup()
	{
	}
	
    static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function connect()
    {

        $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_errno) {
            echo "<br>Ошибка подключения к базе: (" . $db->connect_errno . ") <br>" . $db->connect_error;
            exit();
        }
        $db->query("SET NAMES 'utf8';");
        $db->query("SET CHARACTER SET 'utf8';");

        return $db;
    }

    public function disconnect($db)
    {
        $db->close();
    }


    public function addNewTask($prepare)
    {
        $db_link = $this->connect();

        $query = "INSERT INTO tasks 
                (
                    name, 
                    email,
                    text,
                    checked
                ) 
                VALUES 
                ( 
                    '$prepare[name]',
                    '$prepare[email]',
                    '$prepare[text]',
                    0
                );";

        if ($result = $db_link->query($query)) {
            $result_id = $db_link->insert_id;
        } else {
            $this->disconnect($db_link);
            return false;
        }
        $this->disconnect($db_link);
        return true;
    }

    public function SuccessMessage($message)
    {
        return "<div class='alert alert-success align-content-center m_t_20' role='alert'>$message</div>";
    }

    public function ErrorMessage($message)
    {
        return "<div class='alert alert-danger align-content-center m_t_20' role='alert'>$message</div>";
    }

    public function defineParametersForPagination($page_count)
    {
        $number_of_records = REC_PER_PAGE;
        $pagination_array['number_of_records'] = $number_of_records;
        $db_link = $this->connect();
        $query = "SELECT * FROM tasks";

        if ($result = $db_link->query($query)) {
            $row_count = $result->num_rows;
            $pagination_array['row_count'] = $row_count;
            $result->free();
        } else {
            $this->disconnect($db_link);
            return false;
        }

        $total = intval(($row_count - 1) / $number_of_records) + 1;
        $pagination_array['total'] = $total;
        $page_count = intval($page_count);
        $pagination_array['page_count'] = $page_count;
        if (empty($page_count) or $page_count < 0) {
            $page_count = 1;
        }
        if ($page_count > $total) {
            $page_count = $total;
        }
        $start = $page_count * $number_of_records - $number_of_records;
        $pagination_array['start'] = $start;
        $this->disconnect($db_link);
        return $pagination_array;
    }

    public function selectTasksForPage($page_count, $name_of_sorting, $sign_of_sorting)
    {
        $db_link = $this->connect();

        $pagination_array = $this->defineParametersForPagination($page_count);

        $query = "SELECT * FROM tasks ORDER BY $name_of_sorting $sign_of_sorting LIMIT " . $pagination_array['start'] . ", " . $pagination_array['number_of_records'];

        if ($result = $db_link->query($query)) {
            $count = 0;
            while ($row = $result->fetch_assoc()) {
                $arr_tasks[$count]['id'] = $row["id"];
                $arr_tasks[$count]['name'] = $row["name"];
                $arr_tasks[$count]['email'] = $row["email"];
                $arr_tasks[$count]['text'] = $row["text"];
                $arr_tasks[$count]['checked'] = $row["checked"];
                $arr_tasks[$count]['modified'] = $row["modified"];
                $count++;
            }
            unset($count);
            unset($pagination_array);
            $result->free();
            $this->disconnect($db_link);
            return $arr_tasks;
        } else {
            $this->disconnect($db_link);
            return false;
        }
    }

    public function insertPagination($page_count)
    {
        $pagination_array = $this->defineParametersForPagination($page_count);
        if ($pagination_array['row_count'] > $pagination_array['number_of_records']) {
            ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if ($page_count == 1) {
                        echo "disabled";
                    } ?>">
                        <a class="page-link" href="index.php?page=<?php if ($page_count != 1) {
                            echo $page_count - 1;
                        } else {
                            echo 1;
                        } ?>" aria-label="Предидущие">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Предидущие</span>
                        </a>
                    </li>
                    <?php
                    for ($i = 1; $i <= $pagination_array['total']; $i++) {
						if (($_GET['page'] == $i) || (($i == 1)&&(!isset($_GET['page'])))){
							$s = 'active';
						}else{
							$s = '';
						}	
                        ?>
                        <li class="page-item <?= $s ?>"><a class="page-link" href="index.php?page=<?= $i ?>"><?= $i	?></a></li>
                        <?php
                    }
                    ?>

                    <li class="page-item <?php if ($page_count == $pagination_array['total']) {
                        echo "disabled";
                    } ?>">
                        <a class="page-link" href="index.php?page=<?php if ($page_count != $pagination_array['total']) {
                            echo $page_count + 1;
                        } else {
                            echo $pagination_array['total'];
                        } ?>" aria-label="Следующие">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Следующие</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php
        }
        unset($pagination_array);
    }

    public function isAdminCheck($prepare)
    {
		if((mb_strtolower($prepare['login']) == mb_strtolower('Admin')) && ($prepare['password'] == '123')){
				$_SESSION['is_admin'] = 1;
				$_SESSION['login'] = $prepare['login'];
				return true;
		}
		return false;

    }

    public function selectTaskId($id)
    {
        $db_link = $this->connect();
        $query = "  SELECT * 
                    FROM tasks
                    WHERE id = $id
                    LIMIT 1";
        if ($result = $db_link->query($query)) {
			
            while ($row = $result->fetch_assoc()) {
                $arr_task['id'] = $row["id"];
                $arr_task['name'] = $row["name"];
                $arr_task['email'] = $row["email"];
                $arr_task['text'] = $row["text"];
                $arr_task['checked'] = $row["checked"];
                $arr_task['modified'] = $row["modified"];
            }
            $result->free();
            $this->disconnect($db_link);
			
            return $arr_task;
        } else {
            $this->disconnect($db_link);
            return false;
        }
    }

    public function editTask($prepare)
    {
        $db_link = $this->connect();

        $query = "  UPDATE tasks
                    SET
                        name = '$prepare[name]',
                        email = '$prepare[email]',
                        text = '$prepare[text]'
                    WHERE id = '$prepare[id]';";

        if ($result = $db_link->query($query)) {
        } else {
            $this->disconnect($db_link);
            return false;
        }
        $this->disconnect($db_link);
        return true;
    }

    public function addCheckedMark($id)
    {
        $db_link = $this->connect();

        $query = "  UPDATE tasks
                    SET
                        checked = 1
                    WHERE id = '$id';";

        if ($result = $db_link->query($query)) {
        } else {
            $this->disconnect($db_link);
            return false;
        }
        $this->disconnect($db_link);
        return true;
    }

    public function addModifiedMark($id)
    {
        $db_link = $this->connect();

        $query = "  UPDATE tasks
                    SET
                        modified = 1
                    WHERE id = '$id';";

        if ($result = $db_link->query($query)) {
        } else {
            $this->disconnect($db_link);
            return false;
        }
        $this->disconnect($db_link);
        return true;
    }

    public function genSortButton($field, $page)
    {
        ?>
        <?php
        if ($_COOKIE['field'] == $field) {
            if ($_COOKIE['sign'] == "ASC") {
				$t = 'A-z';
                ?>
                <a href="set_sort.php?page=<?= $page ?>&field=<?=$field?>&sign=DESC">
                    <i class="fa fa-sort-amount-asc" aria-hidden="true"><?= $t ?></i>
                </a>
                <?php
            } else {
				$t = 'z-A';
                ?>
                <a href="set_sort.php?page=<?= $page ?>&field=<?=$field?>&sign=ASC">
                    <i class="fa fa-sort-amount-desc" aria-hidden="true"><?= $t ?></i>
                </a>
                <?php
            }
        } else {
            ?>
            <a href="set_sort.php?page=<?= $page ?>&field=<?=$field?>&sign=DESC">
                <i class="" aria-hidden="true">...</i>
            </a>
            <?php
        }
        ?>
        <?php
    }
}
