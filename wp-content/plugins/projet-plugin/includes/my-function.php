<?php

// Création du menu admin 
function pp_admin_menu() {
    $page_title = 'Projet plugin';
    $menu_title = 'Projet plugin';
    $capability = 'manage_options';
    $menu_slug = 'projetplugin';
    $function = 'pp_admin_page';
    $icon_url = 'dashicons-admin-generic';
    $position = 26;

    // Menu pricipal
    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);

    // Sous-menu d'ajout des produits
    add_submenu_page($menu_slug, 'Add', 'Add', $capability, 'pp-admin-add', 'pp_admin_add_page');

    // Sous-menu d'édition des produits
    add_submenu_page($menu_slug, 'Edit', 'Edit', $capability, 'pp-admin-edit', 'pp_admin_edit_page');
}
add_action('admin_menu', 'pp_admin_menu');

// Création de la page principal du menu admin
function pp_admin_page(){
    // Variable global de connexion a la base de données de wordpress
    global $wpdb;

    // Récupération de tout les articles
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
    // Boucle pour décomposer tout les produits
    foreach($pp_all_product as $pp_product){
        // Récuperation des noms des catégories en fonction de celle du produit
        $pp_product_cat = $wpdb->get_var('SELECT * FROM wp_pp_category WHERE category_id='.$pp_product->category_id,1);
        // Affiche des produits
        echo '<tr><td><img style="width:50px; height:50px;" src="'.$pp_product->image_product.'"></td>
            <td>'.$pp_product->name.'</td>
            <td>'.$pp_product_cat.'</td>
            <td>'.$pp_product->price.'</td>
            <td>'.$pp_product->create_at.'</td>
            <td>'.$pp_product->update_at.'</td>';
            // Bouton d'édition
            echo '<td>
                    <a href="' . admin_url('admin.php?page=pp-admin-edit&categoryid='.$pp_product->category_id) . '&productid='.$pp_product->product_id.'">Edit</a>
                </td></tr>';
    }
    echo '</tbody>';
}

// Création de la page du sous-menu d'ajout des produits
function pp_admin_add_page(){
    // Si aucune catégories n'est séléctionné 
    if (!isset($_GET['select-cat'])){
        global $wpdb;

        // Récupération de toutes les catégories
        $pp_all_cat = $wpdb->get_results('SELECT * FROM wp_pp_category');

        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">Choix de la categorie</h1>';
        echo '<hr class="wp-header-end">';

        // Formulaire de séléction de la catégories
        echo '<form method="post"><select name="select-cat">';
            foreach($pp_all_cat as $pp_cat_value){
                echo '<option value="'.$pp_cat_value->category_id.'">'.$pp_cat_value->name.'</option>';
            }
        echo '</select><br>';
        echo '<input type="submit" class="button" name="submit" value="Submit">';
        echo '</form>';

        // Si le formulaire est soumis
        if (isset($_POST['submit'])){
            // Redirection vers la page d'ajout en js
            echo '<script>window.location.href="' . admin_url('admin.php?page=pp-admin-add&select-cat='.$_POST['select-cat']) . '";</script>';
        }

    }else{
        // Stockage de l'id de la catégories séléctionné
        $cat_id = $_GET['select-cat'];

        // var_dump($_GET);
        global $wpdb;

        // Récupération des éléments de formulaire en fonction de l'id de la catégorie
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
        // Crochet d'action pour créé mon bouton média
        do_action('media_buttons');

        // Crochet d'action pour ajouter un bouton média
        do_action('wp_enqueue_media');

            // Boucle pour sortir les éléments du formulaire
            foreach ($pp_cat_order as $pp_value){
                echo $pp_value->champ;
            }
        echo '</table>';
        echo '<p class="submit"><input type="submit" name="add-new" id="add-new" class="button button-primary" value="Add New"></p>';
        echo '</form>';

        // Si le formulaire est soumis
        if (isset($_POST['add-new'])){
            // var_dump($_POST);

            // Si aucune image n'a été séléctionné
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

            // Insertion des élements contenu dans le tableau de post
            $wpdb->insert('wp_pp_product', compact('image_product', 'name', 'description', 'price', 'category_id'));

            // Récupération du dernier id inserré
            $product_id = $wpdb->insert_id;

            // Boucle de déposition du tableau de post
            foreach ($_POST as $pp_post_key => $pp_post_value){
                // Si la clé du post est un chiffre
                if (rest_is_integer($pp_post_key)){
                    // var_dump($pp_post_key);
                    // var_dump($pp_post_value);

                    $term_id = $pp_post_key;
                    $value = $pp_post_value;
                    $status = 1;

                    // Insertion des catégories séléctionné
                    $wpdb->insert('wp_pp_product_cat', compact('term_id', 'value', 'product_id', 'status'));
                }
            }

            // Redirection de l'utilisateur vers la page principal
            echo '<script>window.location.href="' . admin_url('admin.php?page=projetplugin') . '";</script>';
        }
    }
}

// Création de la page du sous-menu d'édition des produits
function pp_admin_edit_page(){
    // Si l'utilisateur essaye de venir sur la page sans séléctionné de produit a éditer
    if (!isset($_GET['categoryid'])){
        echo '<script>window.location.href="' . admin_url('admin.php?page=projetplugin') . '";</script>';
    }else{
        // Stockage de l'id de la catégories séléctionné
        $cat_id = $_GET['categoryid'];

        global $wpdb;

        // Récupération des éléments de formulaire en fonction de l'id de la catégorie
        $pp_cat_order = $wpdb->get_results('SELECT * FROM wp_pp_order INNER JOIN wp_pp_category ON wp_pp_order.category_id = wp_pp_category.category_id INNER JOIN wp_pp_elem_form ON wp_pp_order.elem_id = wp_pp_elem_form.elem_id WHERE wp_pp_order.category_id='.$cat_id);

        // Stockage de l'id du produits séléctionné
        $product_id = $_GET['productid'];

        // Récupération des catégories du produit en fonction de l'id du produit
        $pp_product_cat = $wpdb->get_results('SELECT * FROM wp_pp_product_cat WHERE product_id ='.$product_id);

        // Récupération du produit en fonction de son id
        $all_row = $wpdb->get_results('SELECT * FROM wp_pp_product WHERE product_id ='.$product_id);
        $row = $all_row[0];
       if (!empty($row)){
            // Stockage des infos du produit dans des variables
            $src = $row->image_product;
            $name = $row->name;
            $description = $row->description;
            $price = $row->price;
       }

        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">Projet plugin</h1>';
        echo '<hr class="wp-header-end">';
        
        echo '<form method="post">';

        // Stockage des infos du produit dans des inputs hidden
        echo '<input type="hidden" id="src" name="src" value="'.$src.'" required>';
        echo '<input type="hidden" id="hidden-name" name="hidden-name" value="'.$name.'" required>';
        echo '<input type="hidden" id="hidden-description" name="hidden-description" value="'.$description.'" required>';
        echo '<input type="hidden" id="hidden-price" name="hidden-price" value="'.$price.'" required>';
        // Stockage des infos des catégories active du produit dans des inputs hidden
        foreach($pp_product_cat as $pp_cat){
            // var_dump($pp_cat);
            if ($pp_cat->status == 1){
                echo '<input type="hidden" class="product-cat" value="'.$pp_cat->value.'">';
            }
        }
        echo '<table class="form-table">';
            // Crochet d'action pour créé mon bouton média
            do_action('media_buttons');

            // Crochet d'action pour ajouter un bouton média
            do_action('wp_enqueue_media');

            // Crochet d'action pour ajouter mon ficher js
            do_action('wp_enqueue_script');

            // Boucle pour sortir les éléments du formulaire
            foreach ($pp_cat_order as $pp_value){
                echo $pp_value->champ;
            }
        echo '</table>';
        echo '<p class="submit"><input type="submit" name="edit" id="edit" class="button button-primary" value="Edit"></p>';
        echo '</form>';

        // Si le formulaire est soumis
        if (isset($_POST['edit'])){
            // var_dump($_POST);

            $image_product = $_POST['src'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];

            // Mise à jour des infos avec le tableau post
            $wpdb->update('wp_pp_product', compact('image_product', 'name', 'description', 'price'), array('product_id' => $product_id));

            // Décomposition du tableau de post
            foreach ($_POST as $pp_post_key => $pp_post_value){

                // Si la clé du tableau post est un chiffre
                if (rest_is_integer($pp_post_key)){
                    // Récupération de la 3ème colonne des catégories du produit en fonction de l'id du produit et de l'id de la catégorie
                    $pp_query = $wpdb->get_var('SELECT * FROM wp_pp_product_cat WHERE product_id ='.$product_id.' AND term_id ='.$pp_post_key,2);
                    $term_id = $pp_post_key;
                    $value = $pp_post_value;
                    $status = 1;
                    // var_dump($pp_query);
                    // var_dump($pp_post_key);
                    // var_dump($pp_post_value);

                    // Si le résultat n'est pas null et si le résutat est égal a une valeur du tableau de post
                    if ($pp_query != null && $pp_query == $pp_post_value){
                        // Mise a jour du status en fonction de l'id du produit et de la valeur du résultat
                        $wpdb->update('wp_pp_product_cat', compact('status'), array('product_id' => $product_id, 'value' => $pp_query));
                    }else{
                        // Sinon insertion de la nouvelle catégories
                        $wpdb->insert('wp_pp_product_cat', compact('term_id', 'value', 'product_id', 'status'));
                    }
                }
            }
            // Boucle de décomposition des catégories du produit
            foreach ($pp_product_cat as $pp_cat){
                // Si la valeur en tableau de post n'est pas égal a une valeur dans les catégories
                if ($_POST[$pp_cat->term_id] !== $pp_cat->value){
                    $status = 0;
                    // Mise à jour du status de la catégories
                    $wpdb->update('wp_pp_product_cat', compact('status'), array('product_id' => $product_id, 'value' => $pp_cat->value));
                }
            }
            // Redirection vers la page principal
            echo '<script>window.location.href="' . admin_url('admin.php?page=projetplugin') . '";</script>';
        }
    }
}

// Fonction de création du bouton média
function pp_add_my_media_button() {
    // Changement du nom du bouton en fonction de la page
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
    // Le bouton média
    echo '<a href="#" id="pp-insert-media" class="button">'.$ancre_text.'</a>';
}
// Ajout du crochet de bouton média
add_action('media_buttons', 'pp_add_my_media_button');

// Fonction de mise en attente du js
function pp_include_media_button_js_file() {
    wp_enqueue_script('pp_media_button', '../wp-content/plugins/projet-plugin/includes/js/pp-btn-media.js', array('jquery'), false, true);
}
// Ajout du crochet de mise en attente du média
add_action('wp_enqueue_media', 'pp_include_media_button_js_file');

// Fonction de mise en attente du js
function pp_check_checked(){
    wp_enqueue_script('pp_check', '../wp-content/plugins/projet-plugin/includes/js/pp-check.js');

}
// Ajout du crochet de mise en attente du script
add_action('wp_enqueue_script', 'pp_check_checked');