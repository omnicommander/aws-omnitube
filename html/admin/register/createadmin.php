<?php
// New administrator or manager account
require($_SERVER['DOCUMENT_ROOT'] . '/functions/db.php');
require($_SERVER['DOCUMENT_ROOT'] . '/functions/admin.php');

 extract($_POST);
  addAdmin($adminName, $email, $pass, $role);
