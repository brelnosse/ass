<?php
    //$bdd = new PDO('mysql:host=localhost;dbname=ass','root', '');
    // gerer les demandes assurances 
    if (!class_exists('Database')) {
        class Database {
            private static $instance = null;
            private $connection;
            
            private function __construct() {
                try {
                    $host = 'localhost';
                    $dbname = 'ass';
                    $username = 'root';
                    $password = '';
                    
                    $this->connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
                }
            }
            
            public static function getInstance() {
                if (self::$instance === null) {
                    self::$instance = new self();
                }
                return self::$instance;
            }
            
            public function getConnection() {
                return $this->connection;
            }
        }
    }