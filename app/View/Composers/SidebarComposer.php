<?php
namespace App\View\Composers;

use App\Services\Sidebar\ItemHeader;   
use App\Services\Sidebar\ItemLink;      
use App\Services\Sidebar\ItemGroup;    
use Illuminate\View\View;

class SidebarComposer
{
    public function compose(View $view){
        $items = collect(config('sidebar'))
        ->map(function ($item){
            return $this->parseItem($item);
        });
        $view->with('sidebarItems', $items);
    }

    public function parseItem($item)
    {
        switch ($item['type']) {
            case 'header':
               return new ItemHeader(
                title: $item['title'],
                can:$item['can'] ?? []
               );
                break;

            case 'link':
                return new ItemLink(
                title: $item['title'],
                icon: $item['icon'] ?? 'fa-regular fa-circle',
                href: $item['route'] ? route ($item['route']): '#',
                active: $item['active'] ? request()->routeIs($item['active']): false,
                can: $item['can'] ?? []
                );
                break;

            case 'group':
                $group = new ItemGroup(
                    title: $item['title'],
                    icon: $item['icon'] ?? 'fa-regular fa-folder',
                    active: $item['active'] ? request()->routeIs($item['active']): false
                );

                foreach ($item['items'] as $subItem) {
                    $group->add(
                        $this->parseItem($subItem));
                }
                break;
            
            default:
                throw new \InvalidArgumentException("Unknown sidebar item type: {$item['type']}");
                break;
        }
    }
}