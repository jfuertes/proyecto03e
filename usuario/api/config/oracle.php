<?php

/**
 * Handling Oracle database connection
 *
 * @author Mindtec
 */
class dbConnect {

    private $dbh;

    function __construct() { }

    /**
     * Establishing database connection
     * @return database connection handler
     */
    function conectardb() {
        include_once dirname(__FILE__) . '/config.php';

        header('Access-Control-Allow-Origin: *');
        $tns = "
        (DESCRIPTION =
            (ADDRESS_LIST =
              (ADDRESS = (PROTOCOL = TCP)(HOST =".DB_HOST.")(PORT = 1521))
            )
            (CONNECT_DATA =
              (SERVICE_NAME = ".DB_NAME.")
            )
          )";
        try {
            $this->dbh = new PDO("oci:dbname=".$tns, DB_USERNAME, DB_PASSWORD);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {

            $response = array('error' => $e->getMessage(), 'database' => 'Error al intentar conectarse a la base de Datos' );
            echo json_encode($response);
            exit;
        }
        return $this->dbh;
    }
}

?>