<?php
namespace ORM\Model;

class Article extends Model
{
    protected static $table='article';
    protected static $idColumn='id';
    
    public function categorie(){
        return $this->belongs_to('Categorie', 'id_categ');
    }
}

