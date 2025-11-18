<?php
namespace App\Services\Sidebar;
use ItemLink;
class ItemsGroup implements ItemSidebar
{   
    private string $title;
    private string $icon;
    private string $active;
    private array $items = [];

    public function __construct( 
        string $title, 
        string $icon,
        string $active,
        )
    {
       $this->title = $title;
       $this->icon = $icon;;
       $this->active = $active;

    }

    public function add(ItemLink $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    public function reder():string
    {
        return view('sidebar.items-group',[
            'title' => $this->title,
           'icon' => $this->icon,
           'active' => $this->active,
            'items' => $this->items,
            ])->render();
    }

    public function authorize():bool
    {
        return true;
    }
}