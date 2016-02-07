agit.ns("agit.common");

(function(){
    var msgH = function() { };

    msgH.prototype.clear = function(category) { };

    msgH.prototype.showMessage = function(message)
    {
        alert(message.getText());
    };

    msgH.prototype.alert = function(text, type, category)
    {
        this.clear(category);
        this.showMessage(new agit.common.Message(text, type || "error", category));
    };

    agit.common.MessageHandler = msgH;
})();
