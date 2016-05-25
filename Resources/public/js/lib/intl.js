ag.ns("ag");

agit.intl =
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
            string = agit.intl.t(origString),
            lastpipe = string.lastIndexOf("|");

        return (lastpipe === -1)
            ? string
            : string.slice(0, lastpipe);
    },

    // TODO: Fix for languages with more than one plural form
    tn : function(string, num)
    {
        var
            tString = agit.intl.t(string),
            parts = tString.indexOf("|") > 0 ? tString.split("|") : [tString, tString];

        return (num === 1) ? parts[0] : parts[1];
    },

    u : function(string) // just a shortcut
    {
        return agit.intl.mlStringTranslate(string, agit.cfg.locale);
    },

    mlObjToString : function(obj)
    {
        var string = "";

        Object.keys(obj).forEach(function(key){
            key.length === 2 && (string += "[:" + key + "]" + obj[key]);
        });

        return string;
    },

    mlStringToObj : function(string)
    {
        var
            regex = new RegExp("\\[:(?=[a-z]{2}\\])", "g"),
            obj = {};

        if (typeof(string) === "string")
        {
            string.split(regex).forEach(function(part){
                var
                    key = part.substr(0, 2),
                    string = part.substr(3);

                if (part.length >= 3 && part.substr(0,3).match(/[a-z]{2}\]/))
                {
                    obj[key] = string;
                }
            });
        }

        return obj;
    },

    mlStringTranslate : function(string, locale)
    {
        var
            lang = locale.substr(0, 2),
            fallbackLang = agit.cfg.locale.substr(0, 2),
            obj,
            outString = string;

        if (typeof(string) === "string" && string.match(/\[:[a-z]{2}\]/))
        {
            obj = agit.intl.mlStringToObj(string);

            if (typeof(obj[lang]) === "string")
            {
                outString = obj[lang];
            }
            else if (typeof(obj[fallbackLang]) === "string")
            {
                outString = obj[fallbackLang];
            }
            else if (typeof(obj.en) === "string")
            {
                outString = obj.en;
            }
            else if (Object.keys(obj).length)
            {
                outString = obj[Object.keys(obj)[0]];
            }
            else
            {
                outString = "";
            }
        }

        return outString;
    }
};

// console.log(agit.intl.mlStringTranslate("[:de]foo[:en]bar", "de_AT")); // "foo"
// console.log(agit.intl.mlStringTranslate("[:de]foo[:en]bar", "it_IT")); // "foo"
// console.log(agit.intl.mlStringTranslate("[:de][:en]bar", "de_DE")); // ""
// console.log(agit.intl.mlStringTranslate("[de]foo[:en]bar", "de_DE")); // "bar"
// console.log(agit.intl.mlStringTranslate("[defoo[:en]bar", "de_DE")); // "bar"
// console.log(agit.intl.mlStringTranslate("[:defoo[:en]bar", "de_DE")); // "bar"
// console.log(agit.intl.mlStringTranslate("foobar", "de_DE")); // "foobar"
//
// console.log(agit.intl.mlStringToObj("[:de]foo[:en]bar")); // { de : "foo", en : "bar" }
// console.log(agit.intl.mlStringToObj("[:de]foo[:en]bar")); // { de : "foo", en : "bar" }
// console.log(agit.intl.mlStringToObj("[:de][:en]bar")); // { de : "", en : "bar" }
// console.log(agit.intl.mlStringToObj("[de]foo[:en]bar")); // { en : "bar" }
// console.log(agit.intl.mlStringToObj("[defoo[:en]bar")); // { en : "bar" }
// console.log(agit.intl.mlStringToObj("[:defoo[:en]bar")); // { en : "bar" }
// console.log(agit.intl.mlStringToObj("foobar")); // { }
