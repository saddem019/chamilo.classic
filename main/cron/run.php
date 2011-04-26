<?php
/*
 * This script should be called by a properly set cron process on your server.
 * For more information, check the installation guide in the documentation 
 * folder. 
 * Add your own executable scripts below the inclusion of notification.php
 */
/**
 * Settings that will influence the execution of the cron tasks
 */
//ini_set('max_execution_time',300); //authorize execution for up to 5 minutes
//ini_set('memory_limit','100M'); //authorize script to use up to 100M RAM 
/**
 * Included cron-ed tasks
 */
require_once 'notification.php';