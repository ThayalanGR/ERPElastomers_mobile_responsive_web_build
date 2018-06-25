<?php

/*
  OEM version can dynamically change the logo on report's header

  - To enable add to user/defaults.php: 
  $mydbr_defaults['reportheader_logo']['enabled'] = true;
*/
class Reportheader_image {
  /*
    @param string $autocomplete_param value for any automatic parameter defined in $mydbr_defaults['reportheader_logo']['param']
      for example: $mydbr_defaults['reportheader_logo']['param'] = 'inLogin';
    @param string logo_cell $mydbr_defaults['header_logo_cell'] value which can be overridden here
    @return array img-tag + logo_cell formatting
  */
  static function get_image( $autocomplete_param, $logo_cell ) {
    $image = 'user/images/companylogo.png';
    $width = 146;
    $height = 31;
    
    if ($autocomplete_param=='dba') {
      $image = 'user/images/mydbr_logo_header.png';
      $width = 47;
      $height = 31;
      $logo_cell = 'width: 65px; padding: 1px;text-align:center';
    }
    return array( '<img src="'.$image.'" width="'.$width.'" height="'.$height.'" alt="logo">', $logo_cell );
  }
}
