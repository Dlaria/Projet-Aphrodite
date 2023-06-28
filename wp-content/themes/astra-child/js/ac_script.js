var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("custom-slider");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
}

var init = () => {
    afficheProduit();
}

window.addEventListener("load", init);

// Affichage et récupération des produits en base
let afficheProduit = async (text) => {
    // console.log(text);
            await fetch("/Projet-Aphrodite/wp-content/themes/astra-child/get_product.php?"+text)
            .then((resp) => resp.json())
            .then((data) =>{
                // console.log(data);
                let listProduct = document.getElementById('list-product');

                    let products = data[0],
                    categorys = data[1];
                    // console.log(categorys);
                    for(let j=0;j<products.length;j++){
                        // console.log(products[j]);

                        for(let i=0;i<categorys.length;i++){
                            if (products[j].id == categorys[i].product_id){
                                if (categorys[i].status == 1){
                                    // console.log(categorys[i]);
                                }
                            }
                        }

                        let imgNew = '',
                        imgFav = '',
                        idFav = '';

                        if (products[j].status_new == "New"){
                            imgNew = 'http://localhost/Projet-Aphrodite/wp-content/plugins/mon-plugin/asset/Sacapuce/NEW-etiquette';
                        }else{
                            imgNew = '';
                        }

                        if (products[j].status_fav == "1"){
                            imgFav = 'http://localhost/Projet-Aphrodite/wp-content/plugins/mon-plugin/asset/Sacapuce/icons/like';
                            idFav = "favori";
                        }else{
                            imgFav = 'http://localhost/Projet-Aphrodite/wp-content/plugins/mon-plugin/asset/Sacapuce/icons/unlike';
                            idFav = "nonfavori";
                        }

                        let divProduct = createBlock('div',"","divProduct"),
                        legendProduct = createBlock('div', '', 'legendProduct'),
                        imgProduct = createBlock('img',"","imgProduct"),
                        etiqNew = createBlock('img', "", "etiqNew"),
                        etiqFav = createBlock('a', '<img src="'+ imgFav +'">', "etiqFav");
                        nameProduct = createBlock('p', products[j].name, "nameProduct");
                        priceProduct = createBlock('p', products[j].price, "priceProduct");

                        divProduct.id = 'divProduct' + j;
                        imgProduct.src = products[j].image_product;
                        etiqNew.src = imgNew;
                        etiqFav.id = idFav;


                        if (document.getElementById(divProduct.id)){
                            let removeProduct = document.getElementById('divProduct' + j)
                            removeProduct.remove();
                        }
                        listProduct.appendChild(divProduct);
                        divProduct.appendChild(imgProduct);
                        divProduct.appendChild(legendProduct);
                        divProduct.appendChild(etiqNew);
                        divProduct.appendChild(etiqFav);
                        legendProduct.appendChild(nameProduct);
                        legendProduct.appendChild(priceProduct);
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
// let change_fav = () => {
//     let etiqFav = document.getElementsByClassName('etiqFav');

//     for(let i=0;i<etiqFav.length;i++){
//         allFav = etiqFav[i];

//         allFav.addEventListener('click', () => {
//             console.log(this);
//         })
//     }

// }