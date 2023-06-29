<?php

// Menu principal
function pp_admin_menu() {
    $page_title = 'Projet plugin';
    $menu_title = 'Projet plugin';
    $capability = 'manage_options';
    $menu_slug = 'projetplugin';
    $function = 'pp_admin_page';
    $icon_url = 'dashicons-admin-generic';
    $position = 26;

    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);

    add_submenu_page($menu_slug, 'Add', 'Add', $capability, 'pp-admin-add', 'pp_admin_add_page');
    add_submenu_page($menu_slug, 'Edit', 'Edit', $capability, 'pp-admin-edit', 'pp_admin_edit_page');
}
add_action('admin_menu', 'pp_admin_menu');


function pp_admin_page(){
    global $wpdb;

    $pp_all_product = $wpdb->get_results('SELECT * FROM wp_pp_product');
    // var_dump($pp_all_product);
    
    


    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Projet plugin</h1>';
    echo '<a href="' . admin_url('admin.php?page=pp-admin-add') . '" class="page-title-action">Add New</a>';
    echo '<hr class="wp-header-end">';

    echo '<table class="wp-list-table widefat fixed striped">';
    echo    '<thead>
                <tr>
                    <th>Product image</th>
                    <th>Name</th>
                    <th>Categorie</th>
                    <th>Price</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th> 
                </tr>
            </thead>';
    echo '<tbody>';
    foreach($pp_all_product as $pp_product){
        $pp_product_cat = $wpdb->get_var('SELECT * FROM wp_pp_category WHERE category_id='.$pp_product->category_id,1);
        echo '<tr><td><img style="width:50px; height:50px;" src="'.$pp_product->image_product.'"></td>
            <td>'.$pp_product->name.'</td>
            <td>'.$pp_product_cat.'</td>
            <td>'.$pp_product->price.'</td>
            <td>'.$pp_product->create_at.'</td>
            <td>'.$pp_product->update_at.'</td>';
            echo '<td>
                    <a href="' . admin_url('admin.php?page=pp-admin-edit&categoryid='.$pp_product->category_id) . '&productid='.$pp_product->product_id.'">Edit</a>
                </td></tr>';
    }
    echo '</tbody>';
}


function pp_admin_add_page(){
    if (!isset($_GET['select-cat'])){
        global $wpdb;

        $pp_all_cat = $wpdb->get_results('SELECT * FROM wp_pp_category');

        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">Choix de la categorie</h1>';
        echo '<hr class="wp-header-end">';

        echo '<form method="post"><select name="select-cat">';
            foreach($pp_all_cat as $pp_cat_value){
                echo '<option value="'.$pp_cat_value->category_id.'">'.$pp_cat_value->name.'</option>';
            }
        echo '</select><br>';
        echo '<input type="submit" class="button" name="submit" value="Submit">';
        echo '</form>';

        if (isset($_POST['submit'])){
            echo '<script>window.location.href="' . admin_url('admin.php?page=pp-admin-add&select-cat='.$_POST['select-cat']) . '";</script>';
        }

    }else{
        $cat_id = $_GET['select-cat'];

        // var_dump($_GET);
        global $wpdb;
        $pp_cat_order = $wpdb->get_results('SELECT * FROM wp_pp_order INNER JOIN wp_pp_category ON wp_pp_order.category_id = wp_pp_category.category_id INNER JOIN wp_pp_elem_form ON wp_pp_order.elem_id = wp_pp_elem_form.elem_id WHERE wp_pp_order.category_id='.$cat_id);

        // var_dump($pp_cat_order);
        // var_dump($_POST);

        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">Projet plugin</h1>';
        echo '<hr class="wp-header-end">';
        echo '<a style="position:relative; bottom:30px; left:95%;" class="button button-link-delete" href="'.admin_url('admin.php?page=pp-admin-add').'">Retour</a>';
        
        echo '<form method="post">';
        echo '<input type="hidden" id="src" name="src" required>';
        echo '<table class="form-table">';
        do_action('media_buttons');
        do_action('wp_enqueue_media');
            foreach ($pp_cat_order as $pp_value){
                echo $pp_value->champ;
            }
        echo '</table>';
        echo '<p class="submit"><input type="submit" name="add-new" id="add-new" class="button button-primary" value="Add New"></p>';
        echo '</form>';

        if (isset($_POST['add-new'])){
            // var_dump($_POST);
            if ($_POST['src'] == ''){
                echo '<script>alert("Il manque une image de produit !")</script>';
                return false;
            }else{
                $image_product = $_POST['src'];
            }
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_GET['select-cat'];

            $wpdb->insert('wp_pp_product', compact('image_product', 'name', 'description', 'price', 'category_id'));
            $product_id = $wpdb->insert_id;

            foreach ($_POST as $pp_post_key => $pp_post_value){
                if (rest_is_integer($pp_post_key)){
                    // var_dump($pp_post_key);
                    // var_dump($pp_post_value);

                    $term_id = $pp_post_key;
                    $value = $pp_post_value;
                    $status = 1;

                    $wpdb->insert('wp_pp_product_cat', compact('term_id', 'value', 'product_id', 'status'));
                }
            }

            echo '<script>window.location.href="' . admin_url('admin.php?page=projetplugin') . '";</script>';
        }
    }
}

function pp_admin_edit_page(){
    if (!isset($_GET['categoryid'])){
        echo '<script>window.location.href="' . admin_url('admin.php?page=projetplugin') . '";</script>';
    }else{
        $cat_id = $_GET['categoryid'];

        global $wpdb;
        $pp_cat_order = $wpdb->get_results('SELECT * FROM wp_pp_order INNER JOIN wp_pp_category ON wp_pp_order.category_id = wp_pp_category.category_id INNER JOIN wp_pp_elem_form ON wp_pp_order.elem_id = wp_pp_elem_form.elem_id WHERE wp_pp_order.category_id='.$cat_id);

        $product_id = $_GET['productid'];
        $pp_product_cat = $wpdb->get_results('SELECT * FROM wp_pp_product_cat WHERE product_id ='.$product_id);

        $all_row = $wpdb->get_results('SELECT * FROM wp_pp_product WHERE product_id ='.$product_id);
        $row = $all_row[0];
       if (!empty($row)){
           $src = $row->image_product;
           $name = $row->name;
           $description = $row->description;
           $price = $row->price;
       }

        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">Projet plugin</h1>';
        echo '<hr class="wp-header-end">';
        
        echo '<form method="post">';
        echo '<input type="hidden" id="src" name="src" value="'.$src.'" required>';
        echo '<input type="hidden" id="hidden-name" name="hidden-name" value="'.$name.'" required>';
        echo '<input type="hidden" id="hidden-description" name="hidden-description" value="'.$description.'" required>';
        echo '<input type="hidden" id="hidden-price" name="hidden-price" value="'.$price.'" required>';
        foreach($pp_product_cat as $pp_cat){
            // var_dump($pp_cat);
            if ($pp_cat->status == 1){
                echo '<input type="hidden" class="product-cat" value="'.$pp_cat->value.'">';
            }
        }
        echo '<table class="form-table">';
        do_action('media_buttons');
        do_action('wp_enqueue_media');
        do_action('wp_enqueue_script');
            foreach ($pp_cat_order as $pp_value){
                echo $pp_value->champ;
            }
        echo '</table>';
        echo '<p class="submit"><input type="submit" name="edit" id="edit" class="button button-primary" value="Edit"></p>';
        echo '</form>';

        if (isset($_POST['edit'])){
            // var_dump($_POST);

            $image_product = $_POST['src'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];

            // $wpdb->update('wp_pp_product', compact('image_product', 'name', 'description', 'price'), array('product_id' => $product_id));

            foreach ($_POST as $pp_post_key => $pp_post_value){
                if (rest_is_integer($pp_post_key)){
                    $pp_query = $wpdb->get_var('SELECT * FROM wp_pp_product_cat WHERE product_id ='.$product_id.' AND term_id ='.$pp_post_key,2);
                    $term_id = $pp_post_key;
                    $value = $pp_post_value;
                    $status = 1;
                    // var_dump($pp_query);
                    // var_dump($pp_post_key);
                    // var_dump($pp_post_value);

                    if ($pp_query != null && $pp_query == $pp_post_value){
                        $wpdb->update('wp_pp_product_cat', compact('status'), array('product_id' => $product_id, 'value' => $pp_query));
                    }else{
                        $wpdb->insert('wp_pp_product_cat', compact('term_id', 'value', 'product_id', 'status'));
                    }
                }
            }
            foreach ($pp_product_cat as $pp_cat){
                if ($_POST[$pp_cat->term_id] !== $pp_cat->value){
                    $status = 0;
                    $wpdb->update('wp_pp_product_cat', compact('status'), array('product_id' => $product_id, 'value' => $pp_cat->value));
                }
            }
            echo '<script>window.location.href="' . admin_url('admin.php?page=projetplugin') . '";</script>';
        }
    }
}

function pp_add_my_media_button() {
    if(isset($_GET['page'])){
        switch ($_GET['page']){
            case 'pp-admin-add':
                $ancre_text = 'Add my media';
                break;
            case 'pp-admin-edit':
                $ancre_text = 'Edit my media';
                break;
            default:
                $ancre_text = 'My media';
                break;
        }
    }
    echo '<a href="#" id="pp-insert-media" class="button">'.$ancre_text.'</a>';
}
add_action('media_buttons', 'pp_add_my_media_button');

function pp_include_media_button_js_file() {
    wp_enqueue_script('pp_media_button', '../wp-content/plugins/projet-plugin/includes/js/pp-btn-media.js', array('jquery'), false, true);
}
add_action('wp_enqueue_media', 'pp_include_media_button_js_file');

function pp_check_checked(){
    wp_enqueue_script('pp_check', '../wp-content/plugins/projet-plugin/includes/js/pp-check.js');

}
add_action('wp_enqueue_script', 'pp_check_checked');