# Flattener

A lib for (un)flatten php data structure and a small cli for apply this on Json input.

## Usage

### Lib

Basically, `Flattener::flatten(...)` turn this

```php
<?php

[
  'hello' => [
    'world' => [
      '!', '?'
    ],
    'people' => [
      1, 2.3 
    ],
  ],
  'bye' => null
]
```

into

```php
<?php 

[
  '.hello.world[0]' => '!',
  '.hello.world[1]' => '?',
  '.hello.people[0]' => 1,
  '.hello.people[1]' => 2.3,
  '.bye' => null,
]
```

(And `Flattener::unflatten(...)` will play this backward)

### CLI


Get help

```shell
bin/json-unflatten --help
```

## Dev

### Coverage

```shell
make coverage
````
