<?php

require '../vendor/autoload.php';

use App\{Helpers, Update};

$id = (int)Helpers::checkInput($_GET['id']);

Update::upLogo('fixed', $id);

header ("Location: ../index.php");

