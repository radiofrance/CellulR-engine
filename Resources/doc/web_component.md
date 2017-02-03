Le Web Component
================

Un Web Component rassemble:
- ses dépendances et sa définition
- les templates
- les assets
- la View Object

Les types
---------

Un Web Component possède un type. Il doit être classée selon son type dans un répertoire associés.
Il existe actuellement deux types: ***Page*** et ***Component***.

Exemple
-------

Voici un exemple d'un Web Component `Image` de type `Component`

```
 src/
 .... /WebComponent
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

Le type ***Page*** est un regroupement de Web Components et est accessible depuis une route. Il répond donc à un besoin fonctionnel et structurel.

Le type ***Component*** est unitaire. Il peut aussi être un regroupement de Web Components mais ne répond seulement qu'à un besoin fonctionnel.

Nomenclature
------------

Un Web Component est référencé par son nom. Ce nom est utilisé pour stocker la View Object et le corps du Web Component.
Quelques règles sont à respecter:

- Le Web Component est encapsulé par le dossier portant son nom: WebComponent/***Image***
    - Le nom doit commencer par une majuscule
    - Le nom doit être en CamelCase
    - Le nom ne doit pas contenir de caractères spéciaux ou de ponctuation

- Son template doit porter son nom:
    - En minuscule
    - En snake_case
    - Avec l'extension .html.twig

Le fichier de dépendances
-------------------------

Chaque Web Component doit possèder un fichier de dépendance et de définition `component.json`.
Ce fichier permet de relier les assets des Web Components entre eux et de les prendre en compte lors de la compilation.

Voici l'exemple du Web Component `Image` de type `Component` 

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
- `name`: ***[string]*** Le Nom du Web Component
- `description`: ***[string]*** La description du Web Component
- `master`: ***[boolean]*** Un Web Component master se place en haut de l'arbre de dépendances
- `require`: ***(required) [array]*** Liste les dépendances du Web Component:
    - `"NomDuWebComponent": "*"` //TODO La version est à venir

Appel d'un Web Component
------------------------

L'appel d'un Web Component se fait :

- Grâce à son nom au travers d'une fonction twig `web_component`. Il est possible de lui passer des paramètres directement envoyés à sa View Object.

```
{{ web_component('Image', {request: app.request}) }}
```

- Grâce à son URL d'accès si celui-ci a définit une route dans sa View Object.

Web Component as ESI
--------------------

Pour permettre a un composant de se comporter comme un **bloc ESI**, il suffit de l'appeler comme suit : 

```
{{ web_component('Image', {arg1: 'a value'}, {strategy: 'esi'}) }}
```

Le 3ème paramètre permet de passer des options à la fonction de rendu du composant. Grâce à l'option `strategy` avec la valeur `esi`, le composant sera rendu en tant que **bloc ESI**.

Voir aussi
-----------

[La View Object](view_object.md)