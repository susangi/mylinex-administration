<?php

namespace Administration;

use Administration\Models\Menu;
use Administration\Models\Role;
use Administration\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;

class AdministrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // register our controller
        $this->app->make('Administration\Controllers\DocumentationController');
        $this->app->bind('Administration', function ($app) {
            return new Administration;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/web.php');
        $this->loadViewsFrom(__DIR__ . '/Views', 'Administration');
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');
        $this->publishes([
            __DIR__ . '/Assets/js' => public_path('js'),
            __DIR__ . '/Assets/css' => public_path('css'),
            __DIR__ . '/Assets/images' => public_path('images'),
            __DIR__ . '/Assets/fonts' => public_path('fonts'),
            __DIR__ . '/Views' => resource_path('views'),
        ], 'public');

        View::composer('*', function ($view) {
            $html = '';
            if (Auth::check()) {
                $user_id = Auth::id();
                $user = User::find($user_id);
                $menu_ids = [];
                $current_route = Request::route()->getName();

                if ($user->hasRole(['Super Admin', 'Admin'])) {
                    $menu_ids = Menu::with('parentMenu')->get()->pluck('id');
                } else {
                    $menu_id = $user->getAllPermissions()->pluck('menu_id');
                    $menu_ids = $menu_id->unique();
                }

                $menus = Menu::whereIn('id', $menu_ids)->with('parentMenu')->get();

                $parent = collect();
                foreach ($menus as $menu) {
                    $parentMenu = $menu->parentMenu;
                    if (!empty($parentMenu->parentMenu)){
                        $menus->push($parentMenu);
                        $parent->push($parentMenu->parentMenu);
                    }
                    if (!empty($parentMenu) && empty($parentMenu->parent_id)){
                        $parent->push($parentMenu);
                    }
                }
                $parents = $parent->unique();
                $menus = $menus->unique();

                $html = '';
                foreach ($parents as $parent) {

                    $inner_html = "";
                    $submenu_html = "";
                    $category_act = "";
                    foreach ($menus as $menu) {

                        if (empty($menu->url) && !empty($menu->parent_id)) {

                            if ($menu->parent_id == $parent->id){
                                $childrens = $menu->children()->get();
                                $inner_submenu_html = "";
                                foreach ($childrens as $children) {
                                    if ($menu->id == $children->parent_id) {
                                        $page_act = ($children->url == $current_route) ? 'active' : '';
                                        if ($children->url == $current_route){
                                            $category_act = "active";
                                        }
                                        $inner_submenu_html .= "<a href='" . route($children->url) . "'  class='dropdown-item $page_act'>" . $children->title . "</a>";
                                    }
                                }

                                $inner_html .= "<ul class='navbar-nav'>";
                                $inner_html .= "<li class='nav-item dropdown dropright show-on-hover $category_act'>";
                                $inner_html .= " <a class='nav-link dropdown-toggle' href='#' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
                                $inner_html .= $menu->title;
                                $inner_html .= '</a>';
                                $inner_html .= '<div class="dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">';
                                $inner_html .= '<div class="sub-dropdown-menu show-on-hover">';
                                $inner_html .= $inner_submenu_html;
                                $inner_html .= '</div>';
                                $inner_html .= '</div>';
                                $inner_html .= '</li>';
                                $inner_html .= '</ul>';
                            }


                        } else {
                            if ($parent->id == $menu->parent_id) {
                                $page_act = ($menu->url == $current_route) ? 'active' : '';
                                if ($menu->url == $current_route){
                                    $category_act = "active";
                                }
                                $inner_html .= "<a href='" . route($menu->url) . "'  class='dropdown-item $page_act'>" . $menu->title . "</a>";
                            }
                        }

                    }

                    $html .= "<li class='nav-item dropdown show-on-hover $category_act'>";
                    $html .= " <a class='nav-link dropdown-toggle' href='#' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
                    $html .= $parent->title;
                    $html .= '</a>';

                    $html .= '<div class="dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">';
                    $html .= '<div class="sub-dropdown-menu show-on-hover">';

                    $html .= $inner_html;

                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</li>';
                }
            }

            View::share('menu', $html);
        });
    }
}
