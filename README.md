**Agitation** is an e-commerce framework, based on Symfony2, focussed on
extendability through plugged-in APIs, UIs, payment modules and other
components.

## AgitCronBundle

This bundle allows other components to register Unix-like cronjobs. When using
this bundle, the application needs only one entry in `/etc/crontab` for the CLI
command of this bundle:

```/etc/crontab
# running the script every minute as webserver user
* * * * * www-data app/console --env=prod agit:cron:execute
```

All the application’s actual cronjobs are registered with a cron-like scheduling
pattern through this bundle and are executed when due.
