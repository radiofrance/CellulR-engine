```
  +--------+                  +--------+            +------------------------+      +------------+
  | Client |                  | Finder |            | CoreObjectAndCellCache |      | Collection |
  +---+----+                  +---+----+            +-----------+------------+      +-----+------+
      |                           |                             |                         |
      |                           |                             |                         |
      |   Ask for a Cell or A CO  |                             |                         |
      |      (by shortname)       |                             |                         |
      +--------------------------->                             |                         |
      |                           |                             |                         |
+-----------------------------------------------------------------------------------------+------+
|     |                           |                             |       | If cache doesn't exist |
|     |                           |                             |       +-----------------+------+
|     |                           |                             |                         |      |
|     |                           |                             |                         |      |
|     |                           |        Retrieve data from CoreObject collection       |      |
|     |                           +------------------------------------------------------->      |
|     |                           <-------------------------------------------------------+      |
|     |                           |                             |                         |      |
|     |                           |        Build cache          |                         |      |
|     |                           +----------------------------->                         |      |
|     |                           |                             |                         |      |
+--------------------------------------------------------------------------------------------+
      |                           |                             |                         |
      |                           | Ask by type and shortname   |                         |
      |                           +----------------------------->                         |
      |                           |                             |                         |
      |                           |   return CO or Cell data    |                         |
      |                           <-----------------------------+                         |
      |     Transfer data         |                             |                         |
      <---------------------------+                             |                         |
      |                           |                             |                         |
      |                           |                             |                         |
      +                           +                             +                         +
```