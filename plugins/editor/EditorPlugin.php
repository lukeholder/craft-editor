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
        return 'http://patchworks.io';
    }

    public function hasCpSection()
    {
        return false;
    }
}
