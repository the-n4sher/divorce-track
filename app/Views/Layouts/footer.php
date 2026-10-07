<!-- Footer -->

        </div><!-- /.col -->

    </div><!-- /.row -->

</div> <!-- /.container-fluid -->

</body>
</html>

<script>
    $(document).ready(function() {

      var bannerHeight;

      updateMarginTopFixedElements();

      $(window).resize(function() {
          updateMarginTopFixedElements();
      });

      $(".notification-icon").click(function(){
        $(".notification-popup").toggle();
      });

      $(".notification-close").click(function(){
        $(".notification-popup").hide();
      });

      function updateMarginTopFixedElements() {
          bannerHeight = $(".announcement-banner").outerHeight() - 1;
          $('.notification-icon').css('margin-top',bannerHeight);
          $('.notification-popup').css('margin-top',bannerHeight);
      }


    });
</script>


<script>
  $(document).on("click","#update-notification",function(){

      var smbid = $(this).attr("data-smbid"),
          myData = new Object();
          myData.smbid = smbid;
      
      if(smbid != ""){

         $.ajax({
          url: '<?=site_url();?>'+'account/update_notification',
          type: 'POST',
          dataType:"html",
          data: myData,
          success: function(res){
           var response = JSON.parse(res);
              
              if(response.status == "success"){}
                 //reload the current page
                 location.reload();
              }
         });
      }
  });
</script>


<?php if(strpos($_SERVER['REQUEST_URI'], 'consent-order') !== false): ?>
    <style>
      #customgpt_chat_panel {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 150;
        width: 332px;
        height: min(560px, calc(100vh - 100px));
        transform: translateX(calc(100% + 32px));
        transition: transform 0.3s ease;
      }
      #customgpt_chat_panel.is-open {
        transform: translateX(0);
      }
      #customgpt_chat {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
      }
      #customgpt_chat_close {
        display: none;
        position: absolute;
        top: -10px;
        right: -10px;
        z-index: 151;
        width: 32px;
        height: 32px;
        padding: 0;
        border: none;
        border-radius: 50%;
        background: #16a085;
        color: #fff;
        font-size: 20px;
        line-height: 1;
        cursor: pointer;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      }
      #customgpt_chat_close:hover {
        background: #138d75;
      }
      #customgpt_chat_panel.is-open #customgpt_chat_close {
        display: flex;
      }
      #customgpt_chat_toggle {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 149;
        padding: 12px 20px;
        border: none;
        border-radius: 24px;
        background: #16a085;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 2px 12px rgba(22, 160, 133, 0.35);
      }
      #customgpt_chat_toggle:hover {
        background: #138d75;
      }
      #customgpt_chat_toggle.is-hidden {
        display: none;
      }
      @media (max-width: 576px) {
        #customgpt_chat_panel {
          bottom: 16px;
          right: 16px;
          left: 16px;
          width: auto;
          height: min(520px, calc(100vh - 88px));
        }
        #customgpt_chat_toggle {
          bottom: 16px;
          right: 16px;
          font-size: 15px;
          padding: 11px 18px;
        }
      }
    </style>

    <button type="button" id="customgpt_chat_toggle" aria-label="Chat with us">Chat with us</button>
    <div id="customgpt_chat_panel">
      <button type="button" id="customgpt_chat_close" aria-label="Close chat">&times;</button>
      <div id="customgpt_chat"></div>
    </div>
    <script src="https://cdn.customgpt.ai/js/embed.js" defer div_id="customgpt_chat" p_id="95916" p_key="ea4d34f65c666f2a072c7c5a803f0b2c"></script>
    <script>
      $(function() {
        var $panel = $('#customgpt_chat_panel');
        var $toggle = $('#customgpt_chat_toggle');
        var $close = $('#customgpt_chat_close');

        function openChat() {
          $panel.addClass('is-open');
          $toggle.addClass('is-hidden');
        }

        function closeChat() {
          $panel.removeClass('is-open');
          $toggle.removeClass('is-hidden');
        }

        $toggle.on('click', openChat);
        $close.on('click', closeChat);
      });
    </script>
<?php endif; ?>