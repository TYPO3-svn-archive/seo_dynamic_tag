<?php
if( ! defined ( 'TYPO3_MODE' ) )
{
  die( 'Access denied.' );
}

  //  add static TypoScript
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/resetPageMeta',  'SEO - reset page.meta' );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/',               'SEO'                   );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/cal/',           '+SEO - cal'            );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/tt_news/',       '+SEO - tt_news'        );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/tt_products/',   '+SEO - tt_products'    );

?>