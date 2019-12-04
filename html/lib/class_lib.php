<?php
require($_SERVER['DOCUMENT_ROOT']. '/functions/db.php');

class site{
    public function __construct () {
        // footer content goes here
        $this->footer = '<footer class="footer"> &copy; Omnicommander '. date('Y'). ' v0.1</footer>';

    }
}