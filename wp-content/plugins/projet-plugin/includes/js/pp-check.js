jQuery(function($) {
    $(document).ready(function(){
    // Récupération des input hidden des catégories du produit et des input checkbox et radio
    let allTermId = document.getElementsByClassName('product-cat');
    let allInputcheck = document.querySelectorAll('input[type=checkbox]');
    let allInputradio = document.querySelectorAll('input[type=radio]');

    // Boucle des inputs checkbox
    for(let j=0; j<allInputcheck.length;j++){
        // Boucle des inputs hidden 
        for(let i=0; i<allTermId.length;i++){

            // Si la valeur des inputs checkbox est égale a la valeur des inputs hidden
            if (allInputcheck[j].value == allTermId[i].value){
                // Alors les inputs checkbox se coche
                allInputcheck[j].checked = true;
            }
        }
    }

    // Même chose que au dessus mais avec les inputs radio 
    for(let a=0; a<allInputradio.length;a++){
        for(let i=0; i<allTermId.length;i++){

            if (allInputradio[a].value == allTermId[i].value){
                allInputradio[a].checked = true;
            }
        }
    }

    // Récupération des inputs texte du produits
    let imageProduct = document.getElementById('imageProduct');
    let name = document.getElementById('name');
    let description = document.getElementById('description');
    let price = document.getElementById('price');
    
    // Récupération des inputs hidden des infos du produits
    let hiddenSrc = document.getElementById('src');
    let hiddenName = document.getElementById('hidden-name');
    let hiddenDescription = document.getElementById('hidden-description');
    let hiddenPrice = document.getElementById('hidden-price');

    // Ajout des valeurs des inputs hidden dans les inputs texte
    imageProduct.src = hiddenSrc.value;
    name.value = hiddenName.value;
    description.value = hiddenDescription.value;
    price.value = hiddenPrice.value

})
})