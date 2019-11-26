<?php
// Logout the admin and redirect to login page
session_start();
session_destroy();

header('location:/');