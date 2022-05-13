<header class="s-header container" role="banner">
  <div class="row">
    <div class="col-lg-6">
      <a href="index.php" class="s-brand">
        <img class="s-logo animated flipInY delay7" src="<?php echo $sysconf['template']['dir']; ?>/default/img/logo.png" alt="<?php echo $sysconf['library_name']; ?>" />
        <!-- <h1 class="animated fadeInUp delay2">--><?php //echo $sysconf['library_name']; ?><!--</h1>-->
        <h1 class="animated fadeInUp delay2">PERPUSTAKAAN FAKULTAS EKONOMI</h1>
        <div class="s-brand-tagline animated fadeInUp delay3"><?php echo $sysconf['library_subname']; ?></div><br>
      </a>
    </div>
    <div class="col-lg-6">
      <div class="s-nav-header pull-right">
        <ul>
        <?php 
        $nav_header = array(
          'login' => array(
            'text' => '<span>LOGIN</span>',
            'url' => 'index.php?p=member',
            'icon' => 'login.png'),
          'reference' => array(
            'text' => '<span>REFERENCE</span><span>SERVICE</span>',
            'url' => 'index.php?p=reference',
            'icon' => 'reference-services.png'),
          'suggestion' => array(
            'text' => '<span>SUGGESTION</span><span>BOX</span>',
            'url' => 'index.php?p=suggestion',
            'icon' => 'suggestion-box.png')
          );
        $img_location = $sysconf['template']['dir'].'/'.$sysconf['template']['theme'].'/img/gmd/';
        foreach ($nav_header as $nav_key => $nav_value) {
          echo '<li><a href="'.$nav_value['url'].'"><img src="'.$img_location.$nav_value['icon'].'" /><div class="nav-text">'.$nav_value['text'].'</div></a></li>';
        }
        ?>
        <li class="s-menu">
          <a href="#" id="show-menu" class="s-menu-toggle" role="navigation"><span></span></a>
        </li>
        </ul>
      </div>
    </div>
  </div>
</header>
