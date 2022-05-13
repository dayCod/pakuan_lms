<?php
/**
 * @Author: ido
 * @Date:   2016-10-08 21:45:59
 * @Last Modified by:   ido
 * @Last Modified time: 2016-10-08 22:22:05
 */

// be sure that this file not accessed directly
if (!defined('INDEX_AUTH')) {
  die("can not access this file directly");
} elseif (INDEX_AUTH != 1) {
  die("can not access this file directly");
} 

$gmd = array(
  'book' => array(
    'url' => 'index.php?search=Search&gmd=Book',
    'icon' => 'ebook.png'
    ),
  'thesis_dissertation' => array(
    'url' => 'index.php?search=Search&gmd=Thesis+And+Disertation',
    'icon' => 'skripsi.png'
    ),
  'article' => array(
    'url' => 'index.php?search=Search&gmd=Article',
    'icon' => 'sirkulasi.png'
    ),
  'business_data' => array(
    'url' => 'index.php?search=Search&gmd=Business+Data',
    'icon' => 'referensi.png'
    )
  );

$img_location = $sysconf['template']['dir'].'/'.$sysconf['template']['theme'].'/img/gmd/';

echo '<h2 style="color: black;">'.__('Collection Type').'</h2>';

$gmd_button = '<ul class="gmd-button">';
foreach ($gmd as $gmd_key => $gmd_value) {
  $gmd_button .= '<li>';
  $gmd_button .= '<a href="'.$gmd_value['url'].'" class="btn btn-img"><img src="'.$img_location.$gmd_value['icon'].'" /></a>';
  $gmd_button .= '</li>';
}
$gmd_button .= '<ul>';

echo $gmd_button;

?>