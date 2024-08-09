# AlanVdb / HTML Library

## Overview

`alanvdb/html` is a PHP library designed for working with HTML documents in a structured and object-oriented way. It provides an easy-to-use API for querying, manipulating, and creating HTML elements, enabling you to build and manage HTML documents programmatically.

## Features

- **Object-Oriented HTML Manipulation**: Interact with HTML elements using a simple and intuitive object-oriented API.
- **XPath Support**: Use XPath selectors for advanced querying of HTML elements.
- **Element Creation and Insertion**: Easily create, append, insert, and remove HTML elements.
- **CSS Selector Support**: Query elements using familiar CSS selectors.
- **Factory Pattern**: Leverage factory patterns for easy instantiation and dependency management.
- **Flexible and Extendable**: Designed to be extended and customized to meet specific project requirements.

## Installation

You can install this package via Composer:

```bash
composer require alanvdb/html
```

For development purposes, you can install the development dependencies as well:

```bash
composer install --dev
```

## Usage

### Basic Usage

Here is an example of how to use the `HtmlDom` class to load an HTML document and manipulate its elements:

```php
require 'vendor/autoload.php';

use AlanVdb\Html\HtmlDom;

// Load HTML content
$htmlContent = '<div id="main"><p class="text">Hello World!</p></div>';
$dom = new HtmlDom($htmlContent);

// Query an element by ID
$element = $dom->getElementById('main');
echo $element->getInnerHtml(); // Outputs: <p class="text">Hello World!</p>
```

### Querying Elements

You can query elements using CSS selectors or by their attributes:

```php
// Get elements by class name
$paragraphs = $dom->getElementsByClassName('text');

// Get elements by tag name
$divs = $dom->getElementsByTagName('div');

// Query using CSS selectors
$firstParagraph = $dom->querySelector('.text');
$allParagraphs = $dom->querySelectorAll('p');
```

### Manipulating Elements

Create, insert, and manipulate HTML elements easily:

```php
// Create a new element
$newDiv = $dom->createElement('div');
$newDiv->setAttribute('id', 'new-div');

// Append the new element to an existing one
$element->appendChild($newDiv);

// Insert HTML content
$newDiv->insertAdjacentHTML('beforeend', '<p>New paragraph inside new div.</p>');
```

### Using the Factory Pattern

You can create instances of `HtmlDom` using a factory pattern, allowing for better dependency management:

```php
use AlanVdb\Html\Factory\HtmlDomFactory;

$factory = new HtmlDomFactory();
$dom = $factory->createHtmlDom('<div>Hello Factory!</div>');
```

## Running Tests

To run the tests, you need to install the development dependencies first:

```bash
composer install --dev
```

Then, you can run the tests using PHPUnit:

```bash
vendor/bin/phpunit --testdox
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
