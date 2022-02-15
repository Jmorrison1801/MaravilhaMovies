<?php
/** index.php
	* PHP program to demonstrate the usage of sessions, using the SLIM framework
	*
	* @package sessions
	*/

ini_set('xdebug.trace_output_name', 'sessions');
ini_set('display_errors', 'On');
ini_set('html_errors', 'On');
ini_set('xdebug.trace_format', 1);

if (function_exists(xdebug_start_trace()))
{
    xdebug_start_trace();
}

include_once 'sessions/bootstrap.php';

if (function_exists(xdebug_stop_trace()))
{
    xdebug_stop_trace();
}
