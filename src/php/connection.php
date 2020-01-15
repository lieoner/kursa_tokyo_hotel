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
  {
  }
  public function __wakeup()
  {
    throw new \Exception("Cannot unserialize a singleton.");
  }
  public function test()
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

  private function checkHashInTable($params)
  {
    if (strcasecmp($params[2], 'clients') == 0) {
      $query = 'SELECT IDc FROM clients WHERE IDc=? and user_hash=? LIMIT 1';
    } else if (strcasecmp($params[2], 'inside_persons') == 0) {
      $query = 'SELECT IDip FROM inside_persons WHERE IDip=? and user_hash=? LIMIT 1';
    }

    $stmt = $this->pdo->prepare($query);
    $stmt->execute([$params[0], $params[1]]);

    $user_true = array();
    foreach ($stmt as $row) {
      $user_true[] = $row;
    }

    if (isset($user_true[0])) {
      return true;
    } else {
      return false;
    }
  }

  private function getUserNameFromDB($params)
  {
    if ($params[1] == 'client') {
      $first_query = 'SELECT IDc FROM clients WHERE IDc=? LIMIT 1';
    } else if ($params[1] == 'personal') {
      $first_query = 'SELECT IDip FROM inside_persons WHERE IDip=? LIMIT 1';
    }
    $stmt = $this->pdo->prepare($first_query);
    $stmt->execute([$params[0]]);
    $user_true = array();
    foreach ($stmt as $row) {
      $user_true[] = $row;
    }
    if (isset($user_true[0])) {
      if ($params[1] == 'client') {
        $second_query = 'SELECT client_name FROM clients_data WHERE IDc=? LIMIT 1';
      } else if ($params[1] == 'personal') {
        $second_query = 'SELECT person_name, person_fam FROM inside_persons_data WHERE IDip=? LIMIT 1';
      }
      $stmt = $this->pdo->prepare($second_query);
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
    $stmt = $this->pdo->prepare('INSERT INTO clients_data (IDc,  client_name, client_phone, client_email) VALUES (?, ?, ?, ?)');
    $stmt->execute([$params[0], $params[1], $params[2], $params[3]]);
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
      $result[0]['bool_status'] = 1;
    } else {
      $result[0]['status'] = 'Бронь активна';
      $result[0]['bool_status'] = 0;
    }
    return $result;
  }

  private function CheckAndRemoveOldBookFromDB()
  {
    $stmt = $this->pdo->prepare('SELECT booking_list.IDc AS IDc FROM booking_list
    LEFT OUTER JOIN living_list ON booking_list.IDc = living_list.IDc 
    WHERE ISNULL(living_list.IDc)
      AND booking_list.book_status = 1
      AND CURDATE() > booking_list.comingDate + INTERVAL 2 DAY');
    $stmt->execute();
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    foreach ($result as $row) {
      $sql = "UPDATE clients SET user_password=?, user_hash=?, account_status=0 WHERE IDc=?";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute(['password_has_been_reset', '', $row['IDc']]);

      $sql = "UPDATE booking_list SET book_status=? WHERE (IDc=?) AND (book_status = 1)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute(['0', $row['IDc']]);

      static::appendLog('Сброшен бронь и аккаунт с ID = ' . $row['IDc'] . ' по истечению срока брони', 1, 2);
    }
    return $result;
  }
  private function resetBookFromDB($params)
  {
    $sql = "UPDATE clients SET user_password=?, user_hash=?, account_status=0 WHERE IDc=?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['password_has_been_reset', '', $params[0]]);

    $sql = "UPDATE booking_list SET book_status=? WHERE IDc=?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['0', $params[0]]);
  }


  private function appendLogIntoDB($params)
  {
    $stmt = $this->pdo->prepare('INSERT INTO logs (a_date,  a_caption, IDip, IDop) VALUES (now(), ?, ?, ?)');
    $stmt->execute([$params[0], $params[1], $params[2]]);
  }

  private function getServiceByIDst($params)
  {
    $stmt = $this->pdo->prepare('SELECT IDs, sname, scost, simgpath FROM services WHERE IDst = ?');
    $stmt->execute([$params[0]]);
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result;
  }
  private function getAllServices()
  {
    $stmt = $this->pdo->prepare('SELECT IDs, sname, scost, simgpath FROM services');
    $stmt->execute();
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
    $data = $this->callMethod($method_name, array($login));
    return $data;
  }

  public function getUserName($id, $who = "client")
  {
    $method_name = 'getUserNameFromDB';
    $data = $this->callMethod($method_name, array($id, $who));
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

  public function addBaseUserData($IDc, $clientName, $clientPhone, $clientEmail)
  {
    $method_name = 'insertClientDataByIDc';
    $this->callMethod($method_name, array($IDc, $clientName, $clientPhone, $clientEmail));
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

  public function getServices()
  {
    $method_name = 'getAllServices';
    $data = $this->callMethod($method_name, array());
    return $data;
  }

  public function getEat()
  {
    $method_name = 'getServiceByIDst';
    $idst = 1;
    $data = $this->callMethod($method_name, array($idst));
    return $data;
  }
  public function getTravel()
  {
    $method_name = 'getServiceByIDst';
    $idst = 3;
    $data = $this->callMethod($method_name, array($idst));
    return $data;
  }

  public function appendLog($caption, $initiator, $operation_id = 5)
  {
    $method_name = 'appendLogIntoDB';
    $this->callMethod($method_name, array($caption, $initiator, $operation_id));
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

  private function selectBookingByNumber($params)
  {
    $stmt = $this->pdo->prepare('SELECT
    clients.user_login AS bookNumber,
    booking_list.comingDate AS comingDate,
    booking_list.outDate AS outDate,
    rooms.roomNumber AS roomNumber,
    TO_DAYS(booking_list.outDate) - TO_DAYS(booking_list.comingDate) AS totalDaysCount,
    room_types.r_typeCost * (TO_DAYS(booking_list.outDate) - TO_DAYS(booking_list.comingDate)) AS totalCost
    FROM booking_list
      INNER JOIN clients
        ON booking_list.IDc = clients.IDc
      INNER JOIN rooms
        ON booking_list.IDr = rooms.IDr
      INNER JOIN room_types
        ON rooms.IDrt = room_types.IDrt
    WHERE clients.user_login = ? LIMIT 1');
    $stmt->execute([$params[0]]);
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result[0];
  }

  private function selectBookingForConfirming()
  {
    $query = 'SELECT
    nearest_booking.bookNumber AS bookNumber,
    nearest_booking.comingDate AS comingDate,
    nearest_booking.outDate AS outDate,
    nearest_booking.roomNumber AS roomNumber,
    nearest_booking.totalCost AS totalCost,
    nearest_booking.totalDaysCount AS totalDaysCount,
    nearest_booking.IDr AS IDr,
    nearest_booking.IDc AS IDc
    FROM nearest_booking
      LEFT OUTER JOIN living_list
        ON nearest_booking.IDc = living_list.IDc
    WHERE ISNULL(living_list.IDc)';

    $stmt = $this->pdo->prepare($query);
    $stmt->execute();
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result;
  }
  private function selectBookingForPayment()
  {
    $query = 'SELECT * FROM human_view_total_cost';
    $stmt = $this->pdo->prepare($query);
    $stmt->execute();
    $result = array();
    foreach ($stmt as $row) {
      $query = 'SELECT serviceTotalCostSum FROM total_service_cost WHERE IDc=' . $row['IDc'];
      $res = $this->pdo->prepare($query);
      $res->execute();
      foreach ($res as $rec) {
        $row['serviceCost'] = floatval($rec['serviceTotalCostSum']);
      }
      if (empty($row['serviceCost'])) {
        $row['serviceCost'] = 0;
      }
      $result[] = $row;
    }
    return $result;
  }

  private function insertBookDataIntoLivingList($params)
  {
    $query = 'INSERT INTO living_list (IDc,  IDr) VALUES (?, ?)';
    $stmt = $this->pdo->prepare($query);
    try {
      $stmt->execute([$params[0], $params[1]]);
      return true;
    } catch (\Throwable $th) {
      return false;
    }
  }

  private function confirmServiceQueries($params)
  {
    $stmt = $this->pdo->prepare('SELECT IDliv_l FROM living_list WHERE IDc=? LIMIT 1');
    $stmt->execute([$params[0]]);
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    $IDliv_l = $result[0]['IDliv_l'];

    $sql = 'INSERT INTO service_bills (IDs, IDliv_l, sbCount, sbCreateDate) VALUES ';
    $insertQuery = array();
    $insertData = array();
    foreach ($params[1] as $record) {
      $insertQuery[] = '(?,?,?,now())';
      $insertData[] = $record['IDs'];
      $insertData[] = $IDliv_l;
      $insertData[] = $record['sbCount'];
    }
    if (!empty($insertQuery)) {
      $sql .= implode(', ', $insertQuery);
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($insertData);
    }
  }

  private function getLivingBillFromDB($params)
  {
    $stmt = $this->pdo->prepare('SELECT * FROM nearest_booking WHERE IDc=? LIMIT 1');
    $stmt->execute([$params[0]]);
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result[0];
  }

  private function getServiceBillFromDB($params)
  {
    if ($params[0] == 'all' && $params[1] == 'all') {
      $stmt = $this->pdo->prepare('SELECT * FROM human_view_service_queries');
      $stmt->execute();
    } else if ($params[0] != 'all' && $params[1] != 'all') {
      $stmt = $this->pdo->prepare('SELECT * FROM human_view_service_queries WHERE IDc=? and sbStatus = ?');
      $stmt->execute([$params[0], $params[1]]);
    } else if ($params[0] == 'all' && $params[1] != 'all') {
      $stmt = $this->pdo->prepare('SELECT * FROM human_view_service_queries WHERE sbStatus = ?');
      $stmt->execute([$params[1]]);
    } else if ($params[0] != 'all' && $params[1] == 'all') {
      $stmt = $this->pdo->prepare('SELECT * FROM human_view_service_queries WHERE IDc=?');
      $stmt->execute([$params[0]]);
    } else {
      return 0;
    }

    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result;
  }

  private function getAllLogsFromDB($params)
  {
    $stmt = $this->pdo->prepare('SELECT * FROM human_view_logs');
    $stmt->execute();
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result;
  }
  private function getFiltredLogsFromDB($params)
  {
    $stmt = $this->pdo->prepare('SELECT * FROM human_view_logs WHERE IDop=?');
    $stmt->execute([$params[0]]);
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result;
  }

  private function getOperationTypesFromDB($params)
  {
    $stmt = $this->pdo->prepare('SELECT * FROM operations');
    $stmt->execute([]);
    $result = array();
    foreach ($stmt as $row) {
      $result[] = $row;
    }
    return $result;
  }

  private function insertServiceSolveAndUpdateSolveTime($params)
  {
    $sql = 'INSERT INTO service_queries (IDsb, IDip) VALUES ';
    $insertQuery = array();
    $insertData = array();
    foreach ($params[1] as $record) {
      $insertQuery[] = '(?,?)';
      $insertData[] = $record;
      $insertData[] = $params[0];
    }
    if (!empty($insertQuery)) {
      $sql .= implode(', ', $insertQuery);
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($insertData);
    }
    foreach ($params[1] as $record) {
      $sql = 'UPDATE service_bills SET sbResolveDate=now(), sbStatus=1 WHERE IDsb=?';
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$record]);
    }
  }

  private function insertToServiceBills($params)
  {
    $stmt = $this->pdo->prepare('INSERT INTO total_bills (IDip,  IDliv_l, tbLivingCost, tbLivingDaysCount, tbServiceCost) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$params[0], $params[1], $params[2], $params[3],  $params[4]]);
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

  public function checkHash($uid, $hash, $table = 'clients')
  {
    $method_name = 'checkHashInTable';
    $data = $this->callMethod($method_name, array($uid, $hash, $table));
    return $data;
  }

  public function findBooking($book_number)
  {
    $method_name = 'selectBookingByNumber';
    $data = $this->callMethod($method_name, array($book_number));
    return $data;
  }
  public function findNearestBooking()
  {
    $method_name = 'selectBookingForConfirming';
    $data = $this->callMethod($method_name, array());
    return $data;
  }

  public function confirmBook($client_id, $room_id)
  {
    $method_name = 'insertBookDataIntoLivingList';
    $data = $this->callMethod($method_name, array($client_id, $room_id));
    return $data;
  }

  public function confirmService($params)
  {
    $method_name = 'confirmServiceQueries';
    $data = $this->callMethod($method_name, array($params['uid'], $params['items']));
    return $data;
  }

  public function getBill($forWhat, $uid = 'all', $sbStatus = 1)
  {
    if (strcasecmp($forWhat, 'living') == 0) {
      $method_name = 'getLivingBillFromDB';
      $data = $this->callMethod($method_name, array($uid));
      return $data;
    } else if (strcasecmp($forWhat, 'service') == 0) {
      $method_name = 'getServiceBillFromDB';
      $data = $this->callMethod($method_name, array($uid, $sbStatus));
      return $data;
    } else {
      return 0;
    }
  }

  public function getLogs($tab = 'all')
  {
    if (strcasecmp($tab, 'all') == 0) {
      $method_name = 'getAllLogsFromDB';
      $data = $this->callMethod($method_name, array());
      return $data;
    } else {
      $method_name = 'getFiltredLogsFromDB';
      $data = $this->callMethod($method_name, array($tab));
      return $data;
    }
  }
  public function getOperationTypes()
  {
    $method_name = 'getOperationTypesFromDB';
    $data = $this->callMethod($method_name, array());
    return $data;
  }

  public function confirmServiceSolve($aid, $sbid_array)
  {
    $method_name = 'insertServiceSolveAndUpdateSolveTime';
    $this->callMethod($method_name, array($aid, $sbid_array));
  }

  public function getAllUsersTotalCost()
  {
    $method_name = 'selectBookingForPayment';
    $data = $this->callMethod($method_name, array());
    return $data;
  }

  public function confirmPayment($user_total, $admin_id)
  {
    $method_name = 'insertToServiceBills';
    $this->callMethod($method_name, array($admin_id, $user_total['IDliv_l'], $user_total['totalCost'], $user_total['totalDaysCount'], $user_total['serviceCost']));
    static::resetBookFromDB(array($user_total['IDc']));
    static::appendLog('Сброшен бронь и аккаунт с ID = ' . $user_total['IDc'] . ', оплата подтверждена', $admin_id, 3);
    return 1;
  }
}
