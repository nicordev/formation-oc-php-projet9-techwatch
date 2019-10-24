let showArticleButtons = document.getElementsByClassName('show-content-btn'),
    elementDisplayHandler = new ElementDisplayHandler();

elementDisplayHandler.init("hidden", '-', '+');
elementDisplayHandler.applyToElementsOnClick(showArticleButtons);
