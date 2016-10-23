<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UserBundle\Entity\User;
use UserBundle\Entity\Role;
use FlikerBundle\Entity\Category;

/**
 * Command to generate some predefined data in DB
 * 
 * php bin/console fixture:generateData
 */
class DataFixtureCommand extends ContainerAwareCommand
{
    private $roles;
    private $data = array(
        'categories' => array(
            'cats',
            'dogs',
            'flowers',
            'trees',
            'men',
            'women',
            'boys',
            'girls',
            'mountain',
            'tennis'
        ),
        'roles' => array(
            array('name' => 'User', 'role' => Role::ROLE_USER),
            array('name' => 'Admin', 'role' => Role::ROLE_ADMIN),
        ),
        'users' => array(
            array(
                'username' => 'admin@admin.com',
                'password' => 'admin',
                'role' => Role::ROLE_ADMIN,
                'firstName' => 'Nesa',
                'lastName' => 'Mouzehkesh',
                ),
             array(
                'username' => 'mojan@admin.com',
                'password' => 'mojan',
                'role' => Role::ROLE_USER,
                'firstName' => 'Mojan',
                'lastName' => 'Jimi',
                ),
        )
    );

    protected function configure()
    {
        $this
            ->setName('fixture:generateData')
            ->setDescription('Generate some sample data');
    }
    
    /**
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->truncateEntities();
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $output->writeln('<comment>Importing predefined data ...<comment>');
        
        // Create fliker categories
        foreach ($this->data['categories'] as $name) {
            $category = new Category();
            $category->setTag($name);
            $category->setTitle(ucfirst($name));
            $category->setDescription('');
            
            $em->persist($category);
        }
        $em->flush(); 

        // Create roles
        foreach ($this->data['roles'] as $roleData) {
            $role = new Role();
            $role->setName($roleData['name']);
            $role->setRole($roleData['role']);
            $this->roles[$roleData['role']] = $role;
            $em->persist($role);
        }
        $em->flush(); 
        
        foreach ($this->data['users'] as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setPassword($userData['password']);
            $user->addRole($this->roles[$userData['role']]);
            $user->setFirstName($userData['firstName']);
            $user->setLastName($userData['lastName']);

            $em->persist($user);
        }
        $em->flush(); 
        
        $output->writeln('<info>Data loaded successfully. <info>');
    }
    
    /**
     * Truncate all doctrine entities
     * 
     * @return \AppBundle\Service\DataFixture
     */
    public function truncateEntities()
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $schemaManager = $em->getConnection()->getSchemaManager();

        $query = array('SET FOREIGN_KEY_CHECKS=0;');
        foreach($schemaManager->listTables() as $table) {
            array_push($query, sprintf('TRUNCATE %s;', $table->getName()));
        }
        array_push($query, 'SET FOREIGN_KEY_CHECKS=1;');
        $em->getConnection()->executeQuery(implode('', $query), array(), array());
        
        return $this;
    }    
}