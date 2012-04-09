<?php
define('APPLICATION', 'Vanilla');
define('APPLICATION_VERSION', '2.0.18.4');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR);
ini_set('display_errors', 'on');
ini_set('track_errors', 1);

ob_start();

// // 0. Start profiling if requested in the querystring
// if (isset($_GET['xhprof']) && $_GET['xhprof'] == 'yes')
//     define('PROFILER', TRUE);

// if (defined('PROFILER') && PROFILER) {
//     $ProfileWhat = 0;

//     if (isset($_GET['memory']) && $_GET['memory'] == 'yes')
//         $ProfileWhat += XHPROF_FLAGS_MEMORY;

//     if (isset($_GET['cpu']) && $_GET['cpu'] == 'yes')
//         $ProfileWhat += XHPROF_FLAGS_CPU;

//     xhprof_enable($ProfileWhat);
// }

// 1. Define the constants we need to get going.
define('DS', '/');
define('PATH_ROOT', dirname(__FILE__) . '/../../../');

// 2. Include the bootstrap to configure the framework.
require_once(PATH_ROOT.'/bootstrap.php');

$db = new Gdn_Database(Gdn::Config());

$db->Connection()->;
