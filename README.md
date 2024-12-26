# Shelf
Shelf _(Super Handy Electronic Library Function)_ was designed to manage the inventory of a school library in Uganda. It is designed to be used in an offline environment hence the Codeigniter framework andfd assets such as bootstrap are included in the repository. Composer packages are used for development purposes only, the `vendor` folder is not required to be present for Shelf to work correctly.

## Requirements
* `PHP 8.1` or higher
* PHP extentions: `php-mbstring`, `php-intl` 

## Installation
### Download source
Download the latest release, unzip and point your webserver to `/public` or test using `php spark serve`. The release contains all the necessary files, libraries, frameworks etc. After extracting, you will only need to build the database:
```bash
php spark migrate
```

### Build your own
Alternatively you can build your own shelf. This requires you to install composer packages and run the publisher command to import these packages into shelf and build the database.
```bash
git clone christianberkman/shelf
cd shelf
composer install
php spark publish
php spark migrate
```
You can now serve the `public/` folder or use `php spark serve` to test.

After every `composer update` you will need to run `php spark publish` as well to update the packages into shelf. Shelf does not use any files from the `vendor/` folder as it is built to be as portable as possible.