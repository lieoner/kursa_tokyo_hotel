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
    $stmt = $this->pdo->prepare('SELECT IDc FROM clients WHERE IDc=? AND user_hash=? LIMIT 1');
    $stmt->execute([$params[0], $params[1]]);
    $user_true = array();
    foreach ($stmt as $row) {
      $user_true[] = $row;
    }
    if (isset($user_true[0])) {
      $stmt = $this->pdo->prepare('SELECT client_name FROM clients_data WHERE IDc=? LIMIT 1');
      $stmt->execute([$params[0]]);
      $result = array();
      foreach ($stmt as $row) {
        $result[] = $row;
      }
      return $result[0];
    } else {
      return 0;
    }
  }

  private function updateHashIntoTables($params)
  {
    if (strcasecmp($params[2], 'clients') == 0) {
      $query = 'UPDATE clients SET user_hash=? WHERE IDc=?';
    } else if (strcasecmp($params[2], 'inside_persons') == 0) {
      $query = 'UPDATE inside_persons SET user_hash=? WHERE IDip=?';
    }
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

    return $login;
  }

  private function insertClientDataByIDc($params)
  {
    $stmt = $this->pdo->prepare('INSERT INTO clients_data (IDc,  client_name, client_phone) VALUES (?, ?, ?)');
    $stmt->execute([$params[0], $params[1], $params[2]]);
  }

  private function getUserByID($params)
  {
    $stmt = $this->pdo->prepare('SELECT `cd`.`client_name` AS `client_name`, `cd`.`client_fam` AS `client_fam`, `cd`.`client_phone` AS `client_phone`, `c`.`user_login` AS `user_login` FROM (`clients_data` `cd` JOIN `clients` `c`) WHERE ((`cd`.`IDc` = ?) AND (`c`.`IDc` = `cd`.`IDc`)) LIMIT 1');
    $stmt->execute([$params[0]]);
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result;
  }

  private function editUserDataUpdateDB($params)
  {
    $stmt = $this->pdo->prepare('UPDATE clients_data SET client_name=?, client_fam=?, client_phone=? WHERE IDc=?');
    $stmt->execute($params);
  }

  private function getUserBookingDataFromDB($params)
  {
    $stmt = $this->pdo->prepare('SELECT `bl`.`IDr` AS `IDr`, `bl`.`comingDate` AS `comingDate`, `bl`.`outDate` AS `outDate` FROM `booking_list` `bl`  WHERE (`bl`.`IDc` = ?)');
    $stmt->execute([$params[0]]);
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }

    $stmt = $this->pdo->prepare('SELECT ll.IDliv_l FROM living_list ll WHERE ll.IDc = ? AND ll.IDr = ?');
    $stmt->execute([$params[0], $result[0]['IDr']]);
    $temp = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }

    if (isset($result[1])) {
      $stmt = $this->pdo->prepare('SELECT roomNumber FROM rooms WHERE IDr = ?');
      $stmt->execute([$result[0]['IDr']]);
      foreach ($stmt as $row) {
        $result[] = $row;
      }
      $result[0]['status'] = 'Вы зарегистрированы, ваша комната №' . $result[2]['roomNumber'];
    } else {
      $result[0]['status'] = 'Бронь активна';
    }
    return $result;
  }

  private function CheckAndRemoveOldBookFromDB()
  {
    $stmt = $this->pdo->prepare('SELECT IDc FROM booking_list WHERE (outDate < CURDATE()) AND (book_status = 1)');
    $stmt->execute();
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    foreach ($result as $row) {
      $sql = "UPDATE clients SET user_password=?, user_hash=?, account_status=0 WHERE IDc=?";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute(['password_has_been_reset', '', $row['IDc']]);

      $sql = "UPDATE booking_list SET book_status=? WHERE (outDate < CURDATE()) AND (book_status = 1)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute(['0']);

      static::appendLog('Сброшен бронь и аккаунт с ID = ' . $row['IDc'] . ' по истечению срока брони', 1);
    }
    return $result;
  }

  private function appendLogIntoDB($params)
  {
    $stmt = $this->pdo->prepare('INSERT INTO logs (a_date,  a_caption, a_initiator) VALUES (now(), ?, ?)');
    $stmt->execute([$params[0], $params[1]]);
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

  public function getUserName($id, $hash)
  {
    $method_name = 'getUserNameFromDB';
    $data = $this->callMethod($method_name, array($id, $hash));
    return $data;
  }

  public function updateHash($hash, $user_id, $table = 'clients')
  {
    $method_name = 'updateHashIntoTables';
    $data = $this->callMethod($method_name, array($hash, $user_id, $table));
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
    $data = $this->callMethod($method_name);
    return $data;
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

  public function getUser($UID)
  {
    $method_name = 'getUserByID';
    $data = $this->callMethod($method_name, array($UID));
    return $data;
  }

  public function editUserData($uid, $uname, $ufam, $uphone)
  {
    $method_name = 'editUserDataUpdateDB';
    $data = $this->callMethod($method_name, array($uname, $ufam, $uphone, $uid));
  }

  public function getBookingData($uid)
  {
    $method_name = 'getUserBookingDataFromDB';
    $data = $this->callMethod($method_name, array($uid));
    return $data;
  }

  public function removeOldBook()
  {
    $method_name = 'CheckAndRemoveOldBookFromDB';
    $data = $this->callMethod($method_name);
    return $data;
  }
  public function appendLog($caption, $initiator)
  {
    $method_name = 'appendLogIntoDB';
    $this->callMethod($method_name, array($caption, $initiator));
  }
  ///////////////////////////////////ADMINKA//////////////////////////////////////

  private function createNewAdminAccount($params)
  {
    $d = new DateTime();
    $login = strval($d->format('myd') * $d->format('u'));
    $token = "";
    for ($i = 0; $i < 6; $i++) {
      $token .= $login[strlen($login) - $i];
    }
    $login = $token;
    $pass = $this->genPass(8);
    $stmt = $this->pdo->prepare('INSERT INTO inside_persons (user_login,  user_password) VALUES (?, ?)');
    $stmt->execute([$login, md5(md5($pass))]);
    $fileopen = fopen("admins.txt", "a+");
    $write = $login . ':' . $pass . "\r\n";
    fwrite($fileopen, $write);
    fclose($fileopen);

    return $login;
  }

  private function authorizeAdminAccount($params)
  {
    $stmt = $this->pdo->prepare('SELECT IDip, user_password FROM inside_persons WHERE user_login=? LIMIT 1');
    $stmt->execute([$params[0]]);
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result[0];
  }

  ////////////////////////////////////////////////////////////

  public function createAdmin()
  {
    $method_name = 'createNewAdminAccount';
    $data = $this->callMethod($method_name, array());
    return $data;
  }

  public function authorizeAdmin($user_login)
  {
    $method_name = 'authorizeAdminAccount';
    $data = $this->callMethod($method_name, array($user_login));
    return $data;
  }
}
