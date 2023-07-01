var init = () => {
    afficheProduit();
    spanCouleur();
}

window.addEventListener("load", init);

let selectCat = document.getElementById('select-cat');

selectCat.addEventListener('change', () => {
    afficheProduit(selectCat.value);
})

// Affichage et récupération des produits en base
let afficheProduit = async (text) => {
    // console.log(text);
            await fetch("/Projet-Aphrodite/wp-content/themes/astra-child/includes/ajax/get_product.php?"+text)
            .then((resp) => resp.json())
            .then((data) =>{
                // console.log(data);
                let listProduct = document.getElementById('list-product'),
                products = data[0],
                categorys = data[1];
                // console.log(categorys);
                for(let j=0;j<products.length;j++){
                    // console.log(products[j]);

                    let imgNew = '',
                    imgFav = '',
                    classFav = '';

                    if (products[j].status_new == "New"){
                        imgNew = 'http://localhost/Projet-Aphrodite/wp-content/plugins/mon-plugin/asset/Sacapuce/NEW-etiquette';
                    }else{
                        imgNew = '';
                    }

                    if (products[j].status_fav == "1"){
                        imgFav = 'http://localhost/Projet-Aphrodite/wp-content/plugins/mon-plugin/asset/Sacapuce/icons/like';
                        classFav = " favori";
                    }else{
                        imgFav = 'http://localhost/Projet-Aphrodite/wp-content/plugins/mon-plugin/asset/Sacapuce/icons/unlike';
                        classFav = " nonfavori";
                    }

                    let divProduct = createBlock('div', "", "divProduct"),
                    legendProduct = createBlock('div', '', 'legendProduct'),
                    imgProduct = createBlock('img', "", "imgProduct"),
                    etiqNew = createBlock('img', "", "etiqNew"),
                    etiqFav = createBlock('a', '<img id="imgFav'+j+'" src="'+ imgFav +'">', "etiqFav" + classFav);
                    nameProduct = createBlock('p', products[j].name, "nameProduct");
                    priceProduct = createBlock('p', products[j].price, "priceProduct"),
                    pointures = createBlock('div', '', "pointures");

                    divProduct.id = 'divProduct' + j;
                    imgProduct.src = products[j].image_product;
                    etiqNew.src = imgNew;
                    etiqFav.id = "etiqFav" + j;

                    for(let i=0;i<categorys.length;i++){
                        if (products[j].id == categorys[i].product_id){
                            if (categorys[i].status == 1){
                                if (categorys[i].cat_parent_name == "Pointure"){
                                    // console.log(categorys[i]);
                                    let pointure = createBlock('span', categorys[i].value, "pointure");
                                    pointures.appendChild(pointure);
                                    
                                }
                            }
                        }
                    }
                    // console.log(pointures);

                    if (document.getElementById(divProduct.id)){
                        let removeProduct = document.getElementById('divProduct' + j)
                        removeProduct.remove();
                    }
                    listProduct.appendChild(divProduct);
                    divProduct.appendChild(imgProduct);
                    divProduct.appendChild(legendProduct);
                    divProduct.appendChild(pointures);
                    divProduct.appendChild(etiqNew);
                    divProduct.appendChild(etiqFav);
                    legendProduct.appendChild(nameProduct);
                    legendProduct.appendChild(priceProduct);

                    if (document.getElementById(etiqFav.id)){
                        let eventFav = document.getElementById('etiqFav' + j);
                        let imgFav = document.getElementById('imgFav' + j);

                        eventFav.addEventListener('click', () => {change_fav(eventFav, products[j].id, imgFav)})
                    }
                    
                }
                }
            )
}

var createBlock = function (tag, content, cssClass) {
	var element = document.createElement(tag);
	if (cssClass != undefined) {
		element.className =  cssClass;
	}
	element.innerHTML = content;
	return element;
}

// non fonctionnel
let change_fav = (eventFav, product_id, imgFav) => {
    favClass = eventFav.className
    // console.log(eventFav);
    if (favClass.indexOf('nonfavori') != -1){
        // Il n'est pas favori
        fetchUrl = '/Projet-Aphrodite/wp-content/themes/astra-child/includes/ajax/get_fav.php?nonfavori&productid='+product_id;
    }else{
        // Il est favori
        fetchUrl = '/Projet-Aphrodite/wp-content/themes/astra-child/includes/ajax/get_fav.php?favori&productid='+product_id;
    }
    // console.log(fetchUrl);

    fetch(fetchUrl).then((resp) => resp.json())
    .then((data) =>{
        // console.log(imgFav);
        switch (data){
            case 'fav':
                imgFav.src = 'http://localhost/Projet-Aphrodite/wp-content/plugins/mon-plugin/asset/Sacapuce/icons/like';
                eventFav.className = 'etiqFav favori';
                break;
            case 'nonfav':
                imgFav.src = 'http://localhost/Projet-Aphrodite/wp-content/plugins/mon-plugin/asset/Sacapuce/icons/unlike';
                eventFav.className = 'etiqFav nonfavori';
                break;
        }
    })

}

let spanCouleur = () => {
    let couleurs = document.getElementsByClassName('couleur');
    for(let i=0;i<couleurs.length;i++){
        couleurs[i].style.backgroundColor = couleurs[i].id;
    }
}