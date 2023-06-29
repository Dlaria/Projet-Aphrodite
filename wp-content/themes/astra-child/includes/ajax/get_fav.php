<?php
    define('DB_HOST','localhost');
    define('DB_USER','root');
    define('DB_PASS','root');
    define('DB_NAME','sacapuce_db');
    try
    {
        $cd_dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS);
    }
    catch (PDOException $e)
    {
        exit("Error: " . $e->getMessage());
    } 
    $product_id = $_GET['productid'];
    $fav = '';
    if(isset($_GET['favori'])){
        $cd_sql = 'UPDATE wp_product SET status_fav = 0 WHERE id = :productid';
        $fav = 'nonfav';
    }else{
        $cd_sql = 'UPDATE wp_product SET status_fav = 1 WHERE id = :productid';
        $fav = 'fav';
    }
    $cd_query = $cd_dbh->prepare($cd_sql);
    $cd_query->bindParam('productid',$product_id,PDO::PARAM_INT);
    $cd_query->execute();

    echo json_encode($fav);