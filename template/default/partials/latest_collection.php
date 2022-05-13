<?php
/**
 * @Author: ido
 * @Date:   2016-10-09 08:01:51
 * @Last Modified by:   ido
 * @Last Modified time: 2016-10-09 08:27:38
 */

// be sure that this file not accessed directly
if (!defined('INDEX_AUTH')) {
  die("can not access this file directly");
} elseif (INDEX_AUTH != 1) {
  die("can not access this file directly");
} 

$last  = '<h2>'.__('Lastest Collection').'</h2>';
$last .= '<hr>';

$last .= '<ul class="latest-collection">';
$last_q = $dbs->query('SELECT biblio_id, title FROM biblio ORDER BY input_date DESC LIMIT 10');
while ($last_d = $last_q->fetch_assoc()) {
  $title = $last_d['title'];
  $len = 40;
  if (strlen($title) > $len) {
    $title = substr($title, 0, $len) . '...';
  }
  $last .= '<li><a href="index.php?p=show_detail&id='.$last_d['biblio_id'].'">'.$title.'</a></li>';
}
$last .= '</ul>';

echo $last;