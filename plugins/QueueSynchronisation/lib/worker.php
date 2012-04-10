<?php

// Run in background:
// nohup php workerExample.php > /dev/null &
use Behat\Mink\Exception\Exception;

define('APPLICATION', true);
define('DEBUG', true);
require_once __DIR__ . '/vendor/fpMq/lib/autoload.php';
require_once __DIR__ . '/class.userconvector.php';

class Worker
{

   /**
    * @var array
    */
   protected $configuration = null;

   protected $woker;

   public function __construct()
   {
      $this->initErrorCatcher();
      $this->worker = new fpMqWorker(
         array($this, 'callService'),
         fpMqQueue::init(
            $this->getConfigs('Plugins.QueueSynchronisation.Options'),
            $this->getConfigs('Plugins.QueueSynchronisation.AmazonUrl')
         ),
         'dev' // FIXME check environment
      );
      $this->worker->run();
   }

   protected function initErrorCatcher()
   {
      if ($this->getConfigs('EnabledPlugins.ErrorCatcher') && $this->getConfigs('Plugins.ErrorCatcher.To')) {
          require_once dirname(__FILE__) . '/../../ErrorCatcher/lib/class.errorcatcher.php';
          ErrorCatcher::init($this->getConfigs('Plugins.ErrorCatcher'));
      }
   }

   /**
    *
    *
    * @param string $key
    *
    * @return array
    */
   public function getConfigs($key = null, $default = false)
   {
      if (null === $this->configuration) {
         require __DIR__ . '/../../../conf/config.php';
         $this->configuration = $Configuration;
      }
      $path = explode('.', $key);

      $value = ($this->configuration);
      $count = count($path);
      for($i = 0; $i < $count; ++$i) {
         if(is_array($value) && array_key_exists($path[$i], $value)) {
            $value = $value[$path[$i]];
         } else {
            return $default;
         }
      }
      return $value;
   }

   /**
    * Call service
    *
    * @param string $message
    * @param string $queueName
    *
    * @return bool
    */
   public function callService($message, $queueName) {
       if ('user' == $queueName) {
          echo "Get one\n";
          $this->createUser((array)$message);
          return true;
       }
       return false;
   }

   protected function genFieldsQuery($data)
   {
      $return = array();
      foreach ($data as $key => $val) {
         $return[] = "`$key` = '{$val}'";
      }
      return implode(',', $return);
   }

   protected function createUser(array $data)
   {
      $userConvector = new UserConvector($data);
      $newData = $userConvector->vanilla();
      if (empty($newData)) return;
      $fields = $this->genFieldsQuery($newData);
      $tablePrefix = 'GDN_';
      $con = new PDO(
         'mysql:host=' . $this->getConfigs('Database.Host') . ';dbname=' . $this->getConfigs('Database.Name'),
         $this->getConfigs('Database.User'),
         $this->getConfigs('Database.Password')
      );
      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $query = "";
      try {
         $query = "SELECT UserID FROM {$tablePrefix}UserAuthentication WHERE ForeignUserKey = {$data['id']}";
         $res = $con->prepare($query);
         $res->execute();
         if ($user = $res->fetch(PDO::FETCH_ASSOC)) {
            $query = "UPDATE {$tablePrefix}User
               SET {$fields}
               WHERE UserID = {$user['UserID']}";
            $sth = $con->prepare($query);
            $sth->execute();
            echo "Update '{$newData['Name']}'\n";
         } else {
            $query = "INSERT INTO {$tablePrefix}User SET {$fields}";
            $sth = $con->prepare($query);
            $sth->execute();
            $newUserId = $con->lastInsertId();
            $query = "INSERT INTO {$tablePrefix}UserAuthentication
               SET UserID = {$newUserId}, ForeignUserKey = {$data['id']}";
            $sth = $con->prepare($query);
            $sth->execute();
            $query = "INSERT INTO {$tablePrefix}UserRole
            SET UserID = {$newUserId}, RoleID = 8"; // TODO is not forget
            $sth = $con->prepare($query);
            $sth->execute();
            echo "Insert '{$newData['Name']}'\n";
         }
      } catch (PDOException $e) {
         throw new ErrorException('Query error: ' . strtr($query, "\n", ' '), $e->getCode(), null, $e->getFile(), $e->getLine(), $e);
      }
   }
}

$woker = new Worker();
