<html>
<style type="text/css">
</style>
<script type="text/javascript">
   function maFonctionAjax(Id, Nom)
    {
      var OAjax;
      if (window.XMLHttpRequest) 
		OAjax = new XMLHttpRequest();
      else if (window.ActiveXObject) 
		OAjax = new ActiveXObject('Microsoft.XMLHTTP');
      OAjax.open('POST',"maPageDeRequPHP.php",true);
      
	  OAjax.onreadystatechange = function()
      {
          if (OAjax.readyState == 4 && OAjax.status==200)
          {
              if (document.getElementById)
              {   
                  if (OAjax.responseText =='true') { /* OK */
                        document.getElementById('msg').innerHTML='<font color=GREEN>'+OAjax.responseText+'</font>';
                  }else{                             /* PAS OK */
                        document.getElementById('msg').innerHTML='<font color=RED>'+OAjax.responseText+'</font>';
                  }
              }     
          }
      }
      OAjax.setRequestHeader('Content-type','application/x-www-form-urlencoded');
      OAjax.send('Id='+Id+'&Nom='+Nom);                 
    }
</script>

    <form method="post" onsubmit="maFonctionAjax(this.Id.value,this.Nom.value);return false" action="">
        <table border="0" cellspacing="0">
        <tr>
          <td colspan=2>
            Formulaire Ajax
          </td>
        </tr>
          <tr>
            <td>Id:</td>
            <td>
              <input name="Id" id="Id" type="text"></td>
          </tr>
          <tr>
            <td>Nom:</td>
            <td><input name="Nom" id="Nom" type="text"></td>
          </tr>
          <tr>
            <td colspan="2"><input type="submit" value="envoyer"  /></td>
          </tr>
        </table>
    </form>
    <div id="msg"></div>

</html>