<?php
// LAISSER EN ANSI
require_once("./conf.php");
header("Content-Type: application/octet-stream;");
$name = $_POST["nom"];
if(strlen($name)<1) $name="sans_titre";
if(strlen($name)>100) $name="sans_titre";
header("Content-disposition: attachment; filename=$name.stl");
session_cache_limiter('public, must-revalidate');
sqlConnect($dbServer, $dbLogin, $dbPassword, $dbCharset); 
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
	function getBinary()
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
function creeTriangle($x1,$y1,$z1,$x2,$y2,$z2,$x3,$y3,$z3)
{
	$r = '';
	$r .= pack("f",0.0);	// N
	$r .= pack("f",0.0);	// N
	$r .= pack("f",0.0);	// N
	$r .= pack("f",$x1);	// V1
	$r .= pack("f",$y1);	// V1
	$r .= pack("f",$z1);	// V1
	$r .= pack("f",$x2);	// V2
	$r .= pack("f",$y2);	// V2
	$r .= pack("f",$z2);	// V2
	$r .= pack("f",$x3);	// V3
	$r .= pack("f",$y3);	// V3
	$r .= pack("f",$z3);	// V3
	$r .= pack("S",0);	// 16 bits Attribute byte count
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

// VARIABLES DE FONCTIONNEMENT
if($steps_h<3) $steps_h = 3;
if($steps_v<2) $steps_v = 2;
$h = $height / ($steps_v-1);
$angle_add = 360.0 / $steps_h;
$nombre_triangles = (2 * $steps_h) * ($steps_v -1);
if($closetop) $nombre_triangles += $steps_h;
if($closebottom) $nombre_triangles += $steps_h;
//$sa = ($varlin1 - $varlin0) / $steps_v;	// variation lin�aire amplitude de periode. $sa=diff�rence d'1 �tape
$sa = ($varlin1 - $varlin0);	



$bd='';
$bd .=creeHeader($nombre_triangles);

// Creation en memoire
// POINTS
$tab_z =  array();
for($cpt_z = 0 ; $cpt_z<$steps_v ; $cpt_z++)		
{
	
	$fh = $cpt_z / $steps_v;			// Pourcentage hauteur
	$moda = $fh * $revolution;
	//$c_rayon = $rayon * (1.0 + $offsethorizontal+cos(deg2rad(($fh*$profil_plage)+$offsetvertical)));	// rayon g�n�ral du profil en fct de la hauteur
	$varr = $varrad1 - $varrad0;
	$ray = $rayon * ($fh * $varr + $varrad0);
	$c_rayon = $offsethorizontal + $ray * (1.0 + cos(deg2rad(($fh*$profil_plage)+$offsetvertical)));	// rayon g�n�ral du profil en fct de la hauteur	
	$tab_z[$cpt_z] =  array();		// Tableau des circonf�rences
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



// Generation data stl
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
	
			

$html = $nombre_triangles;


//$html .= $data;


sqlStatementExecute($dbName,"UPDATE vazy_compteur SET valeur = valeur + 1 WHERE id=1;");
// **************************************************
// ********* AFFICHAGE
// **************************************************
echo $bd;
//echo $html;
//mysql_close();
?>