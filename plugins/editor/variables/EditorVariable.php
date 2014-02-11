<?php

namespace Craft;

/**
 * Editor Variable provides access to database objects from templates
 */
class EditorVariable
{
    public function templateLink($obj)
    {

            $template = $obj->getTemplateName();
            $templatePath = craft()->templates->findTemplate($template);
            $templatesFolder = craft()->path->getTemplatesPath();

            $siteUrl = craft::getSiteUrl();
            $actionUrl = craft()->config->get("actionTrigger");

          if (strncmp($templatePath, $templatesFolder, strlen($templatesFolder)) == 0) {
              $relTemplatesPath = substr($templatePath, strlen($templatesFolder), strlen($templatePath));

              return $siteUrl.$actionUrl."/editor/edit/templates?f=".$relTemplatesPath;

          }

    }

    public function tree()
    {
      return json_encode($this->makeTree(craft()->path->getTemplatesPath()));
    }

    private function makeTree($dir, $prefix = '')
    {
      $dir = rtrim($dir, '\\/');
      $siteUrl = craft::getSiteUrl();
      $actionUrl = craft()->config->get("actionTrigger");
      $result = array();

      foreach (scandir($dir) as $f) {
        if ($f !== '.' and $f !== '..' and $f !== '.DS_Store') {
          if (is_dir("$dir/$f")) {
            $result[] = array("label"=>$f, 'children' => $this->makeTree("$dir/$f", "$prefix$f/"));
          } else {
            $result[] = array("label"=>"<a href='$siteUrl/$actionUrl/editor/edit/templates?f=$prefix$f'>$f</a>");
          }
        }
      }

      return $result;
    }
}
