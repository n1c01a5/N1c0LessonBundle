n1c0LessonBundle
======================

Bundle to manage lessons.

Step 1: Setting up the bundle
-----------------------------

### A) Download and install N1c0Lesson

To install N1c0Lesson run the following command

``` bash
$ php composer.phar require n1c01a5/n1c0lesson-bundle
```

### B) Enable the bundle

Enable the required bundles in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FOS\RestBundle\FOSRestBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
        new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
        new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        new N1c0\LessonBundle\N1c0LessonBundle(),
    );
}
```
FOSRestBundle, StofDoctrineExtensionsBundle and NelmioApiDocBundle must be configured.
This bundle require the Diff implementation for PHP: "sebastian/diff": "*" (``composer.json``).

### C) Enable Http Method Override

[Enable HTTP Method override as described here](http://symfony.com/doc/master/cookbook/routing/method_parameters.html#faking-the-method-with-method)

As of symfony 2.3, you just have to modify your config.yml :

``` yaml
# app/config/config.yml

framework:
    http_method_override: true
```
    

Step 2: Setup Doctrine ORM mapping
----------------------------------

The ORM implementation does not provide a concrete Lesson class for your use, you must create one. This can be done by extending the abstract entities provided by the bundle and creating the appropriate mappings.

For example, the lesson entity:

``` php
<?php
// src/MyProject/MyBundle/Entity/Lesson.php

namespace MyProject\MyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use N1c0\LessonBundle\Entity\Lesson as BaseLesson;

/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Lesson extends BaseLesson
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;
}
```
For example, the argument entity:

``` php
<?php
// src/MyProject/MyBundle/Entity/Argument.php

namespace MyProject\MyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use N1c0\LessonBundle\Entity\Argument as BaseArgument;

/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Argument extends BaseArgument
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * Lesson of this argument
     *
     * @var Lesson 
     * @ORM\ManyToOne(targetEntity="MyProject\MyBundle\Entity\Argument")
     */
    protected $lesson;
}
```

Add in app/config/config.yml:
``` yaml
# N1c0LessonBundle
n1c0_lesson:
    db_driver: orm
    class:
        model:
            lesson: MyProject\MyBundle\Entity\Lesson
            argument: MyProject\MyBundle\Entity\Argument

entity_managers:
            default:
                mappings:
                    N1c0LessonBundle: ~
                    MyBundleMyProjectBundle: ~

assetic:
    bundles:        ["N1c0LessonBundle"]

```

Step 3: Import N1c0LessonBundle routing files
---------------------------------------------------

```
# /app/config/routing.yml
n1c0_lesson:
    type: rest
    prefix: /api
    resource: "@N1c0Lesson/Resources/config/routing.yml"
```

Content negociation
-------------------

Each ressource is accessible into different formats.

HTTP verbs:

For the lessons:

GET:

In html format:
```
curl -i localhost:8000/api/v1/lessons/10
```

In json format:
```
curl -i -H "Accept: application/json" localhost:8000/api/v1/lessons/10
```

POST:

In html format:
```
curl -X POST -d "n1c0_lesson_lesson%5Btitle%5D=myTitle&n1c0_lesson_lesson%5Bbody%5D=myBody" http://localhost:8000/api/v1/lessons
```

In json format:
```
curl -X POST -d '{"n1c0_lesson_lesson":{"title":"myTitle","body":"myBody"}}' http://localhost:8000/api/v1/lessons.json --header "Content-Type:application/json" -v
```
PUT:

In json format:
```
curl -X PUT -d '{"n1c0_lesson_lesson":{"title":"myNewTitle","body":"myNewBody http://localhost:8000/api/v1/lessons/10 --header "Content-Type:application/json" -v
```
For the arguments:

GET:

In json format:
```
curl -i -H "Accept: application/json" localhost:8000/api/v1/lessons/10/arguments
```
POST:

In json format:
```
curl -X POST -d '{"n1c0_lesson_argument":{"title":"myTitleArgument","body":"myBodyArgument"}}' http://localhost:8000/api/v1/lessons/10/arguments.json --header "Content-Type:application/json" -v
```
PUT:

In json format:
```
curl -X PUT -d '{"n1c0_lesson_argument":{"title":"myNewTitleArgument","body":"myNewBodyArgument"}}' http://localhost:8000/api/v1/lessons/10/arguments/11.json --header "Content-Type:application/json" -v 
```
PATCH:

In json format:
```
curl -X PATCH -d '{"n1c0_lesson_argument":{"title":"myNewTitleArgument"}}' http://localhost:8000/api/v1/lessons/10/arguments/11.json --header "Content-Type:application/json" -v
```
HATEOAS REST
============

Introduction of the HATEOAS constraint.
```
{
    "user": {
        "id": 10,
        "title": "myTitle",
        "body": "MyBody",
        "_links": {
            "self": { "href": "http://localhost:8000/api/v1/lessons/10" }
        }
    }
}
```

Integration with FOSUserBundle
==============================
By default, lessons are made anonymously.
[FOSUserBundle](http://github.com/FriendsOfSymfony/FOSUserBundle)
authentication can be used to sign the lessons.

### A) Setup FOSUserBundle
First you have to setup [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle). Check the [instructions](https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Resources/doc/index.md).

### B) Extend the Lesson class
In order to add an author to a lesson, the Lesson class should implement the
`SignedLessonInterface` and add a field to your mapping.

For example in the ORM:

``` php
<?php
// src/MyProject/MyBundle/Entity/Lesson.php

namespace MyProject\MyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use N1c0\LessonBundle\Entity\Lesson as BaseLesson;
use N1c0\LessonBundle\Model\SignedLessonInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 */
class Lesson extends BaseLesson implements SignedLessonInterface
{
    // .. fields

    /**
     * Authors of the lesson
     *
     * @ORM\ManyToMany(targetEntity="Application\UserBundle\Entity\User")
     * @var User
     */
    protected $authors;

    public function __construct()
    {
        $this->authors = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add author 
     *
     * @param Application\UserBundle\Entity\User $user
     */
    public function addAuthor(\Application\UserBundle\Entity\User $user)
    {
        $this->authors[] = $user;
    }

    /**
     * Remove user
     *
     * @param Application\UserBundle\Entity\User $user
     */
    public function removeUser(\Application\UserBundle\Entity\User $user)
    {
        $this->authorss->removeElement($user);
    }

    public function getAuthors()
    {
        return $this->authors;
    }

    public function getAuthorsName()
    {
        return $this->authors ?: parent::getAuthorsName(); 
    }
}
```

Step 7: Adding role based ACL security
======================================

**Note:**

> This bundle ships with support different security setups. You can also have a look at [Adding Symfony2's built in ACL security](8-adding_symfony2s_builtin_acl_security.md).

LessonBundle also provides the ability to configure permissions based on the roles
a specific user has. See the configuration example below for how to customise the
default roles used for permissions.

To configure Role based security override the Acl services:

``` yaml
# app/config/config.yml

n1c0_lesson:
    acl: true
    service:
        acl:
            lesson:  n1c0_lesson.acl.lesson.roles
        manager:
            lesson:  n1c0_lesson.manager.lesson.acl
```

To change the roles required for specific actions, modify the `acl_roles` configuration
key:

``` yaml
# app/config/config.yml

n1c0_lesson:
    acl_roles:
        lesson:
            create: IS_AUTHENTICATED_ANONYMOUSLY
            view: IS_AUTHENTICATED_ANONYMOUSLY
            edit: ROLE_ADMIN
            delete: ROLE_ADMIN
```

Using a markup parser
=====================

N1c0Lesson bundle allows a developer to implement RawLessonInterface, which
will tell the bundle that your lessons are to be parsed for a markup language.

You will also need to configure a rawBody field in your database to store the parsed lessons.

```php
use N1c0\LessonBundle\Model\RawLessonInterface;

class Lesson extends BaseLesson implements RawLessonInterface
{
    /**
     * @ORM\Column(name="rawBody", type="text", nullable=true)
     * @var string
     */
    protected $rawBody;
    
    ... also add getter and setter as defined in the RawLessonInterface ...
}
```

When a comment is added, it is parsed and setRawBody() is called with the raw version 
of the comment which is then stored in the database and shown when the lesson is later rendered.

Any markup language is supported, all you need is a bridging class that
implements `Markup\ParserInterface` and returns the parsed result of a lesson
in raw html to be displayed on the page.

To set up your own custom markup parser, you are required to define a service
that implements the above interface, and to tell N1c0LessonBundle about it,
adjust the configuration accordingly.

``` yaml
# app/config/config.yml

n1c0_lesson:
    service:
        markup: your_markup_service
```

For example using the Sundown PECL extension as Markup service
==============================================================

The markup system in N1c0LessonBundle is flexible and allows you to use any
syntax language that a parser exists for. PECL has an extension for markdown
parsing called Sundown, which is faster than pure PHP implementations of a
markdown parser.

N1c0LessonBundle doesnt ship with a bridge for this extension, but it is
trivial to implement.

First, you will need to use PECL to install Sundown. `pecl install sundown`.

You will want to create the service below in one of your application bundles.

``` php
<?php
// src/Vendor/LessonBundle/Markup/Sundown.php

namespace Vendor\LessonBundle\Markup;

use N1c0\LessonBundle\Markup\ParserInterface;
use Sundown\Markdown;

class Sundown implements ParserInterface
{
    private $parser;

    protected function getParser()
    {
        if (null === $this->parser) {
            $this->parser = new Markdown(
                new \Sundown\Render\HTML(array('filter_html' => true)),
                array('autolink' => true)
            );
        }

        return $this->parser;
    }

    public function parse($raw)
    {
        return $this->getParser()->render($raw);
    }
}
```

And the service definition to enable this parser bridge

``` yaml
# app/config/config.yml

services:
    # ...
    markup.sundown_markdown:
        class: Vendor\LessonBundle\Markup\Sundown
    # ...

n1c0_lesson:
    # ...
    service:
        markup: markup.sundown_markdown
    # ...
```

An other example, using Pandoc as Markup service
================================================

Pandoc is a Haskell program that allows you to convert documents from one format to another. See more in [Pandoc](http://johnmacfarlane.net/pandoc/index.html).

To install Pandoc run this following command
``` bash
$ apt-get install pandoc
```
For more information on the installation of Pandoc, see [Pandoc installation](http://johnmacfarlane.net/pandoc/installing.html).

And we need a naive PHP Wrapper.
The recommended method to installing Pandoc PHP is with [composer](http://getcomposer.org)

```json
{
    "require": {
        "ryakad/pandoc-php": "dev-master"
    }
}
```
Once installed you can create a service markup like

``` php
<?php

namespace vendor\LessonBundle\Markup;

use N1c0\LessonBundle\Markup\ParserInterface;
use Pandoc\Pandoc;

class MarkupPandoc implements ParserInterface
{
    private $parser;

    protected function getParser()
    {
        if (null === $this->parser) {
            $this->parser = new Pandoc();        
        }

        return $this->parser;
    }

    public function parse($raw)
    {
        return $this->getParser()->convert($raw, "markdown", "html");
    }
}
```
And the service definition to enable this parser bridge

``` yaml
# app/config/config.yml

services:
    # ...
    markup.pandoc_markdown:
        class: Vendor\LessonBundle\Markup\MarkupPandoc
    # ...

n1c0_lesson:
    # ...
    service:
        markup: markup.pandoc_markdown
    # ...
```


Integration with FOSCommentBundle
---------------------------------

Add in ```src/MyProject/MyBundle/Resources/views/Lesson/getLessons.html.twig```:
```
<a href="{{ path('api_1_get_lesson_thread', {'id': lesson.id}) }}">Commentaires</a>
```

Documentation as bonus (NelmioApiDocBundle)
-------------------------------------------

Go to http://localhost:8000/api/doc.
