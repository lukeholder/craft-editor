<?php

namespace Craft;

class EditorPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Editor');
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function getDeveloper()
    {
        return 'Luke Holder';
    }

    public function getDeveloperUrl()
    {
        return 'http://logicandpixels.com.au';
    }

    public function hasCpSection()
    {
        return false;
    }

    /**
     * Register control panel routes
     */
    // public function hookRegisterCpRoutes()
    // {
    //     return array(
    //         'editor\/ingredients\/new' => 'editor/ingredients/_edit',
    //         'editor\/ingredients\/(?P<ingredientId>\d+)' => 'editor/ingredients/_edit',
    //     );
    // }

    /**
     * Register twig extension
     */
    // public function hookAddTwigExtension()
    // {
    //     Craft::import('plugins.editor.twigextensions.EditorTwigExtension');

    //     return new EditorTwigExtension();
    // }

    /**
     * Add default ingredients after plugin is installed
     */
    public function onAfterInstall()
    {
        // $ingredients = array(
        //     array('name' => 'Gin'),
        //     array('name' => 'Tonic'),
        //     array('name' => 'Lime'),
        //     array('name' => 'Soda'),
        //     array('name' => 'Vodka'),
        // );

        // foreach ($ingredients as $ingredient) {
        //     craft()->db->createCommand()->insert('editor_ingredients', $ingredient);
        // }
    }
}
