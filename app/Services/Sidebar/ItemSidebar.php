<?php
namespace App\Services\Sidebar;

interface ItemSidebar
{
    public function render():string;
    public function authorize():bool;
}