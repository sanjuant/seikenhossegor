<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\CartItemType;
use AppBundle\Shop\CartItem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/boutique")
 */
class ShopController extends Controller
{
    /**
     * @Route("", name="boutique")
     */
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getRepository(Product\Category::class)->findAllWithProducts();

        return $this->render('@App/Shop/index.html.twig', array(
            'img' => 'assets/img/boutique.jpg',
            'position' => 'center',
            'categories' => $categories,
        ));
    }

    /**
     * @Route("/{id}", name="boutique.product")
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Product $product, Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(Product\Category::class)->findAllWithProducts();

        $cartItem = new CartItem();
        $cartItem->setProduct($product);

        $form = $this->createForm(CartItemType::class, $cartItem, array("product" => $product));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->get('app.cart')->add($cartItem);

            return $this->redirectToRoute('boutique');
        }

        return $this->render('@App/Shop/index.html.twig', array(
            'img' => 'assets/img/boutique.jpg',
            'position' => 'center',
            'cartItem' => $cartItem,
            'categories' => $categories,
            'product' => $product,
            'form' => $form->createView()
        ));
    }
}