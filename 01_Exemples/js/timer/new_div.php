<html>
<style type="text/css">
</style>
<script type="text/javascript">
var ind=0;
function newdiv(txt) {
	var div = document.createElement('DIV');
	ind++;
	div.id = 'div_'+ind;
	div.style.position = 'absolute';
	div.style.top = ind+'em';
	div.innerHTML = txt;
	var cible = document.getElementById('insert');
	cible.appendChild(div);
	alert('largeur = '+document.getElementById('div_'+ind).offsetWidth);
}
</script>
<p><a href="#"
onclick="newdiv('voici un nouveau div');return false;">div 1</a></p>
<p><a href="#"
onclick="newdiv('voici un autre div');return false;">div 2</a></p>
<p id="insert" style="position:relative">ici les nouveaux divs :</p>
</html>