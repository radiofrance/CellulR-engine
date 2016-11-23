La View Object
==============

Une View Object est une classe qui représente une Action dans le design pattern ADR.
Elle se situe à la racine du dossier du Web Component.

Exemple
-------

Voici un exemple de View Object d'un Web Component `Image` de type `Component`

```
 /src
 .... /WebComponent
 ........ /Component
 ............ /Image
 ................ Image.php
 ................ ...
 ........ /Page
 ............ ...
```

Nomenclature
------------

La classe de la View Object doit porter le nom du Web Component également et suivre les même règles de nommage.

Contenu
-------

La classe de la View Object se décrit comme suit :

```php
<?php
// src/WebComponent/Component/Image/Image.php
 
namespace WebComponent\Component\Image;
    
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

La méthode `__invoke` est appelée à l'appel du Web Component.
Chaque paramètre passé à l'appel sera récupérable sur cette méthode.

La classe peut posséder un constructeur avec des arguments.
L'`autowiring` de Symfony permettra d'y injecter à la volée les différents services définis en paramètre lorsque ces derniers seront typés :

```php
    <?php
    // src/WebComponent/Component/Image/Image.php
     
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

Chaque View Object peut être surchargée à partir du moment ou :
- Elle est contenue dans le dossier `ViewObject` qui se situe dans le `root_dir`
- Elle suit la même arborescence que celle définit initialement dans le Web Component

```
 /src
 .... /ViewObject
 ........ /Component
 ............ /Image
 ................ Image.php <--- View Object par surchargée
 .... /WebComponent
 ........ /Component
 ............ /Image
 ................ Image.php <--- View Object par défaut
 ................ ...
```

```php
<?php
// src/ViewObject/Component/Image/Image.php
 
namespace ViewObject\Component\Image;
    
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

Un composant peut être accessible depuis une route déclarée en annotation grâce au composant `Routing` de Symfony :

```php
<?php
// src/WebComponent/Component/Image/Image.php
 
namespace WebComponent\Component\Image;
    
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
Voir aussi
-----------

[Les paths Twig](paths_twig.md)
