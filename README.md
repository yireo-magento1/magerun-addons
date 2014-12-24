Yireo MageRun Addons
====================
This project contains additional commands for N98-MageRun.

* List / create / reset / delete admin roles

Installation
------------
You can check out the different options in the [MageRun docs](http://magerun.net/introducting-the-new-n98-magerun-module-system/).

Here is a manual method:

1. Create ~/.n98-magerun/modules/ if it doesn't already exist.

        mkdir -p ~/.n98-magerun/modules/

2. Clone the magerun-addons repository in there

        cd ~/.n98-magerun/modules/
        git clone git@github.com:yireo/magerun-addons.git

Commands
--------

### Admin Roles ###

List all backend roles:

    $ n98-magerun.phar admin:role:list

Create a new backend role with ALL privileges:
    
    $ n98-magerun.phar admin:role:create

Reset privileges of a certain backend role to ALL privileges:

    $ n98-magerun.phar admin:role:reset

Delete a certain backend role:

    $ n98-magerun.phar admin:role:delete

