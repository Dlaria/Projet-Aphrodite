<?php
/*
Template Name: Boutique
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$all_cat = get_all_cat();
// var_dump($all_cat);
get_header(); ?>

<div class="bandeau">
	<img src="wp-content/plugins/mon-plugin/asset/Sacapuce/BANNER" alt="">
</div>

<div id="categories">
	<h3 id="this-cat">Nouveautés</h3>
	<select name="select-cat" id="select-cat">
		<option value="Nouveautés">Nouveautés</option>
		<option value="Petit prix">Petit prix</option>
	</select>
</div>

<section id="principal">
	<form id="list-filter">
		<div id="taille">
			<h3 class="titre-filtre titre-taille">Taille</h3>
			<div>
				<?php asort($all_cat); foreach($all_cat as $cat_value){
					if ($cat_value->parent == '18'){
						echo '<a class="taille" id="'.$cat_value->term_id.'">'.$cat_value->name.'</a>';
					}
				} ?>
			</div>
		</div>
		<div id="hauteur-de-talon">
			<h3 class="titre-filtre titre-hauteur-de-talon">Hauteur de talon</h3>
			<div>
			<?php asort($all_cat); foreach($all_cat as $cat_value){
					if ($cat_value->parent == '62'){
						echo '<input type="checkbox" name="'.$cat_value->term_id.'" id="'.$cat_value->term_id.'" class="hauteur" value="'.$cat_value->name.'">
							<label for="'.$cat_value->term_id.'">'.$cat_value->name.'</label><br>';
					}
				} ?>
			</div>
		</div>
		<div>
			<h3 class="titre-filtre titre-couleur">Couleur</h3>
			<div id="couleur">
			<?php asort($all_cat); foreach($all_cat as $cat_value){
					if ($cat_value->parent == '48'){
						echo '<a class="couleur" id="'.$cat_value->name.'"></a>';
					}
				} ?>
			</div>
		</div>
		<input class="btn" type="submit" value="Filtre">
	</form>
	
	<div id="list-product">
		
	</div>
	
</section>

<?php get_footer(); ?>