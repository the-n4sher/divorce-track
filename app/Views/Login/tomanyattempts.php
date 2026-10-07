<?= $this->extend('Layouts/no_nav') ?>

<?= $this->section("title") ?> DivorceTrack :: Login <?= $this->endSection();?>
<?= $this->section("content");?>
    
   
<div class="col-8">
   <div class="panel panel-primary">
      <div class="panel-heading">
         <h2>Account Locked</h2>
      </div>
      <div class="panel-body">
            <div class="col-md-8" style="padding:20px;text-align: center;font-size:22px;font-weight:bold;width:100%;">
               <p>Your account has been locked due to there being to many requests to the server. Please try again shortly.</p>
            </div>
      </div>
   </div>
</div>


<?= $this->endSection(); ?>