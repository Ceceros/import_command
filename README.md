Getting Started:

1. Use `composer install` to install the necessary utilities and add the vendor folder.

2. Update the database in the .env to reflect the database you are using. 

3. Either manually create the Database before, or create one with `php bin/console doctrine:database:create`.

4. Use the migration to update your database using `php bin/console doctrine:migrations:migrate`.

5. Start the server using `symfony server:start`.

6. Add your .xml file to the /import_command folder, or just use the provided feed.xml.

7. Enter the command `php bin/console app:import feed.xml` (replace feed.xml with your filename) to execute the command.  
