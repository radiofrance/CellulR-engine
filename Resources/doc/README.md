Getting started: EngineBundle
=============================

Télécharger le bundle
---------------------

```
$ composer require webcomponents/engine-bundle:dev-features/v3
```

Activer le bundle
-----------------

```php
<?php
// app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Dunglas\ActionBundle\DunglasActionBundle(),
            new Rf\WebComponent\EngineBundle\EngineBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            // ...
            $bundles[] = new Rf\WebComponent\GeneratorBundle\GeneratorBundle(); // <--- Optionnel
            $bundles[] = new Rf\WebComponent\DocBundle\DocBundle(); // <--- Optionnel
        }
        // ...
    }

    // ...
}
```

Configurer le bundle
--------------------

Voici la configuration par défaut. Vous pouvez la surcharger dans le fichier `app/config/config.yml`.

```yml
# app/config/config.yml
wc_engine:
    root_dir: '%kernel.root_dir%/../src' #default dir
```

L'option `root_dir` définit le répertoire où sont/seront situés les Web Components. Il est relatif au répertoir `/src` du full stack framework de Symfony.

Importer la configuration de Routage
------------------------------------

Pour pouvoir utiliser les Web Components, l'application doit connaître les routes fournies par l'EngineBundle en
accord avec sa configuration. Vous pouvez importer ces routes dans la configuration de routage de l'application :

```yml
# app/config/routing.yml
engine_bundle:
    resource: .
    type: component
```

Créer l'arborescence
--------------------

Pour créer l'arborescence des Web Components:
- Vous pouvez installer le bundle de génération de Web Components ([GeneratorBundle](https://gitlab.dnm.radiofrance.fr/webcomponents/generator-bundle/tree/features/v3)) et vous référer à sa documentation.
- Ou vous pouvez la créer comme suit dans le répertoire `root_dir` que vous aurez choisi:

```
 /ViewObject
 .... /Component
 .... /Page
 /WebComponent
 .... /Component
 .... /Page
```

Les Web Components
------------------

Un Web Component est composé de :
- Une View Object qui correspond à l'Action appelée lors de l'utilisation du Web Component
- Son corps regroupant la partie templating et assets

Il se caractérise par un type:
- ***Page***: comme son nom l'indique il représente une page accessible depuis le navigateur
- ***Component***: il représente une fonctionnalité implémentée au sein d'une page

Vous pouvez vous référer aux documentations suivantes pour créer votre premier Web Component:

- [Le Web Component](web_component.md)
- [La View Object](view_object.md)
- [Les paths Twig](paths_twig.md)


### Charts

Pour mieux comprendre les rouages du système, référez vous au charts suivants:
 
 - [Finder](chart_finder.md)
 - [Cas simple d'utilisation de la fonction "web_component"](chart_web_component_simple_usecase.md)
 - [Cas d'utilisation de la fonction "web_component" pour une "subrequest"](chart_web_component_subrequest_usecase.md)
 