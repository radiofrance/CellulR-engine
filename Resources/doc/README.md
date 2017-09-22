Getting started: EngineBundle
=============================

Télécharger le bundle
---------------------

```
$ composer require cellulr/engine-bundle:dev-features/v3
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
            new Rf\CellulR\EngineBundle\EngineBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            // ...
            $bundles[] = new Rf\CellulR\GeneratorBundle\GeneratorBundle(); // <--- Optionnel
            $bundles[] = new Rf\CellulR\DocBundle\DocBundle(); // <--- Optionnel
        }
        // ...
    }

    // ...
}
```

et il faut modifier le ```composer.json``` pour avoir l'autoload des Cell :

```
    "autoload": {
        "psr-4": {
            "AppBundle\\": "src/AppBundle",
            "Cell\\": "src/Cell"
        },
        "classmap": [
            "app/AppKernel.php",
            "app/AppCache.php"
        ]
    },
```

Configurer le bundle
--------------------

Voici la configuration par défaut. Vous pouvez la surcharger dans le fichier `app/config/config.yml`.

```yml
# app/config/config.yml
cellulr_engine:
    root_dir: '%kernel.root_dir%/../src' #default dir
```

L'option `root_dir` définit le répertoire où sont/seront situés les Cells. Il est relatif au répertoire `/src` du full stack framework de Symfony.

Importer la configuration de Routage
------------------------------------

Pour pouvoir utiliser les Cells, l'application doit connaître les routes fournies par l'EngineBundle en accord avec sa configuration.
Vous pouvez importer ces routes dans la configuration de routage de l'application :

```yml
# app/config/routing.yml
engine_bundle:
    resource: .
    type: component
```

Créer l'arborescence
--------------------

Pour créer l'arborescence des Cells:
- Vous pouvez installer le bundle de génération de Cells ([GeneratorBundle](https://gitlab.dnm.radiofrance.fr/cellulR/generator-bundle/tree/features/v3)) et vous référer à sa documentation.
- Ou vous pouvez la créer comme suit dans le répertoire `root_dir` que vous aurez choisi:

```
 /CoreObject
 .... /Component
 .... /Page
 /Cell
 .... /Component
 .... /Page
```

Les Cells
------------------

Une Cell est composé de :
- Un Core Object qui correspond à l'Action appelée lors de l'utilisation de la Cell
- Son corps regroupant la partie templating et assets

Elle se caractérise par un type:
- ***Page***: comme son nom l'indique elle représente une page accessible depuis le navigateur
- ***Component***: elle représente une fonctionnalité implémentée au sein d'une page

Vous pouvez vous référer aux documentations suivantes pour créer votre première Cell:

- [La Cell](cell.md)
- [Le Core Object](core_object.md)
- [Les paths Twig](paths_twig.md)


### Charts

Pour mieux comprendre les rouages du système, référez vous au charts suivants:

 - [Finder](chart_finder.md)
 - [Cas simple d'utilisation de la fonction "cell"](chart_cell_simple_usecase.md)
 - [Cas d'utilisation de la fonction "cell" pour une "subrequest"](chart_cell_subrequest_usecase.md)

