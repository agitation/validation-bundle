agit.ns("agit.api");

agit.api.MessageHandler = function()
{
    /**
     * If the handler shows multiple messages at once, this
     * method clears/removes the currently shown messages.
     *
     * If the category parameter is passed, only messages of that
     * category are cleared.
     */
    this.clear = function(category) { },

    this.showMessage = function(message)
    {
        alert(message.getText());
    }
};
