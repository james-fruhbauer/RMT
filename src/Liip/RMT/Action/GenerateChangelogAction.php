<?php

namespace Liip\RMT\Action;

class GenerateChangelogAction extends \Liip\RMT\Action\BaseAction
{

  public function getTitle()
  {
    return "Application changelog update";
  }

  public function execute()
  {
    /* @var $versionPersister \Liip\RMT\Version\Persister\VcsTagPersister */
    $versionPersister = \Liip\RMT\Context::get( 'version-persister' );
    $tagName = $versionPersister->getCurrentVersionTag();
    $newVersion = \Liip\RMT\Context::getParam( 'new-version' );
    $args = array(
      'readmegen',
      '--from',
      $tagName,
      '--release',
      $newVersion
    );
    new \ReadmeGen\Bootstrap( $args );

    \Liip\RMT\Context::get( 'output' )->write( "Updated changelog to [<yellow>{$newVersion}</yellow>]" );
    $this->confirmSuccess();
  }

}
