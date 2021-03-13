<?php

namespace App\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginatorExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('paginator', [$this, 'paginator']),
        ];
    }

    public function paginator($totalPage, $pageCurrent, $categoryNumber)
    {
        if ($totalPage > 1) {
            $html = "<nav aria-label='Page navigation example'><ul class='pagination justify-content-center'>";



            for ($i = 1; $i <=  $totalPage; $i++) {
                $html += "<li class='page-item'>";
                if (($pageCurrent > 0) && ($i == $pageCurrent)) {
                    if ($categoryNumber != null) {
                        $html += '';
                    }
                }
            }

            //     return "
            //                         <a class='page-link bg-primary text-white' href='{{ path('app_category',{id:category.id,page:item}) }}'>{{ item}}</a>
            //                     {% else %}
            //                         <a class='page-link bg-primary text-white' href='{{ path('app_home',{page:item}) }}'>{{ item}}</a>
            //                     {% endif %}
            //                 {% else %}
            //                     {% if categoryNumber != null %}
            //                         <a class='page-link' tabindex='-1' aria-disabled='true' href='{{ path('app_category',{id:category.id,page:item}) }}'>{{ item}}</a>
            //                     {% else %}
            //                         <a class='page-link' tabindex='-1' aria-disabled='true' href='{{ path('app_home',{page:item}) }}'>{{ item}}</a>
            //                     {% endif %}
            //                 {% endif %}
            //             </li>
            //         {% endfor %}
            //     </ul>
            // </nav>
            // ";
        }
    }
}
