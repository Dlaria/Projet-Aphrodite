<?php
/*
Template Name: Acceuil
*/
$get_product = get_product();

if (!empty($_POST)){
    var_dump($_POST);
}

get_header();

if (have_posts()){
    // foreach ($get_product as $get_value){
    //     echo '<input type="hidden" class="product_data" value="'.$get_value->id.'">';
    // }
?>
    <h3 class="title-meddon">Chaussures grande taille pour femmes <br> à Paris depuis 1980</h3>
    <div class="slide-container">
            <div class="custom-slider fade">
                <img class="slide-img" src="wp-content/plugins/mon-plugin/asset/Sacapuce/promotion.png">
            </div>
            <div class="custom-slider fade">
                <img class="slide-img" src="wp-content/plugins/mon-plugin/asset/Sacapuce/promotion2.png">
            </div>
        </div>
        <br>
        <div class="slide-dot">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
        </div>
        <div class="conteneur-produit">
            <a class="affiche-produit" id="nouveautes" onclick="afficheProduit(this.id)">nouveautes</a>
            <a class="affiche-produit" id="petit_prix" onclick="afficheProduit(this.id)">petit prix</a>
            <div id="list-product">
            
            </div>
            <div class="conteneur-fleche">
                <a class="" onclick="afficheProduit('nouveautes')"><img class="arrow-left" src="wp-content/plugins/mon-plugin/asset/Sacapuce/icons/ei_arrow-down.svg"></a>
                <a class="" onclick="afficheProduit('petit_prix')"><img src="wp-content/plugins/mon-plugin/asset/Sacapuce/icons/ei_arrow-down.svg"></a>
            </div>
        </div>
        <form method="post" class="newsletter">
            <p class="newsletter"><strong>10€ Offert*</strong><br>En vous abonnant a notre Newsletter</p>
            <input type="email" name="email" id="email" placeholder="Entrez votre mail">
            <input class="btn-newsletter" type="submit" value="Envoyer">
            <a href="#" class="condition"><small>*voir les condition de <br> l'offre</small></a>
        </form>
        <div class="paiment">
            <img src="wp-content/plugins/mon-plugin/asset/Sacapuce/masterc" alt="master-card">
            <img src="wp-content/plugins/mon-plugin/asset/Sacapuce/visa" alt="visa">
            <img src="wp-content/plugins/mon-plugin/asset/Sacapuce/cb" alt="carte-bleu">
            <img src="wp-content/plugins/mon-plugin/asset/Sacapuce/paypalimg" alt="paypal">
        </div>
<?php
}



get_footer();