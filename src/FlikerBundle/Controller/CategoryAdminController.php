<?php

namespace FlikerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Library\Base\BaseController;
use FlikerBundle\Form\Type\CategoryType;

class CategoryAdminController extends BaseController
{
    
    /**
     * @Route("/categories", name="admin_fliker_category_index")
     */
    public function indexAction()
    {
        // Get a query of listing all categories from category service
        $query = $this->get('app.fliker.service')->getCategories();
        
        // Get pagination
        $pagination = $this->get('app.service')->paginate($query);
        
        // Render view and then generate a Response object and return it
        return $this->render(
            '::admin/fliker/category/categories.html.twig', 
            array(
                'pagination' => $pagination,
                )
            );
    }
    
    /**
     * @Route("/categories/add", defaults={"id" = null}, name="admin_fliker_category_add")
     * @Route("/categories/edit/{id}", name="admin_fliker_category_edit")
     */  
    public function addEditCategoryAction(Request $request, $id)
    {
        // Get a category from category service. 
        // If $id is null then it will returns a new category object
        $category = $this->get('app.fliker.service')->getCategory($id);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            // Use saveEntity function in app.service to save this entity
            $this->get('app.service')->saveEntity($category);
            
            return $this->redirectToRoute('admin_fliker_category_index');
        }
        
        return $this->render('::admin/fliker/category/category.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/categories/delete/{id}", name="admin_fliker_category_delete")
     */  
    public function deleteCategoryAction($id)
    {
        // Get a category from category service. 
        $category = $this->get('app.fliker.service')->getCategory($id);
        
        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($category);
        
        return $this->redirectToRoute('admin_fliker_category_index');        
    }
}