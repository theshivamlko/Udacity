<?php

/**
 * Handling database connection
 *
 */
class DbConnect {

    private $conn;

    function __construct() {
    }

    /**
     * Establishing database connection
     * @return database connection handler
     */
    function connect() {
        include_once '/home/wevandsc/link.wevands.com/ei/data/include/Config.php';
            // Connecting to mysql database
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_CONTROL);

        // Check for database connection error
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit;
        }

        // returing connection resource
        return $this->conn;
    }

}

?>
