<?php

namespace App\Controller;

use App\Entity\SubCategoria;
use App\Entity\Categoria;
use App\Form\SubCategoriaType;
use App\Repository\SubCategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/subcategoria")
 */
class SubCategoriaController extends AbstractController
{
    /**
     * @Route("/", name="sub_categoria_index", methods="GET")
     */
    public function index(SubCategoriaRepository $subCategoriaRepository): Response
    {
        $subCategoriasRepository = $subCategoriaRepository->findAllAsc();

        $subCategorias = [];

        foreach ($subCategoriasRepository as $subCategoria){

            $subCategorias[] = $subCategoria->jsonSerialize();

        }

        return new JsonResponse(
            $subCategorias,
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/new", name="sub_categoria_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $data = json_decode(
            $request->getContent(),
            true
        );
    
        $subCategorium = new SubCategoria();
        $form = $this->createForm(SubCategoriaType::class, $subCategorium);

        $form->submit($data);
        

        if (false === $form->isValid()) {
            return new JsonResponse(
                [
                    'status' => 'error',
                ]
            );
        }

        $em = $this->getDoctrine()->getManager();
            $em->persist($subCategorium);
            $em->flush();

        return new JsonResponse(
                
                    $subCategorium->jsonSerialize()
                ,
                JsonResponse::HTTP_CREATED
            );
    }

    /**
     * @Route("/{id}", name="sub_categoria_show", methods="GET")
     */
    public function show(SubCategoria $subCategorium): Response
    {
       
        $categoria = $this->getDoctrine()
        ->getRepository(Categoria::class)
        ->findOneBy(
            ['id' => $subCategorium->getCategoria()->getId()]
        );


        return $this->render('sub_categoria/show.html.twig', [
            'sub_categorium' => $subCategorium,
            'categoria' => $categoria,
            ]);
    }

    /**
     * @Route("/{id}/edit", name="sub_categoria_edit", methods="GET|POST")
     */
    public function edit(Request $request, SubCategoria $subCategorium): Response
    {

        $categoria = $this->getDoctrine()
        ->getRepository(Categoria::class)
        ->findOneBy(
            ['id' => $subCategorium->getCategoria()->getId()]
        );

        $form = $this->createForm(SubCategoriaType::class, $subCategorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sub_categoria_edit', ['id' => $subCategorium->getId()]);
        }

        return $this->render('sub_categoria/edit.html.twig', [
            'sub_categorium' => $subCategorium,
            'form' => $form->createView(),
            'categoria' => $categoria,
        ]);
    }

    /**
     * @Route("/{id}", name="sub_categoria_delete", methods="DELETE")
     */
    public function delete(Request $request, SubCategoria $subCategorium): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subCategorium->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($subCategorium);
            $em->flush();
        }

        return $this->redirectToRoute('sub_categoria_index');
    }
}
