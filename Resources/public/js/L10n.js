Agit.L10n =
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
            string = Agit.L10n.t(origString),
            lastpipe = string.lastIndexOf('|');

        return (lastpipe === -1)
            ? string
            : string.slice(0, lastpipe);
    },

    // TODO: Fix for languages with more than one plural form
    tn : function(string, num)
    {
        var
            tString = Agit.L10n.t(string),
            parts = tString.indexOf('|') > 0 ? tString.split('|') : [tString, tString];

        return (num === 1) ? parts[0] : parts[1];
    },

    u : function(string) // just a shortcut
    {
        return Agit.L10n.mlStringTranslate(string, Agit.locale);
    },

    mlStringToObj : function(string)
    {
        var
            regex = new RegExp('\\[:(?=[a-z]{2}\\])', 'g'),
            obj = {};

        if (typeof(string) === 'string')
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
            fallbackLang = Agit.locale.substr(0, 2),
            obj,
            outString = string;

        if (typeof(string) === 'string' && string.match(/\[:[a-z]{2}\]/))
        {
            obj = Agit.L10n.mlStringToObj(string);

            if (typeof(obj[lang]) === 'string')
            {
                outString = obj[lang];
            }
            else if (typeof(obj[fallbackLang]) === 'string')
            {
                outString = obj[fallbackLang];
            }
            else if (typeof(obj.en) === 'string')
            {
                outString = obj.en;
            }
            else
            {
                outString = '';
            }
        }

        return outString;
    }
};

// console.log(Agit.L10n.mlStringTranslate('[:de]foo[:en]bar', 'de_AT')); // 'foo'
// console.log(Agit.L10n.mlStringTranslate('[:de]foo[:en]bar', 'it_IT')); // 'foo'
// console.log(Agit.L10n.mlStringTranslate('[:de][:en]bar', 'de_DE')); // ''
// console.log(Agit.L10n.mlStringTranslate('[de]foo[:en]bar', 'de_DE')); // 'bar'
// console.log(Agit.L10n.mlStringTranslate('[defoo[:en]bar', 'de_DE')); // 'bar'
// console.log(Agit.L10n.mlStringTranslate('[:defoo[:en]bar', 'de_DE')); // 'bar'
// console.log(Agit.L10n.mlStringTranslate('foobar', 'de_DE')); // 'foobar'
//
// console.log(Agit.L10n.mlStringToObj('[:de]foo[:en]bar')); // { de : "foo", en : "bar" }
// console.log(Agit.L10n.mlStringToObj('[:de]foo[:en]bar')); // { de : "foo", en : "bar" }
// console.log(Agit.L10n.mlStringToObj('[:de][:en]bar')); // { de : "", en : "bar" }
// console.log(Agit.L10n.mlStringToObj('[de]foo[:en]bar')); // { en : "bar" }
// console.log(Agit.L10n.mlStringToObj('[defoo[:en]bar')); // { en : "bar" }
// console.log(Agit.L10n.mlStringToObj('[:defoo[:en]bar')); // { en : "bar" }
// console.log(Agit.L10n.mlStringToObj('foobar')); // { }
