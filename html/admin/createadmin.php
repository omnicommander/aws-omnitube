<?php
// New administrator or manager account
require('../functions/db.php');
require('../functions/admin.php');

 extract($_POST);
  addAdmin($adminName, $email, $pass, $role);
