<?php

/* MySQL Config */
define("DB_HOST","localhost");
define("DB_USER","root");
define("DB_PASS","");
define("DB_BASE","wp_base");
/* 				*/


/* This used to generate random numbers by field type */
define("tinyint",127);
define("smallint",32767);
define("mediumint",8388607);
define("justint",2147483647);
/* */

/* Option for default values. TRUE - default values will be saved. FALSE - default values will be overrided */
define("save_default_values", false);


/* Option for debagging. All data will be displayed , but SQL query will not be executed */
define("debug", true);