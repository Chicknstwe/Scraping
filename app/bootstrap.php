<?php

define( 'API_ROOT', dirname( __FILE__) );

// Import all the libraries in libs folder
foreach (glob(API_ROOT . '/libs/*.inc.php') as $lib) {
  include $lib;
}
?>
