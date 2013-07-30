<?php
if( ! defined ( 'TYPO3_MODE' ) )
{
  die( 'Access denied.' );
}

  //  add static TypoScript
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/',                           'SEO Dynamic Tag'                           );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/cal/eventView/',             '+SEO Dynamic Tag cal: event view'          );
//t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/cal/eventViewYearMonthDay/', '+SEO Dynamic Tag cal event view (default)' );
//t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/cal/eventViewGetdate/',      '+SEO Dynamic Tag cal event view (getdate)' );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/tt_news/',                   '+SEO Dynamic Tag tt_news'                  );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/tt_products/',               '+SEO Dynamic Tag tt_products'              );

?>