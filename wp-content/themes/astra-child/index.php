<?php
/*
Template Name: Acceuil
*/
get_header();

if (have_posts()){
    echo '<h3 class="title-meddon">Chaussures grande taille pour femmes <br> à Paris depuis 1980</h3>';
    echo '<div class="slide-container">
            <div class="custom-slider fade">
                <img class="slide-img" src="wp-content/plugins/mon-plugin/asset/Sacapuce/promotion.png">
            </div>
            <div class="custom-slider fade">
                <img class="slide-img" src="wp-content/plugins/mon-plugin/asset/Sacapuce/promotion2.png">
            </div>
            <a class="prev" onclick="plusSlides(-1)"><img class="arrow-left" src="wp-content/plugins/mon-plugin/asset/Sacapuce/icons/ei_arrow-down.svg"></a>
            <a class="next" onclick="plusSlides(1)"><img src="wp-content/plugins/mon-plugin/asset/Sacapuce/icons/ei_arrow-down.svg"></a>
        </div>
        <br>
        <div class="slide-dot">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
        </div>';
}



get_footer();