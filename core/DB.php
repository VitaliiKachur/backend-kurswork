<?php

namespace core;

class DB
{
    public $pdo;

    public function __construct($host, $name, $login, $password)
    {
        $this->pdo = new \PDO(
            "mysql:host={$host};dbname={$name}",
            $login,
            $password,
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]
        );
    }

    protected function where($where)
    {
        $where_string = "";
        if (is_array($where) && !empty($where)) {
            $where_string = "WHERE ";
            $parts = [];
            foreach ($where as $field => $value) {
                $parts[] = "{$field} = :{$field}";
            }
            $where_string .= implode(' AND ', $parts);
        } elseif (is_string($where) && !empty($where)) {
            $where_string = "WHERE " . $where;
        }
        return $where_string;
    }

    public function select($table, $fields = "*", $where = null)
    {
        if (is_array($fields))
            $fields_string = implode(', ', $fields);
        else
            if (is_string($fields))
                $fields_string = $fields;
            else
                $fields_string = "*";

        $where_string = $this->where($where);

        $sql = "SELECT {$fields_string} FROM {$table} {$where_string}";
        $sth = $this->pdo->prepare($sql);

        if (is_array($where) && !empty($where)) {
            foreach ($where as $key => $value)
                $sth->bindValue(":{$key}", $value);
        }

        $sth->execute();
        return $sth->fetchAll();
    }


    public function insert($table, $row_to_insert)
    {
        $fields_list = implode(", ", array_keys($row_to_insert));
        $params_array = [];
        foreach ($row_to_insert as $key => $value) {
            $params_array[] = ":{$key}";
        }
        $params_list = implode(", ", $params_array);
        $sql = "INSERT INTO {$table} ({$fields_list}) VALUES ({$params_list})";
        error_log("SQL: $sql");
        error_log("Params: " . print_r($row_to_insert, true));

        try {
            $sth = $this->pdo->prepare($sql);
            foreach ($row_to_insert as $key => $value) {
                $sth->bindValue(":{$key}", $value);
            }
            $sth->execute();
            $lastId = $this->pdo->lastInsertId();
            error_log("Insert successful. Last insert ID: " . $lastId);
            return $lastId;
        } catch (\PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            error_log("SQL State: " . $e->getCode());
            throw $e;
        }
    }

    public function delete($table, $where)
    {
        $where_string = $this->where($where);

        $sql = "DELETE FROM {$table} {$where_string}";
        $sth = $this->pdo->prepare($sql);

        if (is_array($where) && !empty($where)) {
            foreach ($where as $key => $value)
                $sth->bindValue(":{$key}", $value);
        }

        $sth->execute();
        return $sth->rowCount();
    }

    public function update($table, $row_to_update, $where)
    {
        $where_string = $this->where($where);
        $set_array = [];
        foreach ($row_to_update as $key => $value) {
            $set_array[] = "{$key} = :set_{$key}"; 
        }
        $set_string = implode(", ", $set_array);
        $sql = "UPDATE {$table} SET {$set_string} {$where_string}";
        $sth = $this->pdo->prepare($sql);

        if (is_array($where) && !empty($where)) {
            foreach ($where as $key => $value)
                $sth->bindValue(":{$key}", $value);
        }

        foreach ($row_to_update as $key => $value)
            $sth->bindValue(":set_{$key}", $value); 

        $sth->execute();
        return $sth->rowCount();
    }
}