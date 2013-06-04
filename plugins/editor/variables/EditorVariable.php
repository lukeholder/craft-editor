<?php

namespace Craft;

/**
 * Editor Variable provides access to database objects from templates
 */
class EditorVariable
{
    /**
     * Get all available ingredients
     *
     * @return array
     */
    public function templates(){
          return $this->listIn(craft()->path->getTemplatesPath());
    }

    public function templateLink(){
      if (isset($_GET['template']))
      {
          $template = $_GET['template'];
          $templatePath = craft()->templates->findTemplate($template);
          $templatesFolder = craft()->path->getTemplatesPath();

          $siteUrl = craft::getSiteUrl();
          $actionUrl = craft()->config->get("actionTrigger");

          if (strncmp($templatePath, $templatesFolder, strlen($templatesFolder)) == 0)
          {
              $relTemplatesPath = substr($templatePath, strlen($templatesFolder), strlen($templatePath));
              return $siteUrl."/".$actionUrl."/editor/edit/templates?f=".$relTemplatesPath;

          }
      }
    }

    private function listIn($dir, $prefix = '') {
      $dir = rtrim($dir, '\\/');
      $result = array();

        foreach (scandir($dir) as $f) {
          if ($f !== '.' and $f !== '..' and $f !== '.DS_Store') {
            if (is_dir("$dir/$f")) {
              $result = array_merge($result, $this->ListIn("$dir/$f", "$prefix$f/"));
            } else {
              $result[] = $prefix.$f;
            }
          }
        }

      return $result;
    }
}
