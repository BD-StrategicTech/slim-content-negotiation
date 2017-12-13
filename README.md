# Slim Content Negotiation Middleware

## Installation

`composer require budgetdumpster/slim-content-negotiation:1.0.0`

## Usage

The Content Negotiation Middleware can be easily attached to a slim route like so:

```php
<?php

use BudgetDumpster\Middleware\ContentNegotiation;

$app->get('/', function($request, $response, $args) {
    echo 'hello world';
})->add(new ContentNegotiationHalJson);
```

This will reject any requests that don't have content-types that match
the provided content types.
