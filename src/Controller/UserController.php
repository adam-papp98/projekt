<?php


namespace App\Controller;

use App\Form\UserType;
use App\Model\User\UserData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\User\UserFacade;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{

    private $userFacade;
    
    /**
     *  @param UserFacade $userFacade
     */
    public function __construct(UserFacade $userFacade)
    {
        $this->userFacade = $userFacade;
    }
    

    /**
     * @Route("/users", name="user-list")
     */
    public function list()
    {
        $users = $this->userFacade->getAll();
        return $this->render("user/list.html.twig",['users'=> $users]);
    }

    /**
     * @Route("/user-edit/{id}", name="user-edit" , requirements={"id" = "\d+"})
     */
    public function edit(Request $request, int $id)
    {
        //$visitorname = $request->query->get('username');
        $user = $this->userFacade->getById($id);
        if(!$user)
        {
            
            return $this->redirectToRoute("user-list");
            
        }
        $userdata = UserData::createFromUser($user);
        $form = $this->createForm(UserType::class, $userdata, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userFacade->edit($user->getId(), $form->getData());
            return $this->redirectToRoute('user-list');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
