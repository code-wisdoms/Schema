A simple wrapper to run Laravel migrations in Codeigniter 3 and 4. I created this to make an old project maintainable.

## How to Install

The recommended way to install schema is through
[Composer](https://getcomposer.org/).

```bash
composer require codewisdoms/schema
```

# Example request

```php
use \CodeWisdoms\Schema\Schema;
use \CodeWisdoms\Schema\Table;

Schema::create('my_table', function(Table $table) {
    $table->id();
})
```
