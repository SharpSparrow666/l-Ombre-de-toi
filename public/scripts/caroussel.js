class Carousel {

    constructor

}

document.addEventListener('DOMContentLoaded', function(){

    new Carousel(document.querySelector('#carousel1'), {
        slidesToScroll: 3,
        slidesVisible: 3
    })

})

