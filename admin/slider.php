<?php
  require('includes/application_top.php');
 // $css = array('top'=>'X Position','left'=>'Y Position','font-size'=>'Font Size','color'=>'Color','background'=>'Background Color','padd');
  //$codephp = array('top'=>'slider_title_top','left'=>'slider_title_left','font-size'=>'slider_title_font_size','color'=>'slider_title_color','background'=>'slider_title_background');
  $anistyle = array('fadeIn'=>'Fade In','topBottom'=>'from TOP','bottomTop'=>'from Bottom','leftRight'=>'from Left','rightLeft'=>'from Right');
  foreach($anistyle as $key => $value){ $anistyle_array[] = array('id' => $key, 'text' => $value); }
  $delay = array('1'=>'1 second','2'=>'2 second','3'=>'3 second');
  foreach($delay as $key=>$value){$delay_array[] = array('id'=>$key,'text'=>$value);}
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
    $nowdate = date("Y-m-d");
	
  if (tep_not_null($action)) {
  

 
    
  
  
  switch ($action) {
	case 'insert_new':
		$sql_data_array = array('slider_title' => tep_db_prepare_input($HTTP_POST_VARS['slider_title']),
								'slider_link' => tep_db_prepare_input($HTTP_POST_VARS['slider_link']),
								'slider_status'=> '1',
								'slider_sort'=>tep_db_prepare_input($HTTP_POST_VARS['slider_sort'])
								);
		
        $slider_image = new upload('slider_image');
        $slider_image->set_destination(DIR_FS_CATALOG_IMAGES.'/slider/');
        if ($slider_image->parse() && $slider_image->save()) {
          $sql_data_array['slider_image'] = tep_db_prepare_input($slider_image->filename);
        }
		tep_db_perform(TABLE_SLIDER,$sql_data_array);
		tep_redirect(tep_href_link(FILENAME_SLIDER));
		break;
	case 'saveanimate':
		  $slider_id = tep_db_prepare_input($HTTP_GET_VARS['sID']);
		  $color_title = ((!empty($HTTP_POST_VARS['slider_color_title'])) ? 'color:'.tep_db_prepare_input($HTTP_POST_VARS['slider_color_title']).';' : '');
		  $bg_title = ((!empty($HTTP_POST_VARS['slider_background_title'])) ? 'background:'.tep_db_prepare_input($HTTP_POST_VARS['slider_background_title']).';' : '');	
		  $font_title = ((!empty($HTTP_POST_VARS['slider_font_title'])) ? 'font-size:'.tep_db_prepare_input($HTTP_POST_VARS['slider_font_title']).'px; ' : '');
		  $padding_title = ((!empty($HTTP_POST_VARS['slider_padding_title'])) ? 'padding:'.tep_db_prepare_input($HTTP_POST_VARS['slider_padding_title']).'px !important;' : '');
		  $width_title = ((!empty($HTTP_POST_VARS['slider_width_title'])) ? 'width:'.tep_db_prepare_input($HTTP_POST_VARS['slider_width_title']).'px;' : '');
		  $left_title = ((!empty($HTTP_POST_VARS['slider_left_title'])) ? 'left:'.tep_db_prepare_input($HTTP_POST_VARS['slider_left_title']).'%;' : '');
		  $top_title = ((!empty($HTTP_POST_VARS['slider_top_title'])) ? 'top:'.tep_db_prepare_input($HTTP_POST_VARS['slider_top_title']).'%;' : ''); 
		  if(isset($HTTP_POST_VARS['backtitle_cb'])) { $title_bg = '1'; } else {$title_bg = '0';}
		  
		  
		  $color_text1 = ((!empty($HTTP_POST_VARS['slider_color_text1'])) ? 'color:'.tep_db_prepare_input($HTTP_POST_VARS['slider_color_text1']).';' : '');
		  $bg_text1= ((!empty($HTTP_POST_VARS['slider_background_text1'])) ? 'background:'.tep_db_prepare_input($HTTP_POST_VARS['slider_background_text1']).';' : '');	
		  $font_text1 = ((!empty($HTTP_POST_VARS['slider_font_text1'])) ? 'font-size:'.tep_db_prepare_input($HTTP_POST_VARS['slider_font_text1']).'px;' : '');
		  $padding_text1 = ((!empty($HTTP_POST_VARS['slider_padding_text1'])) ? 'padding:'.tep_db_prepare_input($HTTP_POST_VARS['slider_padding_text1']).'px !important;' : '');
		  $width_text1 = ((!empty($HTTP_POST_VARS['slider_width_text1'])) ? 'width:'.tep_db_prepare_input($HTTP_POST_VARS['slider_width_text1']).'px;' : '');
		  $left_text1 = ((!empty($HTTP_POST_VARS['slider_left_text1'])) ? 'left:'.tep_db_prepare_input($HTTP_POST_VARS['slider_left_text1']).'%;' : '');
		  $top_text1 = ((!empty($HTTP_POST_VARS['slider_top_text1'])) ? 'top:'.tep_db_prepare_input($HTTP_POST_VARS['slider_top_text1']).'%;' : '');
		  if(isset($HTTP_POST_VARS['text1_cb'])) { $text1_bg = '1'; } else {$text1_bg = '0';}

		  $color_text2 = ((!empty($HTTP_POST_VARS['slider_color_title'])) ? 'color:'.tep_db_prepare_input($HTTP_POST_VARS['slider_color_text2']).';' : '');
		  $font_text2 = ((!empty($HTTP_POST_VARS['slider_font_title'])) ? 'font-size:'.tep_db_prepare_input($HTTP_POST_VARS['slider_font_text2']).'px;' : '');
		  $bg_text2= ((!empty($HTTP_POST_VARS['slider_background_text2'])) ? 'background:'.tep_db_prepare_input($HTTP_POST_VARS['slider_background_text2']).';' : '');	
		  $padding_text2 = ((!empty($HTTP_POST_VARS['slider_padding_text2'])) ? 'padding:'.tep_db_prepare_input($HTTP_POST_VARS['slider_padding_text2']).'px !important;' : '');
		  $width_text2 = ((!empty($HTTP_POST_VARS['slider_width_title'])) ? 'width:'.tep_db_prepare_input($HTTP_POST_VARS['slider_width_text2']).'px;' : '');
		  $left_text2 = ((!empty($HTTP_POST_VARS['slider_left_title'])) ? 'left:'.tep_db_prepare_input($HTTP_POST_VARS['slider_left_text2']).'%;' : '');
		  $top_text2 = ((!empty($HTTP_POST_VARS['slider_top_title'])) ? 'top:'.tep_db_prepare_input($HTTP_POST_VARS['slider_top_text2']).'%;' : '');
		  if(isset($HTTP_POST_VARS['text2_cb'])) { $text2_bg = '1'; 
		  } else {$text2_bg = '0';}
		  $css_title = $color_title.$font_title.(($title_bg=='1') ? $bg_title.$padding_title : '' ).$width_title.$left_title.$top_title;
		  $css_text1 = $color_text1.$font_text1.(($text1_bg=='1') ? $bg_text1.$padding_text1 : '' ) .$width_text1.$left_text1.$top_text1;
		  $css_text2 = $color_text2.$font_text2.(($text2_bg=='1') ? $bg_text2.$padding_text2 : '' ).$width_text2.$left_text2.$top_text2;
		  
		  $sql_data_array = array('slider_title'=> tep_db_prepare_input($HTTP_POST_VARS['slider_title']),
									'slider_text1'=>tep_db_prepare_input($HTTP_POST_VARS['slider_text1']),
									'slider_text2'=>tep_db_prepare_input($HTTP_POST_VARS['slider_text2']),
									'slider_ani_title'=>$HTTP_POST_VARS['slider_ani_title'].$HTTP_POST_VARS['slider_ani_title_delay'],
									'slider_ani_text1'=>$HTTP_POST_VARS['slider_ani_text1'].$HTTP_POST_VARS['slider_ani_text1_delay'],
									'slider_ani_text2'=>$HTTP_POST_VARS['slider_ani_text2'].$HTTP_POST_VARS['slider_ani_text2_delay'],
									'slider_title_style'=>$css_title,
									'slider_text1_style'=>$css_text1,
									'slider_text2_style'=>$css_text2,
									'slider_title_bg'=>$title_bg,
									'slider_text1_bg'=>$text1_bg,
									'slider_text2_bg'=>$text2_bg);
			//echo $css_text1;
		  tep_db_perform(TABLE_SLIDER,$sql_data_array,'update',"slider_id='".(int)$slider_id."'");
		  if (USE_CACHE == 'true') {
          tep_reset_cache_block('slider');
		  }
		tep_redirect(tep_href_link(FILENAME_SLIDER,'action=edita&sID='.$slider_id));
		
		
		break;
	case 'save':
		$slider_id = tep_db_prepare_input($HTTP_GET_VARS['sID']);
		$sql_data_array = array('slider_title' => tep_db_prepare_input($HTTP_POST_VARS['slider_title']),
								'slider_link'=> tep_db_prepare_input($HTTP_POST_VARS['slider_link']),
								'slider_sort'=>tep_db_prepare_input($HTTP_POST_VARS['slider_sort'])
								);
        $slider_image = new upload('slider_image');
        $slider_image->set_destination(DIR_FS_CATALOG_IMAGES.'/slider/');
        if ($slider_image->parse() && $slider_image->save()) {
          $sql_data_array['slider_image'] = tep_db_prepare_input($slider_image->filename);
        }
		tep_db_perform(TABLE_SLIDER,$sql_data_array,'update',"slider_id='".(int)$slider_id."'");
		if (USE_CACHE == 'true') {
          tep_reset_cache_block('slider');
        }
		tep_redirect(tep_href_link(FILENAME_SLIDER));
		break;
	case 'deleteconfirm':
		 $slider_id = tep_db_prepare_input($HTTP_GET_VARS['sID']);
		$slider_query = tep_db_query("select slider_image from ".TABLE_SLIDER." where slider_id = '".(int)$slider_id."'");
		$slider = tep_db_fetch_array($slider_query);
		$image_location = DIR_FS_DOCUMENT_ROOT.DIR_FS_CATALOG_IMAGES.'/slider/'.$slider['slider_image'];
		if (file_exists($image_location)) @unlink($image_location);
		tep_db_query("delete from ".TABLE_SLIDER." where slider_id = '".(int)$slider_id."'");
		@unlink(DIR_FS_CATALOG_IMAGES.'/slider/'. $slider['slider_image']);
        if (USE_CACHE == 'true') {
          tep_reset_cache_block('slider');
        }
		tep_redirect(tep_href_link(FILENAME_SLIDER));
	break;
	}
  }

require(DIR_WS_INCLUDES . 'template_top.php');
function get_value_css($source_value)
{
	$css_value = substr($source_value,strpos($source_value,":")+1);
	$css_value_clean = str_replace('px','',$css_value);
	$css_value_clean1 = str_replace('%','',$css_value_clean);
	$css_value_clean2 = str_replace('!important','',$css_value_clean1);
	return str_replace(' ', '', $css_value_clean2);
}
function get_name_css($source_name){
	return str_replace(' ','',substr($source_name,0,strpos($source_name,":")));
}
function get_array($source){
	for($i=0;$i<count($source)-1;$i++){
		$makearray[get_name_css($source[$i])] = get_value_css($source[$i]);
	}
	return $makearray;
}
?>
<script>
$(function() {
    $(".colorTitle").colorpicker({format:'rgba'});
	 $(".bgTitle").colorpicker({format:'rgba'});
	 $(".colorText1").colorpicker({format:'rgba'});
	 $(".bgText1").colorpicker({format:'rgba'});
	  $(".colorText2").colorpicker({format:'rgba'});
	 $(".bgText2").colorpicker({format:'rgba'});
});
function checkbox(item) {
$('.'+item).show();
	
}
function uncheck(item) {
	$('.'+item).hide();
}

$(document).ready(function(){
	if ($("#backtitle_cb").is(':checked'))$(".backtitle_cb").show(); else $(".backtitle_cb").hide();
	if ($("#text1_cb").is(':checked'))$(".text1_cb").show(); else $(".text1_cb").hide();
	if ($("#text2_cb").is(':checked'))$(".text2_cb").show(); else $(".text2_cb").hide();
});
</script>

<?php
	if($action == 'edita' && isset($HTTP_GET_VARS['sID'])) {
?>
<div class="sub-content">
<?php
		$s_query = tep_db_query("select * from ".TABLE_SLIDER." where slider_id ='".(int)$HTTP_GET_VARS['sID']."'");
		$s = tep_db_fetch_array($s_query);
		$title_style = explode(';', $s['slider_title_style']);
		$text1_style = explode(';', $s['slider_text1_style']);
		$text2_style = explode(';', $s['slider_text2_style']);
		$title_array = get_array($title_style);
		$text1_array = get_array($text1_style);
		$text2_array = get_array($text2_style);
 echo tep_draw_form('slider', FILENAME_SLIDER, 'page=' . $HTTP_GET_VARS['page'] . '&action=saveanimate&sID='.$HTTP_GET_VARS['sID']);
 
?>
	
	<h1><?php echo $s['slider_title'];?></h1>
	<?php
		echo '<style>.image div{font-weight:normal; }.title{'.$s['slider_title_style'].'} .text1{'.$s['slider_text1_style'].'} .text2{'.$s['slider_text2_style'].'}</style>';
	?>
	<div class="image" style="width:1130px; border:2px solid #999; top:0; position:relative; overflow:hidden">
		<?php echo tep_image(DIR_WS_CATALOG_IMAGES.'/slider/'.$s['slider_image'],'', '','','style="width:100%; height:auto"');?>
		<div class="title" style="position:absolute;"><?php echo $s['slider_title'];?></div>
		<div class="text1" style="position:absolute;"><?php echo $s['slider_text1'];?></div>
		<div class="text2" style="position:absolute;"><?php echo $s['slider_text2'];?></div>
	</div>
	
	<table class="table">
	   <tr>
	   <td>
	   <table class="table borderless">
	   <tr><td colspan="2"><h2>Title<h2></td></tr>
		<tr>
			<td>Slider Title</td>
			<td><?php echo tep_draw_input_field('slider_title',$s['slider_title'],' style="width:200px;"');?></td>
		</tr>
		<tr>
			<td>Slider Animation</td>
			<td><?php echo tep_draw_pull_down_menu('slider_ani_title', $anistyle_array,str_replace(substr($s['slider_ani_title'],-1),'',$s['slider_ani_title']));?> </td>
		</tr>
		<tr>
			<td>Slider Animation Delay</td>
			<td><?php echo tep_draw_pull_down_menu('slider_ani_title_delay',$delay_array,substr($s['slider_ani_title'],-1));?>  from Start</td>
		</tr>
		<tr>
			<td>Slider Text Color</td>
			
			<td><div class="input-group colorTitle">
				<span class="input-group-addon"><i></i></span><?php echo tep_draw_input_field('slider_color_title',$title_array['color']);?></div>
			</td>
		</tr>
		
		<tr>
			<td>Font Size</td>
			<td><?php echo tep_draw_input_field('slider_font_title',$title_array['font-size']);?>
			</td>
		</tr>
		
		<tr>
			<td>Fix Width</td>
			<td><?php echo tep_draw_input_field('slider_width_title',$title_array['width']);?>
			</td>
		</tr>
		<tr>
			<td>Position X</td>
			<td><?php echo tep_draw_input_field('slider_left_title',$title_array['left']);?>
			</td>
		</tr>
		<tr>
			<td>Position Y</td>
			<td><?php echo tep_draw_input_field('slider_top_title',$title_array['top']);?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><input id="backtitle_cb" name="backtitle_cb" type="checkbox" <?php echo (($s['slider_title_bg']=='1') ? 'checked':'');?> onclick="if(this.checked){checkbox(this.name)} else{uncheck(this.name)}"> Use Background Text
		</tr>
		<tr class="backtitle_cb">
			<td>Slider Text Background</td>
			<td><div class="input-group bgTitle"><span class="input-group-addon"><i></i></span><?php echo tep_draw_input_field('slider_background_title',$title_array['background']);?></div>
			</td>
		</tr>
		<tr class="backtitle_cb">
			<td>Padding</td>
			<td><?php echo tep_draw_input_field('slider_padding_title',$title_array['padding']);?>
			</td>
		</tr>
		</table>
		</td>
		
		<!----TEXT 1----->
		
		<td>
		<table class="table borderless">
		<tr><td colspan="2"><h2>TEXT 1<h2></td></tr>
		<tr>
			<td>Slider Text</td>
			<td><?php echo tep_draw_input_field('slider_text1',$s['slider_text1'],' style="width:200px;"');?></td>
		</tr>
		<tr>
			<td>Slider Animation</td>
			<td><?php echo tep_draw_pull_down_menu('slider_ani_text1', $anistyle_array,str_replace(substr($s['slider_ani_text1'],-1),'',$s['slider_ani_text1']));?> </td>
		</tr>
		<tr>
			<td>Slider Animation Delay</td>
			<td><?php echo tep_draw_pull_down_menu('slider_ani_text1_delay',$delay_array,substr($s['slider_ani_text1'],-1));?>  from Start</td>
		</tr>
		<tr>
			<td>Slider Text Color</td>
			<td><div class="input-group colorText1"><span class="input-group-addon"><i></i></span><?php echo tep_draw_input_field('slider_color_text1',$text1_array['color']);?></div>
			</td>
		</tr>
		
		<tr>
			<td>Font Size</td>
			<td><?php echo tep_draw_input_field('slider_font_text1',$text1_array['font-size']);?>
			</td>
		</tr>
	
		<tr>
			<td>Fix Width</td>
			<td><?php echo tep_draw_input_field('slider_width_text1',$text1_array['width']);?>
			</td>
		</tr>
		<tr>
			<td>Position X</td>
			<td><?php echo tep_draw_input_field('slider_left_text1',$text1_array['left']);?>
			</td>
		</tr>
		<tr>
			<td>Position Y</td>
			<td><?php echo tep_draw_input_field('slider_top_text1',$text1_array['top']);?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><input id="text1_cb" name="text1_cb" type="checkbox" <?php echo (($s['slider_text1_bg']=='1') ? 'checked':'');?> onclick="if(this.checked){checkbox(this.name)} else{uncheck(this.name)}"> Use Background Text
		</tr>
		<tr class="text1_cb">
			<td>Slider Text Background</td>
			<td><div class="input-group bgText1"><span class="input-group-addon"><i></i></span><?php echo tep_draw_input_field('slider_background_text1',$text1_array['background']);?></div>
			</td>
		</tr>
		<tr class="text1_cb">
			<td>Padding</td>
			<td><?php echo tep_draw_input_field('slider_padding_text1',$text1_array['padding']);?>
			</td>
		</tr>
		</table>
		</td>
		<!--TEXT1 END-->
		<!--TEXT2 start-->
		<td>
		<table class="table borderless">
		<tr><td colspan="2"><h2>TEXT 2<h2></td></tr>
		<tr>
			<td>Slider Text1</td>
			<td><?php echo tep_draw_input_field('slider_text2',$s['slider_text2'],' style="width:200px;"');?></td>
		</tr>
		<tr>
			<td>Slider Animation</td>
			<td><?php echo tep_draw_pull_down_menu('slider_ani_text2', $anistyle_array,str_replace(substr($s['slider_ani_text2'],-1),'',$s['slider_ani_text2']));?> </td>
		</tr>
		<tr>
			<td>Slider Animation Delay</td>
			<td><?php echo tep_draw_pull_down_menu('slider_ani_text2_delay',$delay_array,substr($s['slider_ani_text2'],-1));?>  from Start</td>
		</tr>
		<tr>
			<td>Slider Text Color</td>
			<td><div class="input-group colorText2"><span class="input-group-addon"><i></i></span><?php echo tep_draw_input_field('slider_color_text2',$text2_array['color']);?></div>
			</td>
		</tr>
		
		<tr>
			<td>Font Size</td>
			<td><?php echo tep_draw_input_field('slider_font_text2',$text2_array['font-size']);?>
			</td>
		</tr>
		
		<tr>
			<td>Fix Width</td>
			<td><?php echo tep_draw_input_field('slider_width_text2',$text2_array['width']);?>
			</td>
		</tr>
		<tr>
			<td>Position X</td>
			<td><?php echo tep_draw_input_field('slider_left_text2',$text2_array['left']);?>
			</td>
		</tr>
		<tr>
			<td>Position Y</td>
			<td><?php echo tep_draw_input_field('slider_top_text2',$text2_array['top']);?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><input id="text2_cb" name="text2_cb" type="checkbox" <?php echo (($s['slider_text2_bg']=='1') ? 'checked':'');?> onclick="if(this.checked){checkbox(this.name)} else{uncheck(this.name)}"> Use Background Text
		</tr>
		<tr class="text2_cb">
			<td>Slider Text Background</td>
			<td><div class="input-group bgText2"><span class="input-group-addon"><i></i></span><?php echo tep_draw_input_field('slider_background_text2',$text2_array['background']);?></div>
			</td>
		</tr>
		<tr class="text2_cb">
			<td>Padding</td>
			<td><?php echo tep_draw_input_field('slider_padding_text2',$text2_array['padding']);?>
			</td>
		</tr>
		</table>
		</td>
		
		<!--TEXT2 END-->
	</table>
	<?php echo tep_draw_button_bs(IMAGE_SAVE, 'save', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_SLIDER, 'page=' . $HTTP_GET_VARS['page']),'primary');?>
	
</form>
</div>
<?php		
		
	
//////		
	}
		else {
///////
 ?>
<div class="sub-content">
<h1><?php echo HEADING_TITLE;?></h1>
<div class="row">
<div class="col-md-9 main-content">
<table class="table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SLIDER_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SLIDER_STATUS; ?></td>
				<td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SLIDER_SORT; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SLIDER_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $slider_query_raw = "select slider_id, slider_title, slider_status, slider_link, slider_image, slider_sort, slider_text1,slider_text2 from " . TABLE_SLIDER . " order by slider_sort";
  $slider_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $slider_query_raw, $slider_query_numrows);
  $slider_query = tep_db_query($slider_query_raw);
  while ($slider = tep_db_fetch_array($slider_query)) {
    if ((!isset($HTTP_GET_VARS['sID']) || (isset($HTTP_GET_VARS['sID']) && ($HTTP_GET_VARS['sID'] == $slider['slider_id']))) && !isset($sInfo) && (substr($action, 0, 3) != 'new')) {
      $sInfo = new objectInfo($slider);
    }

    if (isset($sInfo) && is_object($sInfo) && ($slider['slider_id'] == $sInfo->slider_id)) {
      echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SLIDER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->slider_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SLIDER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $slider['slider_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $slider['slider_title']; ?></td>
                <td class="dataTableContent" align="center" width="40"><?php echo $slider['silder_status']; ?></td>
                <td class="dataTableContent" align="center" width="40"><?php echo $slider['slider_sort']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($sInfo) && is_object($sInfo) && ($slider['slider_id'] == $sInfo->slider_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_SLIDER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $slider['slider_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $slider_split->display_count($slider_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SLIDER); ?></td>
                    <td class="smallText" align="right"><?php echo $slider_split->display_links($slider_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td class="smallText" colspan="2" align="right"><?php echo tep_draw_button_bs(IMAGE_NEW_SLIDER, 'plus', tep_href_link(FILENAME_SLIDER, 'page=' . $HTTP_GET_VARS['page'] . '&action=new')); ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table>
</div>
<div class="col-md-3">
<?php
  $heading = array();
  $contents = array();	
  
  switch ($action) {
	case 'new':
		$heading[] = array('text'=> '<strong>'.TEXT_NEW_SLIDER.'</strong>');
		$contents = array('form' => tep_draw_form('slider', FILENAME_SLIDER, 'action=insert_new', 'post', 'enctype="multipart/form-data"'));
		$contents[] = array('text' => TEXT_INFO_TITLE.' : <br />'.tep_draw_input_field('slider_title','','class="form-control"'));
		$contents[] = array('text' => TEXT_INFO_LINK.' : <br />'.tep_draw_input_field('slider_link','','class="form-control"'));
		$contents[] = array('text' => TEXT_INFO_IMAGE.' : <br />'.tep_draw_file_field('slider_image'));
		$contents[] = array('text' => TEXT_INFO_SORT.' : <br />'.tep_draw_input_field('slider_sort','','class="form-control"'));
		$contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_SAVE, 'save', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_SLIDER, 'sID=' . $HTTP_GET_VARS['sID']),'primary'));
	break;
    case 'edit':
		$heading[] = array('text' => '<strong>'.$sInfo->slider_title.'</strong>');
        $contents = array('form' => tep_draw_form('slider', FILENAME_SLIDER, 'action=save&sID='.$sInfo->slider_id, 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => tep_image(DIR_WS_CATALOG_IMAGES.'/slider/'.$sInfo->slider_image,'', '','','style="width:100%"'));
		$contents[] = array('text' => TEXT_INFO_TITLE.' : <br />'.tep_draw_input_field('slider_title',$sInfo->slider_title,'class="form-control"'));
		$contents[] = array('text' => TEXT_INFO_LINK.' : <br />'.tep_draw_input_field('slider_link',$sInfo->slider_link,'class="form-control"'));
		$contents[] = array('text' => TEXT_INFO_IMAGE.' : <br />'.tep_draw_file_field('slider_image'));
		$contents[] = array('text' => TEXT_INFO_SORT.' : <br />'.tep_draw_input_field('slider_sort',$sInfo->slider_sort,'class="form-control"'));
		$contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_SAVE, 'save', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_SLIDER, 'sID=' . $HTTP_GET_VARS['sID']),'primary'));
      break;
    case 'delete':
      $heading[] = array('text' => '<strong>' . TEXT_INFO_DELETE_SLIDER . '</strong>');

      $contents = array('form' => tep_draw_form('countries', FILENAME_SLIDER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->slider_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><strong>' . $sInfo->slider_title . '</strong>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button_bs(IMAGE_DELETE, 'trash', null, 'primary') . tep_draw_button_bs(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_SLIDER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->slider_id)));
      break;
    default:
      if (is_object($sInfo)) {
        $heading[] = array('text' => '<strong>' . $sInfo->slider_title . '</strong>');
		$contents[] = array('text' => tep_image(DIR_WS_CATALOG_IMAGES.'/slider/'.$sInfo->slider_image,'', '','','style="width:100%"'));
        $contents[] = array('text' => '<br />' . TEXT_INFO_TITLE . '<br /><strong>' . $sInfo->slider_title.'</strong>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_TEXT1 . '<br /><strong>' . $sInfo->slider_text1.'</strong>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_TEXT2 . '<br /><strong>' . $sInfo->slider_text2.'</strong>');
		$contents[] = array('align' => 'center', 'text' => '<br />'.tep_draw_button_bs(IMAGE_EDIT, 'edit', tep_href_link(FILENAME_SLIDER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->slider_id . '&action=edit'),'primary') .tep_draw_button_bs(IMAGE_TEXT_ANIMATE, 'edit', tep_href_link(FILENAME_SLIDER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->slider_id . '&action=edita')). tep_draw_button_bs(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_SLIDER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->slider_id . '&action=delete'),'primary').'<br />');
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
  
	</div>
</div>	
</div>
<?php
	}
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
