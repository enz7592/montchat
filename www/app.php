<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require dirname(__DIR__) . "/vendor/autoload.php";

use app\Kernel;

$kernel = new Kernel();

return $kernel->execute();
