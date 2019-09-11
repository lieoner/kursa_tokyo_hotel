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
  private function genPass($length)
  {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i = 0; $i < $length; $i++) {
      $token .= $codeAlphabet[random_int(0, $max - 1)];
    }

    return $token;
  }

  private function getUserDataFromDB($params)
  {
    $stmt = $this->pdo->prepare('SELECT IDc, user_password FROM clients WHERE user_login=? LIMIT 1');
    $stmt->execute([$params[0]]);
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result[0];
  }

  private function getUserNameFromDB($params)
  {
    $stmt = $this->pdo->prepare('SELECT client_name FROM clients_data WHERE IDc=? LIMIT 1');
    $stmt->execute([$params[0]]);
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

  private function callCHECKBOOKED($params)
  {
    $stmt = $this->pdo->prepare('CALL CHECK_BOOKED(?,?,?)');
    $stmt->execute([$params[0], $params[1], $params[2]]);
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result;
  }

  private function callROOMBOOKING($params)
  {
    $stmt = $this->pdo->prepare('CALL ROOM_BOOKING(?,?,?,?)');
    $stmt->execute([$params[0], $params[1], $params[2], $params[3]]);
  }

  private function createNewClientAccount()
  {
    $d = new DateTime();
    $login = strval($d->format('myd') * $d->format('u'));
    $token = "";
    for ($i = 0; $i < 9; $i++) {
      $token .= $login[$i];
    }
    $login = $token;
    $pass = $this->genPass(8);
    $stmt = $this->pdo->prepare('INSERT INTO clients (user_login,  user_password) VALUES (?, ?)');
    $stmt->execute([$login, md5(md5($pass))]);
    $fileopen = fopen("udata.txt", "a+");
    $write = $login . ':' . $pass . "\r\n";
    fwrite($fileopen, $write);
    fclose($fileopen);
  }

  private function insertClientDataByIDc($params)
  {
    $stmt = $this->pdo->prepare('INSERT INTO clients_data (IDc,  client_name, client_phone) VALUES (?, ?, ?)');
    $stmt->execute([$params[0], $params[1], $params[2]]);
  }

  protected function callMethod($method_name, $params = [])
  {
    return static::$method_name($params);
  }

  public function getUserData($login)
  {
    $method_name = 'getUserDataFromDB';
    $data = $this->callMethod($method_name, array($login));
    return $data;
  }
  public function getUserName($id)
  {
    $method_name = 'getUserNameFromDB';
    $data = $this->callMethod($method_name, array($id));
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

  public function createAccount()
  {
    $method_name = 'createNewClientAccount';
    $this->callMethod($method_name);
  }

  public function addBaseUserData($IDc, $clientName, $clientPhone)
  {
    $method_name = 'insertClientDataByIDc';
    $this->callMethod($method_name, array($IDc, $clientName, $clientPhone));
  }

  public function bookRoom($freeroomID, $client_id, $startDate, $endDate)
  {
    $method_name = 'callROOMBOOKING';
    $this->callMethod($method_name, array($freeroomID, $client_id, $startDate, $endDate));
  }
}
