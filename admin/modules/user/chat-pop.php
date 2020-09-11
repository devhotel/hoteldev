<?php
define("ADMIN", true);
define("SYSBASE", str_replace("\\", "/", realpath(dirname(__FILE__)."/../../../")."/"));
require_once(SYSBASE."common/lib.php");
require_once(SYSBASE."common/define.php");

if(!isset($_SESSION['admin'])) exit();
elseif($_SESSION['admin']['type'] == "registered"){
    unset($_SESSION['admin']);
    exit();
}else{}

?>
<html>
<body>
<?php
if(isset($_POST['id']) && isset($_SESSION['admin']['id'])){
    $id_user = (int)$_POST['id'];
    $sender_slug=$_SESSION['admin']['id']."_".$id_user;
    $receiver_slug=$id_user."_".$_SESSION['admin']['id'];
    if(is_numeric($id_user)){ ?>
      <div class="white-popup-block">
          <div id="popup-chat-<?php echo $id_user; ?>">
            <div id="adminChatBox">
              <div class="chat_content">
                  <div class="chatbody">
                    <div id="headDiv"></div>
                    <div id="messageDiv"></div>
                  </div>
                  <input type="hidden" id="sender_id" value="<?=($_SESSION['admin']['id']!=""?$_SESSION['admin']['id']:'')?>" />
                  <input type="hidden" id="sender_name" value="<?=($_SESSION['admin']['id']!=""?$_SESSION['admin']['name']:'')?>" />
                  <input type="hidden" id="receiver_name" value="sonjoy" />
                  <input type="hidden" id="receiver_id" value="<?=($id_user!=""?$id_user:'')?>" />
                  <input id="messageInput" placeholder="Message...">
              </div>
            </div> 
          </div>
       </div> 
      <?php } ?>
    </body>
        
        <?php
     }
   ?>
   <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
   <script type="text/javascript" src="https://cdn.firebase.com/js/client/2.4.2/firebase.js"></script>
   
   
   <script>
        var messageRef = new Firebase('https://testproject-1547038629594.firebaseio.com/MessageArea/<?=$sender_slug?>');
        var messageRef2 = new Firebase('https://testproject-1547038629594.firebaseio.com/MessageArea/<?=$sender_slug?>');
        $('#messageInput').keypress(function(e){
           if(e.keyCode == 13) {

            var receiver_id = $('#receiver_id').val();
            var receiver_name = $('#receiver_name').val();
            var sender_name = $('#sender_name').val();
            var sender_id = $('#sender_id').val();
            var text = $('#messageInput').val();

          var today = new Date();
          var dd = String(today.getDate()).padStart(2, '0');
          var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
          var yyyy = today.getFullYear();
         //today.getHours() > 12 ? ("0" + (today.getHours() - 12)).slice(-2) : (today.getHours() < 10 ? today.getHours() : today.getHours());
          var curHour = today.getHours() > 12 ? "0" + (today.getHours() - 12) : (today.getHours() < 10 ? "0" + today.getHours() : today.getHours());
          var curMinute = today.getMinutes() < 10 ? "0" + today.getMinutes() : today.getMinutes();
          var curSeconds = today.getSeconds() < 10 ? "0" + today.getSeconds() : today.getSeconds();

          today = dd + '/' + mm + '/' + yyyy + ' '+ curHour + ':' + curMinute + ':' + curSeconds;

          var pUsh = {message:text, receiver_id:receiver_id, receiver_name:receiver_name, sender_id:sender_id, sender_name:sender_name, time:today}

          messageRef.push(pUsh);
          messageRef2.push(pUsh);
          //messageRef2.push(pUsh);
          //messageRef.push({name:name, text:text});
          // messageRef.child('currentMessage');
          $('#messageInput').val();
           }

        });

        /*messageRef.on('child_added',function(snapshot){
           var message = snapshot.val();
          console.log(message);
          document.getElementById('messageDiv').innerHTML += message.sender_name+'--'+message.message+'<br/>';

        });*/
        messageRef2.on('child_added',function(snapshot){
           var message = snapshot.val();
          console.log(message);
          document.getElementById('messageDiv').innerHTML += message.sender_name+'--'+message.message+'<br/>';

        });

</script>
</html>
