<?php

namespace Acme\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\StoreBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    public function createAction()
    {
        $product = new Product();
        $product->setName('A Foo Bar');
        $product->setPrice('19.99');
        $product->setDescription('Lorem ipsum dolor');

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response('Created product id '.$product->getId());
    }

    public function showAction($id)
    {
        $product = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Product')
            ->find($id);

        //$categoryName = $product->getCategory()->getName();

        /*$product = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Product')
            ->findOneByIdJoinedToCategory($id);

        $category = $product->getCategory();*/

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        //return new Response('Created product id '.$product->getId());
        return $this->render(
            'AcmeStoreBundle:Store:show.html.twig',
            array('product' => $product));
    }

    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AcmeStoreBundle:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $em->flush();

        return $this->redirect($this->generateUrl('acme_home'));
    }

    public function listAction()
    {
    //    $em = $this->getDoctrine()->getManager();
    //    $product = $em->getRepository('AcmeStoreBundle:Product')
    //        ->findAllOrderedByName();

    //    $product = $this->getDoctrine()
    //        ->getRepository('AcmeStoreBundle:Product')
    //        ->findAll();

    //    $em = $this->getDoctrine()->getManager(); //return good with price more than entered value
    //    $query = $em->createQuery(
    //        'SELECT p
    //        FROM AcmeStoreBundle:Product p
    //        WHERE p.price > :price
    //        ORDER BY p.price ASC'
    //    )->setParameter('price', '11,99');
    //    $product = $query->getResult();

        $repository = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Product');

        $query = $repository->createQueryBuilder('p')
            ->where('p.price > :price')
            ->setParameter('price', '10.99')
            ->orderBy('p.id', 'ASC')
            ->getQuery();

        $product = $query->getResult();

        return $this->render(
            'AcmeStoreBundle:Store:list.html.twig',
            array('product' => $product));
    }
}