<?
require_once('./connection.php');
$con = tokyo_hotel::getInstance();
if (isset($_GET['d'])) {
  $_POST['roomtypeID'] = 2;
  $_POST['start'] = '28-08-2019';
  $_POST['end'] = '31-08-2019';
}
if (!empty($_POST['roomtypeID']) && !empty($_POST['start']) && !empty($_POST['end'])) {
  $roomIDs = $con->getRoomIDs($_POST['roomtypeID']);
  $temp =  explode('-', $_POST['start']);
  $begDate = $temp[2] . $temp[1] . $temp[0];
  $temp = explode('-', $_POST['end']);
  $endDate = $temp[2] . $temp[1] . $temp[0];
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
        'free_roomID' => $free_room

      ];
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
