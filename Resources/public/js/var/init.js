(function(){
    var services = {};

    window.ag = window.ag || {};

    // this method will be used at the top of each namespaced file to create the namespace on the fly
    ag.ns = function(namespace)
    {
        var
            parts = namespace.split("."),
            i = 0,
            ref = window;

        for (i; i < parts.length; i++)
        {
            ref[parts[i]] = ref[parts[i]] || {};
            ref = ref[parts[i]];
        }
    };

    ag.srv = function(name, instance)
    {
        if (instance)
            services[name] = instance;
        else
            return services[name];
    };
})();
