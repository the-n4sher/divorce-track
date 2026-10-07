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

            <?=$this->include("Layouts/nav");?>


        <div id="the-main-content" class="col-sm-8 col-sm-offset-1 animated fadeInLeft">

        <!-- Page Content -->
        <div class="container-fluid">
          <span id="burl" data-url="<?=site_url();?>"></span>

          <!-- Need to remove the below from individual files and then uncomment the code below -->
          <!--
          <div class="row"> 
             <div class="col-8">
                <div class="panel panel-primary">
                   <div class="panel-body"> -->
                      <?= $this->renderSection("content") ; ?>
                   <!--
                   </div>
              </div>
          </div>
           -->
        </div>

<?= $this->include("Layouts/footer") ; ?> 