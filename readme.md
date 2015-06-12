lilyhealey.co.uk
----------------

outstanding issues: 
+ combine reusable parts of inc/URL.php and admin/inc/URL.php
+ fix parents() in admin/inc/URL.php
+ delete inc/Request.php? 
+ deal with error pages
+ test slug function (in lib/lib.php)

+ add functionality to revive deactivated objects?
  - archive --> unlink object
  - delete --> delete object completely
  - currently 'deleted' objects are just deactivated, though they're effectively deleted because there is no way to revive them.
  
+ URL problems
  - when creating a record, check to see that the url is valid based on children of its parents
  - when editing a record, check to see that the url is valid based on all siblings
  - when linking a record, make sure that the link will not result in any conflicts
  - deleting a record is safe, because url will just be removed from a (hopefully) consistent state
  