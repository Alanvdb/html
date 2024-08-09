# HTML Library

A PHP library for manipulating and querying HTML documents.

## Overview

This project provides a lightweight and efficient PHP library for working with HTML documents. It offers an intuitive API for querying, manipulating, and creating HTML elements, enabling developers to interact with HTML content programmatically. The main components of this library include:

- **HtmlDom**: A class that represents an HTML document and provides methods for querying and manipulating its content.
- **HtmlDomElement**: A class that represents an HTML element and provides methods for manipulating individual elements within the document.
- **HtmlDomFactory**: A factory class for creating instances of `HtmlDom`.

## Installation

To install the `alanvdb/html` library, you can use Composer:

```bash
composer require alanvdb/html
```

## Usage

### HtmlDom

The `HtmlDom` class allows you to load an HTML document and perform various operations such as querying elements by ID, class, or tag name, and selecting elements using CSS selectors.

#### Example

```php
use AlanVdb\Html\HtmlDom;

$htmlContent = '<div id="container" class="main"><p class="text">Hello World</p></div>';
$dom = new HtmlDom($htmlContent);

$element = $dom->getElementById('container');
echo $element->getInnerHtml(); // Outputs: <p class="text">Hello World</p>
```

### HtmlDomElement

The `HtmlDomElement` class represents an individual HTML element and provides methods for manipulating it, such as setting attributes, adding/removing classes, and appending child elements.

#### Example

```php
use AlanVdb\Html\HtmlDomElement;

$element = new HtmlDomElement($dom->getElementById('container'));

$element->addClass('new-class');
$element->setAttribute('data-attr', 'value');

echo $element->getAttribute('class'); // Outputs: main new-class
```

### HtmlDomFactory

The `HtmlDomFactory` class provides a convenient way to create instances of `HtmlDom`. This factory can be used to standardize the creation of HTML documents in larger applications.

#### Example

```php
use AlanVdb\Html\Factory\HtmlDomFactory;

$factory = new HtmlDomFactory();
$dom = $factory->createHtmlDom('<div><p>Hello World</p></div>');

$element = $dom->querySelector('p');
echo $element->getInnerHtml(); // Outputs: Hello World
```

## API Documentation

### HtmlDom

#### Methods

- `getElementById(string $id): HtmlDomElementInterface`
  - Retrieves an element by its ID.

- `getElementsByClassName(string $className): array`
  - Retrieves all elements with the specified class name.

- `getElementsByTagName(string $tagName): array`
  - Retrieves all elements with the specified tag name.

- `querySelector(string $selector): HtmlDomElementInterface`
  - Retrieves the first element that matches the specified CSS selector.

- `querySelectorAll(string $selector): array`
  - Retrieves all elements that match the specified CSS selector.

- `createElement(string $tagName): HtmlDomElementInterface`
  - Creates a new element with the specified tag name.

### HtmlDomElement

#### Methods

- `appendChild(HtmlDomElementInterface $child): self`
  - Appends a child element to the current element.

- `insertBefore(HtmlDomElementInterface $newNode, HtmlDomElementInterface $referenceNode): self`
  - Inserts a new node before the reference node.

- `getInnerHtml(): string`
  - Gets the inner HTML of the element.

- `insertAdjacentHTML(string $position, string $html): self`
  - Inserts HTML at the specified position relative to the current element.

- `setAttribute(string $name, string $value): self`
  - Sets an attribute on the element.

- `getAttribute(string $name): string`
  - Gets the value of an attribute on the element.

- `removeAttribute(string $name): self`
  - Removes an attribute from the element.

- `addClass(string $className): self`
  - Adds a class to the element.

- `removeClass(string $className): self`
  - Removes a class from the element.

- `toggleClass(string $className): self`
  - Toggles a class on the element.

- `remove()`
  - Removes the current element from the DOM.

- `getParent(): ?HtmlDomElementInterface`
  - Gets the parent element of the current element.

- `getChildNodes(): array`
  - Gets the child nodes of the current element.

- `getFirstChild(): ?HtmlDomElementInterface`
  - Gets the first child of the current element.

- `getLastChild(): ?HtmlDomElementInterface`
  - Gets the last child of the current element.

- `getNextSibling(): ?HtmlDomElementInterface`
  - Gets the next sibling of the current element.

- `getPreviousSibling(): ?HtmlDomElementInterface`
  - Gets the previous sibling of the current element.

### HtmlDomFactory

#### Methods

- `createHtmlDom(string $htmlContent): HtmlDomInterface`
  - Creates a new instance of `HtmlDom` with the provided HTML content.

## Testing

To run the tests, use the following command:

```bash
vendor/bin/phpunit
```

The tests are located in the `tests` directory and cover the functionality of `HtmlDom`, `HtmlDomElement`, and `HtmlDomFactory`.

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue to discuss any changes or improvements.

---

Ce README couvre tous les aspects essentiels de votre bibliothèque, y compris l'installation, l'utilisation, la documentation de l'API, les tests, et les informations sur la licence et les contributions.