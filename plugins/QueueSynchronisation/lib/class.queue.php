<?php

if (!defined('APPLICATION')) exit();

/**
 * Decorator for Zend_Queue
 *
 * @author ton
 */
class Queue {

   /**
    * Object instance
    *
    * @var Queue
    */
   protected static $instance;

   protected $zendQueue;

   /**
    * Constructor
    *
    * @return void
    */
   protected function __construct() {
      $options = Gdn::Config('Plugins.QueueSynchronisation.Options');
      $driver = new fpMqAmazonQueue($options);
      $this->zendQueue = new Zend_Queue($driver);
   }

   /**
    * Return singleton
    *
    * @return Queue
    */
   public static function getInstance() {
      if (empty(static::$instance)) {
         static::$instance = new static();
      }
      return static::$instance;
   }

   /**
    *
    * @param mixed $data
    *
    * @return fpMqQueue
    */
   public function send($data, $queue) {
      $this->zendQueue->setOption('queueUrl', Gdn::Config('Plugins.QueueSynchronisation.AmazonUrl') . $queue);
      $this->zendQueue->send(json_encode($data));
      return $this;
   }

   /**
    * Decorated object
    *
    * @var object
    */
   protected $object = null;

   /**
    * Magic method
    *
    * @param string $method
    * @param array $params
    *
    * @throws Exception
    *
    * @return mixed
    */
   public function __call($method, $params) {
      if (!method_exists($this->zendQueue, $method)) {
         throw new Exception("Called '{$method}' method does not exist in " . get_class($this));
      }
      $return = call_user_func_array(array($this->zendQueue, $method), $params);
      return $return;
   }
}
