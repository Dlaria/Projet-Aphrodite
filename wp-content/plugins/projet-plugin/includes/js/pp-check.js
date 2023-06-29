jQuery(function($) {
    $(document).ready(function(){
    let allTermId = document.getElementsByClassName('product-cat');
    let allInputcheck = document.querySelectorAll('input[type=checkbox]');
    let allInputradio = document.querySelectorAll('input[type=radio]');

    for(let j=0; j<allInputcheck.length;j++){
        for(let i=0; i<allTermId.length;i++){

            if (allInputcheck[j].value == allTermId[i].value){
                allInputcheck[j].checked = true;
            }
        }
    }
    for(let a=0; a<allInputradio.length;a++){
        for(let i=0; i<allTermId.length;i++){

            if (allInputradio[a].value == allTermId[i].value){
                allInputradio[a].checked = true;
            }
        }
    }

    let imageProduct = document.getElementById('imageProduct');
    let name = document.getElementById('name');
    let description = document.getElementById('description');
    let price = document.getElementById('price');
    
    let hiddenSrc = document.getElementById('src');
    let hiddenName = document.getElementById('hidden-name');
    let hiddenDescription = document.getElementById('hidden-description');
    let hiddenPrice = document.getElementById('hidden-price');

    imageProduct.src = hiddenSrc.value;
    name.value = hiddenName.value;
    description.value = hiddenDescription.value;
    price.value = hiddenPrice.value

})
})