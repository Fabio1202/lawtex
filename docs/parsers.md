# Parsers <span style="display:block; opacity: .8; font-size: 1rem;">App\Parsers</span>
<!-- Subtitle -->

<br>
This page demonstrates how to create a parser for a new website and how to register it in the application. It also explains the classes and interfaces that are used to parse laws.

## Available Parsers

<!-- Table of currently available parsers -->
| Parser                         | URL                                                      | Supported law books             |
|--------------------------------|----------------------------------------------------------|---------------------------------|
| `App/Parsers/DSGVOGesetz`      | [dsgvo-gesetz.de](https://dsgvo-gesetz.de)               | DSGVO <br> BDSG <br> DSGVO-ErwG |               
| `App/Parsers/GestzeImInternet` | [gesetze-im-internet.de](https://gesetze-im-internet.de) | All available law books         |

## Create a parser

If you want to add a new parser, you can do so by creating a new parser class in the `App/Parsers` namespace. The class must implement the `ParserInterface` interface. The parser class should be named after the law book it is parsing.

Here is an example of a parser class:


```php
namespace App\Parsers;

use App\Parsers\Base\ParserInterface;

class MyParser implements ParserInterface
{
    public function fullParse(Law $law, Crawler $crawler): ParsedLaw
    {
        // Parse the law
    }

    public function parseInformation(Crawler $crawler): ParsedInformation
    {
        // Parse the information
    }
}
```

The `fullParse` method should return a [`ParsedLaw`](#parsedlaw) object, which contains the parsed law. It is called when a LaTeX document is being created to get all information and paragraphs of the law.
As most laws are structured similiar and the information was already retreived in the `parseInformation` method, you can use the [`FullLawParseFromLaw`](#fulllawparsefromlaw) trait to automate most of the parsing process.

The `parseInformation` method should return a [`ParsedInformation`](#parsedinformation) object, which contains the parsed information about the law. It is called when a user adds a law to one of their projects to get the information about the law.



## Registering Parsers

To register a parser, you need to add it to the `parsers` array in the `config/parsers.php` file. The key should be the domain of the website, and the value should be the fully qualified class name of the parser.

```php
return [
    'dsgvo-gesetz.de' => App\Parsers\DSGVOGesetz::class,
    'gesetze-im-internet.de' => App\Parsers\GesetzeImInternet::class,
    'my-website.com' => App\Parsers\MyParser::class,
];
```

:partying_face: Congratulations! You have successfully created a parser for a new website. You can now parse laws from the website using the `LawParser` class.

## Other classes

### FullLawParseFromLaw

Most laws are structured similiar and the information was already retreived in the `parseInformation` method. To automate most of the parsing process, you can use the `FullLawParseFromLaw` trait. You only need to parse the paragraphs of the law. Here is an example of a parser class using the `FullLawParseFromLaw` trait:

```php
namespace App\Parsers;

use App\Parsers\Base\ParserInterface;
use App\Parsers\Base\FullLawParseFromLaw;

class MyParser implements ParserInterface
{
    use FullLawParseFromLaw;

    public function fullParse(Law $law, Crawler $crawler): ParsedLaw
    {
        $paragraphs = [] // TODO: Parse the paragraphs of the law
        
        return $this->autoFullParse($law, $paragraphs);
    }

    public function parseInformation(Crawler $crawler): ParsedInformation
    {
        // Parse the information
    }
}
```

The method `autoFullParse` will automatically parse the law using the information from the database, retrieved during the `parseInformation` call before, and the paragraphs of the law.

### ParsedLaw

The `ParsedLaw` class is a data transfer object that contains the parsed law. It has the following properties:

```php
public function __construct(
    string $section, 
    string $title, 
    array $paragraphs, 
    string $url, 
    string $lawBookSlug, 
    string $lawBookShort, 
    string $lawBookTitle, 
    string $prefix = '§'
) {...}
```

### ParsedInformation

The `ParsedInformation` class is a data transfer object that contains the parsed information about the law. It has the following properties:

```php
public function __construct(
    string $lawBookTitle, 
    string $lawBookSlug, 
    string $lawSlug, 
    string $lawPrefix, 
    string $lawTitle, 
    string $lawUrl
) {...}
```
### LawParser

The `LawParser` class is a helper class that provides methods to parse laws. It is the central class for parsing laws, as it decides which parser to use based on the URL of the law book.

It can be used to parse laws like this:

```php
$parser = new LawParser();

// To retrieve all information, including the paragraphs
Law $law = Law::first();
$parser->fullParse($law);

// To retrieve only the information about the law
$url = 'https://gesetze-im-internet.de/urhg/__1.html';
$parser->parseInformation($url);


```
