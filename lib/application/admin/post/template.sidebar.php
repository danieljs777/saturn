<form name="search" action="?city/list_page" method="post">
Filtro
<br />
<br />
<p>
<label for="search_table">Cidade</label>
<? Render::select_list("city", $sidebar, (isset($_REQUEST['city']) ? $_REQUEST['city'] : '')); ?>
</p>
<br />
<input type="submit" value="Buscar" class="button"/>
<br />
</form>
