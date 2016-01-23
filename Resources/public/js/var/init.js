(function(){
    var services = {};

    window.agit =
    {
        // this method will be used at the top of each namespaced file to create the namespace on the fly
        ns : function(namespace)
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
        },

        cfg : {},

        srv : function(name, instance)
        {
            if (instance)
                services[name] = instance;
            else
                return services[name];
        }
    };
})();

