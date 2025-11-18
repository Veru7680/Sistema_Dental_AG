<?php
namespace App\Services\Sidebar;
use Illuminate\Support\Facades\Gate;

class ItemHeader implements ItemSidebar
{   private string $title;
    private array $can;


    public function __construct(string $title, array $can)
    {
        $this->title = $title;
        $this->can = $can;

    }

    public function render():string
    {
        return <<<HTML
            <div class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase ">
              {$this->title}
            </div>
        HTML;
    }

    public function authorize():bool
    {
        return count($this->can)
        ? Gate::any($this->can): true;
    }
}