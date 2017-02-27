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




var hauteurvisible = 0;
function envoyer()
{
	document.mainform.submit();

}
function deg2rad(k)
{
	return k * 0.0174532925;
}
function change(n,val)
{
	switch(n)
	{
		case 1:
		document.getElementById("plage").value = val;
		break;
		case 2:
		document.getElementById("offsetvertical").value = val;
		break;
		case 3:
		document.getElementById("rayon").value = val;
		break;
		case 4:
		document.getElementById("offsethorizontal").value = val;
		break;
		case 5:
		document.getElementById("varrad0").value = val/100.0;
		break;
		case 6:
		document.getElementById("varrad1").value = val/100.0;
		break;
		case 7:
		document.getElementById("periodes").value = val;
		break;
		case 8:
		document.getElementById("amplitude").value = val/100.0;
		break;
		case 9:
		document.getElementById("varlin1").value = val/100.0;
		break;
		case 10:
		document.getElementById("varlin0").value = val/100.0;
		break;
		case 11:
		document.getElementById("varsinp").value = val;
		break;
		case 12:
		document.getElementById("varsino").value = val;
		break;
		case 13:
		document.getElementById("revolution").value = val;
		break;
	}
	maj();
}
function maj()
{
	lines1="";
	lines2="";
	linesp1="";
	linesp2="";
	cercle1="";
	cercle2="";
	hauteur = parseFloat(document.getElementById("hauteur").value);
	rayon = parseFloat(document.getElementById("rayon").value);
	plage = parseFloat(document.getElementById("plage").value);
	offsetv = parseFloat(document.getElementById("offsetvertical").value);
	offseth = parseFloat(document.getElementById("offsethorizontal").value);
	varrad0 = parseFloat(document.getElementById("varrad0").value);
	varrad1 = parseFloat(document.getElementById("varrad1").value);
	amplitude = parseFloat(document.getElementById("amplitude").value);
	varlin0 = parseFloat(document.getElementById("varlin0").value);
	varlin1 = parseFloat(document.getElementById("varlin1").value);
	varsinp = parseFloat(document.getElementById("varsinp").value);
	varsino = parseFloat(document.getElementById("varsino").value);
	periodes = parseFloat(document.getElementById("periodes").value);
	revolution = parseFloat(document.getElementById("revolution").value);
	varr = varrad1 - varrad0;
	varlin = varlin1 - varlin0;
	for(n=0 ; n<200 ; n++)
	{
		a = ((n/200.0) * plage) + offsetv;
		h = (n * hauteur) / 200.0;
		fh = (n / 200.0);
		ray = rayon * (fh * varr + varrad0);
		tmp = (1.0 + Math.cos(deg2rad(a)));
		amp = amplitude * (fh * varlin + varlin0) * (Math.cos(deg2rad((n * varsinp / 200.0)  + varsino)));
		if(n==0) pref="M ";
		else pref="L ";
		lines1 +=pref+((150.0 + offseth) +  ( ray * tmp)) + " " + (300 - h) + " ";
		lines2 +=pref+((150.0 - offseth) +  (-ray * tmp)) + " " + (300 - h) + " ";
		linesp1 +=pref+((150.0 + offseth) +  (ray * tmp) + amp) + " " + (300 - h) + " ";
		linesp2 +=pref+((150.0 + offseth) +  (ray * tmp) - amp) + " " + (300 - h) + " ";
	}
	var fh2 = (hauteurvisible / hauteur)
	var tmpray = rayon * (fh2 * varr + varrad0) * (1.0 + Math.cos(deg2rad((fh2 * plage) + offsetv)));;	// rayon;
	var tmpamp = amplitude * (fh2 * varlin + varlin0) * (Math.cos(deg2rad((fh2 * varsinp)  + varsino)));;	// amplitude
	var tmpdan = fh2 * revolution;	// delta angle
	for(n=0 ; n<360 ; n++)
	{
		if(n==0) pref="M ";
		else pref="L ";
		ray = tmpray + offseth + tmpamp * Math.sin(deg2rad(n * periodes));
		
		cercle1 += pref+(150.0 +  ((tmpray + offseth) * Math.sin(deg2rad(n + tmpdan)))) + " " + (80.0 + ((tmpray+offseth) * Math.cos(deg2rad(n + tmpdan)))) + " ";
		cercle2 += pref+(150.0 +  (ray * Math.sin(deg2rad(n + tmpdan)))) + " " + (80.0 +(ray * Math.cos(deg2rad(n + tmpdan)))) + " ";
	}
	f1 = document.getElementById("sin1");
	f2 = document.getElementById("sin2");
	f3 = document.getElementById("sin3");
	f6 = document.getElementById("sin4");
	f4 = document.getElementById("cercle1");
	f5 = document.getElementById("cercle2");
	f7 = document.getElementById("axe1");
	f8 = document.getElementById("axe2");
	f9 = document.getElementById("axe3");
	f1.setAttribute("d","M 150 300 "+lines1+"\"");
	f2.setAttribute("d","M 150 300 "+lines2+"\"");
	f3.setAttribute("d","M 150 300 "+linesp1+"\"");
	f6.setAttribute("d","M 150 300 "+linesp2+"\"");
	f4.setAttribute("d",cercle1+" z");
	f5.setAttribute("d",cercle2+" z");
	f7.setAttribute("d","M 150,20 L 150,300");
	//f8.setAttribute("d","M "+(rayon + offseth + 150)+",20 L "+(rayon + offseth + 150)+",300");
	//f9.setAttribute("d","M "+(150-(rayon + offseth))+",20 L "+(150 -(rayon + offseth))+",300");
}
function qu(val)
{
	switch(val)
	{
		case 1:
		document.getElementById("pasvertical").value = 35;
		document.getElementById("pashorizontal").value = 50;
		break;
		case 2:
		document.getElementById("pasvertical").value = 60;
		document.getElementById("pashorizontal").value = 120;		
		break;
		case 3:
		document.getElementById("pasvertical").value = 160;
		document.getElementById("pashorizontal").value = 240;
		break;
	}
}
function hauteur(val)	// hauteur visible en %
{
	;
}
function mousemove(x,y)
{
	var info = document.getElementById("infospan");
	var hauteur = parseFloat(document.getElementById("hauteur").value);

	var b = document.getElementById("barre");
	hauteurvisible = 300-y;
	if(hauteurvisible<0) hauteurvisible = 0;
	if(hauteurvisible>hauteur) hauteurvisible = hauteur;
	b.setAttribute("d","M 10 "+(300-hauteurvisible)+" 290 "+(300-hauteurvisible));
	info.innerHTML="height:"+hauteurvisible;
	maj();
}
function mouse(evt,t)
{
	evt.preventDefault();
	var svgf = document.getElementById("dessinsvg");
	var a = svgf.getScreenCTM().inverse();
	var ptg,ptf;
	ptg = svgf.createSVGPoint();
	ptf = svgf.createSVGPoint();
	ptf.x = evt.clientX;
	ptf.y = evt.clientY;
	ptg.x = ptf.x;
	ptg.y = ptf.y;
	ptf=ptf.matrixTransform(a);
	
	switch(t)
	{
		case 1:		//down
		//mousedown(evt);
		break;
		case 2:		//up
		//mouseup(evt);
		break;
		case 3:		// move
		mousemove(ptf.x,ptf.y);
		break;
	}
}


function creer(t)
{
	switch(t)
	{
		case 1:
		document.getElementById("nom").value = "borsa";
		document.getElementById("hauteur").value = 130;
		document.getElementById("rayon").value = 21;
		document.getElementById("plage").value = 263;
		document.getElementById("offsetvertical").value =325.0;
		document.getElementById("offsethorizontal").value = 20.0;
		document.getElementById("varrad0").value = 1.0;
		document.getElementById("varrad1").value = 1.0;
		document.getElementById("amplitude").value = 9.15;
		document.getElementById("varlin0").value = -0.65;
		document.getElementById("varlin1").value = 1.0;
		document.getElementById("varsinp").value = 180;
		document.getElementById("varsino").value = 0;
		document.getElementById("periodes").value = 7;
		document.getElementById("revolution").value = 90.0;
		break;
		case 2:
		document.getElementById("nom").value = "bosselé";
		document.getElementById("hauteur").value = 130;
		document.getElementById("rayon").value = 10;
		document.getElementById("plage").value = 259;
		document.getElementById("offsetvertical").value =307.0;
		document.getElementById("offsethorizontal").value = 9.0;
		document.getElementById("varrad0").value = 1.0;
		document.getElementById("varrad1").value = 1.0;
		document.getElementById("amplitude").value = 2;
		document.getElementById("varlin0").value = 1.0;
		document.getElementById("varlin1").value = 1.0;
		document.getElementById("varsinp").value = 2880;
		document.getElementById("varsino").value = 90.0;
		document.getElementById("periodes").value = 17;
		document.getElementById("revolution").value = 180.0;
		break;
		case 3:
		document.getElementById("nom").value = "bi-fleur";
		document.getElementById("hauteur").value = 130;
		document.getElementById("rayon").value = 12;
		document.getElementById("plage").value = 341;
		document.getElementById("offsetvertical").value =318.0;
		document.getElementById("offsethorizontal").value = 11.0;
		document.getElementById("varrad0").value = 1.17;
		document.getElementById("varrad1").value = 0.35;
		document.getElementById("amplitude").value = 10.56;
		document.getElementById("varlin0").value = 0.5;
		document.getElementById("varlin1").value = 1.5;
		document.getElementById("varsinp").value = 387;
		document.getElementById("varsino").value = 0.0;
		document.getElementById("periodes").value = 2;
		document.getElementById("revolution").value = 360.0;
		break;
	}
	
		maj();

}

