let showArticleButtons = document.getElementsByClassName('show-article-btn'),
    elementDisplayHandler = new ElementDisplayHandler();

elementDisplayHandler.init("hidden", '-', '+');

for (let button of showArticleButtons) {
    button.addEventListener("click", function () {
        elementDisplayHandler.toggleExpandNextElement(button);
    });
}
