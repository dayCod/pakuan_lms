<?php
/**
 * @Author: ido
 * @Date:   2016-10-09 18:35:31
 * @Last Modified by:   ido
 * @Last Modified time: 2016-10-09 21:29:05
 */

// key to authenticate
define('INDEX_AUTH', '1');
// key to get full database access
define('DB_ACCESS', 'fa');

if (!defined('SB')) {
    // main system configuration
    require '../../../sysconfig.inc.php';
    // start the session
    require SB.'admin/default/session.inc.php';
}
// IP based access limitation
require LIB.'ip_based_access.inc.php';
do_checkIP('smc');
do_checkIP('smc-circulation');

require SB.'admin/default/session_check.inc.php';
require SIMBIO.'simbio_GUI/table/simbio_table.inc.php';
require SIMBIO.'simbio_GUI/form_maker/simbio_form_table_AJAX.inc.php';
require SIMBIO.'simbio_GUI/paging/simbio_paging.inc.php';
require SIMBIO.'simbio_DB/datagrid/simbio_dbgrid.inc.php';
require SIMBIO.'simbio_DB/simbio_dbop.inc.php';

// privileges checking
$can_read = utility::havePrivilege('circulation', 'r');
$can_write = utility::havePrivilege('circulation', 'w');

if (!($can_read AND $can_write)) {
    die('<div class="errorBox">'.__('You don\'t have enough privileges to view this section').'</div>');
}
/* RECORD OPERATION */
if (isset($_POST['saveData']) AND $can_read AND $can_write) {
  $data['name'] = trim($dbs->escape_string(strip_tags($_POST['name'])));
  $data['email'] = trim($dbs->escape_string(strip_tags($_POST['email'])));
  $data['company'] = trim($dbs->escape_string(strip_tags($_POST['company'])));
  $data['ref_desc'] = trim($dbs->escape_string(strip_tags($_POST['desc'])));
  $data['status'] = $_POST['status'];
  $data['create_at'] = date('Y-m-d H:i:s');
  $data['update_at'] = date('Y-m-d H:i:s');
  // create sql op object
  $sql_op = new simbio_dbop($dbs);
  if (isset($_POST['updateRecordID'])) {
    unset($data['create_at']);
    $updateRecordID = $dbs->escape_string(trim($_POST['updateRecordID']));
    // update the data
    $update = $sql_op->update('reference', $data, 'ref_id='.$updateRecordID);
    if ($update) {
        utility::jsAlert(__('Data Successfully Updated'));
        echo '<script type="text/javascript">parent.jQuery(\'#mainContent\').simbioAJAX(parent.jQuery.ajaxHistory[0].url);</script>';
    } else { utility::jsAlert(__('Data FAILED to Updated. Please Contact System Administrator')."\nDEBUG : ".$sql_op->error); }
    exit();
  }
}
?>

<fieldset class="menuBox">
<div class="menuBoxInner masterFileIcon">
  <div class="per_title">
      <h2><?php echo __('Reference Service'); ?></h2>
  </div>
  <div class="sub_section">
    <div class="btn-group">
    </div>
    <form name="search" action="<?php echo MWB; ?>circulation/reference_service.php" id="search" method="get" style="display: inline;"><?php echo __('Search'); ?> :
    <input type="text" name="keywords" size="30" />
    <input type="submit" id="doSearch" value="<?php echo __('Search'); ?>" class="button" />
    </form>

    <div class="btn-group">
        <a href="<?php echo MWB; ?>circulation/reference_service.php" class="btn btn-default"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;<?php echo __('All Data List'); ?></a>
        <a href="<?php echo MWB; ?>circulation/reference_service.php?needreview=true" class="btn btn-default" style="color: #FF0000;"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;<?php echo __('Need review'); ?></a>
    </div>
  </div>
</div>
</fieldset>

<?php
if (isset($_POST['detail']) OR (isset($_GET['action']) AND $_GET['action'] == 'detail')) {
  if (!($can_read AND $can_write)) {
    die('<div class="errorBox">'.__('You don\'t have enough privileges to view this section').'</div>');
  }
  /* RECORD FORM */
  $itemID = (integer)isset($_POST['itemID'])?$_POST['itemID']:0;
  $rec_q = $dbs->query('SELECT * FROM reference WHERE ref_id='.$itemID);
  $rec_d = $rec_q->fetch_assoc();

  // create new instance
  $form = new simbio_form_table_AJAX('mainForm', $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'], 'post');
  $form->submit_button_attr = 'name="saveData" value="'.__('Save').'" class="button"';

  // form table attributes
  $form->table_attr = 'align="center" id="dataList" cellpadding="5" cellspacing="0"';
  $form->table_header_attr = 'class="alterCell" style="font-weight: bold;"';
  $form->table_content_attr = 'class="alterCell2"';

  // edit mode flag set
  if ($rec_q->num_rows > 0) {
      $form->edit_mode = true;
      // record ID for delete process
      $form->record_id = $itemID;
      // form record title
      $form->record_title = $rec_d['name'];
      // submit button attribute
      $form->submit_button_attr = 'name="saveData" value="'.__('Update').'" class="button"';
      // name
      $form->addTextField('text', 'name', __('Name'), $rec_d['name'], 'style="width: 50%;"');
      // email
      $form->addTextField('text', 'email', __('Email'), $rec_d['email'], 'style="width: 50%;"');
      // company
      $form->addTextField('text', 'company', __('Company'), $rec_d['company'], 'style="width: 50%;"');
      // status
      $status_options = array();
      $status_options[] = array(0, 'Need Review');
      $status_options[] = array(1, 'In Process');
      $status_options[] = array(2, 'Done');
      $form->addSelectList('status', __('Status'), $status_options, $rec_d['status'], '', '');
      // description
      $form->addTextField('textarea', 'desc', __('Description'), $rec_d['ref_desc'], 'rows="5" style="width: 100%; overflow: auto;"', '');
      echo $form->printOut();
  }
} else {
  // create datagrid
  function showStatus($obj_db, $array_data)
  {
    switch ($array_data[4]) {
      case 1:
        return 'In Process';
      case 2:
        return 'Done';
      default:
        return 'Need Review';
    }
  }
  // table spec
  $table_spec = 'reference AS r';
  $datagrid = new simbio_datagrid();
  if ($can_read AND $can_write) {
    $datagrid->setSQLColumn('r.ref_id',
      'r.name AS \''.__('Name').'\'',
      'r.email AS \''.__('Email').'\'',
      'r.company AS \''.__('Company').'\'',
      'r.status AS \''.__('Status').'\'',
      'r.create_at AS \''.__('Input Date').'\'');
    $datagrid->modifyColumnContent(4, 'callback{showStatus}');
  } else {
    $datagrid->setSQLColumn(
      'r.name AS \''.__('Name').'\'',
      'r.email AS \''.__('Email').'\'',
      'r.company AS \''.__('Company').'\'',
      'r.status AS \''.__('Status').'\'',
      'r.create_at AS \''.__('Input Date').'\'');
    $datagrid->modifyColumnContent(3, 'callback{showStatus}');
  }
  $datagrid->setSQLorder('create_at DESC');
  // is there any search
  $criteria = 'r.ref_id IS NOT NULL';
  if (isset($_GET['keywords']) AND $_GET['keywords']) {
     $keywords = $dbs->escape_string($_GET['keywords']);
     $criteria .= "AND r.name LIKE '%$keywords%' OR r.email LIKE '%$keywords%'";
  }
  if (isset($_GET['needreview'])) {
    $criteria .= ' AND r.status = 0';
  }
  $datagrid->setSQLCriteria($criteria);
  // set table and table header attributes
  $datagrid->table_attr = 'align="center" id="dataList" cellpadding="5" cellspacing="0"';
  $datagrid->table_header_attr = 'class="dataListHeader" style="font-weight: bold;"';
  // set delete proccess URL
  $datagrid->chbox_form_URL = $_SERVER['PHP_SELF'];
  // disable button
  $datagrid->chbox_property = false;
  // put the result into variables
  $datagrid_result = $datagrid->createDataGrid($dbs, $table_spec, 20, ($can_read AND $can_write));
  if (isset($_GET['keywords']) AND $_GET['keywords']) {
      $msg = str_replace('{result->num_rows}', $datagrid->num_rows, __('Found <strong>{result->num_rows}</strong> from your keywords')); //mfc
      echo '<div class="infoBox">'.$msg.' : "'.$_GET['keywords'].'"</div>';
  }
  echo $datagrid_result;
}