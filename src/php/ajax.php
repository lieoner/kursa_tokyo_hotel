<?
require_once('./connection.php');
AjaxRequester::callMethod($_GET['action']);

class AjaxRequester
{
  static protected $con;

  static public function callMethod($method_name)
  {
    static::$con = tokyo_hotel::getInstance();
    try {
      return static::$method_name();
    } catch (\Throwable $th) {
      echo 'Error. Unknown method was called.';
      echo $th;
    }
  }

  static protected function test()
  {
    echo static::$con->test();
  }

  static protected function tailCustom($filepath, $lines = 1, $adaptive = true)
  {
    $f = @fopen($filepath, "rb");
    if ($f === false) return false;
    if (!$adaptive) $buffer = 4096;
    else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
    fseek($f, -1, SEEK_END);
    if (fread($f, 1) != "\n") $lines -= 1;
    $output = '';
    $chunk = '';
    while (ftell($f) > 0 && $lines >= 0) {
      $seek = min(ftell($f), $buffer);
      fseek($f, -$seek, SEEK_CUR);
      $output = ($chunk = fread($f, $seek)) . $output;
      fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
      $lines -= substr_count($chunk, "\n");
    }
    while ($lines++ < 0) {
      $output = substr($output, strpos($output, "\n") + 1);
    }
    fclose($f);
    return trim($output);
  }

  static private function convert_sqlDate_to_normalDate($sql_date, $lenght = 'short')
  {
    if (strcasecmp($lenght, 'short') == 0) {
      return date('d.m.Y', strtotime($sql_date));
    } else 
    if (strcasecmp($lenght, 'long') == 0) {
      return date('d.m.Y H:i:s', strtotime($sql_date));
    } else {
      return 0;
    }
  }

  static protected function generateCode($length = 6)
  {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
      $code .= $chars[mt_rand(0, $clen)];
    }
    return $code;
  }

  static protected function cronRemoveOldBook()
  {
    //%progdir%\modules\php\%phpdriver%\php-win.exe -c %progdir%\userdata\temp\config\php.ini -q -f %sitedir%\kursa\src\php\cron\cron.php из файла
    //%progdir%\modules\wget\bin\wget.exe -q --no-cache http://kursa/src/php/ajax.php?action=cronRemoveOldBook -O %progdir%\userdata\temp\temp.txt po url

    $log = static::$con->removeOldBook();
    $fileopen = fopen("kursa_logs.txt", "a+");
    $d = new DateTime();
    $date = strval($d->format('H:i:s m.d.y'));
    $write = $date . ' Я сделал крон (деактивировал устаревшие брони) из ajax.php' . "\r\n";
    fwrite($fileopen, $write);
    fclose($fileopen);
  }

  static protected function checkFreeRoom()
  {
    if (!empty($_POST['roomtypeID']) && !empty($_POST['start']) && !empty($_POST['end'])) {
      $roomIDs = static::$con->getRoomIDs($_POST['roomtypeID']);
      $temp =  explode('-', $_POST['start']);
      $begDate = $temp[2] . $temp[1] . $temp[0];
      unset($temp);
      $temp = explode('-', $_POST['end']);
      $endDate = $temp[2] . $temp[1] . $temp[0];
      unset($temp);
      $result = [
        'status' => false
      ];
      if (empty($roomIDs)) {
        echo json_encode($result);
        die();
      } else {
        //$free_room;
        foreach ($roomIDs as $i => $value) {
          $room = static::$con->findFreeRoom($roomIDs[$i]['IDr'], $begDate, $endDate);

          if ($room[0]['COUNT(*)'] === 0) {
            $free_room = $roomIDs[$i]['IDr'];
          }
        }
        if (empty($free_room)) {
          echo json_encode($result);
          die();
        } {
          $result = [
            'status' => true,
            'free_roomID' => $free_room,
            'begDate' => $begDate,
            'endDate' => $endDate
          ];
          unset($_POST);
          echo json_encode($result);
          die();
        }
      }
    } else {
      $result = [
        'status' => false
      ];
      echo json_encode($result);
      die();
    }
  }

  static protected function createAccount()
  {
    $login = static::$con->createAccount();
    $client_id = static::$con->getUserData($login)['IDc'];

    static::$con->addBaseUserData($client_id, $_POST['uname'], $_POST['uphone'], $_POST['umail']);
    static::$con->bookRoom($_POST['free_roomID'], $client_id,  $_POST['begDate'], $_POST['endDate']);

    //echo json_encode($logpass);
    die();
  }

  static protected function login()
  {
    $login  = str_replace(' ', '', $_POST['login']);
    $pass = md5(md5($_POST['pass']));
    $user = static::$con->getUserData($login);
    if ($user['user_password'] === $pass) {
      $hash = md5(static::generateCode(10));
      static::$con->updateHash($hash, $user['IDc']);

      setcookie("id", $user['IDc'], time() + 60 * 60 * 24 * 30, '/', '');
      setcookie("hash", $hash, time() + 60 * 60 * 24 * 30, '/', '', null, true);

      $result = ['status' => true, 'user' => $user];
      echo json_encode($result);
      die();
    }

    $result = ['status' => false];
    echo json_encode($result);
    die();
  }

  static protected function checkHash()
  {
    if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
      if (static::$con->checkHash($_COOKIE['id'], $_COOKIE['hash'])) {
        $user_name = static::$con->getUserName($_COOKIE['id']);
        $result = ['status' => true, 'user_name' => $user_name['client_name']];
        echo json_encode($result);
        die();
      } else {
        $result = ['status' => false];
        echo json_encode($result);
        die();
      }
    } else {
      $result = ['status' => false];
      echo json_encode($result);
      die();
    }
  }

  static protected function logout()
  {
    if (isset($_COOKIE['id'])) {
      unset($_COOKIE['id']);
      setcookie("id", "", time() - 3600, '/', '');
    }
    if (isset($_COOKIE['hash'])) {
      unset($_COOKIE['hash']);
      setcookie("hash", "", time() - 3600, '/', '');
    }
    $result = ['status' => true];
    header("Location: ../../index.php");
    die();
  }

  static protected function editUserData()
  {
    static::$con->editUserData($_POST['uid'], $_POST['uname'], $_POST['ufam'], $_POST['uphone']);
  }

  static protected function createNewAdmin()
  {
    //echo static::$con->createAdmin();
  }

  static protected function authorizeAdmin()
  {
    session_start();
    $login  = str_replace(' ', '', $_POST['login']);
    $pass = md5(md5($_POST['pass']));
    $user = static::$con->authorizeAdmin($login);
    if ($user['user_password'] === $pass) {
      $hash = md5(static::generateCode(10));
      static::$con->updateHash($hash, $user['IDip'], 'inside_persons');

      $result = ['status' => true, 'user' => $user];

      $_SESSION['admins'] = ['aid' => $user['IDip'], 'ah' => $hash];

      echo json_encode($result);
      die();
    }

    $result = ['status' => false];
    echo json_encode($result);
    die();
  }

  static protected function checkAdminHash()
  {
    session_start();
    if (isset($_SESSION['admins'])) {
      $admin_id_hash =  $_SESSION['admins'];
      if (static::$con->checkHash($admin_id_hash['aid'], $admin_id_hash['ah'], 'inside_persons')) {
        echo true;
        die();
      }
    }
    echo false;
    die();
  }
  static protected function getAdminName()
  {
    session_start();
    if (isset($_SESSION['admins'])) {
      $admin_id_hash =  $_SESSION['admins'];
      $admin_name = static::$con->getUserName($admin_id_hash['aid'], 'personal');
      echo json_encode($admin_name);
      die();
    }
    echo false;
    die();
  }

  static protected function selectBookByNumber()
  {
    echo json_encode(static::$con->findBooking(str_replace(' ', '', $_POST['book-number'])));
    die();
  }

  static protected function getNearestBookingTable()
  {
    $nearest_logs = static::$con->findNearestBooking();
    foreach ($nearest_logs as $key => $log) {
      ?>
      <tr data-client-id=<?= $log['IDc'] ?> data-room-id=<?= $log['IDr'] ?>>
        <td class="bookNumber" data-book-number=<?= $log['bookNumber'] ?>><?= $log['bookNumber'] ?></td>
        <td class="comingDate" data-coming-date=<?= explode(' ', $log['comingDate'])[0] ?>><?= static::convert_sqlDate_to_normalDate($log['comingDate']) ?></td>
        <td class="outDate" data-out-date=<?= explode(' ', $log['outDate'])[0] ?>><?= static::convert_sqlDate_to_normalDate($log['outDate']) ?></td>
        <td class="roomNumber" data-room-number=<?= $log['roomNumber'] ?>><?= $log['roomNumber'] ?></td>
        <td class="totalDaysCount" data-total-days-count=<?= $log['totalDaysCount'] ?>><?= $log['totalDaysCount'] ?></td>
        <td class="totalCost" data-total-сost=<?= $log['totalCost'] ?>><?= $log['totalCost'] ?></td>
      </tr>
    <?
        }
        die();
      }

      static protected function confirmBook()
      {
        session_start();
        if (isset($_SESSION['admins'])) {
          $admin_id_hash =  $_SESSION['admins'];
          $result = static::$con->confirmBook($_POST['client_id'], $_POST['room_id']);
          if ($result) {
            static::appendLog('Подтверждено заселение клиента ID = ' . $_POST['client_id'] . ' в комнату ID = ' . $_POST['room_id'], $admin_id_hash['aid'], 1);
          }
        } else {
          $result = 0;
        }
        echo $result;
        die();
      }

      static protected function appendLog($caption, $initiator)
      {
        static::$con->appendLog($caption, $initiator);
      }

      static protected function alogout()
      {
        session_start();
        if (isset($_SESSION['admins'])) {
          unset($_SESSION['admins']);
        }
        $result = ['status' => true];
        header("Location: ../../admin.php");
        die();
      }

      static protected function getHtmlCart($cart)
      {
        $output = '';
        foreach ($cart as $key => $value) {
          $output .=
            '<li class="cart-item list-group-item" data-service-id="' . $key . '">
          <div class="row">
            <div class="col-10">
              <h6 class="card-title">' . $cart[$key]['name'] . '</h6>
              <p class="card-text">
                <span class="count">' . $cart[$key]['count'] . ' шт.</span>
                <span class="cost">' . $cart[$key]['cost'] * $cart[$key]['count'] . ' руб.</span>
              </p>
            </div>
            <div class="col-2"><button type="button" class="btn btn-secondary btn-sm">X</button></div>
          </div>
        </li>';
        }
        return $output;
      }

      static protected function checkCart()
      {
        session_start();
        if (isset($_SESSION['cart'])) {
          $output = static::getHtmlCart($_SESSION['cart']);
        } else {
          $output = 0;
        }
        echo $output;
        die();
      }

      static protected function cartAddService()
      {
        session_start();
        if (isset($_SESSION['services'])) {
          $services = $_SESSION['services'];
        } else {
          $_SESSION['services'] = static::$con->getServices();
          $services = $_SESSION['services'];
        }
        $key = array_search($_POST['serviceID'], array_column($services, 'IDs'));
        if (isset($_SESSION['cart'])) {
          $cart = $_SESSION['cart'];
        } else {
          $cart = array();
        }

        $cart[$_POST['serviceID']] = [
          'id' => $_POST['serviceID'],
          'name' => $services[$key]['sname'],
          'count' => $_POST['serviceCount'],
          'cost' => $services[$key]['scost'],
        ];
        $output = static::getHtmlCart($cart);
        echo $output;
        $_SESSION['cart'] = $cart;
        die();
      }

      static protected function cartRemoveService()
      {
        session_start();
        if (isset($_SESSION['cart'][$_POST['serviceID']])) {
          unset($_SESSION['cart'][$_POST['serviceID']]);
        }
        $output = static::getHtmlCart($_SESSION['cart']);
        echo $output;
        die();
      }

      static protected function clearCart()
      {
        session_start();
        if (isset($_SESSION['cart'])) {
          unset($_SESSION['cart']);
        }
        die();
      }

      static protected function confirmCart()
      {
        session_start();
        if (isset($_COOKIE['id'])) {
          if (isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
            $params = array();
            $i = 0;
            $params['uid'] = $_COOKIE['id'];
            foreach ($cart as $key => $value) {
              $params['items'][$i]['IDs'] = $cart[$key]['id'];
              $params['items'][$i++]['sbCount'] = $cart[$key]['count'];
            }
            static::$con->confirmService($params);
            static::clearCart();
          }
        }
        die();
      }

      static protected function getTotalCost()
      {
        $uid = 0;
        if (isset($_GET['uid'])) {
          $uid = $_GET['uid'];
        } else if (isset($_COOKIE['id'])) {
          $uid = $_COOKIE['id'];
        }
        if ($uid == 0) {
          echo $uid;
          die();
        }
        $totalLivingBill = static::$con->getBill('living', $uid);
        $totalServicesBill = static::$con->getBill('service', $uid);
        $totalServiceBill = array();
        $totalCost = 0;
        foreach ($totalServicesBill as $i => $value) {
          $totalServiceBill['items'][$i]['name'] = $totalServicesBill[$i]['sName'];
          $totalServiceBill['items'][$i]['count'] = $totalServicesBill[$i]['sbCount'];
          $totalServiceBill['items'][$i]['cost'] = $totalServicesBill[$i]['sCost'];
          $totalCost += $totalServicesBill[$i]['sCost'] * $totalServicesBill[$i]['sbCount'];
        }
        $totalServiceBill['cost'] = $totalCost;
        $totalCost += $totalLivingBill['totalCost'];
        echo json_encode(['living' => ['daysCount' => $totalLivingBill['totalDaysCount'], 'cost' => $totalLivingBill['totalCost']], 'service' => $totalServiceBill, 'total' => ['cost' => $totalCost]]);
        die();
      }

      static protected function getLogs()
      {
        session_start();
        if (!isset($_SESSION['admins'])) {
          echo 0;
          die();
        }
        $logs = static::$con->getLogs($_POST['tab']);
        foreach ($logs as $key => $log) {
          ?>
      <tr data-log-id=<?= $log['log_id'] ?>>
        <td class="opName" data-operation-id=<?= $log['IDop'] ?>><?= $log['opName'] ?></td>
        <td class="a_caption"><?= $log['a_caption'] ?></td>
        <td class="ipName" data-initiator-id=<?= $log['IDip'] ?>><?= $log['ipName'] ?></td>
        <td class="a_date" data-log-date=<?= explode(' ', $log['a_date'])[0] ?>><?= static::convert_sqlDate_to_normalDate($log['a_date'], 'long') ?></td>
      </tr>
    <? }
        die();
      }
      static protected function getServiceByBookNumberAndStatus()
      {
        session_start();
        if (!isset($_SESSION['admins'])) {
          echo 0;
          die();
        }
        if ($_POST['book_number'] == 'all') {
          $uid = 'all';
        } else {
          $uid = static::$con->getUserData($_POST['book_number'])['IDc'];
        }
        $serviceQueries = static::$con->getBill('service', $uid, $_POST['status']);
        foreach ($serviceQueries as $query) {
          if ($query['sbStatus'] == 0) {
            $solvStatus = 'unsolved table-info';
          } else {
            $solvStatus = 'solved table-success';
          }
          ?>
      <tr class="<?= $solvStatus ?>" data-query-id=<?= $query['IDsb'] ?>>
        <td class="sName" data-service-id=<?= $query['IDs'] ?>><?= $query['sName'] ?></td>
        <td class="sbCount"><?= $query['sbCount'] ?></td>
        <td class="sbCreateDate" data-queryCreate-date=<?= explode(' ', $query['sbCreateDate'])[0] ?>><?= static::convert_sqlDate_to_normalDate($query['sbCreateDate'], 'long') ?></td>
        <td class="sbResolveDate" data-queryResolve-date=<?= explode(' ', $query['sbResolveDate'])[0] ?>><?= static::convert_sqlDate_to_normalDate($query['sbResolveDate'], 'long') ?></td>
        <td class="totalCost"><?= $query['totalCost'] ?></td>
      </tr>
<?
    }
    die();
  }
}
