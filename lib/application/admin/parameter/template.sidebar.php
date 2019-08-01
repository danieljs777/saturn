<form name="search" action="?parameter/list_page" method="post">
Filtro
<br />
<br />
<p>
<label for="search_table">Tabela</label>
<? Render::select_list("search_table", $sidebar, (isset($_REQUEST['search_table']) ? $_REQUEST['search_table'] : '')); ?>
</p>
<br />
<input type="submit" value="Buscar" class="button"/>
<br />
</form>
