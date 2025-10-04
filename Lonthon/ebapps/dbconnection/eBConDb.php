<?php
namespace ebapps\dbconnection;
class eBConDb {
  private static $instance = null;
  private $eBCon;

  // Private constructor to prevent direct instantiation
  private function __construct() {
    $this->eBCon = new \mysqli(EB_HOSTNAME, EB_DB_USERNAME, EB_DB_PASSWORD, EB_DATABASE);

    if (mysqli_connect_errno()) {
      // Log error or handle it
      error_log("Database connection error: " . mysqli_connect_error());
      include_once(docRoot . "/under-maintenance.php");
      exit();
    }
    
  }

  // Public static method to get the instance of the class
  public static function eBgetInstance() {
    if (!self::$instance) {
      self::$instance = new eBConDb();
    }

    return self::$instance;
  }

  // Public method to get the database connection
  public function eBgetConection() {
    return $this->eBCon;
  }

  // Prevent instance from being cloned (which would create a second instance of it)
  private function __clone() {}

  // Prevent from being unserialized (which would create a second instance of it)
  public function __wakeup() {}
}

?>