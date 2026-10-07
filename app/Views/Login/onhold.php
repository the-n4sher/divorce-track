<?= $this->extend('Layouts/no_nav') ?>

<?= $this->section("title") ?> DivorceTrack :: Login <?= $this->endSection();?>
<?= $this->section("content");?>
    
   
<div class="col-8">
   <div class="panel panel-primary">
      <div class="panel-heading">
         <h2>Welcome to Divorce Track</h2>
      </div>
      <div class="panel-body">
            <div class="col-md-8" style="padding:20px;text-align: center;font-size:22px;font-weight:bold;width:100%;">

               Your account has been placed on hold.<br /><br />In order to initiate proceedings please call us on 01793 211211<br />or email us at <a href="mailto:support@divorce-online.co.uk">support@divorce-online.co.uk</a>
            </div>
      </div>
   </div>
</div>


<?= $this->endSection(); ?>