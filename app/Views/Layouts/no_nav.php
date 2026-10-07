<?= $this->include("Layouts/header");?>
<body style="background:#EEEEEE;">

    <script>
        $(window).load(function() {
            $('body').css('padding-top',$(".announcement-banner").outerHeight());
            $(window).resize(function() {
                $('body').css('padding-top',$(".announcement-banner").outerHeight());
            });
        });
    </script>


    <div class="container-fluid">
        <div class="row">

    <body data-float-pos="left">
        

<div id="the-side-nav" class="col-sm-2">
    <a class="navbar-brand mobile" href="/"><img src="https://www.divorce-track.com/images/divorcetrack-logo.png" style="width: 100%" alt="Divorce Track Logo"></a>
    <nav class="navbar navbar-inverse navbar-fixed-side" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <a class="navbar-brand desktop" href="/"><img src="https://www.divorce-track.com/images/divorcetrack-logo.png" style="width: 100%" alt="Divorce Track Logo"></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">

                
                </ul>

            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    
</div>

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


<div id="the-main-content" class="col-sm-8 col-sm-offset-1 animated fadeInLeft">

<!-- Page Content -->
<div class="container-fluid">
<span id="burl" data-url="<?=site_url();?>"></span>

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

                    <style>

h2 {
font-size: 30px;

}

h2, h3 {
margin-bottom: 20px;
text-align: center;
}

@media only screen and (max-width: 768px) {

    h2 {
        font-size: 30px;
    }

    .navbar-fixed-side {
        display: none;
    }
    
    .navbar-brand {
        margin-top: 0px;
    }

}

</style>

<div class="row">
   <div class="col-8">
      <div class="panel panel-primary">
         <div class="panel-body">
            <?= $this->renderSection("content") ; ?>
         </div>
      </div>
   </div>
   </body>
</div>
<!-- /.container-fluid -->

<?= $this->include("Layouts/footer") ; ?> 