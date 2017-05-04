Database Maintenance with Doctrine & MySQL
==========================================

This bundle allows you to execute mysql and mysqldump commands without passing any authentication parameters, il will
automatically uses the one declared in doctrine.

## Dumping local database:
```bash
$ bin/console sidus:database:mysqldump > dump.sql
```

## Dumping remote database to local file:
```bash
$ ssh username@host "/path/to/symfony/bin/console sidus:database:mysqldump" > dump.sql
```

## Copying remote database to local:
```bash
$ ssh username@host "/path/to/symfony/bin/console sidus:database:mysqldump" | bin/console sidus:database:mysql
```

## Copying local database to remote:
```bash
$ bin/console sidus:database:mysqldump | ssh username@host "/path/to/symfony/bin/console sidus:database:mysql"
```
