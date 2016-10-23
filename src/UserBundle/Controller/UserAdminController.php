<?php

namespace UserBundle\Controller;

use Symfony\Component\Form\Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Library\Base\BaseController;
use UserBundle\Form\UserType;

class UserAdminController extends BaseController
{
    /**
     * Display all users in the user main page
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     * 
     * @Route("/", name="admin_user_index")
     */
    public function indexAction()
    {
        // Get a query of listing all users from user service
        $pages = $this->get('app.user.service')->getUsers();
        
        // Get pagination
        $pagination = $this->get('app.service')->paginate($pages);
        
        // Render and return the view
        return $this->render(
            '::admin/user/users.html.twig',
            array(
                'pagination' => $pagination
                )
            );
    }
    
    /**
     * Display and handel add edit user action
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     * @Route("/edit/user/{userId}", name="admin_user_edit")
     * @Route("/add/user", name="admin_user_add", defaults={"id" = null})
     */
    public function addEditUserAction(Request $request, $userId = null)
    {
        // Get user object
        $user = $this->get('app.user.service')->getUser($userId);
        $em = $this->getDoctrine()->getManager();

        // Generate User Form
        $form = $this->createForm(
            UserType::class, 
            $user,
            array(
                'action' => $request->getUri(),
                'method' => 'post'
                )
            );

        $form->handleRequest($request);
        // If form is submited and it is valid then add or update this $user
        if ($form->isValid()) {
            $result = $this->userFormIsValid($form);
            if (true === $result) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('admin_user_index');
            }
        }

        return $this->render('::admin/user/addEditUser.html.twig', array(
            'form' => $form->createView(),
        ));        
    }
    
    /**
     * Delete a user
     * 
     * @param type $userId
     * @return type
     * @Route("/delete/{userId}", name="admin_user_delete")
     */
    public function deleteUserAction($userId)
    {
        // Get user
        $user = $this->get('app.user.service')->getUser($userId);

        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($user);

        return $this->redirectToRoute('admin_user_index');
    }
    
    /**
     * 
     * @param Form $userForm
     * @return string|boolean
     */
    private function userFormIsValid(Form $userForm)
    {
        $em = $this->getAppService()->getEntityManager();
        $user = $userForm->getData();
        $userName = $user->getUsername();

        // Check the username if it is not taken
        $result = $em
            ->getRepository('UserBundle:User')
            ->canUserUseUsername($user, $userName);
        
        // Check the result
        if (!$result) {
            return 'This username is already used';
        }

        // Check password and rePassword
        $password = $userForm->get('password')->getData();
        $rePassword = $userForm->get('rePassword')->getData();
        if ($password !== $rePassword) {
            return 'Passwords are no matched';
        }

        // Set this $password for user password
        $user->setPassword($password);
        
        return true;
    }    
}
