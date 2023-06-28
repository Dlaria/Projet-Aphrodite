<?php

// Menu principal
function custom_data_menu() {
    $page_title = 'Manage Product';
    $menu_title = 'Manage Product';
    $capability = 'manage_options';
    $menu_slug = 'manageproduct';
    $function = 'custom_data_page';
    $icon_url = 'dashicons-admin-generic';
    $position = 25;

    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);

    // Submenu pages
    add_submenu_page($menu_slug, 'Add', 'Add', $capability, 'custom-data-add', 'custom_data_add_product');
    add_submenu_page($menu_slug, 'Edit', 'Edit', $capability, 'custom-data-edit', 'custom_data_edit_page');
}
add_action('admin_menu', 'custom_data_menu');


// Page du menu principal
function custom_data_page() {
    global $wpdb;

    // var_dump(get_post(100));

    $cd_results = $wpdb->get_results('SELECT * FROM wp_product WHERE status_actif = 1');
    
    $cd_cat_parent = $wpdb->get_results('SELECT * FROM wp_term_taxonomy INNER JOIN wp_terms ON wp_term_taxonomy.term_id = wp_terms.term_id WHERE taxonomy = "category" AND parent = 0');

    // On défini la date et l'heure de Paris
    date_default_timezone_set('Europe/Paris');

    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Manage product</h1>';
    echo '<a href="' . admin_url('admin.php?page=custom-data-add') . '" class="page-title-action">Add New</a>';
    echo '<hr class="wp-header-end">';

    echo '<table class="wp-list-table widefat fixed striped">';
    echo    '<thead>
                <tr>
                    <th></th>
                    <th>Product image</th>
                    <th>Name</th>';
                    foreach ($cd_cat_parent as $cd_parent_value){
                        if ($cd_parent_value->slug != "non-classe"){
                            echo '<th>'.$cd_parent_value->name.'</th>';
                        }
                    }
                echo '<th>Price</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>';
    echo '<tbody>';
        foreach($cd_results as $cd_value){
            $cd_category = $wpdb->get_results('SELECT * FROM wp_product_cat INNER JOIN wp_term_taxonomy ON wp_product_cat.term_id = wp_term_taxonomy.term_id INNER JOIN wp_terms ON wp_product_cat.term_id = wp_terms.term_id WHERE product_id ='. $cd_value->id);
            // var_dump($cd_category);

            $createDate = strtotime($cd_value->created_at);
            $lastDate = strtotime("+1 Months", $createDate);
            // var_dump(date("Y-m-d H:i:s",$notNew));
            if ($createDate <= $lastDate){
                $wpdb->update("wp_product", array("status_new" => "New"), array("id" => $cd_value->id));
                $text_new = '<img style="width:70px;" src="../wp-content/plugins/mon-plugin/asset/Sacapuce/NEW-etiquette">';
            }else{
                $wpdb->update("wp_product", array("status_new" => "Ancien"), array("id" => $cd_value->id));
                $text_new = "Ancien produit";
            }

            if ($cd_value->status_fav == 1){
                $tstatus_fav = 'Favori';
            }else{
                $tstatus_fav = 'Non favori';
            }
            echo '<tr>
                <td>'.$text_new.'</td>
                <td><img style="width:50px; height:50px;" src="'.$cd_value->image_product.'"></td>
                <td>'.$cd_value->name.'</td><td>';
                
                    foreach ($cd_category as $cat){
                        if ($cat->status != 0 && $cat->parent == "18"){
                            echo $cat->value.'<br>';
                        }
                    }
            echo '</td><td>';
                foreach ($cd_category as $cat){
                    if ($cat->status != 0 && $cat->parent == "62"){
                        echo $cat->value.'<br>';
                    }
                }
            echo '</td><td>';
                foreach ($cd_category as $cat){
                    if ($cat->status != 0 && $cat->parent == "48"){
                        echo $cat->value.'<br>';
                    }
                }
            echo '</td><td>'.$cd_value->price.'</td>
                <td>'.$cd_value->created_at.'</td>
                <td>'.$cd_value->updated_at.'</td>
                <td>
                    <a href="' . admin_url('admin.php?page=custom-data-edit&productid='.$cd_value->id) . '">Edit</a>|
                    <a href="' . admin_url('admin.php?page=manageproduct&productid='.$cd_value->id.'&statusfav='.$cd_value->status_fav).'">'.$tstatus_fav.'</a>
                    <a href="' . admin_url('admin.php?page=manageproduct&productid='.$cd_value->id.'&statusactif='.$cd_value->status_actif).'" class="bouton-supprimer button">Supprimer</a>
                </td>
                
                </tr>';
        }
    echo '</tbody>';

    if (isset($_GET['statusfav'])){
        update_fav_status();
        echo "<script>document.location.href='".htmlspecialchars($_SERVER['SCRIPT_NAME'])."?page=manageproduct';</script>";
    }
    if (isset($_GET['statusactif'])){
        update_actif_status();
        echo "<script>document.location.href='".htmlspecialchars($_SERVER['SCRIPT_NAME'])."?page=manageproduct';</script>";
    }
}

// Page d'ajout des produits
function custom_data_add_product() {
    global $wpdb;
    $table_name =  $wpdb->prefix . 'product';

    $cd_category = $wpdb->get_results('SELECT * FROM wp_term_taxonomy INNER JOIN wp_terms ON wp_term_taxonomy.term_id = wp_terms.term_id WHERE taxonomy = "category"');
    // var_dump($cd_category);
    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Add product</h1>';
    echo '<hr class="wp-header-end">';
    do_action('media_buttons');
    do_action('wp_enqueue_media');


    echo '<form method="post">';
        echo '<input type="hidden" id="src" name="src">';
        echo '<table class="form-table">';
            echo '<tr>
                    <th scope="row"><label for="image_product">Product image</label></th>
                    <td><img style="width:200px;" src="" alt="" id="imageProduct"><td>
                </tr>';
            echo '<tr>';
                echo '<th scope="row"><label for="name">Product name</label></th>';
                echo '<td><input name="name" type="text" id="name" class="regular-text" required></td>';
            echo '</tr>';
            echo '<tr>';
                echo '<th scope="row"><label for="description">Description</label></th>';
                echo '<td><textarea name="description" id="description" class="large-text" rows="10" required></textarea></td>';
            echo '</tr>';
            echo '<tr>';
                echo '<th scope="row"><label for="price">Product price</label></th>';
                echo '<td><input name="price" type="text" id="price" class="" required>(€)</td>';
            echo '</tr>';
            asort($cd_category);
            foreach($cd_category as $cd_value){
                echo '<tr>';
                if ($cd_value->parent == '0' && $cd_value->name != "Non classé"){
                    $cat_parent_name = $cd_value->name;
                    $cat_parent_id = $cd_value->term_id;
                        echo '<th>'.$cat_parent_name.'</th>';
            }
                // var_dump($cd_category);
                if ($cd_value->parent != '0'){
                     echo '<td><label for="'.$cd_value->name.'">'.$cd_value->name.'</label>&nbsp;<input type="checkbox" name="'.$cd_value->term_id.'" value="'.$cd_value->name.'"></td>';
                }
                echo'</tr>';
            }
        echo '</table>';
        echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Add New"></p>';
    echo '</form>';

    if (isset($_POST['submit'])){
        // var_dump($_POST);
        $image_product = $_POST['src'];
        $name = sanitize_text_field($_POST['name']);
        $description = sanitize_textarea_field($_POST['description']);
        $price = sanitize_text_field($_POST['price']);
        $status_actif = 1;

        // $wpdb->insert($table_name, compact('image_product', 'name', 'description', 'price', "status_actif"));
        $product_id = $wpdb->insert_id;
        foreach($cd_category as $cd_value){
            if ($cd_value->parent != '0'){
                if (isset($_POST[$cd_value->term_id])){
                    $term_id = $cd_value->term_id;
                    $value = $_POST[$cd_value->term_id];
                    $status = 1;
                }
            }elseif ($cd_value->name != "Non classé"){
                // Trouver le moyen de mettre en base le nom de la categorie parent 
                    // var_dump($cd_value);
                    // $cat_parent_name = ?
                }
                // $wpdb->insert('wp_product_cat', compact('term_id', 'value', 'product_id', 'status'));
        }
        // echo '<div class="notice notice-success is-dismissible"><p>Custom data added successfully!</p></div>';
        // echo '<script>window.location.href="' . admin_url('admin.php?page=manageproduct') . '";</script>';
    }
}

// Page d'édition des produits
function custom_data_edit_page() {
    global $wpdb;
    $table_name =  $wpdb->prefix . 'product';

    // var_dump($_GET);
     if (isset($_GET['productid'])){
        $productId = intval($_GET['productid']);
     }else{
        echo '<script>window.location.href="' . admin_url('admin.php?page=manageproduct') . '";</script>';
     }

     $cd_category = $wpdb->get_results('SELECT * FROM wp_term_taxonomy INNER JOIN wp_terms ON wp_term_taxonomy.term_id = wp_terms.term_id WHERE taxonomy = "category"');

     $all_row = $wpdb->get_results('SELECT * FROM '.$table_name.' WHERE id ='.$productId);
     $row = $all_row[0];
    if (!empty($row)){
        $src = $row->image_product;
        $name = $row->name;
        $description = $row->description;
        $price = $row->price;
    }

    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Edit product</h1>';
    echo '<hr class="wp-header-end">';

    do_action('media_buttons');
    do_action('wp_enqueue_media');

    echo '<form method="post">';
    echo '<input type="hidden" id="src" name="src" value="'.$src.'">';
        echo '<table class="form-table">';
            echo '<tr>
                    <th scope="row"><label for="image_product">Product image</label></th>
                    <td><img style="width:200px;" src="'.$src.'" alt="" id="imageProduct"><td>
                </tr>';
            echo '<tr>';
                echo '<th scope="row"><label for="name">Product name</label></th>';
                echo '<td><input name="name" type="text" id="name" class="regular-text" value="'.$name.'" required></td>';
            echo '</tr>';
            echo '<tr>';
                echo '<th scope="row"><label for="description">Description</label></th>';
                echo '<td><textarea name="description" id="description" class="large-text" rows="10" required>'.$description.'</textarea></td>';
            echo '</tr>';
            echo '<tr>';
                echo '<th scope="row"><label for="price">Product price</label></th>';
                echo '<td><input name="price" type="text" id="price" class="" value="'.$price.'" required>(€)</td>';
            echo '</tr>';
            
            asort($cd_category);
            foreach($cd_category as $cd_value){
                $cd_get_status = $wpdb->get_var('SELECT * FROM wp_product_cat WHERE product_id ='.$productId.' AND term_id ='.$cd_value->term_id,4);
                // var_dump($cd_get_status);
                if ($cd_get_status != 0 && $cd_get_status != null){
                    $checked = 'checked';
                }else{
                    $checked = '';
                }
                echo '<tr>';
                if ($cd_value->parent == '0' && $cd_value->name != "Non classé"){
                    $cat_parent_name = $cd_value->name;
                    $cat_parent_id = $cd_value->term_id;
                        echo '<th>'.$cat_parent_name.'</th>';
            }
                
                if ($cd_value->parent != '0'){
                     echo '<td><label for="'.$cd_value->name.'">'.$cd_value->name.'</label>&nbsp;<input type="checkbox" name="'.$cd_value->term_id.'" value="'.$cd_value->name.'" '.$checked.'></td>';
                }
                echo'</tr>';
            }

        echo '</table>';
        echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Edit"></p>';
    echo '</form>';

    if (isset($_POST['submit'])){
        // var_dump($_POST);
        $image_product = $_POST['src'];
        $name = sanitize_text_field($_POST['name']);
        $description = sanitize_textarea_field($_POST['description']);
        $price = sanitize_text_field($_POST['price']);
        
        $wpdb->update($table_name, compact('image_product','name', 'description', 'price'), array('id' => $productId));

       
        foreach($cd_category as $cd_value){

            if ($cd_value->parent != '0'){
                if (isset($_POST[$cd_value->term_id])){
                    $term_id = $cd_value->term_id;
                    $value = $_POST[$cd_value->term_id];
                    $status = 1;

                    $cd_query = $wpdb->get_var('SELECT * FROM wp_product_cat WHERE product_id ='.$productId.' AND term_id ='.$term_id,2);
                    // var_dump($cd_query);

                    if ($cd_query != null){
                        $wpdb->update('wp_product_cat', compact('status'), array('product_id' => $productId, 'value' => $cd_query));
                    }else{
                        $product_id = $productId;
                        $wpdb->insert('wp_product_cat', compact('term_id', 'value', 'product_id', 'status'));
                    }
                }else{
                    $cd_query = $wpdb->get_var('SELECT * FROM wp_product_cat WHERE product_id ='.$productId.' AND term_id ='.$cd_value->term_id,2);
                    // var_dump($cd_query);

                    if ($cd_query != null){
                        $status = 0;
                        $wpdb->update('wp_product_cat', compact('status'), array('product_id' => $productId, 'value' => $cd_query));
                    }
                } 
            }
        echo '<div class="notice notice-success is-dismissible"><p>Custom data added successfully!</p></div>';
        echo '<script>window.location.href="' . admin_url('admin.php?page=manageproduct') . '";</script>';
    
    }
}
}

// Mise a jour des favoris en base
function update_fav_status(): void
{
    global $wpdb;
    $table_name =  $wpdb->prefix . 'product';
    $cd_postId = $_GET['productid'];
    $cd_statusFav = $_GET['statusfav'];
    
    switch ($cd_statusFav){
        case 0:
            $wpdb->update($table_name, array('status_fav' => '1'), array('id' => $cd_postId));
            break;
        case 1:
            $wpdb->update($table_name, array('status_fav' => '0'), array('id' => $cd_postId));
            break;
    }
}

function update_actif_status(): void
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'product';
    $cd_postId = $_GET['productid'];
    $cd_statusActif = $_GET['statusactif'];

    switch ($cd_statusActif){
        case 0:
            $wpdb->update($table_name, array('status_actif' => '1'), array('id' => $cd_postId));
            break;
        case 1:
            $wpdb->update($table_name, array('status_actif' => '0'), array('id' => $cd_postId));
            break;
    }
}

function add_my_media_button() {
    if(isset($_GET['page'])){
        switch ($_GET['page']){
            case 'custom-data-add':
                $ancre_text = 'Add my media';
                break;
            case 'custom-data-edit':
                $ancre_text = 'Edit my media';
                break;
            default:
                $ancre_text = 'My media';
                break;
        }
    }
    echo '<a href="#" id="insert-my-media" class="button">'.$ancre_text.'</a>';
}
add_action('media_buttons', 'add_my_media_button');

function include_media_button_js_file() {
    wp_enqueue_script('media_button', '../wp-content/plugins/mon-plugin/includes/custom-data.js', array('jquery'), false, true);
}
add_action('wp_enqueue_media', 'include_media_button_js_file');
