<link type="text/css" href="css/layout.css" rel="stylesheet" />	
<style>
body
{
	background:none !important;
	font-size: 11px !important;
}
</style>

  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="5">
    <tr>
      <td>
        <form id="insert_video" name="insert_video" action="?<? echo($_module); ?>/upload" method="post" enctype="multipart/form-data">
        <table width="100%"  border="0" align="center" cellpadding="0" class="fullwidth">
            <tr>
                <td>
                  Título &nbsp;<input name="title" type="textfield" class="field">
                  Tipo &nbsp;
                  <select name="type">
                  <? foreach ($type_list as $type) : ?>
                    <option value="<? echo $type['p_id']; ?>"><? echo $type['p_name']; ?></option>
                  <? endforeach; ?>
                  </select>&nbsp;&nbsp;
                  Duração &nbsp;<input name="lenght" type="textfield" class="field" style="width:60px">
                  Link &nbsp;<input name="video_url" type="textfield" class="field">
                  Capa : &nbsp;
                  <input name="image" type="file" class="field" >
                  <input name="<? echo($config['id_column']); ?>" type="hidden" value="<? echo $id; ?>" class="field">
                  <input name="Submit" type="button" class="button" value="Salvar">  
              </td>
            </tr>
           </table>
         </form>
         </td>
         <tr>
  </table>      
</form>


<form id="multiple_op" method="post" action="?<? echo($module); ?>/delete_images">
<input type="hidden" value="<? echo $id; ?>" name="<? echo($config['id_column']); ?>"/>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="fullwidth">
    <thead>
        <tr>
            <td width="20"><input type="checkbox" class="checkall" /></td>
            <td>Título</td>
            <td>Tipo</td>
            <td>Duração</td>
            <td>Link</td>
        </tr>
    </thead>
    <tbody>
<?
$x=0;

if(is_array($videos))
{
foreach($videos as $video)
{

?>
    <tr class="<? echo ($x % 2 == 0) ? 'odd' : '' ?>">
        <td><input type="checkbox" name="id_s[]" id="id_s" value="<? echo($image[$config['file_id_column']]); ?>"/></td>
        <td><? echo($video['title']); ?></td>
        <td><? echo($video['type']); ?> Kb</td>
        <td><? echo($video['lenght']); ?></td>
        <td><? echo($video['video_url']); ?></td>
    </tr>

 <?
	$x++;
}
}
?>

<?  if ( $x == 0 ) { ?>
    <tr>
        <td align="center" colspan="6"><? if ($x == 0) echo("Sem videos cadastrados! Use o box acima para inseri-los.");?>&nbsp;</td>
    </tr>
<? } ?>

    </tbody>
    <tr>
        <td colspan="10">
        <? if ($_SESSION["admin_luid"] < 2) { ?>
          <input type="submit" value="Deletar Seleção" class="button">
        <? } ?>
        </td>
    </tr>
</table>
</form>

<script language="Javascript">
document.insert_video.reset();
</script>
