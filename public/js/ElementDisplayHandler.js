/**
 * Hide or show elements
 *
 * @returns {{expandElement: expandElement, init: init, retractElement: retractElement, retractedClass: null, toggleExpandNextElement: toggleExpandNextElement, retractedButtonContent: null, expandedButtonContent: null}}
 * @constructor
 */
function ElementDisplayHandler() {

    let that = {

        retractedClass: null,
        expandedButtonContent: null,
        retractedButtonContent: null,

        init: function (
            retractedClass = "hidden",
            expandedButtonContent = "-",
            retractedButtonContent = "+"
        ) {

            that.retractedClass = retractedClass;
            that.expandedButtonContent = expandedButtonContent;
            that.retractedButtonContent = retractedButtonContent;
        },

        /**
         * Hide or show the next element
         *
         * @param buttonElement
         * @param expandedButtonContent
         * @param retractedButtonContent
         */
        toggleExpandNextElement: function (
            buttonElement,
            expandedButtonContent = null,
            retractedButtonContent = null
        ) {
            let buttonWrapperElement = buttonElement.parentElement,
                elementToExpand = buttonWrapperElement.nextElementSibling;

            if (elementToExpand.classList.contains(that.retractedClass)) {
                that.expandElement(elementToExpand, buttonElement, expandedButtonContent || that.expandedButtonContent);
            } else {
                that.retractElement(elementToExpand, buttonElement, retractedButtonContent || that.retractedButtonContent);
            }
        },

        retractElement: function (elementToRetract, buttonElement, retractedButtonContent = that.retractedButtonContent) {
            elementToRetract.classList.add(that.retractedClass);
            buttonElement.textContent = retractedButtonContent;
        },

        expandElement: function (elementToExpand, buttonElement, expandedButtonContent = that.expandedButtonContent) {
            elementToExpand.classList.remove(that.retractedClass);
            buttonElement.textContent = expandedButtonContent;
        }
    };

    return that;
}
