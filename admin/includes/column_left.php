<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  if (tep_session_is_registered('admin')) {
    $cl_box_groups = array();

    if ($dir = @dir(DIR_FS_ADMIN . 'includes/boxes')) {
      $files = array();

      while ($file = $dir->read()) {
        if (!is_dir($dir->path . '/' . $file)) {
          if (substr($file, strrpos($file, '.')) == '.php') {
            $files[] = $file;
          }
        }
      }

      $dir->close();

      natcasesort($files);

      foreach ( $files as $file ) {
        if ( file_exists(DIR_FS_ADMIN . 'includes/languages/' . $language . '/modules/boxes/' . $file) ) {
          include(DIR_FS_ADMIN . 'includes/languages/' . $language . '/modules/boxes/' . $file);
        }

        include($dir->path . '/' . $file);
      }
    }

    function tep_sort_admin_boxes($a, $b) {
      return strcasecmp($a['heading'], $b['heading']);
    }

    usort($cl_box_groups, 'tep_sort_admin_boxes');

    function tep_sort_admin_boxes_links($a, $b) {
      return strcasecmp($a['title'], $b['title']);
    }

    foreach ( $cl_box_groups as &$group ) {
      usort($group['apps'], 'tep_sort_admin_boxes_links');
    }
?>
<div class="toogle">
<a class="menu-toogle-on"><i class="fa fa-chevron-right"></i></a>
<a class="menu-toogle"><i class="fa fa-chevron-left"></i></a>
</div>
<div id="adminAppMenu" >

<div class='cssmenu1 text-right'>
<ul>
<li class='active'>
</li>

<?php
    $i = '1';
    foreach ($cl_box_groups as $groups) { 
	   $status = '0';
	   foreach ($groups['apps'] as $app) {
        if ($app['code'] == $PHP_SELF) {
			$status = '1';
        }
      }
      echo '<li class="has-sub"><a><span class="text-menu">' . $groups['heading'] . '</span><i class="'.$groups['icon'].'"></i></a>' .
           '<ul class="'.(($status == '1')? 'open':'').'">';
	 echo '<div class="header" ><a>'. $groups['heading']  .'</a></div>';  
	  $j='0';
	  $cnt = count($groups['apps']);
      foreach ($groups['apps'] as $app) {
        echo '<li '.(($cnt-1 == $j) ? 'class="last"':'').'><a href="' . $app['link'] . '">' . $app['title'] .'</a></li>';
		$j++;
	  }
      $i++;
      echo '</ul></li>';

	}
?>
</ul>
</div>

</div>
<?php
  if (sizeof($languages_array) > 1) {
?>

<?php
  }
?>
<script type="text/javascript">


<?php
    $counter = 0;
    foreach ($cl_box_groups as $groups) {
      foreach ($groups['apps'] as $app) {
        if ($app['code'] == $PHP_SELF) {
          break 2;
        }
      }

      $counter++;
    }

    echo 'active: ' . (isset($app) && ($app['code'] == $PHP_SELF) ? $counter : 'false');
?>


</script>

<?php
  }
?>
