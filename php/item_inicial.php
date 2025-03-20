<?php
	$nombre = toba::usuario()->get_nombre();
	$user_id = toba::usuario()->get_id();
	$user_id = mb_strtolower($user_id, 'LATIN1');  // Normalizar a minúsculas
	
	$perfil = implode('/', toba::usuario()->get_perfiles_funcionales());
	$sql = "SELECT legajo FROM agentes WHERE email = '$user_id';";
	
	$rs = toba::db('desempenio')->consultar($sql);
	
	$legajo = $rs[0]['legajo'];
	echo '<div class="logo">';	
	echo toba_recurso::imagen_proyecto('logo_grande.gif', true).'<br>';
	echo $nombre .'<br>';
	echo $user_id .'<br>';	
	echo $perfil .'<br>';
	echo $legajo .'<br>';
	echo '</div>';
?>