<?php
namespace Brtriver\SimpleCrudBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Crud extends Annotation
{
    public $entity;
    public $repository;
    public $template_list;
    public $template_new;
    public $template_edit;
    public $template_show;
    public $form;
}