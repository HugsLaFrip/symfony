<?php

namespace App\Controller;

use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController
{
    private $twig;

    public function __construct(Slugify $slugify, Environment $twig)
    {
        $this->twig = $twig;
    }
    /** 
     * @Route("/hello/{prenom?world}", name="hello")
     */
    public function sayHello(Request $request) : Response
    {
        $prenom = $request->attributes->get("prenom", 'world');

        $html = $this->twig->render("hello.html.twig", [
            "prenom" => $prenom
        ]);

        return new Response($html);
    }
}