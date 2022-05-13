<?php
/**
 * @Author: ido
 * @Date:   2016-10-09 06:44:59
 * @Last Modified by:   ido
 * @Last Modified time: 2016-10-09 07:49:01
 */

// be sure that this file not accessed directly
if (!defined('INDEX_AUTH')) {
  die("can not access this file directly");
} elseif (INDEX_AUTH != 1) {
  die("can not access this file directly");
} 

$keywords = '&keywords=';
if (isset($_GET['keywords'])) {
  $keywords .= urlencode($_GET['keywords']).'&title='.urlencode($_GET['keywords']);
}

$gmd = array(
  'all' => array(
    'url' => 'index.php?search=Search'.$keywords,
    'text' => 'ALL'
    ),
  'book' => array(
    'url' => 'index.php?search=Search'.$keywords.'&gmd=Book',
    'text' => 'E-book'
    ),
  'thesis_dissertation' => array(
    'url' => 'index.php?search=Search'.$keywords.'&gmd=Thesis+And+Disertation',
    'text' => 'Skripsi'
    ),
  'article' => array(
    'url' => 'index.php?search=Search'.$keywords.'&gmd=Article',
    'text' => 'Sirkulasi'
    ),
  'business_data' => array(
    'url' => 'index.php?search=Search'.$keywords.'&gmd=Business+Data',
    'text' => 'Referensi'
    )
  );

$gmd_button_result = '<ul class="gmd-button gmd-button-result">';
foreach ($gmd as $gmd_key => $gmd_value) {
  if ((isset($_GET['gmd']) && $_GET['gmd'] == $gmd_value['text']) || (!isset($_GET['gmd']) && $gmd_key == 'all')) {
    $class = 'active';
  } else {
    $class = 'not-active';
  }

  $url = $gmd_value['url'];
  if ($gmd_key != 'all') {
    //$url .= urlencode($gmd_value['text']);
  }

  $gmd_button_result .= '<li>';
  $gmd_button_result .= '<a class="btn btn-gmd-result '.$class.'" href="'.$url.'">'.$gmd_value['text'].'</a>';
  $gmd_button_result .= '</li>';
}
$gmd_button_result .= '</ul>';

echo $gmd_button_result;