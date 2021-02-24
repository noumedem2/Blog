<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {

        yield  FormField::addPanel('Posts Details');
        yield TextField::new('title');
        yield TextEditorField::new('description');
        yield TextEditorField::new('content');
        yield  FormField::addPanel('Category Details of Posts');
        yield AssociationField::new('Category');
        yield  FormField::addPanel('Image Details');
        if ($pageName == Crud::PAGE_NEW || $pageName == Crud::PAGE_EDIT) {
            yield ImageField::new('imageName')->setUploadDir('/public/images/posts');
        }
    }
}
