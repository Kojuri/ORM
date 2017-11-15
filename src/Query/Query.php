<?php
namespace ORM\Query;
use Utils\ConnectionFactory;

class Query
{
    private $sqltable;
    private $fields = '*';
    private $where = null;
    private $args = [];
    private $sql = '';
    
    public static function table($table) {
        $query = new Query;
        $query->sqltable= $table;
        return $query;
    }
    public function select( array $fields) {
        $this->fields = implode( ',', $fields);
        return $this;
    }
    public function where($col, $op, $val) {
     $cond = $col.' '.$op.' ? ';
     $this->args[]=$val;
     if(is_null($this->where)){
         $this->where = "where ".$cond;
     }
     else {
         $this->where .= "and ".$cond;
     }
     return $this;
    }
    public function get() {
     $this->sql = 'select '. $this->fields .
    ' from ' . $this->sqltable;
     if($this->where != NULL)
     {
         $this->sql .= " ".$this->where;
     }
     $pdo = ConnectionFactory::getConnection();
     try{
        $stmt = $pdo->prepare($this->sql);
        $stmt->execute($this->args);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
     }
     catch(\Exception $e){
         echo $e->getMessage();
     }
    }
    
    public function delete(){
        $this->sql = 'delete from '.$this->sqltable;
        if($this->where != NULL)
        {
            $this->sql .= " ".$this->where;
        }
        $pdo = ConnectionFactory::getConnection();
        $stmt = $pdo->prepare($this->sql);
        $stmt->execute($this->args);
    }
    
    public function insert(array $tab){
        $col = "";
        $val = "";
        foreach ($tab as $colonne=>$valeur){
            if(!empty($col)){
                $col.=", ".$colonne;
                $val.=", ?";
            }
            else{
                $col=$colonne;
                $val="?";
            }
            $this->args[]=$valeur;
        }
        $this->sql = 'INSERT INTO '.$this->sqltable.' ('.$col.') VALUES('.$val.');';
        $pdo = ConnectionFactory::getConnection();
        try{
            $stmt = $pdo->prepare($this->sql);
            $stmt->execute($this->args);
            return $pdo->lastInsertId();
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
    }   
}

