ag.ns("ag.vld");

ag.vld.ValidationError = function(message)
{
    this.message = message;
};

ag.vld.ValidationError.prototype = Object.create(Error.prototype);
