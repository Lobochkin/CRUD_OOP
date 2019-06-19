<?php 
namespace class_oop;

/**
 * класс для работы с приложением crud
 */
class Main 
{
	protected $mysqli;
	protected $list_status;
	protected $query;
	protected $update;
	protected $insert;
	protected $result_del;
	protected $result_edit;
  
	function __construct($server, $username, $password, $database)
	{
		$this->mysqli = new \mysqli($server, $username, $password, $database);
		$this->mysqli->query("SET NAMES utf8");
		if ($this->mysqli->connect_error) {
			die('Ошибка : ('. $this->mysqli->connect_errno .') '. $this->mysqli->connect_error);
		}
	}

	protected function clear_text($input_text) // Функция для проверки и очистки данных отправленных POST запросом
	{
		$input_text = strip_tags($input_text);
		$input_text = htmlspecialchars($input_text);
		$input_text = mysqli_escape_string($this->mysqli,$input_text);
		return $input_text;
	}

  	public function get_list_status()
	{
		$this->list_status = $this->mysqli->query("SELECT * FROM status");
		return $this->list_status;
	}

	public function get_tr()
	{
		$this->red_db();
	    $result = '';
	    while ($elem = $this->query->fetch_assoc()) {
			$result .= '<tr>';
			$result .= '<td>' . $elem['brand'] . '</td>';
			$result .= '<td>' . $elem['model'] . '</td>';
			$result .= '<td>' . $elem['price'] . '</td>';
			$result .= '<td>' . $elem['status'] . '</td>';
			$result .= '<td>' . $elem['mileage'] . '</td>';
			$result .= '<td><a class="form__delete" href="?del=' . $elem['id'] . '">удалить</a></td>';
			$result .= '<td><a class="form__edit" href="?edit=' . $elem['id'] . '">редактировать</a></td>';
			$result .= '</tr>';
	    }
	    return $result;
	}
	protected function red_db()
	{
		$this->query = $this->mysqli->query("SELECT cars.id as id,brand,model,price, status.status as status,mileage FROM cars INNER JOIN status ON status.id=cars.id_status");
		if (!$this->query) {
		    die('Ошибка : ('. $this->mysqli->error .') '. $this->mysqli->errno);
		}
	}

	public function crud_db()
	{
		// Изменения в базе данных
		if (isset($_POST['button_edit'])) {
			$this->update = $this->mysqli->query("UPDATE cars SET brand='". $this->clear_text($_POST['brand'])."',model='".$this->clear_text($_POST['model'])."',price=".intval($_POST['price']).",id_status=(SELECT status.id FROM status WHERE status.status='".$this->clear_text($_POST['status'])."'),mileage=".intval($_POST['mileage'])." WHERE cars.id=".intval($_POST['id'])."");
			if ($this->update) {
				header("Location: " . "/test/CRUD_oop");
				exit;
			}
			if (!$this->update) {
				die('Ошибка : ('. $this->mysqli->error .') '. $this->mysqli->errno);
			}
		}
		// Добавление в БД
		if (isset($_POST['button_add'])) {
			$this->insert = $this->mysqli->query("INSERT INTO cars SET brand='".$this->clear_text($_POST['brand'])."',model='".$this->clear_text($_POST['model'])."',price=".intval($_POST['price']).",id_status=".intval($_POST['status']).",mileage=".intval($_POST['mileage'])."");
			if ($this->insert) {
				header("Location: " . "/test/CRUD_oop");
				exit;
			}

			if (!$this->insert) {
				die('Ошибка : ('. $mysqli->error .') '. $mysqli->errno);
			}
		}

		// Удаление записи из БД 
		if (isset($_GET['del'])) {
			$id_del = intval($_GET['del']);
			$this->result_del = $this->mysqli->query("DELETE FROM cars WHERE id=".$id_del."");
			if ($this->result_del === null) {
				header("Location: " . "/test/CRUD_oop");
				exit;
			}
		}
		// Запрос данный для редактировани
		if (isset($_GET['edit'])) {
			$id_edit = intval($_GET['edit']);
			$this->result_edit = $this->mysqli->query("SELECT cars.id as id,brand,model,price, status.status as status,mileage FROM cars INNER JOIN status ON status.id=id_status WHERE cars.id=".$id_edit."");
			$this->result_edit = $this->result_edit->fetch_assoc();

			if ($this->result_edit === null) {
				header("Location: " . "/test/CRUD_oop");
				exit;
			}
		}
	}

	public function get_result_edit()
	{
		if ($this->result_edit !== null) {
			return $this->result_edit;
		} 
		return false;
	}
}