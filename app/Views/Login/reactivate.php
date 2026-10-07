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
            Your account has expired due to inactivity for a continuing period of 6 months or more. Please call us on 01793 384029 in order to reactivate and discuss your account with an advisor. 
         </div>
      </div>
   </div>
</div>


<?= $this->endSection(); ?>