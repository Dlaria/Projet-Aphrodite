<?php
/*
Template Name: Boutique
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$all_cat = get_all_cat();
var_dump($all_cat);
get_header(); ?>

<div class="bandeau">
	<img src="wp-content/plugins/mon-plugin/asset/Sacapuce/BANNER" alt="">
</div>

<div id="categories">
		<h3 id="this-cat">Nouveautés</h3>
		<select name="" id="select-cat">
			<option value="nouveautes">nouveautes</option>
			<option value="petit_prix">petit prix</option>
		</select>
	</div>

<section id="principal">
	<div id="list-filter">
		<div id="taille">
			<h3 class="titre-taille">Taille</h3>
			<div>
				<?php asort($all_cat); foreach($all_cat as $cat_value){
					if ($cat_value->parent == '18'){
						echo '<a class="taille" id="'.$cat_value->term_id.'">'.$cat_value->name.'</a>';
					}
				} ?>
			</div>
		</div>
		<div id="hauteur-de-talon">
			<h3 class="titre-hauteur-de-talon">Hauteur de talon</h3>
			<div>
			<?php asort($all_cat); foreach($all_cat as $cat_value){
					if ($cat_value->parent == '62'){
						echo '<input type="checkbox" name="'.$cat_value->term_id.'" id="'.$cat_value->term_id.'" class="hauteur" value="'.$cat_value->name.'">
							<label for="63">0.5 à 2 cm</label><br>';
					}
				} ?>
				<input type="checkbox" name="63" id="63" class="hauteur" value="0.5 à 2 cm">
				<label for="63">0.5 à 2 cm</label><br>
				<input type="checkbox" name="64" id="64" class="hauteur" value="3 à 4 cm">
				<label for="64">3 à 4 cm</label><br>
				<input type="checkbox" name="65" id="65" class="hauteur" value="5 à 6 cm">
				<label for="65">5 à 6 cm</label><br>
				<input type="checkbox" name="66" id="66" class="hauteur" value="7 à 8 cm">
				<label for="66">7 à 8 cm</label><br>
				<input type="checkbox" name="67" id="67" class="hauteur" value="9 cm et +">
				<label for="67">9 cm et +</label>
			</div>
		</div>
		<div>
			<h3 class="titre-couleur">Couleur</h3>
			<div id="couleur">
				<span class="couleur" id="#B5A8A8"></span>
				<span class="couleur" id="#FFFFFF"></span>
				<span class="couleur" id="#EBCEA2"></span>
				<span class="couleur" id="#63B4FF"></span>
				<span class="couleur" id="#FFEC44"></span>
				<span class="couleur" id="#66893A"></span>
				<span class="couleur" id="#6F3617"></span>
				<span class="couleur" id="#FFB2B2"></span>
				<span class="couleur" id="#AD45A9"></span>
				<span class="couleur" id="#FF4444"></span>
				<span class="couleur" id="#000000"></span>
				<span class="couleur" id="#C81375"></span>
			</div>
		</div>
	</div>
	
	<div id="list-product">
		
	</div>
	
</section>

<?php get_footer(); ?>