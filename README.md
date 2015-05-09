**Agitation** is an e-commerce framework, based on Symfony2, focussed on
extendability through plugged-in APIs, UIs, payment modules and other
components.

## AgitIntlBundle

The AgitIntlBundle manges languages and translations, with heavy usage of GNU Gettext.

- It provides a class to wrap around translatable strings (e.g. `Translate::t("Hello world!")`).
- It allows switching the current locale of the PHP thread.
- It ships a Twig extension for frontend translations.
- It provides tools to extract translatable strings from bundles, and create translation catalogs.

**Why not use the tools provided by Symfony2, you ask?**

Fair question. We won’t say that AgitIntlBundle is better than Symfony’s
internationalisation features – but we did have a few reasons to create this bundle.

- Performance: We use the PHP bindings to GNU Gettext, which means that Gettext catalogs are cached by PHP.
- We can also translate messages from JavaScript.
- The translation collector can be extended through event listeners at runtime – e.g. to support Twig and annotations.
- Translation catalogs are merged into one big catalog, which can be given to translators for better handling.
- Symfony doesn’t ship a Gettext extractor by default.

**Message domains**

We have decided not to use message domains, because we want to take advantage of
identical messages across packages. If a package needs to force a different
translation for a certain existing message, it should use a contexted message.
