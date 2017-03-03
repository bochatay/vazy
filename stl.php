<?php
/*
    VAZY Online vase design tool
    Copyright (C) 2017  Valentin Bochatay
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program. If not, see <http://www.gnu.org/licenses/>.

*/

/*
	Informations trouvées sur:
	https://en.wikipedia.org/wiki/STL_(file_format)
	
*/
/*
	Le modèle de base pour un vase est une superposition de cercles formant un cylindre.
	hauteur du modèle: $steps_v points et $height mm;
	La génération du vase commence du bas vers le haut.
	Les paramètres modifient chaque cercle donnant un polygone de $steps_h sommets
	Les points d'un polygones sont reliés aux points du polygone supérieur par des triangles
*/

header("Content-Type: application/octet-stream; charset=iso-8859-1");
$name = $_POST["nom"];
if(strlen($name)<1) $name="sans_titre";
if(strlen($name)>100) $name="sans_titre";
header("Content-disposition: attachment; filename=$name.stl");
session_cache_limiter('public, must-revalidate');
 
class vertex
{
	var $x;
	var $y;
	var $z;
	function vertex($x,$y,$z)
	{
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
	}
	function setPosition($x,$y,$z)
	{
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
	}
}
class triangle
{
	var $v;
	function triangle($x1,$y1,$z1,$x2,$y2,$z2,$x3,$y3,$z3)
	{
		$this->v = array(new vertex($x1,$y1,$z1),new vertex($x2,$y2,$z2),new vertex($x3,$y3,$z3));
	}
	function setVertex($n,$vert)
	{
		$this->v[$n] = $vert;
		//$this->v[$n] = new vertex($vert->x,$vert->y,$vert->z);
	}
	function getBinary()	// retourne au format STL binaire
	{
		$r = '';
		$r .= pack("f",0.0);	// N
		$r .= pack("f",0.0);	// N
		$r .= pack("f",0.0);	// N
		$r .= pack("f",$this->v[0]->x);	// V1
		$r .= pack("f",$this->v[0]->y);	// V1
		$r .= pack("f",$this->v[0]->z);	// V1
		$r .= pack("f",$this->v[1]->x);	// V2
		$r .= pack("f",$this->v[1]->y);	// V2
		$r .= pack("f",$this->v[1]->z);	// V2
		$r .= pack("f",$this->v[2]->x);	// V3
		$r .= pack("f",$this->v[2]->y);	// V3
		$r .= pack("f",$this->v[2]->z);	// V3
		$r .= pack("S",0);	// 16 bits Attribute byte count
		return $r;
	}
}

function creeHeader($t)	// donner le nombre de triangles
{
	$r = 'Created with vazy.livehost.fr by Valentin Bochatay.';
	$s = strlen($r);
	for($n=0 ; $n<(80-$s) ; $n++)
		$r .= pack("C",0);
	$r .= pack("V",$t);
	return $r;
}

function testnumber ($val,$min,$max)
{	
	if(is_numeric($val))
	{
		if($val < $min) $val = $min;
		if($val > $max) $val = $max;
	}
	else
	$val = $min;	
	return $val;
}

// VARIABLES DE FONCTIONNEMENT

$height = testnumber(floatval($_POST["hauteur"]),0.1,1000000.0);
$steps_v = testnumber(intval($_POST["pasvertical"]),2,160);
$steps_h = testnumber(intval($_POST["pashorizontal"]),3,240);
$rayon = testnumber(floatval($_POST["rayon"]),0.1,10000.0);
$offsetvertical = testnumber(floatval($_POST["offsetvertical"]),-10000.0,10000.0);	// degre
$profil_plage = testnumber(floatval($_POST["plage"]),-1000000,1000000);		// degres
$offsethorizontal = testnumber(floatval($_POST["offsethorizontal"]),-1000000.0,1000000.0);	//mm
$periodes = testnumber(intval($_POST["periodes"]),0,10000);
$amplitude = testnumber(floatval($_POST["amplitude"]),-1000000.0,1000000.0);
$revolution = testnumber(floatval($_POST["revolution"]),-1000000.0,1000000.0);
$varlin0 = testnumber(floatval($_POST["varlin0"]),-1000000.0,1000000.0);
$varlin1 = testnumber(floatval($_POST["varlin1"]),-1000000.0,1000000.0);
$varsinp = testnumber(floatval($_POST["varsinp"]),-1000000.0,1000000.0);
$varsino = testnumber(floatval($_POST["varsino"]),-1000000.0,1000000.0);
$varrad0 = testnumber(floatval($_POST["varrad0"]),-1000000.0,1000000.0);
$varrad1 = testnumber(floatval($_POST["varrad1"]),-1000000.0,1000000.0);

$closetop = $_POST[closetop];
$closebottom = $_POST[closebottom];


if($steps_h<3) $steps_h = 3;	// Nombre de points de la circonférence
if($steps_v<2) $steps_v = 2;	// Nombre de points verticalement
$h = $height / ($steps_v-1);	// différence de hauteur entre une "couche" et la suivante
$angle_add = 360.0 / $steps_h;	// angle entre 2 points d'un cercle (-> polygone)
$nombre_triangles = (2 * $steps_h) * ($steps_v -1);	// nombre total de triangles
if($closetop) $nombre_triangles += $steps_h;		// nombre total de triangles augmenté si sommet fermé
if($closebottom) $nombre_triangles += $steps_h;		// nombre total de triangles augmenté si fond fermé
//$sa = ($varlin1 - $varlin0) / $steps_v;		// variation linéaire amplitude de periode. $sa=différence d'1 étape
$sa = ($varlin1 - $varlin0);				// variation linéaire amplitude de periode
$bd='';		// cette variable contient les données du fichier STL à envoyer au client web
$bd .=creeHeader($nombre_triangles);

// Creation en memoire
// POINTS
$tab_z =  array();
for($cpt_z = 0 ; $cpt_z<$steps_v ; $cpt_z++)		
{
	
	$fh = $cpt_z / $steps_v;			// Pourcentage hauteur
	$moda = $fh * $revolution;
	//$c_rayon = $rayon * (1.0 + $offsethorizontal+cos(deg2rad(($fh*$profil_plage)+$offsetvertical)));	// rayon général du profil en fct de la hauteur
	$varr = $varrad1 - $varrad0;
	$ray = $rayon * ($fh * $varr + $varrad0);
	$c_rayon = $offsethorizontal + $ray * (1.0 + cos(deg2rad(($fh*$profil_plage)+$offsetvertical)));	// rayon général du profil en fct de la hauteur	
	$tab_z[$cpt_z] =  array();		// Tableau des circonférences
	$c_angle=0.0;
	$amp = $amplitude * ($varlin0 + $sa * $fh) * (cos(deg2rad($fh * $varsinp + $varsino)));

	//$data .="$cpt_z - ";
	for($cpt_xy=0 ; $cpt_xy<($steps_h) ; $cpt_xy++)
	{	
		$c_angle += $angle_add;
		$r = $c_rayon + ($amp * sin(deg2rad($periodes * ($c_angle+$moda))));// ;
		$x1= $r * sin(deg2rad($c_angle));
		$y1= $r * cos(deg2rad($c_angle));
		$tab_z[$cpt_z][$cpt_xy] = new vertex($x1,$y1,($cpt_z * $h));
	}
}



// Generation data stl (triangles)
$t1 = new triangle(0,0,0,0,0,0,0,0,0);
$t2 = new triangle(0,0,0,0,0,0,0,0,0);
for($cpt_z = 0 ; $cpt_z<($steps_v-1) ; $cpt_z++)
{
	$a1 = $tab_z[$cpt_z];	// array niveau actuel
	$a2 = $tab_z[$cpt_z+1];	// array niveau +1
	for($cpt_xy=0 ; $cpt_xy<($steps_h-1) ; $cpt_xy++)
	{
			$t1->setVertex(0,$a1[$cpt_xy]);
			$t1->setVertex(1,$a2[$cpt_xy]);
			$t1->setVertex(2,$a1[$cpt_xy+1]);
			
			$t2->setVertex(0,$a1[$cpt_xy+1]);
			$t2->setVertex(1,$a2[$cpt_xy]);
			$t2->setVertex(2,$a2[$cpt_xy+1]);
			
			$bd .=$t1->getBinary();
			$bd .=$t2->getBinary();
			//$data .= $t1->getStr()."<br>";
			//$data .= $t2->getStr()."<br>";
	}
	$t1->setVertex(0,$a1[$cpt_xy]);
	$t1->setVertex(1,$a2[$cpt_xy]);
	$t1->setVertex(2,$a1[0]);
	
	$t2->setVertex(0,$a1[0]);
	$t2->setVertex(1,$a2[$cpt_xy]);
	$t2->setVertex(2,$a2[0]);
	
	$bd .=$t1->getBinary();
	$bd .=$t2->getBinary();
}
if($closebottom) // Fermer le fond
{
	$a1 = $tab_z[0];	// array niveau 0
	$t1->setVertex(0,new vertex(0.0,0.0,0.0));
	for($cpt_xy=0 ; $cpt_xy<($steps_h-1) ; $cpt_xy++)
	{
		$t1->setVertex(1,$a1[$cpt_xy]);
		$t1->setVertex(2,$a1[$cpt_xy+1]);
		$bd .=$t1->getBinary();
	}
	$t1->setVertex(1,$a1[$steps_h-1]);
	$t1->setVertex(2,$a1[0]);
	$bd .=$t1->getBinary();
}
if($closetop) // Fermer le sommet
{
	$a1 = $tab_z[$steps_v-1];	// array niveau steps_z-1
	$t1->setVertex(0,new vertex(0.0,0.0,$height));
	for($cpt_xy=0 ; $cpt_xy<($steps_h-1) ; $cpt_xy++)
	{
		$t1->setVertex(2,$a1[$cpt_xy]);
		$t1->setVertex(1,$a1[$cpt_xy+1]);
		$bd .=$t1->getBinary();
	}
	$t1->setVertex(2,$a1[$steps_h-1]);
	$t1->setVertex(1,$a1[0]);
	$bd .=$t1->getBinary();
}
	
			

//$html = $nombre_triangles;


//$html .= $data;

// Mise à jour du nombre de vases générés
//sqlStatementExecute($dbName,"UPDATE vazy_compteur SET valeur = valeur + 1 WHERE id=1;");
// **************************************************
// ********* AFFICHAGE
// **************************************************
//echo utf8_decode($bd);
echo $bd;
//echo $html;
//mysql_close();
?>
