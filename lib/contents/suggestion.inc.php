<?php
/**
 * @Author: ido
 * @Date:   2016-10-09 17:47:22
 * @Last Modified by:   ido
 * @Last Modified time: 2016-10-09 21:23:12
 */
// be sure that this file not accessed directly
if (!defined('INDEX_AUTH')) {
    die("can not access this file directly");
} elseif (INDEX_AUTH != 1) {
    die("can not access this file directly");
}


require SIMBIO.'simbio_DB/simbio_dbop.inc.php';

$page_title = 'Suggestion Form';

// save data
if (isset($_POST['saveData'])) {
  $data['sug_id'] = NULL;
  $data['name'] = trim($dbs->escape_string(strip_tags($_POST['memberName'])));
  $data['email'] = trim($dbs->escape_string(strip_tags($_POST['memberEmail'])));
  $data['company'] = trim($dbs->escape_string(strip_tags($_POST['companyName'])));
  $data['sug_desc'] = trim($dbs->escape_string(strip_tags($_POST['description'])));
  $data['type'] = implode(',', $_POST['suggestionType']);
  $data['status'] = 0;
  $data['create_at'] = date('Y-m-d H:i:s');
  $data['update_at'] = NULL;
  $data['delete_at'] = NULL;

  $sql_op = new simbio_dbop($dbs);
  $insert = $sql_op->insert('suggestion', $data);
  if ($insert) {
    utility::jsAlert('Saran berhasil dikirim.');
    echo '<script>window.location.href="index.php";</script>';
  } else {
    utility::jsAlert('Saran gagal dikirim.');
  }
  exit();
}
?>
<div class="row">
  <div class="col-md-12">
    <div class="row">
      <div class="col-xs-9">
         <h3>KOTAK SARAN<br>PERPUSTAKAAN FAKULTAS EKONOMI<br>UNIVERSITAS PAKUAN</h3>
      </div>
      <div class="col-xs-3">
        <h3>
          <img class="s-logo" src="./template/default/img/logo.png">
        </h3> 
      </div>
    </div>
    <hr>
    <noscript>
        <div style="font-weight: bold; color: #FF0000;"><?php echo __('Your browser does not support Javascript or Javascript is disabled. Application won\'t run without Javascript!'); ?><div>
    </noscript>
    <!-- Captcha preloaded javascript - start -->
    <?php if ($sysconf['captcha']['smc']['enable']) { ?>
      <?php if ($sysconf['captcha']['smc']['type'] == "recaptcha") { ?>
      <script type="text/javascript">
        var RecaptchaOptions = {
          theme : '<?php echo$sysconf['captcha']['smc']['recaptcha']['theme']; ?>',
          lang : '<?php echo$sysconf['captcha']['smc']['recaptcha']['lang']; ?>',
          <?php if($sysconf['captcha']['smc']['recaptcha']['customlang']['enable']) { ?>
                custom_translations : {
                instructions_visual : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['instructions_visual']; ?>",
                instructions_audio : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['instructions_audio']; ?>",
                play_again : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['play_again']; ?>",
                cant_hear_this : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['cant_hear_this']; ?>",
                visual_challenge : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['visual_challenge']; ?>",
                audio_challenge : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['audio_challenge']; ?>",
                refresh_btn : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['refresh_btn']; ?>",
                help_btn : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['help_btn']; ?>",
                incorrect_try_again : "<?php echo $sysconf['captcha']['smc']['recaptcha']['customlang']['incorrect_try_again']; ?>",
                },
          <?php } ?>
        };
      </script>
      <?php } ?>
    <?php } ?>
    <form class="form" action="index.php?p=suggestion" method="post">
      <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="memberName">Nama Lengkap</label>
              <input class="form-control" type="text" name="memberName" placeholder="Masukan Nama Anda...">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="membarEmail">Email</label>
              <input class="form-control" type="email" name="memberEmail" placeholder="Masukan Email Anda...">
            </div>
          </div>
      </div>
      <div class="row">
        <div class="col-md-6">
            <div class="form-group">
              <label for="companyName">Instansi</label>
             <input type="text" name="companyName" class="form-control" placeholder="Masukan Instansi Anda...">
                <?php 
                $com_q = $dbs->query('SELECT company_name FROM mst_companies');
                while ($com_d = $com_q->fetch_row()) {
                  echo '<option value="'.$com_d[0].'">'.$com_d[0].'</option>';
                }
                ?>
              </select>
            </div>
          </div>
      </div>
      <div class="row">
        <div class="col-md-6">
            <div class="form-group">
              <label for="phoneNumber">Jenis Saran / Masukan<br><sup><i>dapat dipilih lebih dari satu</i></sup></label>
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" value="suggestion" name="suggestionType[]">
                Saran
              </label>
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" value="comment" name="suggestionType[]">
                Komentar
              </label>
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" value="collection" name="suggestionType[]">
                Koleksi
              </label>
            </div>
          </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="description">Deskripsi Saran/Masukan</label>
            <textarea rows="5" class="form-control" name="description"></textarea>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <!-- Captcha in form - start -->
          <?php if ($sysconf['captcha']['smc']['enable']) { ?>
            <h4>SECURITY CHECK</h4>
            <?php if ($sysconf['captcha']['smc']['type'] == "recaptcha") { ?>
            <div class="captchaAdmin">
            <?php
              require_once LIB.$sysconf['captcha']['smc']['folder'].'/'.$sysconf['captcha']['smc']['incfile'];
              $publickey = $sysconf['captcha']['smc']['publickey'];
              echo recaptcha_get_html($publickey);
            ?>
            </div>
            <!-- <div><input type="text" name="captcha_code" id="captcha-form" style="width: 80%;" /></div> -->
          <?php 
            } elseif ($sysconf['captcha']['smc']['type'] == "others") {

            }
            #debugging
            #echo SWB.'lib/'.$sysconf['captcha']['folder'].'/'.$sysconf['captcha']['webfile'];
          } ?>
          <!-- Captcha in form - end -->
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <br>
          <button type="submit" name="saveData" class="btn btn-lg btn-block btn-primary">Kirim Saran</button>
        </div>
      </div>
      <hr>
    </form>
  </div>
</div>