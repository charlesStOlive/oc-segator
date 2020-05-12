<?php namespace Waka\Segator;

use Backend;
use System\Classes\PluginBase;
use Lang;

/**
 * Segator Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Segator',
            'description' => 'No description provided yet...',
            'author'      => 'waka',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Waka\Segator\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'waka.segator.admin.super' => [
                'tab' => 'Waka - Segmentation',
                'label' => "Super administrateur de l'outil de segmentation",
            ],
            'waka.segator.admin' => [
                'tab' => 'Waka - Segmentation',
                'label' => "Administrateur de l'outil de segmentation",
            ],
            'waka.segator.user' => [
                'tab' => 'Waka - Segmentation',
                'label' => "Utilisateur de l'outil de segmentation",
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'segator' => [
                'label'       => 'Segator',
                'url'         => Backend::url('waka/segator/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['waka.segator.*'],
                'order'       => 500,
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'segator' => [
                'label' => Lang::get('waka.segator::lang.menu.label'),
                'description' => Lang::get('waka.segator::lang.menu.description'),
                'category' => Lang::get('waka.segator::lang.menu.settings_category'),
                'icon' => 'icon-object-group',
                'permissions' => ['waka.segator.admin'],
                'url' => Backend::url('waka/segator/segments'),
                'order' => 1,
            ],
        ];
    }
}