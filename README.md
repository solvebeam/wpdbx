# SolveBeam WpdbX

Exception-based wrapper around WordPress `$wpdb` for modern PHP usage.

## Installation

```bash
composer require solvebeam/wpdbx
```

## Usage

```php
<?php

use SolveBeam\WpdbX\WpdbAdapter;

global $wpdb;

$wpdbx = new WpdbAdapter( $wpdb );

try {
	$wpdbx->query( '…' );
} catch ( \Exception $e ) {
	\wp_die( $e->getMessage() );
}

```

## Links

- https://github.com/solvebeam/wpdbx
- https://packagist.org/packages/solvebeam/wpdbx
- https://spdx.org/licenses/GPL-3.0-or-later.html
- https://www.solvebeam.com/
