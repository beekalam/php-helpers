<?php

namespace Beekalam\PHPHelpers;

use PDO;
use PDOException;

class DB
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_CLASS);
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function insert($table, $values)
    {
        $sql = "insert into $table(" . implode(",", array_keys($values)) . ") values(" .
            implode(",", array_values($values)) . ")";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            $row = $stmt->fetch();
            return $row;
        } catch (PDOException $err) {
            throw $err;
        }
    }

    public function count($table)
    {
        $sql = "select count(*) as count from $table";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            $row = $stmt->fetch();

            return (int)$row['count'];
        } catch (PDOException $err) {
            throw $err;
        }
    }

    protected function findBy($table, $col, $value)
    {
        $sql = "select *  from $table where $col='$value'";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            $row = $stmt->fetch();
            return $row;
        } catch (PDOException $err) {
            throw $err;
        }
    }

    protected function query($query)
    {
        $stmt = $this->db->prepare($query);
        try {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            return $rows;
        } catch (PDOException $err) {
            throw $err;
        }
    }
}
