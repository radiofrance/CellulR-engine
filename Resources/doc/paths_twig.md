Les Paths Twig
==============

Les Paths Twig permettent de viser une destination grâce à un alias. Ici les Web Components sont visés en fonction de leur type.
L'utilité ici est de pouvoir étendre un template par exemple.

> NB: Il n'est pas recommandé de les utiliser pour éviter tout couplage entre les Web Components. 

Trois paths Twig sont à disposition:

- `wc`: Pointe sur le répertoire `WebComponent` des Web Components
- `wc_component`: Pointe sur le répertoire `WebComponent/Component` des Web Components
- `wc_page`: Pointe sur le répertoire `WebComponent/Page` des Web Components