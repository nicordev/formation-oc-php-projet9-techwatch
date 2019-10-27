let showArticleButtons = document.getElementsByClassName('show-content-btn'),
    elementDisplayHandler = new ElementDisplayHandler();

elementDisplayHandler.init("hidden", 'Close', 'Read more');
elementDisplayHandler.applyToElementsOnClick(showArticleButtons);
