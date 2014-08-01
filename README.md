OpenRepo
========

OpenRepo is a project of a file explorer in PHP using a permission system and the support for multi-users. The aim of the project is to make a clean application easily deployable on any server.

Deploying the application to the server
---------------------------------------
You have to copy install.php and openrepo_res/ on your server and change the chmod of the server directory's to 0777. Then you have to launch install.php from your browser.

You have to fill:
- MySQL parameters
- The administrator's login and password

The permissions
---------------
The differents permissions are:
- View (Read a directory contents)
- Upload (Add files in a directory)
- Delete (Delete files of a directory)
- Administrator (The administrator's permission: manage users, manage directories)
