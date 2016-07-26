ag.ns("ag");

ag.intl =
{
    t : function(_string)
    {
        return (window.messages && window.messages[_string])
            ? window.messages[_string]
            : _string;
    },

    tc : function(origString)
    {
        var
            string = ag.intl.t(origString),
            lastpipe = string.lastIndexOf("|");

        return (lastpipe === -1)
            ? string
            : string.slice(0, lastpipe);
    },

    // TODO: Fix for languages with more than one plural form
    tn : function(string, num)
    {
        var
            tString = ag.intl.t(string),
            parts = tString.indexOf("|") > 0 ? tString.split("|") : [tString, tString];

        return (num === 1) ? parts[0] : parts[1];
    }
};
