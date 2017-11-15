<?php
    require_once 'src/Query/Query.php';
    require_once 'src/Utils/ConnectionFactory.php';
    require_once 'src/Model/Model.php';
    require_once 'src/Model/Article.php';
    require_once 'src/Model/Categorie.php';
    use ORM\Query\Query;
    use Utils\ConnectionFactory;
    
    $conf = parse_ini_file('conf/config.ini') ;
    ConnectionFactory::makeConnection($conf);
    /*
    $q = Query::table('article')
    ->select(['id', 'nom', 'descr', 'tarif'])
    ->where('tarif', '>', 30)
    ->get() ;
    
     var_dump($q);
    */
	/*
    $q = Query::table('article')
    ->insert(['nom'=>'chaussure', 'descr'=>'noire', 'tarif'=>30, 'id_categ'=>1])
    ;
    echo $q;
    */
    /*
    $q = Query::table('article')
    ->where('id', '=', 68)
    ->delete() ;
    */
    /*
    $unArticle = new \ORM\Model\Article();
    $unArticle->nom = "Casque";
    $unArticle->descr = "Un casque de velo rouge";
    $unArticle->tarif = 12;
    $unArticle->id_categ = 1;
    $unArticle->insert();
    */
    //echo "$unArticle->nom $unArticle->desc $unArticle->tarif € $unArticle->id_categ";
    /*
    $q = Query::table('article')
    ->select(['id', 'nom', 'descr', 'tarif', 'id_categ'])
    ->where('id', '=', 67)
    ->get() ;
    
    $chaussure = new \ORM\Model\Article($q[0]);
    var_dump($chaussure);
    $chaussure->delete();
    */
    
    //$liste = \ORM\Model\Article::all() ;
    //var_dump($liste);
    
    $l = \ORM\Model\Article::first([['nom','like','%velo%'],['tarif','<=',100]] );
    $categ = $l->categorie;
    var_dump($categ);
    
    
    $m = \ORM\Model\Categorie::first(1) ;
    $list_article = $m->articles ;
    var_dump($list_article);
    