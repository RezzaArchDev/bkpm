<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/core/autoload.php';
require_once __DIR__ . '/../app/config/app.php';

(new Router())->dispatch();