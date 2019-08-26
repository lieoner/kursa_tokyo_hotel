<?
class tokyo_hotel
{
  private $host = "localhost"; // локалхост
  private $username = "root"; // имя пользователя
  private $password = ""; // пароль если существует
  private $dbname = "tokyo_hotel"; // база данных
  private $pdo;
  private static $instance = null;

  private function __construct()
  {
    $charset = 'utf8';
    // Создание соединения и исключения
    $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=$charset";
    $opt = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $this->pdo = new PDO($dsn, $this->username, $this->password, $opt);
  }

  public static function getInstance()
  {
    if (null === self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }
  private function __clone()
  { }
  public function __wakeup()
  {
    throw new \Exception("Cannot unserialize a singleton.");
  }

  public function checkwork()
  {
    $res = 'ItsWork!';
    return $res;
  }

  private function getUserDataFromDB($login)
  {
    $stmt = $this->pdo->prepare('SELECT IDc, user_password FROM clients WHERE user_login=? LIMIT 1');
    $stmt->execute(array($login));
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result[0];
  }

  private function updateHashIntoTables($params)
  {
    $query = 'UPDATE clients SET user_hash=? WHERE IDc=?';
    $stmt = $this->pdo->prepare($query);
    $stmt->execute([$params[0], $params[1]]);
  }

  private function getRoomsTypesDataFromDB()
  {
    $stmt = $this->pdo->prepare('SELECT IDrt, r_typeName, r_typeImageDir, r_typeCost, r_typeDesc FROM room_types');
    $stmt->execute();
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result;
  }

  private function getRoomsIDbyRoomTypeFromDB($params)
  {
    $stmt = $this->pdo->prepare('SELECT IDr FROM rooms WHERE IDrt = ?');
    $stmt->execute([$params[0]]);
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result;
  }

  private function CallCHECKBOOKED($params)
  {
    $stmt = $this->pdo->prepare('CALL CHECK_BOOKED(?,?,?)');
    $stmt->execute([$params[0], $params[1], $params[2]]);
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result;
  }

  protected function callMethod($method_name, $params = [])
  {
    return static::$method_name($params);
  }

  public function getUserData($login)
  {
    $method_name = 'getUserDataFromDB';
    $data = $this->callMethod($method_name, $login);
    return $data;
  }
  public function updateHash($hash, $user_id)
  {
    $method_name = 'updateHashIntoTables';
    $data = $this->callMethod($method_name, array($hash, $user_id));
    return $data;
  }
  public function getRoomsTypes()
  {
    $method_name = 'getRoomsTypesDataFromDB';
    $data = $this->callMethod($method_name);
    return $data;
  }
  public function getRoomIDs($IDrt)
  {
    $method_name = 'getRoomsIDbyRoomTypeFromDB';
    $data = $this->callMethod($method_name, array($IDrt));
    return $data;
  }

  public function findFreeRoom($IDrt, $begDate, $endDate)
  {
    $method_name = 'CallCHECKBOOKED';
    $data = $this->callMethod($method_name, array($IDrt, $begDate, $endDate));
    return $data;
  }
}
