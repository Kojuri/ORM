<?php
namespace ORM\Model;

abstract class Model
{
    protected $_v = [];
    protected static $table;
    protected static $idColumn = 'id';
    public function __construct(array $t = null) {
        if (!is_null($t)) $this->_v = $t;
    }
    
    public function __get($attr_name) {
        if (array_key_exists($attr_name, $this->_v)){
            return $this->_v[$attr_name];
        }
        if(method_exists($this, $attr_name)){
            return $this->$attr_name();
        }
    }
    
    public function __set($attr_name, $value) {
        $this->_v[$attr_name]=$value;
    }
    
    public function delete() {
        if (array_key_exists(static::$idColumn, $this->_v)){
            
            return \ORM\Query\Query::table(static::$table)
            ->where( static::$idColumn, '=',
                $this->_v[static::$idColumn] )
                ->delete();
        }
    }
    
    public function insert() {
        $this->_v[static::$idColumn] = \ORM\Query\Query::table(static::$table)
        ->insert($this->_v);
    }
    
    public static function all() : array {
        $all = \ORM\Query\Query::table(static::$table)->get();
        $return=[];
        foreach( $all as $m) {
            $return[] = new static($m);
        }
        return $return;
    }
    
    public static function find($search, $cols=NULL) : array {
        $select = \ORM\Query\Query::table(static::$table);
        if(!is_null($cols)){
            $select->select($cols);
        }
        if(!is_array($search)){
            $select->where(static::$idColumn, '=', $search);
        }
        else{
            if(is_array($search[0])){
                foreach ($search as $cond){
                    $select->where($cond[0], $cond[1], $cond[2]);
                }
            }
            else{
                $select->where($search[0], $search[1], $search[2]);
            }
        }
        $select = $select->get();
        $return=[];
        foreach($select as $m) {
            $return[] = new static($m);
        }
        return $return;
    }
    
    public static function first($search, $cols=NULL) {
        $tb = self::find($search, $cols);
        return $tb[0];
    }
    
    public function belongs_to($model_linked, $fk_name){
        $namespace = "\ORM\Model\\$model_linked";
        $val = $this->$fk_name;
        return $namespace::first($val);
    }
    
    public function has_many($model_linked, $fk_name){
        $namespace = "\ORM\Model\\$model_linked";
        $val = $this->_v[static::$idColumn];
        return $namespace::find([$fk_name, '=', $val]);
    }
}

