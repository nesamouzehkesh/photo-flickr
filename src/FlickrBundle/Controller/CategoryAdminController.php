<?php

namespace FlickrBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Library\Base\BaseController;
use FlickrBundle\Form\Type\CategoryType;

class CategoryAdminController extends BaseController
{
    
    /**
     * @Route("/admin/flickr/categories", name="admin_flickr_category_index")
     */
    public function indexAction()
    {
        // Get a query of listing all categories from category service
        $query = $this->get('app.flickr.service')->getCategories();
        
        // Get pagination
        $pagination = $this->get('app.service')->paginate($query);
        
        // Render view and then generate a Response object and return it
        return $this->render(
            '::admin/flickr/category/categories.html.twig', 
            array(
                'pagination' => $pagination,
                )
            );
    }
    
    /**
     * @Route("/admin/flickr/categories/add", defaults={"id" = null}, name="admin_flickr_category_add")
     * @Route("/admin/flickr/categories/edit/{id}", name="admin_flickr_category_edit")
     */  
    public function addEditCategoryAction(Request $request, $id)
    {
        // Get a category from category service. 
        // If $id is null then it will returns a new category object
        $category = $this->get('app.flickr.service')->getCategory($id);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            // Use saveEntity function in app.service to save this entity
            $this->get('app.service')->saveEntity($category);
            
            return $this->redirectToRoute('admin_flickr_category_index');
        }
        
        return $this->render('::admin/flickr/category/category.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/admin/flickr/categories/delete/{id}", name="admin_flickr_category_delete")
     */  
    public function deleteCategoryAction($id)
    {
        // Get a category from category service. 
        $category = $this->get('app.flickr.service')->getCategory($id);
        
        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($category);
        
        return $this->redirectToRoute('admin_flickr_category_index');        
    }
}