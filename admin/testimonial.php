<?php
  require('includes/application_top.php');
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
   function tep_set_testimonial_status($tID, $status) {
    if ($status == '2') {
      return tep_db_query("update " . TABLE_TESTIMONIAL . " set testi_status = '2' where testi_id = '" . (int)$tID . "'");
    } elseif ($status == '1') {
      return tep_db_query("update " . TABLE_TESTIMONIAL . " set testi_status = '1' where testi_id = '" . (int)$tID . "'");
    } else {
      return -1;
    }
  }
  if (tep_not_null($action)) {
    switch ($action) {
		case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '1') || ($HTTP_GET_VARS['flag'] == '2') ) {
          if (isset($HTTP_GET_VARS['tID'])) {
            tep_set_testimonial_status($HTTP_GET_VARS['tID'], $HTTP_GET_VARS['flag']);
          }
        }
		tep_redirect(tep_href_link(FILENAME_TESTIMONIAL));
		break;
		case 'reject':
			tep_db_query("update ".TABLE_TESTIMONIAL." set testi_status='3' where testi_id='".(int)$HTTP_GET_VARS['tID']."'");
			tep_redirect(tep_href_link(FILENAME_TESTIMONIAL));
		break;
		case 'accept':
			tep_db_query("update ".TABLE_TESTIMONIAL." set testi_status='1' where testi_id='".(int)$HTTP_GET_VARS['tID']."'");
			tep_redirect(tep_href_link(FILENAME_TESTIMONIAL));
		case 'del':
			tep_db_query("delete from ".TABLE_TESTIMONIAL." where testi_id='".(int)$HTTP_GET_VARS['tID']."'");
			tep_redirect(tep_href_link(FILENAME_TESTIMONIAL));
		
		break;
    }
  }
	$status = array (TEXT_NEED_APPROVAL,TEXT_ACTIVE,TEXT_INNACTIVE,TEXT_REJECTED);
	for($i=0;$i<count($status);$i++)
	{ $arrayStatus[] = array ('id' => $i, 'text' => $status[$i]); }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<div class="sub-content">
<h1><?php echo HEADING_TITLE;?></h1>

<table class="table">
	<thead>
	<tr>
		<th>ID</th>
		<th><?php echo TABLE_HEADING_TESTI_SENDER;?></th>
		<th><?php echo TABLE_HEADING_TESTI_EMAIL;?></th>
		<th><?php echo TABLE_HEADING_TESTI_CONTENT;?></th>
		<th><?php echo TABLE_HEADING_TESTI_STATUS;?></th>
		<th><?php echo TABLE_HEADING_TESTI_ACTION;?></th>
	</tr>
	</thead>
	<?php
		$testi_query_row = "select * from " . TABLE_TESTIMONIAL . " order by testi_status, testi_date DESC";
		$testi_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $testi_query_row, $testi_query_numrows);
		$testi_query = tep_db_query($testi_query_row);
		while($testi = tep_db_fetch_array($testi_query))
		{
	?>
	<tr  onmouseover="rowOverEffectNo(this)" onmouseout="rowOutEffectNo(this)">
	<td><?php echo $testi['testi_id'];?></td>
		<td><?php echo $testi['testi_name'];?></td>
		<td><?php echo $testi['testi_email'];?></td>
		<td><?php echo $testi['testi_text'];?></td>
		<td>
			<?php
				if($testi['testi_status'] == 0)
				{
					echo '<div class="btnAction">';
					echo '<a href="'.tep_href_link(FILENAME_TESTIMONIAL,'action=accept&tID='.$testi['testi_id']).'" class="btn btn-primary">'.TEXT_TESTI_ACCEPT.'</a>';
					echo ' <a href="'.tep_href_link(FILENAME_TESTIMONIAL,'action=reject&tID='.$testi['testi_id']).'" class="btn btn-primary">'.TEXT_TESTI_REJECT.'</a>';
					echo '</div>';
				} else {
					if ($testi['testi_status'] == '1') {
						echo '<div class="btnOn"><a href="'.tep_href_link(FILENAME_TESTIMONIAL,'action=setflag&flag=2&tID='.$testi['testi_id']).'" class="btn btn-primary"><span>On</span></a></div>';
						} 
					else if ($testi['testi_status'] == '2') {
						echo '<div class="btnOff"><a href="'.tep_href_link(FILENAME_TESTIMONIAL, 'action=setflag&flag=1&tID=' . $testi['testi_id']).'" class="btn btn-primary"><span>Off</span></a></div>';
						}
				}		
			?>
		</td>
		<td><div class="btnAction"><a href="<?php echo tep_href_link(FILENAME_TESTIMONIAL,'action=del&tID='.$testi['testi_id']);?>" onClick="return confirm('<?php echo TEXT_DELETE;?> <?php echo $testi['testi_name'];?> ?')" class="btn btn-primary"><?php echo TEXT_DELETE;?></a></div></td>
		
	</tr>
	<? } ?>
	  <tr>
            <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $testi_split->display_count($testi_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $testi_split->display_links($testi_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;</td>
              </tr>
            </table></td>
       </tr>
</table>

</div>



<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
