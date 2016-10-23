<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Component\Pager\Paginator;
use AppBundle\Library\Base\BaseService;
use AppBundle\Entity\BaseEntity;

class AppService extends BaseService
{
    /**
     * Translator services
     * 
     * @var Translator $translator
     */
    protected $translator;  
    
    /**
     *
     * @var TokenStorage $security
     */
    protected $security;    
    
    /**
     *
     * @var EntityManager $em
     */
    protected $em;
    
    /**
     *
     * @var RequestStack $requestStack
     */
    protected $requestStack;
    
    /**
     *
     * @var ValidatorInterface $validator
     */
    protected $validator;
    
    /**
     *
     * @var Paginator $paginator
     */
    protected $paginator;    

    /**
     * 
     * @param Translator $translator
     * @param TokenStorage $security
     * @param EntityManager $em
     * @param RequestStack $requestStack
     * @param Paginator $paginator
     */
    public function __construct(
        Translator $translator, 
        TokenStorage $security,
        EntityManager $em,
        RequestStack $requestStack,
        Paginator $paginator,
        $parameters = array()
        ) 
    {
        $this->translator = $translator;
        $this->security = $security;
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->paginator = $paginator;
        $this->setParametrs($parameters);
    }   

    /**
     * Get a user from the Security Context
     *
     * @return mixed
     * @throws \LogicException If SecurityBundle is not available
     * @see Symfony\Component\Security\Core\Authentication\Token\TokenInterface::getUser()
     */
    public function getUser()
    {
        if (null === $token = $this->security->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }    
    
    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }
    
    /**
     * 
     * @return RequestStack
     */
    public function getRequestStack()
    {
        return $this->requestStack;
    }


    /**
     * Soft-delete an entity
     * 
     * @param type $entity
     */
    public function deleteEntity($entity)
    {
        if ($entity instanceof BaseEntity) {
            return;
        }
        
        $entity->setDeleted(true);
        $entity->setDeletedTime();
        $this->em->flush();
    }
    
    /**
     * Persist an flush entity manager for this entity
     * 
     * @param type $entity
     * @param type $flush
     * @return \Library\Service\Helper
     */
    public function saveEntity($entity, $flush = true)
    {
        $this->em->persist($entity);
        if ($flush) {
            $this->em->flush();
        }
        
        return $this;
    }
    
    /**
     * use Knp\Component\Pager\Pagination\AbstractPagination;
     * 
     * @return type
     */
    public function getPaginator()
    {
        return $this->paginator;
    }
        
    /**
     * 
     * @param type $query
     * @return type
     */
    public function paginate($query)
    {
        $request = $this->getRequestStack()->getCurrentRequest();
        $limit = $this->getParameter('paginateLimit', 10);
        $itemsPagination = $this->getPaginator()->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit
        );
        
        return $itemsPagination;
    }
    
    

    /**
     * 
     * @param type $success
     * @param type $messageId
     * @param type $content
     * @param type $parameters
     * @param type $ex
     * @return JsonResponse
     * @throws \Exception
     */
    public function getJsonResponse(
        $success, 
        $message = null, 
        $content = null, 
        $parameters = null
        )
    {
        // Set jason success status
        $response = array(
            'success' => $success,
            'message' => $message
        );
        
        // Set jason contet if it is provide
        if (null !== $content) {
            $response['content'] = $content;
        }
        
        // Merge jason responce with some extra user parameters
        if (null !== $parameters) {
            $response = array_merge($response, $parameters);
        }
        
        return new JsonResponse($response);
    }
}