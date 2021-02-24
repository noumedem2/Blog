<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setSearchFields(['slug', 'name'])
            ->setPaginatorPageSize(10)
            ->setPaginatorRangeSize(10);
    }
    public function configureFields(string $pageName): iterable
    {
        yield  TextField::new('name');
        yield  SlugField::new('slug')->setTargetFieldName('slug');
    }
}
