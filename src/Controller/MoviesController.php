<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
class MoviesController extends AbstractController
{

    #create a constractor
    private $movieRepository;
    private $em;

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $em)
    {
        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }

    #[Route('/movies', methods:['GET'],name: 'app_movies')]
    public function index(): Response
    {
        //findAll() -- select * from movies;
        //find() -- select * from movies where id = 5; find(5)
        //findBy() -- select * from movies where title = 'something'; find([], ['id'=>'DESC'])
        //findOneBy() -- select * from movies where id = 6 and title = 'The Dark Knight' order by id DESC; findOneBy(['id'=>1, 'title'=>'The Dark Knight'], ['id'=>'DESC']) 
        //count([])
        //getClassName()
        //$repository = $this->em->getRepository(Movie::class);
        //$movies = $repository->findAll();    
        //dd($movies);

        $movies = $this->movieRepository->findAll();
        
        return $this->render('movies/index.html.twig', ['movies'=>$movies]);
    }


    #[Route('/movies/create', name: 'create_movie')]
    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class, $movie);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newMovie = $form->getData();
            $imagePath = $form->get('imagePath')->getData();
            
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
               
                $newMovie->setImagePath('/uploads/' . $newFileName);
            }

            $this->em->persist($newMovie);
            $this->em->flush();

            return $this->redirectToRoute('app_movies');
        }

        return $this->render('movies/create.html.twig', [
            'form' => $form->createView()
        ]);
    }




    #[Route('/movies/{id}', methods:['GET'],name:'app_movies_show')]
    public function show($id):Response
    {
        $movie = $this->movieRepository->find($id);
        return $this->render('movies/show.html.twig', ['movie'=>$movie]);
    }

   
}
