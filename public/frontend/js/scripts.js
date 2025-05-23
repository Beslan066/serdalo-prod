
const searchBtn = document.querySelector('.search');
const searchInput = document.querySelector('.search-form');
const menu = document.querySelector('.menu');
const lang = document.querySelector('.lang');
const searchIcon = document.querySelector('.search-btn');
const logoTitle = document.querySelector('.logo-title');

const langBtns = document.querySelectorAll('.lang-btn');




console.log(langBtns);


// Показать-скрыть поиск

searchIcon.addEventListener("click", () => {



    const mediaQuery = window.matchMedia('(max-width: 768px)')


    searchInput.classList.toggle("show");

    if(searchInput.classList.contains("show")) {

        lang.style.display = "none";



        if (mediaQuery.matches) {
            menu.style.display = "none";
            logoTitle.style.display = "none";

        }else {
            logoTitle.style.display = "block";
        }

        searchIcon.style.background = "url('frontend/img/close.svg') no-repeat center";


    }else {

        lang.style.display = "flex";

        logoTitle.style.display = "block";

        searchIcon.style.backgroundImage = "url('frontend/img/search.svg')";

    }

});


langBtns.forEach(onTabClick);


function onTabClick(item) {
    item.addEventListener("click", function() {

        let currentBtn = item;
        let tabId = currentBtn.getAttribute("data-tab");
        let currentTab = document.querySelector(tabId);
        if(! currentBtn.classList.contains('active')){
            langBtns.forEach(function(item){
                item.classList.remove('active');
            });
            langBtns.forEach(function(item){
                item.classList.remove('active');
            });
            currentBtn.classList.add('active');
            currentTab.classList.add('active');
        }

    });
}


// Бургер-меню

const burgerBtn = document.querySelector('.burger-icon');
const burgerMenu = document.querySelector('.rubric-menu');
const rubricCloseBtn = document.querySelector('.rubric-close-btn');

burgerBtn.addEventListener("click", () => {

    burgerMenu.style.display = "block";

});

rubricCloseBtn.addEventListener("click", () => {

    burgerMenu.style.display = "none";

});
