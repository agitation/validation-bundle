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
- Bundles’ translations are independent from each other.
- We can process much more than only controllers and Twig templates.
- Translation catalogs are merged to one big catalog during cache warming.
