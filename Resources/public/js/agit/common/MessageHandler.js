agit.ns("agit.common");

(function(){
    var msgH = function() { };

    msgH.prototype.clear = function(category) { };

    msgH.prototype.showMessage = function(message)
    {
        alert(message.getText());
    };

    agit.common.MessageHandler = msgH;
})();
