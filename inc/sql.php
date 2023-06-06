<?php # Access MySQL database
if(count(get_included_files())==1)  exit; //direct access not permitted
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/ckrit/ckrit_db.php"; //database secrets

/* PDO wrapper examples:  https://phpdelusions.net/pdo/pdo_wrapper

//DB::Run automatically calls query if no variables (otherwise uses a prepared statement)
DB::query('CREATE temporary TABLE names (id int auto_increment primary key, name varchar(255))');

//one statement, multiple execusions
$stmt = DB::prepare('INSERT INTO names VALUES (NULL, ?)');
foreach(['Alice','Bob','Chuck'] as $name)
	$stmt->execute([$name]);
var_dump(DB::lastInsertId()); //string(1) "3"

//update
$new  = 'Sue';
$stmt = DB::Run('UPDATE names SET name=? WHERE id=?', [$new, $id]);
var_dump($stmt->rowCount()); //int(1)

//fetch multiple rows, lazy
$stmt = DB::Run('SELECT * FROM names');
while($row = $stmt->fetch(PDO::FETCH_LAZY))
	echo $row['name'] .'='. $row->name .'='. $row[1], PHP_EOL;
//Alice=Alice=Alice
//Bob=Bob=Bob
//Chuck=Chuck=Chuck

//fetch single row
$id  = 1;
$row = DB::Run('SELECT * FROM names WHERE id=?', [$id])->fetch();
var_export($row); //array('id'=>'1', 'name'=>'Alice', ...)

//fetch single value
$name = DB::Run('SELECT name FROM names WHERE id=?', [$id])->fetchColumn();
var_dump($name); //string(3) "Alice"

//fetch multiple rows; ONLY if fits in an array
$aRows = DB::Run('SELECT * FROM names')->fetchAll();
foreach($aRows as $row)
	var_export($row); //array('id'=>'1', 'name'=>'Alice', ...)

//fetch single value from multiple rows;  i.e. array of values
$aName = DB::Run('SELECT name FROM names')->fetchAll(PDO::FETCH_COLUMN);
var_dump($aNames); //array("Alice", "Bob", "Chuck", ...)

//fetch multiple rows as array of pairs
$aRows = DB::Run('SELECT name, id FROM names')->fetchAll(PDO::FETCH_KEY_PAIR);
var_export($aRows); //array('Alice'=>'1', 'Bob'=>'2', 'Chuck'=>'3', ...)

//fetch multiple rows as associative array with first element as key
$aRows = DB::Run('SELECT * FROM names')->fetchAll(PDO::FETCH_UNIQUE | PDO::FETCH_ASSOC);
var_export($aRows); //array('Alice'=>array('2','3'...), 'Bob'=>array('2','3'...), ...)
*/

//---------------------------------------------------------------------------
//DB - PDO wrapper;  singleton
class DB {
	protected static $instance = null;
	public function __construct() {}
	public function __clone() {}

	public static function instance() {
		if(self::$instance === null) {
			$opt  = array(
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES   => TRUE,
			);
			$dsn = 'mysql:host='.DB_SERVER.';dbname='.DB_DATABASE.';charset=utf8';
			self::$instance = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $opt);
		} //if
		return self::$instance;
	} //instance

	public static function __callStatic($method, $args) {
		return call_user_func_array(array(self::instance(), $method), $args);
	} //__callStatic

	public static function Run($sql, $args=[]) {
		if(!$args)  return self::instance()->query($sql);
		$stmt = self::instance()->prepare($sql);
		$stmt->execute($args);
		return $stmt;
	} //run
} //DB
?>
