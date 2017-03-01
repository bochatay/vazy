<!--
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

-->

<!DOCTYPE html>
<html lang="fr">
<head>
<title>STL vase creator</title>

<meta http-equiv="Content-Type" content="text/html;" charset="utf-8" /> 
<meta name="Keywords" content="vase,3d,print,reprap,stl,sinus,cosinus" />
<meta name="Description" content="Générateur de vases." />
<link href="index.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="./favicon.gif" type="image/gif" />
<link rel="shortcut icon" href="./favicon.gif" type="image/gif" />
<script src="stl.js" type="text/javascript"></script>

</head>

<body bgcolor="#FFFFFF" onload="javascript:maj();" >
<span class="titre">Vase creator for 3d printing</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href ="http://www.thingiverse.com/thing:254131">thingiverse.com/thing:254131</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript:envoyer();">Generate and download STL</a><br><br>
<form enctype="multipart/form-data" id="mainform" name="mainform" method="post" action="stl.php">
<div class="divgauche">
<div class="divsGauche">
<table border=0><tr><td style="font-weight:bold">Général</td></tr>
<tr><td width=180>Name</td><td><input class="inputtext" type="text" value="untitled" id="nom" name="nom"></td><td style="width:140px; text-align:right;">set quality:</td></tr>
<tr><td>Height</td><td><input class="inputnumber" type="text" value="130" id="hauteur" name="hauteur"  onkeyup="javascript:maj();">mm</td><td style="text-align:right;" ><a href="javascript:qu(1);">test (170 kB)</a></td></tr>
<tr><td>Vertical steps</td><td><input class="inputnumber" type="text" value="40" id="pasvertical" name="pasvertical">points</td><td style="text-align:right;" ><a href="javascript:qu(2);">good (705 kB)</a></td></tr>
<tr><td>Horizontal steps</td><td><input class="inputnumber" type="text" value="90" id="pashorizontal" name="pashorizontal">points</td><td style="text-align:right;" ><a href="javascript:qu(3);">max (3.7 MB)</a></td></tr>
<tr><td>Close top</td><td><input type="checkbox" id="closetop" name="closetop" checked></td><td></td></tr>
<tr><td>Close bottom</td><td><input type="checkbox" id="closebottom" name="closebottom" checked></td><td></td></tr>
</table>
</div>
<div class="divsGauche">
<table border=0><tr><td style="font-weight:bold">Profil (cosinus)</td></tr>
<tr><td width=180>Radius</td><td><input class="inputnumber" type="text" value="10.0" id="rayon" name="rayon" onkeyup="javascript:maj();"></td><td>mm</td><td><input type="range"  class="range"  min="0.0" max="100.0" value="10.0" oninput="change(3,this.value)" onchange="change(3,this.value)" /></td></tr>
<tr><td style="font-size:0.9em;">&nbsp;&nbsp; Linear variation, top</td><td><input class="inputnumber" type="text" value="1.0" id="varrad1" name="varrad1" onkeyup="javascript:maj();"></td><td></td><td><input type="range"  class="range"  min="-200.0" max="200.0" value="100.0" oninput="change(6,this.value)" onchange="change(6,this.value)" /></td></tr>
<tr><td style="font-size:0.9em;">&nbsp;&nbsp; Linear variation, bottom</td><td><input class="inputnumber" type="text" value="1.0" id="varrad0" name="varrad0"  onkeyup="javascript:maj();"></td><td></td><td><input type="range"  class="range"  min="-200.0" max="200.0" value="100.0" oninput="change(5,this.value)" onchange="change(5,this.value)" /></tr><tr>

<tr><td>Plage</td><td><input class="inputnumber" type="text" value="270.0" id="plage" name="plage" onkeyup="javascript:maj();"></td><td>°</td><td><input type="range"  class="range"  min="0.0" max="720.0" value="270.0" oninput="change(1,this.value)" onchange="change(1,this.value)" /></td></tr>
<tr><td>Vertical Offset </td><td><input class="inputnumber" type="text" value="325.0" id="offsetvertical" name="offsetvertical" onkeyup="javascript:maj();"></td><td>°</td><td><input type="range"  class="range"  min="0.0" max="360.0" value="325.0" oninput="change(2,this.value)" onchange="change(2,this.value)" /></td></tr>
<tr><td>Horizontal offset</td><td><input class="inputnumber" type="text" value="10.0" id="offsethorizontal" name="offsethorizontal" onkeyup="javascript:maj();"></td><td>mm</td><td><input type="range"  class="range"  min="0.0" max="100.0" value="10.0" oninput="change(4,this.value)" onchange="change(4,this.value)" /></td></tr>
</table>
</div>
<div class="divsGauche">
<table border=0><tr><td style="font-weight:bold">Periphery (sinus)</td></tr>
<tr><td width=180>Nb. periods</td><td><input class="inputnumber" type="text" value="5" id="periodes" name="periodes"  onkeyup="javascript:maj();"></td><td></td><td><input type="range"  class="range"  min="0" max="50" value="5" oninput="change(7,this.value)" onchange="change(7,this.value)" /></td></tr>
<tr><td>Turn</td><td><input class="inputnumber" type="text" value="90.0" id="revolution" name="revolution"  onkeyup="javascript:maj();"></td><td>°</td><td><input type="range"  class="range"  min="-360" max="360.0" value="90.0" oninput="change(13,this.value)" onchange="change(13,this.value)" /></td></tr>
<tr><td>Amplitude</td><td><input class="inputnumber" type="text" value="4.0" id="amplitude" name="amplitude" onkeyup="javascript:maj();"></td><td>mm</td><td><input type="range"  class="range"  min="-2500.0" max="2500.0" value="400.0" oninput="change(8,this.value)" onchange="change(8,this.value)" /></td></tr>
<tr><td style="font-size:0.9em;">&nbsp;&nbsp; Linear variation, top</td><td><input class="inputnumber" type="text" value="1.0" id="varlin1" name="varlin1" onkeyup="javascript:maj();"></td><td></td><td><input type="range"  class="range"  min="-100.0" max="100.0" value="10.0" oninput="change(9,this.value)" onchange="change(9,this.value)" /></td></tr><tr>
<tr><td style="font-size:0.9em;">&nbsp;&nbsp; Linear variation, bottom</td><td><input class="inputnumber" type="text" value="0.2" id="varlin0" name="varlin0" onkeyup="javascript:maj();"></td><td></td><td><input type="range"  class="range" min="-100.0" max="100.0" value="2.0" oninput="change(10,this.value)" onchange="change(10,this.value)" /></td></tr><tr>
<tr><td style="font-size:0.9em;">&nbsp;&nbsp; Cosinus variation, plage</td><td><input class="inputnumber" type="text" value="0.0" id="varsinp" name="varsinp" onkeyup="javascript:maj();"><td>°</td><td><input type="range"  class="range"  min="0.0" max="720.0" value="0.0" oninput="change(11,this.value)" onchange="change(11,this.value)" /></td></tr><tr>
<tr><td style="font-size:0.9em;">&nbsp;&nbsp; Cosinus variation, offset</td><td><input class="inputnumber" type="text" value="0.0" id="varsino" name="varsino" onkeyup="javascript:maj();"><td>°</td><td><input type="range"  class="range"  min="0.0" max="360.0" value="0.0" oninput="change(12,this.value)" onchange="change(12,this.value)" /></td></tr><tr>

</tr>
</table>
</div>

</div>
<div class="divdroite">
<div class="divsDroite" style="height:440px; overflow:hidden;">
<svg class="dessinsvg" id="dessinsvg" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 300 310"  onmousedown="mouse(evt,1);" onmouseup="mouse(evt,2);" onmousemove="mouse(evt,3);">
<g class="fond" id="fond" >

<path id="axe1" stroke="grey" fill="none" />
<path id="axe2" stroke="grey" fill="none" />
<path id="axe3" stroke="grey" fill="none" />

<path id="sin1" stroke="black" fill="none" />
<path id="sin2" stroke="black" fill="none" />
<path id="sin3" stroke="red" fill="none" />
<path id="sin4" stroke="red" fill="none" />

<path id="cercle1" stroke="grey" fill="none" />
<path id="cercle2" stroke="red" fill="none" />
<path id="barre" stroke="green" fill="none" />

</g>
</svg>
<span id="infospan">Passez le pointeur sur l'image</span>

</div>
<div class="divsDroite" style="height:220px;">
Exemples:<br>
<a href="javascript:creer(1);">borsa</a><br>
<a href="javascript:creer(2);">bosselé</a><br>
<a href="javascript:creer(3);">bi-fleur</a><br>
<a href="javascript:rnd();">random</a><br>

</div>

</div>



</form>
<br>

<br>


</body>
</html>
