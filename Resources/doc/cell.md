Le Cell
================

Un Cell rassemble:
- ses dépendances et sa définition
- les templates
- les assets
- la Core Object

Les types
---------

Un Cell possède un type. Il doit être classée selon son type dans un répertoire associés.
Il existe actuellement deux types: ***Page*** et ***Component***.

Exemple
-------

Voici un exemple d'un Cell `Image` de type `Component`

```
 src/
 .... /Cell
 ........ /Component
 ............ /Image
 ................ /js
 .................... component.js
 .................... handler.js
 .................... main.js
 ................ /less
 .................... main.less
 ................ component.json
 ................ image.html.twig
 ................ Image.php
 ................ README.md
```

Le type ***Page*** est un regroupement de Cells et est accessible depuis une route. Il répond donc à un besoin fonctionnel et structurel.

Le type ***Component*** est unitaire. Il peut aussi être un regroupement de Cells mais ne répond seulement qu'à un besoin fonctionnel.

Nomenclature
------------

Un Cell est référencé par son nom. Ce nom est utilisé pour stocker la Core Object et le corps du Cell.
Quelques règles sont à respecter:

- Le Cell est encapsulé par le dossier portant son nom: Cell/***Image***
    - Le nom doit commencer par une majuscule
    - Le nom doit être en CamelCase
    - Le nom ne doit pas contenir de caractères spéciaux ou de ponctuation

- Son template doit porter son nom:
    - En minuscule
    - En snake_case
    - Avec l'extension .html.twig

Le fichier de dépendances
-------------------------

Chaque Cell doit possèder un fichier de dépendance et de définition `component.json`.
Ce fichier permet de relier les assets des Cells entre eux et de les prendre en compte lors de la compilation.

Voici l'exemple du Cell `Image` de type `Component`

```json
{
    "name": "Image",
    "description": "Set a description of the image component",
    "require": {
        "Legend": "*"
    }
}
```

Les différentes clefs:
- `name`: ***[string]*** Le Nom de la Cell
- `description`: ***[string]*** La description de la Cell
- `master`: ***[boolean]*** Une Cell **master** se place en haut de l'arbre de dépendances
- `require`: ***(required) [array]*** Liste les dépendances de la Cell:
    - `"NomDeLaCell": "*"` //TODO La version est à venir

Appel d'une Cell
------------------------

L'appel d'une Cell se fait :

- Grâce à son nom au travers d'une fonction twig `cell`. Il est possible de lui passer des paramètres directement envoyés à son Core Object.

```
{{ cell('Image', {request: app.request}) }}
```

- Grâce à son URL d'accès si celle-ci a définit une route dans son Core Object.

Cell as ESI
--------------------

Pour permettre a une cellule de se comporter comme un **bloc ESI**, il suffit de l'appeler comme suit :

```
{{ cell('Image', {arg1: 'a value'}, {strategy: 'esi'}) }}
```

Le 3ème paramètre permet de passer des options à la fonction de rendu de la Cell. Grâce à l'option `strategy` avec la valeur `esi`, la Cell sera rendu en tant que **bloc ESI**.

Utilisation d'un Core Object différente
----------------------------------------

Pour appeler une Cell, on peut également utiliser un Core Object différent afin de fournir à la Cell des **datas** différentes.

```
{{ cell('Image', {arg1: 'a value'}, {co: 'AnotherImageVO'}) }}
```

Voir aussi
-----------

[Le Core Object](core_object.md)
