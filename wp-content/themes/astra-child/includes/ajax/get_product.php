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
   
   if (!isset($_GET['Petit_prix'])){
      $cd_sql = 'SELECT * FROM wp_product ORDER BY created_at DESC';

   }else{
      $cd_sql = 'SELECT * FROM wp_product ORDER BY price ASC';
   }

      $cd_query = $cd_dbh->prepare($cd_sql);
      $cd_query->execute();

      $cd_result = $cd_query->fetchAll(PDO::FETCH_ASSOC);

      if (!empty($cd_result)){
         $new_product = array();
         $new_product_cat = array();
         foreach($cd_result as $all_product){
            $product_id = $all_product['id'];
            $cd_sql1 = 'SELECT * FROM wp_product_cat WHERE product_id = :productid';
            $cd_query1 = $cd_dbh->prepare($cd_sql1);
            $cd_query1->bindParam(':productid',$product_id,PDO::PARAM_INT);
            $cd_query1->execute();

            $cd_result1 = $cd_query1->fetchAll(PDO::FETCH_ASSOC);
            foreach($cd_result1 as $all_cat){
               $new_product_cat[] = $all_cat;
            }
            $new_product[] = $all_product;
         }
            $json = json_encode([
               $new_product,$new_product_cat
            ],
            JSON_PRETTY_PRINT);
            print($json);
      }
   
   