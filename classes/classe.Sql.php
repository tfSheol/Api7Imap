<?php

/**
 * Sauvegarder ces e-mail sur une bdd.
 * 
 * @author Teddy.fontaine@epitech.eu
 * @version 0.1
 */
Class Sql
{
    private $_db = "";
    private $_user = "";
    private $_passwd = "";
    private $_host = "";
    
    private $_pdo;
    
    /**
     * Connextion à la base de donnée.
     */
    public function __construct()
    {
        $this->_pdo = new PDO('mysql:host='.$this->_host.';dbname='.$this->_db.
                ';', $this->_user, $this->_passwd);
    }
}

?>