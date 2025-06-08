<?php

namespace models;

use core\DB;
use core\Config;
use core\Model;

class Admin extends Model
{
    private $db;

    public function __construct()
    {
        $host = Config::get()->dbHost;
        $name = Config::get()->dbName;
        $login = Config::get()->dbLogin;
        $password = Config::get()->dbPassword;
        $this->db = new DB($host, $name, $login, $password);
    }

    public function getTableData(string $table, $fields = "*", $where = null): array
    {
        return $this->db->select($table, $fields, $where);
    }

    public function updateRecords(string $table, array $data, string $primaryKey): array
    {
        $errors = [];
        $success = true;
        
        foreach ($data as $id => $row) {
            if (!empty($id) && isset($row[$primaryKey])) {
                $result = $this->db->update($table, $row, [$primaryKey => $id]);
               
            } else {
                $errors[] = "Пропущено первинний ключ для запису з ID: $id";
                $success = false;
            }
        }
        
        return [
            'success' => $success,
            'errors' => $errors
        ];
    }

    public function deleteRecord(string $table, string $primaryKey, $id): bool
    {
        return $this->db->delete($table, [$primaryKey => $id]);
    }

    public function createRecord(string $table, array $data)
    {
        error_log("Creating record in table: " . $table);
        error_log("Data before processing: " . print_r($data, true));
        
      
        $data = array_filter($data, function($value) {
            return $value !== '' && $value !== null;
        });

        error_log("Data after processing: " . print_r($data, true));
        
        try {
            $result = $this->db->insert($table, $data);
            error_log("Insert result: " . ($result ? "success" : "failed"));
            return $result;
        } catch (\Exception $e) {
            error_log("Error creating record: " . $e->getMessage());
            throw $e;
        }
    }
}