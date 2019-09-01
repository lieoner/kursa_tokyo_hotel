<?
require_once('./connection.php');
$con = tokyo_hotel::getInstance();
if (isset($_GET['d'])) {
  $_POST['roomtypeID'] = 2;
  $_POST['start'] = '28-08-2019';
  $_POST['end'] = '31-08-2019';
}

function tailCustom($filepath, $lines = 1, $adaptive = true)
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



if (strcasecmp($_GET['action'], 'checkFreeRoom') == 0) {
  if (!empty($_POST['roomtypeID']) && !empty($_POST['start']) && !empty($_POST['end'])) {
    $roomIDs = $con->getRoomIDs($_POST['roomtypeID']);
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
      $free_room;
      foreach ($roomIDs as $i => $value) {
        $room = $con->findFreeRoom($roomIDs[$i]['IDr'], $begDate, $endDate);

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
} elseif (strcasecmp($_GET['action'], 'createAccount') == 0) {
  $con->createAccount();

  $logpass = explode(':', tailCustom('udata.txt'));
  $login = $logpass[0];
  $client_id = $con->getUserData($login)['IDc'];

  $con->addBaseUserData($client_id, $_POST['uname'], $_POST['uphone']);
  $con->bookRoom($_POST['free_roomID'], $client_id,  $_POST['begDate'], $_POST['endDate']);

  echo json_encode($logpass);
  die();
}
