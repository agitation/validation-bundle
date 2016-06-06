ag.ns("ag.common");

ag.common.ValidationError = function(message)
{
    this.message = message;
};

ag.common.ValidationError.prototype = Object.create(Error.prototype);
