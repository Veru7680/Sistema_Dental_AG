<?php
namespace App\Services\Sidebar;

class ItemLink implements ItemSidebar
{
    private string $title;
    private string $icon;
    private string $href;
    private bool $active;
    private array $can;

     public function __construct( 
        string $title, 
        string $icon,
        string $href,
        bool $active,
        array $can)
    {
       $this->title = $title;
       $this->icon = $icon;
       $this->href = $href;
       $this->active = $active;
       $this->can = $can;

    }

    public function render():string
    {
        $activeClass = $this->active ? 'bg-gray-100' : '';
        return <<<HTML
            <a href="{$this->href}" 
            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {$activeClass}" aria-label="Dashboard">
                <span class="inline-flex items-center space-x-2 text-gray-800">
                <i class="{$this->icon} mr-2"></i>
                </span>
                <span>{$this->title}</span>   
            </a>
        HTML;
    }

    public function authorize():bool
    {
        return true;
    }
}