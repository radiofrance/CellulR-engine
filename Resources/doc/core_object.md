Le Core Object
==============

Un Core Object est une classe php, sa responsabilité est de fournir les données à la Cell.  
Son design pattern est inspiré du [http://www.engineyard.com/blog/7-patterns-to-refactor-javascript-applications-view-objects](ViewObject).  
Il se situe à la racine du dossier de la Cell.

Exemple
-------

Voici un exemple de Core Object d'une Cell `Image` de type `Component`

```
 /src
 .... /Cell
 ........ /Component
 ............ /Image
 ................ Image.php
 ................ ...
 ........ /Page
 ............ ...
```

Nomenclature
------------

La classe du Core Object doit porter le nom de la Cell également et suivre les même règles de nommage.

Contenu
-------

Voici l'implémentation minimale d'une classe de Core Object :

```php
<?php
// src/Cell/Component/Image/Image.php
 
namespace Cell\Component\Image;
    
/**
 * Class Image.
 */
class Image
{
    /**
     * @return array The array of data
     */
    public function __invoke()
    {
        // 2 types de réponses sont également possibles.
        // Voir le chapitre "Response" plus bas.
        return array(); 
    }
}
```

La méthode `__invoke` est appelée à l'appel de la Cell.
Chaque paramètre passé à l'appel sera récupérable sur cette méthode.

La classe peut posséder un constructeur avec des arguments.
L'`autowiring` de Symfony permettra d'y injecter à la volée les différents services définis en paramètre lorsque ces derniers seront typés :

```php
    <?php
    // src/Cell/Component/Image/Image.php
     
    /**
     * @param Request $request
     */
    public function __constructor(Request $request)
    {
        // ...
    }
```

Surcharge
---------

Le CoreObject qui est situé dans le dossier initial de la Cell doit être impérativement simple est embarqué le moins dépendances au **domaine** possibles.  
Ceci dans un soucis d'exportation de la Cell pour d'autres projets.  
L'idée est donc d'offrir la possibilité de surcharge du CoreObject pour étendre ses fonctionnalités au domaine.

Chaque Core Object peut être surchargé à partir du moment ou :
- Il est contenu dans le dossier `CoreObject` qui se situe dans le `root_dir`
- Il suit la même arborescence que celle définit initialement dans la Cell

```
 /src
 .... /CoreObject
 ........ /Component
 ............ /Image
 ................ Image.php <--- Core Object de surcharge
 .... /Cell
 ........ /Component
 ............ /Image
 ................ Image.php <--- Core Object par défaut
 ................ ...
```

```php
<?php
// src/CoreObject/Component/Image/Image.php
 
namespace CoreObject\Component\Image;
    
/**
 * Class Image.
 */
class Image
{
    /**
     * @return array The array of data
     */
    public function __invoke()
    {
        return array();
    }
}
```

Routing
-------

Une Cell peut être accessible depuis une route déclarée en annotation grâce au composant `Routing` de Symfony :

```php
<?php
// src/Cell/Component/Image/Image.php
 
namespace Cell\Component\Image;
    
/**
 * Class Image.
 */
class Image
{
    /**
     * @Route("/image", name="app_image")
     *
     * @return array The array of data
     */
    public function __invoke()
    {
        return array();
    }
}
```

Response
--------

Un CoreObject peut fonctionner comme un bloc ESI (voir chapitre **["Cell as ESI"](./cell.md)** ).
Pour pouvoir gérer les durées de cache de ces blocs (et même surcharger les TTL de cache d'une page), on peut utiliser un objet **Response** :

```php
<?php
// src/CoreObject/Component/Image/Image.php
 
namespace CoreObject\Component\Image;

use Rf\CellulR\EngineBundle\CoreObject\Response;

/**
 * Class Image.
 */
class Image
{
    /**
     * @return Response With the array of data for the view & specific data for cache directive
     */
    public function __invoke()
    {
        // 2nd paramètre = max-age (en sec.)
        // 3ème paramètre = s-maxage (en sec.)
        return new Response(['variable1' => 'value 1'], 600, 600); 
    }
}
```

Voir aussi
-----------

[Les paths Twig](paths_twig.md)
