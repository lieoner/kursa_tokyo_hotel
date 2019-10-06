<?

class User
{
  static protected $UID;
  static protected $UName;
  static protected $UFam;
  static protected $UPhone;
  static protected $BookNumber;
  static protected $BookingData;

  static public function loadUser()
  {
    $con = tokyo_hotel::getInstance();
    if (!isset(static::$UID)) :
      static::$UID = $_GET['uid'];
    endif;
    $udata = $con->getUser(static::$UID)[0];
    static::$UName = $udata['client_name'];
    static::$UFam = $udata['client_fam'];
    static::$UPhone = $udata['client_phone'];
    static::$BookNumber = $udata['user_login'];
  }
  static public function getUserName()
  {
    return static::$UName;
  }
  static public function getUserFam()
  {
    return static::$UFam;
  }
  static public function getUserPhone()
  {
    return static::$UPhone;
  }
  static public function getBookNumber()
  {
    return static::$BookNumber;
  }
  static public function getUserID()
  {
    return static::$UID;
  }
}
