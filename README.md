Doctrine Postgres Bundle
========================

**NOTE:** For legacy PHP support (7.2+) please use the 1.0 branch.

This bundle provides Doctrine support for some specific PostgreSQL 9.4+ features for Symfony projects:

- Support of JSONB and some array data-types (at present only integers, TEXT and JSONB)
- Implementation of the most commonly used functions and operators when working with array and JSON data-types
Functions for text search

Libraries used:

-  [martin-georgiev/postgresql-for-doctrine][georgiev]

Installation
------------

### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
$ composer require camelot/doctrine-postgres-bundle
```

### Applications that don't use Symfony Flex

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
composer require camelot/doctrine-postgres-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Camelot\DoctrinePostgres\CamelotDoctrinePostgresBundle::class => ['all' => true],
];
```

Using
-----

### Available DBAL Types

 - `jsonb`
 - `jsonb[]`
 - `smallint[]`
 - `integer[]`
 - `bigint[]`
 - `text[]`

### Available String Functions

 - `ALL_OF`
 - `ANY_OF`
 - `ARRAY_APPEND`
 - `ARRAY_CARDINALITY`
 - `ARRAY_CAT`
 - `ARRAY_DIMENSIONS`
 - `ARRAY_LENGTH`
 - `ARRAY_NUMBER_OF_DIMENSIONS`
 - `ARRAY_PREPEND`
 - `ARRAY_REMOVE`
 - `ARRAY_REPLACE`
 - `ARRAY_TO_JSON`
 - `ARRAY_TO_STRING`
 - `CAST()`
 - `CONTAINS`
 - `DATE_PART()`
 - `GREATEST`
 - `ILIKE`
 - `IN_ARRAY`
 - `IS_CONTAINED_BY`
 - `JSON_ARRAY_LENGTH`
 - `JSONB_ARRAY_ELEMENTS`
 - `JSONB_ARRAY_ELEMENTS_TEXT`
 - `JSONB_ARRAY_LENGTH`
 - `JSONB_EACH`
 - `JSONB_EACH_TEXT`
 - `JSONB_EXISTS`
 - `JSONB_INSERT`
 - `JSONB_OBJECT_KEYS`
 - `JSONB_SET`
 - `JSONB_STRIP_NULLS`
 - `JSON_EACH`
 - `JSON_EACH_TEXT`
 - `JSON_GET_FIELD`
 - `JSON_GET_FIELD_AS_INTEGER`
 - `JSON_GET_FIELD_AS_TEXT`
 - `JSON_GET_OBJECT`
 - `JSON_GET_OBJECT_AS_TEXT`
 - `JSON_OBJECT_KEYS`
 - `JSON_STRIP_NULLS`
 - `LEAST`
 - `MAKE_DATE()`
 - `OVERLAPS`
 - `STRING_TO_ARRAY`
 - `TO_CHAR()`
 - `TO_JSON`
 - `TO_JSONB`
 - `TO_TSQUERY`
 - `TO_TSVECTOR`
 - `TSMATCH`

See [Common errors when using ILIKE, CONTAINS, IS_CONTAINED_BY and other operator-like functions][doc-use-case]
for tip(s) on using the functions.

[georgiev]: https://github.com/martin-georgiev/postgresql-for-doctrine
[doc-symfony]: https://github.com/martin-georgiev/postgresql-for-doctrine/blob/master/docs/INTEGRATING-WITH-SYMFONY.md
[doc-use-case]: https://github.com/martin-georgiev/postgresql-for-doctrine/blob/master/docs/USE-CASES-AND-EXAMPLES.md
