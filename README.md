Getting Started:

1. Use `composer install` to install the necessary utilities and add the vendor folder.

2. Update the database in the .env to reflect the database you are using. 

3. Either manually create the Database before, or create one with `php bin/console doctrine:database:create`.

4. Use the migration to update your database using `php bin/console doctrine:migrations:migrate`.

5. Start the server using `symfony server:start`.

6. Add your .xml file to the /import_command folder, or just use the provided feed.xml.

7. Enter the command `php bin/console app:import feed.xml` (replace feed.xml with your filename) to execute the command.  

Testing:

1. Create a test-database with `php bin/console --env=test doctrine:database:create`.

2. Create database-schema using `php bin/console --env=test doctrine:schema:create`.

3. Execute the tests with `php bin/phpunit`.


Why to go stateless: 
By going stateless instead of using setters, we make the behavior of drink more predictabe.
In the previous method using setter, it was possible that a setter was failing or missing, leaving us with an incomplete drink-object. This could lead to errors that are difficult to detect down the line, especially in more complex programs.
By going stateless, the object will simply not be created if something wents wrong, meaning we are left with a binary case of no object or a correct object, which makes things more consistent and predictable, and makes it easy to pinpoint the source of problems since we will receive errors as soon as a drink object is required.