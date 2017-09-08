Les Paths Twig
==============

Les Paths Twig permettent de viser une destination grâce à un alias. Ici les Cells sont visés en fonction de leur type.
L'utilité ici est de pouvoir étendre un template par exemple.

> NB: Il n'est pas recommandé de les utiliser pour éviter tout couplage entre les Cells. 

3 paths Twig sont à disposition:

- `cellulr`: Pointe sur le répertoire `Cell`
- `cellulr_component`: Pointe sur le répertoire `Cell/Component`
- `cellulr_page`: Pointe sur le répertoire `Cell/Page`