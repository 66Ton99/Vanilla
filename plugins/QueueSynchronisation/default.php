<?php if (!defined('APPLICATION')) exit();

$PluginInfo['QueueSynchronisation'] = array(
   'Name' => 'Queue Synchronisation',
   'Description' => 'Synchronizes users by message bus through Zend_Queue wapper',
   'Version' => '1.0',
   'Author' => "Ton Sharp",
   'AuthorEmail' => 'Forma-PRO@66ton99.org.ua',
   'AuthorUrl' => 'http://66ton99.org.ua',
   'RequiredPlugins' => array('ErrorCatcher' => '>=1.0'),
   'MobileFriendly' => TRUE,
   'RequiredApplications' => FALSE,
   'RequiredTheme' => FALSE,
   'RequiredPlugins' => FALSE,
//    'SettingsUrl' => '/dashboard/settings/QueueSynchronisation',
   'SettingsPermission' => 'Garden.Settings.Manage',
);
