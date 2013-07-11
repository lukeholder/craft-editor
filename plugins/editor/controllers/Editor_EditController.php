<?php

namespace Craft;

require __DIR__.'/../vendor/autoload.php';

use Alchemy\Zippy\Zippy;
use Symfony\Component\Filesystem\Filesystem;

define( "FILE_PUT_CONTENTS_ATOMIC_TEMP", dirname( __FILE__ ) . "/../cache" );
define( "FILE_PUT_CONTENTS_ATOMIC_MODE", 0777 );


Craft::requirePackage( CraftPackage::Users );

class Editor_EditController extends BaseController
{
  protected $allowAnonymous = true;

  public function actionTemplates() {

    $this->requireLogin();
    $this->requireAdmin();
    
    $file_path = $this->cleanPath( craft()->request->getQuery( "f", "error" ) );

    $vars = array(
      "file_path" => $file_path,
      "file_contents" => file_get_contents( $file_path )
    );

    $this->renderTemplate( 'Editor/editor', $vars );

  }

  public function actionBackupTemplates() {
    $this->requireLogin();
    $this->requireAjaxRequest();
    $this->requireAdmin();
    
    $fs = new Filesystem();
    $zippy = Zippy::load();

    $templateBackupsDir = CRAFT_STORAGE_PATH.'templatesBackups/';
    if(!$fs->exists($templateBackupsDir )){
      $fs->mkdir($templateBackupsDir);
    };

      try{
        
        $archive = $zippy->create($templateBackupsDir.'templateBackup-'.uniqid().'.zip', array(
            'folder' => CRAFT_TEMPLATES_PATH
        ), true);

        $vars = array(
            "success" => true,
        );

        $this->returnJson( $vars );
      }catch(Exception $e){
        $this->returnErrorJson( $e );
      }


  }

  public function actionSaveTemplate() {
    $this->requireLogin();
    $this->requireAjaxRequest();
    $this->requireAdmin();

    $contents  = craft()->request->getRequiredPost( "contents" );
    $file_path = craft()->request->getRequiredPost( "file_path" );


    if ( $this->_file_put_contents_atomic( $file_path, utf8_encode( $contents ) ) ) {

      $vars = array(
        "success" => true,
        "file_path" => $file_path,
        "file_contents" => $contents
      );

      $this->returnJson( $vars );

    } else {

      $error = "Could not save the file.";
      $this->returnErrorJson( $error );

    }

  }

  private function cleanPath( $filePath ) {

    $extension = substr( $filePath, -4, 4 );
    if ( $extension != "html" ) {
      throw new HttpException( 404 );
    }

    $basedir  = craft()->path->getTemplatesPath();
    $filePath = realpath( $basedir . $filePath );

    if ( !$this->_isChild( $basedir, $filePath ) ) {
      throw new HttpException( 404 );
    }

    return $filePath;

  }

  private function _isChild( $parent, $child ) {
    if ( false !== ( $parent = realpath( $parent ) ) ) {
      $parent = str_replace( '\\', '/', $parent );
      if ( false !== ( $child = realpath( $child ) ) ) {
        $child = str_replace( '\\', '/', $child );
        if ( substr( $child, 0, strlen( $parent ) ) == $parent )
          return true;
      }
    }
    return false;
  }


  private function _file_put_contents_atomic( $filename, $content ) {
    
    $temp = tempnam( FILE_PUT_CONTENTS_ATOMIC_TEMP, 'temp' );
    if ( !( $f = @fopen( $temp, 'wb' ) ) ) {

      $temp = FILE_PUT_CONTENTS_ATOMIC_TEMP . DIRECTORY_SEPARATOR . uniqid( 'temp' );

      if ( !( $f = @fopen( $temp, 'wb' ) ) ) {
        trigger_error( "file_put_contents_atomic() : error writing temporary file '$temp'", E_USER_WARNING );
        return false;
      }
    }

    fwrite( $f, $content );
    fclose( $f );

    if ( !@rename( $temp, $filename ) ) {
      @unlink( $filename );
      @rename( $temp, $filename );
    }

    @chmod( $filename, FILE_PUT_CONTENTS_ATOMIC_MODE );

    return true;

  }

  private function _log( $message, $params = array() ) {
    if ( ( $lumberJack = craft()->plugins->getPlugin( 'LumberJack' ) ) && $lumberJack->isInstalled && $lumberJack->isEnabled ) {

      craft()->lumberJack->log( array(
          'plugin_name' => 'Editor',
          'level' => LumberJack_LogEntryModel::INFO,
          'message' => $message,
          'meta' => $params
        ) );
    }
  }

}
