## Craft and Keen CMS

Symfony3 Based CMS System. Supports:
* Multisites
* Translations
* User Management
* Page In-line editor
* etc. No time for this.

### Installation
##### Deploy Git project, user develop branch for latest code.

```
git checkout develop && git pull
```

##### Create Proper Virtual hosts anв update your local env. hosts file (no need on production), assume you have a **ck.cms.lo** domain:

```
127.0.0.1	ck.cms.lo
127.0.0.1	www.ck.cms.lo test.ck.cms.lo mikalai.ck.cms.lo hohoho.ck.cms.lo 
```

##### Example of Apache Host File:

```
<VirtualHost *:80>
    DocumentRoot "c:\Users\projects\ck_cms\web"
    ServerName ck.cms.lo
    ServerAlias *.ck.cms.lo
 
    <Directory "c:\Users\projects\ck_cms\web">
        AllowOverride All
        Require all Granted
    </Directory>

    ErrorLog "c:\Users\projects\logs\ck.cms.lo-error.log"
    CustomLog "c:\Users\projects\logs\ck.cms.lo-access.log" common

</VirtualHost>
```

##### Then just run :

```
sh reinstall.sh
``` 
