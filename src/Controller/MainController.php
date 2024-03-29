<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Scene1;
use App\Repository\Scene1Repository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('main/main.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
    * @Route("/sendData", name="send_data", methods={"POST"})
    */
    public function sendData(Request $request): Response
    {
        $color = $request->request->get('color');
        $weight = $request->request->get('weight');
        $numLine = $request->request->get('numLine');
        $imgFile = $request->request->get('file');
        
        // Decode the base64 string and save it as a .png file
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imgFile));

    
        // Créer un fichier temporaire à partir des données image
        $tempFile = tmpfile();

        // Écrire les données image dans le fichier temporaire
        fwrite($tempFile, $imageData);
        // Obtenir le chemin absolu du fichier temporaire
        $tempFilePath = stream_get_meta_data($tempFile)['uri'];
         // Get the image name
         $imageName = pathinfo($tempFilePath, PATHINFO_FILENAME) . '.png';
        // Créer un nouvel objet UploadedFile
        $imageFile = new UploadedFile($tempFilePath,  $imageName, 'image/png', null, true);
        
        
        // // Créer un objet Image
        // $image = new Scene1();
   

        //utilisateur temporaire
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find(2);
       
        if ($color !== null && $weight !== null && $numLine !== null) {
            $data = new Scene1;
            $data ->setColor($color);
            $data ->setWeight($weight);
            $data ->setNumLine($numLine);
            $data ->setUser($user);
                 // Lier l'image au fichier uploadé
            $data->setImageFile($imageFile);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->flush();

              // Supprimer le fichier temporaire
            fclose($tempFile);
            return new Response('Data successfully saved!', Response::HTTP_OK);
            // redirection managed in javascript
        } 
            return new Response('Error: Missing data!', Response::HTTP_BAD_REQUEST);
        
    }

    
    /**
     * @Route("/gallery", name="gallery")
     */
    public function gallery(Scene1Repository $repo): Response
    {
        $scenes = $repo -> findAll(); 
        return $this->render('main/gallery.html.twig', [
            'scenes' => $scenes,
        ]);   
    }
    /**
     * @Route("/newScene/{id}", name="newScene", methods= {"GET"}))
     */
    public function newScene(Scene1Repository $repo, SerializerInterface $serializer, $id): Response
    {
        $scene = $repo -> find($id); 

        // NORMALIZED + ENCODE METHOD :
        // //transform complex object in an associative array (only group sceneDataRecup to avoid infinite loop with the user entity)
        // $sceneNormalized = $normalizer->normalize($scene, null, ['groups'=> 'sceneDataRecup']);
        // // then encore into json format
        // $json = json_encode($sceneNormalized);

        // SERIALIZER METHOD :
        $json = $serializer->serialize($scene,'json',['groups'=> 'sceneDataRecup']);
        
        return $this->render('main/newScene.html.twig', [
            'scene' => $json,
        ]);   
    }

       
    // /**
    // * @Route("/update/{id}", name="update", methods= {"GET", "POST"})
    // */
    // public function Update(Request $request, ArticleRepository $repo, $id): Response
    // {
    //     $article = $repo-> find($id);
    //     $form = $this->createForm(ArticleType::class, $article); // creation du form
    //     $form -> handleRequest($request);  // Gestion des données envoyées
    //     if ( $form->isSubmitted() && $form->isValid()){
    //         $sendDatabase = $this->getDoctrine()
    //                              ->getManager();
    //         $sendDatabase->persist($article);
    //         $sendDatabase->flush();

    //         $this->addFlash('notice', 'Modification réussie !!'); 
          
    //         return $this->redirectToRoute('article_list');
    //     }
    //     return $this->render('article/updateForm.html.twig', [
    //         'controller_name' => 'MainController',
    //         'form' => $form->createView()
    //     ]);
    // }





    //  /**
    //  * @Route("/upload", name="upload", methods={"POST"})
    //  */
    // public function upload(Request $request, DownloadHandler $downloadHandler): Response
    // {
    //     $data = $request->request->get('file');

    //     // Décoder l'image encodée en base64
    //     $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));

    //     // Créer un nouveau fichier à partir des données image
    //     $imageFile = new UploadedFile($imageData, 'image', null, null, true);

    //     // Créer un objet Image
    //     $image = new Scene1();

    //     // Lier l'image au fichier uploadé
    //     $image->setImageFile($imageFile);

    //     // Traiter l'objet Image comme vous le souhaitez (par exemple, save it to the database)

    //     // Télécharger l'image enregistrée
    //     // $downloadHandler->downloadObject($image, 'image');

    //     return new Response('Image uploaded successfully.');
    // }
}


