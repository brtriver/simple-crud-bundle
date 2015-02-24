<?php

namespace Brtriver\SimpleCrudBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait SimpleCrudTraitController
{
    private $crudAnnotations = null;

    /**
     * get Crud annotations on the controller
     *
     * @return null|object
     * @throws \Exception if annotation is not defined on the controller
     */
    private function getCrudAnnotations()
    {
        if (is_null($this->crudAnnotations)) {
            $reader = new AnnotationReader();
            $this->crudAnnotations = $reader->getClassAnnotation(new \ReflectionClass($this), 'Brtriver\\SimpleCrudBundle\\Annotation\\Crud');
            if(!$this->crudAnnotations) {
                throw new \Exception(<<<EOL
Not found @Crud annotation to class,
like @Crud(repository="BrtriverSimpleCrudBundle:Demo", template_list="BrtriverSimpleCrudBundle:Demo:list.html.twig"), ...
see Brtriver\SimpleCrudBundle\README.md
EOL
);
            }
        }

        return $this->crudAnnotations;
    }

    /**
     * get the repository by Crud annotation
     *
     * @return \Doctrine\ORM\EntityRepository
     * @throws \Exception
     */
    private function getRepository()
    {
        $annotations = $this->getCrudAnnotations();

        return $this->getDoctrine()->getRepository($annotations->repository);
    }

    /**
     * @Route("/list")
     * @Route("/")
     */
    public function listAction()
    {
        $annotations = $this->getCrudAnnotations();
        $template = ($annotations->template_list)? $annotations->template_list: 'BrtriverSimpleCrudBundle:Demo:list.html.twig';
        $entities = $this->getRepository()->findAll();

        return $this->render($template, ['entities' => $entities]);
    }

    /**
     * @Route("/new")
     */
    public function newAction(Request $request)
    {
        $annotations = $this->getCrudAnnotations();
        $template = ($annotations->template_new)? $annotations->template_new: 'BrtriverSimpleCrudBundle:Demo:new.html.twig';
        $formTypeClass = ($annotations->form)? $annotations->form: 'Brtriver\SimpleCrudBundle\Form\DemoType';
        $entityClass = ($annotations->entity)? $annotations->entity: 'Brtriver\SimpleCrudBundle\Entity\Demo';
        $form = $this->createForm(new $formTypeClass, new $entityClass);
        $form->handleRequest($request);
        if ($form->isValid() && $this->insert($form) === true) {
            $pathInfo = sprintf('%s/list', dirname($request->getPathInfo()));
            return $this->redirect($this->generateUrl($this->guessRouteName($pathInfo)));
        }

        return $this->render($template, ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"})
     */
    public function editAction(Request $request, $id)
    {
        $annotations = $this->getCrudAnnotations();
        $template = ($annotations->template_edit)? $annotations->template_edit: 'BrtriverSimpleCrudBundle:Demo:edit.html.twig';
        $formTypeClass = ($annotations->form)? $annotations->form: 'Brtriver\SimpleCrudBundle\Form\DemoType';
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw new NotFoundHttpException(sprintf('not found entity %d', $id));
        }

        $form = $this->createForm(new $formTypeClass, $entity);
        $form->handleRequest($request);
        if ($form->isValid() && $this->update($form) === true) {
            $pathInfo = sprintf('%s/list', dirname(dirname($request->getPathInfo())));
            return $this->redirect($this->generateUrl($this->guessRouteName($pathInfo)));
        }

        return $this->render($template, ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Route("/{id}/show", requirements={"id": "\d+"})
     */
    public function showAction(Request $request, $id)
    {
        $annotations = $this->getCrudAnnotations();
        $template = ($annotations->template_show)? $annotations->template_show: 'BrtriverSimpleCrudBundle:Demo:show.html.twig';
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw new NotFoundHttpException(sprintf('not found entity %d', $id));
        }

        return $this->render($template, ['entity' => $entity]);
    }

    /**
     * insert new Entity
     * @param $form
     * @return bool
     */
    protected function insert($form)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($form->getData());
        $em->flush();

        return true;
    }

    /**
     * update Entity
     * @param $form
     * @return bool
     */
    protected function update($form)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($form->getData());
        $em->flush();

        return true;
    }

    /**
     * @param string $pathInfo : like `hoge/list`
     * @return string
     */
    protected function guessRouteName($pathInfo)
    {
        $match = $this->get('router')->match($pathInfo);

        return (isset($match['_route']))? $match['_route']: 'homepage';
    }

}
