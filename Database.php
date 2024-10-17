<?php
namespace app\core;

class Database {
    public \PDO $pdo;

    public function __construct($config) {
        try {
            $this->pdo = new \PDO($config['dsn'], $config['user'], $config['password']);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
            if ($this->pdo) {
                echo "Connected to the database successfully!";
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function createMigrationTable() {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,  -- Уникальный идентификатор для каждой миграции
            migration VARCHAR(255) NOT NULL,    -- Имя или описание миграции
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Время выполнения миграции
        );');
    }

    public function applyMigrations() {
        $this->createMigrationTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $migrationList = scandir(dirname(__DIR__) . '/migrations');

        $migrationsToApply = array_diff($migrationList, $appliedMigrations);
        
        $migrationsToInsertIntoDB = [];
       
        foreach($migrationsToApply as $migration) {
            if($migration !== '.' and $migration !== '..') {
                $migrationsDir = Application::$ROOT_DIR . '/migrations/';
                require_once $migrationsDir . $migration;

                $className = pathinfo($migration, PATHINFO_FILENAME);
                $migrationObj = new $className();
                
                $migrationObj->up();
                $migrationsToInsertIntoDB[] = $migration;
            }
        }
        
        $this->saveMigrations($migrationsToInsertIntoDB);
    }

    public function saveMigrations($migrations = []) {
        if(!empty($migrations)) {
            $placeholders = implode(',', array_fill(0, count($migrations), '(?)'));
            $sql = "INSERT INTO migrations (migration) VALUES $placeholders";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($migrations);
        }
        var_dump('All migrations are applied!');
    }

    public function getAppliedMigrations() {
        $statement = $this->pdo->query('SELECT migration FROM migrations');
        return $statement->fetchAll(\PDO::FETCH_COLUMN);   
    }

    public function findOne($table, $conditions) {
        $sql = "SELECT * FROM $table WHERE " . buildAndCondition($conditions);
        $stmt = $this->pdo->prepare($sql);
        // // var_dump($param);
        $stmt->execute($conditions);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function insertRecord($table, $params = []) {
        $keys = array_keys($params);
        $columnString = implodeArray($keys);
        $valueString = implodeArray(prependColonToArrayValues($keys));
        $sql = "INSERT INTO $table ($columnString) VALUES ($valueString)";
        var_dump($sql);

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute($params);
        } catch (\PDOException $e) {
            throw new \Exception('Database error: ' . $e->getMessage());
        }
    }

}