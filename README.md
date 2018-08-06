 
##Notes

1) Modify public/ini/data_config.php. Only modify user/pass/host.

2) Tests SHOULD create the db and table. There are some simple integration tests to verify only the db structure. (No data tested against the db)

If the first two tests fail, the db is likely not created and there is some permission/login issue.

3) Code to create the db is in public/database/data_setup.php. It is non-destructive. This is called by the tests.

4) I run phpunit as ./vendor/bin/phpunit. YMMV with older versions in the path... Using 7.2.2

5) Working email template is browsable at public/util/check_mailer.php