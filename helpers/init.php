<?php
/* var_dump(dirname(__FILE__),2);
die(); */
define( 'ROOT_DIR' , dirname(__FILE__,2));  //__FILE__ abszolút útvonal, előre definiált konstans
define( 'DEFINITION_DIR' , dirname(__FILE__,2).'/definitions'); 
define( 'HELPERS_DIR' , dirname(__FILE__, 2). '/helpers');
define( 'MODELS_DIR' , dirname(__FILE__, 2).'/Models');
define( 'TRAITS_DIR' , dirname(__FILE__, 2).'/Traits');
define( 'PUBLIC_USERS_DIR' , dirname(__FILE__, 2).'/public/users'); //fájlfeltöltéskor használjuk (/var/www/etterem/...), HTML-ben képek megjelenítésére nem
define( 'PUBLIC_FOODS_DIR' , dirname(__FILE__, 2).'/public/foods');





?>