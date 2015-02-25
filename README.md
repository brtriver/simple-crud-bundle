SimpleCrudBundle
==================================

SimpleCrudBundle is a Symfony2 Bundle.
This bundle provides simple actions for CRUD in your controller by trait.
You indicate templates and entity/repository by `@Crud` annotation:

```php
<?php
namespace Brtriver\SimpleCrudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brtriver\SimpleCrudBundle\Controller\SimpleCrudTraitController;
use Brtriver\SimpleCrudBundle\Annotation\Crud;

/**
 * @Route("/simple_crud", name="simple_crud_demo")
 * @Crud(
 *   entity="Brtriver\SimpleCrudBundle\Entity\Demo",
 *   repository="BrtriverSimpleCrudBundle:Demo",
 *   template_list="BrtriverSimpleCrudBundle:Demo:list.html.twig",
 *   template_new="BrtriverSimpleCrudBundle:Demo:new.html.twig",
 *   template_edit="BrtriverSimpleCrudBundle:Demo:edit.html.twig",
 *   template_show="BrtriverSimpleCrudBundle:Demo:show.html.twig",
 *   form="Brtriver\SimpleCrudBundle\Form\DemoType"
 * )
 */
class DemoController extends Controller
{
    use SimpleCrudTraitController;

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return new Response('hello world');
    }

}

    ...
```

These actions of trait are just templates, so you have to write your logic in your own templates and use `@Crud` annotations.
If you want to overwrite CRUD actions, you just write your `listAction`, `showAction`, `newAction` or `editAction` method.

You can see a demo controller code in `Brtriver\SimpleCrudBundle\Controller\DemoController` class.

Requirements
------------

SimpleCrudBundle works with PHP 5.4.0 or later.

Install
--------

install via packagist
https://packagist.org/packages/brtriver/simple-crud-bundle

```
$ php composer.phar require brtriver/simple-crud-bundle:dev-master
```

Add this bundle to your `app/AppKernel.php`:

```php
public function registerBundles() {
  $bundles = array(
    new Brtriver\SimpleCrudBundle\BrtriverSimpleCrudBundle(),
  );
}
```


Demo
-----

To be able to access demo pages, you need to add its routes to your application’s routing file:

```yml
_simple_crud:
    resource: "@BrtriverSimpleCrudBundle/Resources/config/routing.yml"
    prefix:   /_simple_crud
```

After clearing the caches, you have only to access your browser:

```
http://127.0.0.1:8000/_simple_crud/demo/list
```

If you want to watch new/edit page, you have to configure your database setting and `demo` table, so check the Entity in `Entity/Demo.php`

Usage
-----

## use SimpleCrudTraitController

Add use statement in your controller in which you want to add CRUD method.

```php
use SimpleCrudTraitController;
```

## @Crud Annotation

the behaviors of SimpleCrudTrait are defined as @Crud Annotation.

- `entity`: target Entity class name (e.g. entity="Brtriver\SimpleCrudBundle\Entity\Demo")
- `repository`: target Repository of the Entity (e.g. repository="BrtriverSimpleCrudBundle:Demo")
- `form`: type class of the Entity (e.g. form="Brtriver\SimpleCrudBundle\Form\DemoType")
- `template_list`: list view template path (e.g. template_list="BrtriverSimpleCrudBundle:Demo:list.html.twig")
- `template_new`: new view template path (e.g. template_new="BrtriverSimpleCrudBundle:Demo:new.html.twig")
- `template_show`: show view template path (e.g. template_new="BrtriverSimpleCrudBundle:Demo:show.html.twig")
- `template_edit`: edit view template path (e.g. template_new="BrtriverSimpleCrudBundle:Demo:edit.html.twig")

And write your Entity, Repository, FormType and each Templates in your bundle.
SimpleCrudBundle make you write minimum codes.

## override action methods

If you want to change behavior of each action method, you can simply override each method in your controller:

```php
    /**
     * list page of json
     *
     * @Route("/list")
     */
    public function listAction()
    {
        $entities = $this->getRepository()->findAll();

        return new JsonResponse($entities);
    }
```

License
-------

SimpleCrudBundle is licensed under the MIT license.
