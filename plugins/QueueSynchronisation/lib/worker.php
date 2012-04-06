<?php

// Run in background:
// nohup php workerExample.php > /dev/null &

require_once dirname(__FILE__) . '/../../ErrorCatcher/lib/class.errorcatcherplugin.php';
ErrorCatcherPlugin::init(array('To' => 'forma@66ton99.org.ua'));

require_once __DIR__ . '/vendor/fpMq/lib/fpMqWorker.class.php';
require_once __DIR__ . '/class.queue.php';

/**
 * Call service
 *
 * @param string $message
 * @param string $queueName
 *
 * @return bool
 */
function callService($message, $queueName)
{
  echo 'Queue name: ', $queueName, "\n";
  echo 'Message: ', print_r($message, true), "\n";
  return true;
}

$worker = new fpMqWorker('callService', Queue::getInstance());
$worker->run();
