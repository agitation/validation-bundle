ag.ns("ag.common");

(function(){
var
    validationError = ag.common.ValidationError,

    getArgs = function(args, offset)
    {
        return Array.prototype.slice.call(args, offset);
    },

    // preflight validation helpers, real validation is usually done on the server side.
    validation =
    {
        selection : function(value, possibleValues)
        {
            if (possibleValues.indexOf(value) < 0)
                throw new validationError(ag.intl.t("The selected value is invalid."));
        },

        integer : function(value, min, max)
        {
            if (isNaN(value) || value !== Math.round(value))
                throw new validationError(ag.intl.t("The value must be an integer."));

            if (min !== undefined && value < min)
                throw new validationError(ag.ui.tool.fmt.sprintf(ag.intl.t("The value is too low, it must be higher than %s."), min));

            if (max !== undefined && value > max)
                throw new validationError(ag.ui.tool.fmt.sprintf(ag.intl.t("The value is too high, it must be lower than %s."), max));
        },

        string : function(value, minLength, maxLength)
        {
            if (typeof(value) !== "string")
                throw new validationError(ag.intl.t("The value must be a string."));

            if (!isNaN(minLength) && value.length < minLength)
                throw new validationError(ag.ui.tool.fmt.sprintf(ag.intl.t("The value is too short, it must have at least %s characters."), minLength));

            if (!isNaN(maxLength) && value.length > maxLength)
                throw new validationError(ag.ui.tool.fmt.sprintf(ag.intl.t("The value is too long, it must have at most %s characters."), maxLength));
        },

        object : function(value, requiredProperties, noOtherProperties)
        {
            requiredProperties = requiredProperties || [];

            if (!(value instanceof Object) || value instanceof Array)
                throw new validationError(ag.intl.t("The value must be an object."));

            if (requiredProperties.length)
            {
                keys = Object.keys(value);

                if (noOtherProperties && keys.length !== requiredProperties.length)
                    throw new validationError(ag.intl.t("The object has an invalid set of properties."));

                requiredProperties.forEach(function(property){
                    if (keys.indexOf(property) === -1)
                        throw new validationError(ag.ui.tool.fmt.sprintf(ag.intl.t("The object is missing the mandatory “%s” property."), property));
                });
            }
        },

        regex : function(value, regex)
        {
            if (!value.match(regex))
                throw new validationError(ag.intl.t("The value doesn’t match the required pattern."));
        },

        email : function(value)
        {
            // simplified, but should work for most cases
            validation.regex(value, /^[a-z0-9][a-z0-9\._\-]*@[a-z][a-z0-9\.\-]*\.[a-z]{2,6}$/i);
        },

        phone : function(value)
        {
            validation.regex(value, /^\+[1-9]\d{1,3}\-?\d{2,7}\-?\d{3,12}$/);
        },

        creditcardNumber : function(ccNum)
        {
            var
                ccNumber = ccNum.toString(),
                checksum = 0,
                j = 1,
                i, calc;

            if (ccNumber.length !== 16)
                throw new validationError(ag.intl.t("The creditcard number is invalid."));

            for (i = ccNumber.length - 1; i >= 0; i--)
            {
                calc = ccNumber.substr(i, 1) * j;

                if (calc > 9)
                {
                    checksum += 1;
                    calc -= 10;
                }

                checksum += calc;
                j = (j === 1) ? 2 : 1;
            }

            if (checksum % 10 !== 0)
                throw new validationError(ag.intl.t("The creditcard number is invalid."));
        }
    };

    ag.common.isValid = function(type, value)
    {
        var valid = false;

        try
        {
            validation[type].apply(null, getArgs(arguments, 1));
            valid = true;
        }
        catch (e) { }

        return valid;
    };

    ag.common.validate = function(type, value)
    {
        validation[type].apply(null, getArgs(arguments, 1));
    };

    ag.common.validateField = function(fieldName, type, value)
    {
        try
        {
            validation[type].apply(null, getArgs(arguments, 2));
        }
        catch (e)
        {
            throw new validationError(ag.ui.tool.fmt.sprintf(ag.intl.t("Error in field “%s”: %s"), fieldName, e.message));
        }
    }
})();
