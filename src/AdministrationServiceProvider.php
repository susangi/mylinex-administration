<?php

namespace Administration;

use Administration\Models\Menu;
use Administration\Models\Permission;
use Administration\Models\Role;
use Administration\Models\User;
use Illuminate\Mail\PendingMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            __DIR__ . '/Assets/plugins' => public_path('plugins'),
            __DIR__ . '/Database/seeds' => database_path('seeds'),
        ], 'public');

        View::composer('*', function ($view) {
            $html = '';
            if (Auth::check()) {
                $user_id = Auth::id();
                $user = User::find($user_id);
                $menu_ids = [];
                $current_route = Request::route()->getName();
                
                if ($user->hasRole(['Super Admin'])) {
                    $menu_ids = Menu::with('parentMenu')->get()->pluck('id');
                } else {
                    $menu_id = $user->getAllPermissions()->pluck('menu_id');
                    $menu_ids = $menu_id->unique();
                }
                
                $menu = Menu::roots()->get();

                $html = null;

                foreach ($menu as $root) {
                    $tree = $root->getDescendantsAndSelf()->toHierarchy()->toArray();
                    $first_menu_ids = $root->getDescendants()->pluck('id');
                    $first_permissions = Permission::whereIn('menu_id', $first_menu_ids)->pluck('name');
                    if (Auth::user()->hasRole('Super Admin') || Auth::user()->hasAnyPermission($first_permissions)) {
                        if ($root->is_single) {

                        } else {
                            $html .= "<li class=\"nav-item dropdown show-on-hover\">";
                            $html .= "<a href=\"#\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"true\" class=\"nav-link dropdown-toggle\">$root->title</a>";
                            $html .= "<ul class=\"dropdown-menu\"  data-dropdown-in=\"fadeIn\" data-dropdown-out=\"fadeOut\">";
                            foreach ($tree as $item) {
                                if (sizeof($item['children']) > 0) {


                                    foreach ($item['children'] as $it) {

                                        if (sizeof($it['children']) > 0) {
                                            $parent_menu_ids = collect($it['children'])->pluck('id');
                                            $parent_permissions = Permission::whereIn('menu_id', $parent_menu_ids)->pluck('name');


                                            if (Auth::user()->hasRole('Super Admin') || Auth::user()->hasAnyPermission($parent_permissions)) {

                                                if (env('APP_TYPE') == 'ALL' || $it['type'] =='ALL') {
                                                    $html .= "<div class=\"sub-dropdown-menu show-on-hover\">";
                                                    $html .= "<a  href=\"#\"  class=\"dropdown-toggle dropdown-item\">" . $it['title'] . "</a>";
                                                    $html .= "<div class=\"dropdown-menu open-right-side\">";
                                                }

                                                foreach ($it['children'] as $i) {

                                                    if (sizeof($i['children']) > 0) {

                                                        $last_level_ids = collect($i['children'])->pluck('id');
                                                        $last_level_permissions = Permission::whereIn('menu_id', $last_level_ids)->pluck('name');

                                                        if (Auth::user()->hasRole('Super Admin') || Auth::user()->hasAnyPermission($last_level_permissions)) {
                                                            if (env('APP_TYPE') == 'ALL' || $i['type'] == env('APP_TYPE') || $i['type'] =='ALL') {
                                                                $html .= "<div class=\"sub-dropdown-menu show-on-hover\">";
                                                                $html .= "<a  href=\"#\"  class=\"dropdown-toggle dropdown-item\">" . $i['title'] . "</a>";
                                                                $html .= "<div class=\"dropdown-menu open-right-side\">";

                                                                foreach ($i['children'] as $y) {
                                                                    $html .= "<li><a href=\"" . route($y['url']) . "\" class=\"dropdown-item\"> " . $y['title'] . " </a></li>";
                                                                }
                                                                $html .= "</div>";
                                                                $html .= "</div>";
                                                            }
                                                        }

                                                    } else {

                                                        $last_level_permissions = Permission::where('menu_id', '=', $i['id'])->pluck('name');

                                                        if (Auth::user()->hasRole('Super Admin') || Auth::user()->hasAnyPermission($last_level_permissions)) {
                                                            if (env('APP_TYPE') == 'ALL' || $i['type'] == env('APP_TYPE') || $i['type'] =='ALL') {
                                                                $html .= "<li><a href=\"" . route($i['url']) . "\" class=\"dropdown-item\"> " . $i['title'] . " </a></li>";
                                                            }
                                                        }
                                                    }
                                                }

                                                if (env('APP_TYPE') == 'ALL' || $it['type'] =='ALL') {
                                                    $html .= "</div>";
                                                    $html .= "</div>";
                                                }
                                            }

                                        } else {
                                            $parent_level_permissions = Permission::where('menu_id', '=', $it['id'])->pluck('name');
                                            if (Auth::user()->hasRole('Super Admin') || Auth::user()->hasAnyPermission($parent_level_permissions)) {
                                                if (env('APP_TYPE') == 'ALL' || $it['type'] == env('APP_TYPE') || $it['type'] =='ALL') {
                                                    $html .= "<li><a href=\"" . route($it['url']) . "\" class=\"dropdown-item\">" . $it['title'] . "</a></li>";
                                                }
                                            }
                                        }

                                    }

                                } else {
                                    if (Auth::user()->hasRole('Super Admin') || $it['type'] == env('APP_TYPE') || $it['type'] == 'ALL') {
                                        $html .= "<li><a href=\"" . route($item['url']) . "\" class=\"dropdown-item\">" . $item['title'] . "</a></li>";
                                    }
                                }
                            }


                            $html .= "</ul>";
                            $html .= "</li>";
                        }
                    }

                }
            }
            View::share('menu', $html);
        });
    }
}

