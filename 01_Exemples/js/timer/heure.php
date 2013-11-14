regardez la barre de statut
<SCRIPT  language=javascript>
   function Timer() { // ne marche pas sous FF
       var dt=new Date()
       window.status=dt.getHours()+":"+dt.getMinutes()+":"+dt.getSeconds();
       setTimeout("Timer()",1000);
   }
   Timer();
</SCRIPT>