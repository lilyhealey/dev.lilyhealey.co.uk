dev.lilyhealey.co.uk
----------------

installation
------------
apache
1. .htpasswd (for admin)
2. .htaccess (for main app + admin)
3. make media folder with proper permisions (755 should do it)
mysql
4. mysql
	+ make mysql database
	+ make admin, read / write, read-only accounts for db
	+ mysql dump into db with admin account
php
5. config.php
	+ add mysql info
	+ add hostname / rootname





outstanding issues: 
+ delete inc/Request.php ? 
+ deal with error pages
+ test slug function (in lib/lib.php)
+ add functionality to revive deactivated objects?
  - archive --> unlink object
  - delete --> delete object completely
  - currently 'deleted' objects are just deactivated, 
  	though they're effectively deleted because there is no way to revive them.
+ URL problems (make errors more verbose)  
  - DONE: when creating a record
  	check to see that the url is valid based on children of its parents
  - DONE: when editing a record
  	check to see that the url is valid based on all siblings
  - DONE: when linking a record
  	make sure that the link will not result in any conflicts
  - deleting a record is safe
  	because url will just be removed from a (hopefully) consistent state