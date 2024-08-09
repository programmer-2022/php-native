<?php

require_once __DIR__ . '/../utils/envLoader.php';

class Connection
{
    private static ?Connection $instance = null;
    private ?PDO $pdo = null;

    private function __construct()
    {
        $config = loadConfig(__DIR__ . '/../config/env.config.json');
        
        $dbType = $config['db_type'];
        $dbConfig = $config[$dbType];
        $dataSourceName = $this->getDatasource($dbType, $dbConfig);
        
        try {
            $this->pdo = new PDO($dataSourceName, $dbConfig['user'], $dbConfig['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            error_log('Conexión exitosa');
        } catch (PDOException $e) {
            error_log('Error de conexión: ' . $e->getMessage());
        }
    }

    public static function getInstance(): Connection
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function getDatasource(string $dbType, array $dbConfig): string
    {
        switch ($dbType) {
            case 'mysql':
                return "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']}";
            case 'postgres':
                return "pgsql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']}";
            default:
                throw new Exception("Tipo de base de datos no soportado.");
        }
    }

    public function getConnection(): ?PDO
    {
        return $this->pdo;
    }

    public function closeConnection()
    {
        return $this->pdo = null;
    }
}
