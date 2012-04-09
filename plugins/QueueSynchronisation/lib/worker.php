<?php

// Run in background:
// nohup php workerExample.php > /dev/null &
define('APPLICATION', true);
require_once __DIR__ . '/vendor/fpMq/lib/autoload.php';

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
         )
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
   function callService($message, $queueName) {
       require __DIR__ . '/service.php';
       return false;
   }
}

$woker = new Worker();
