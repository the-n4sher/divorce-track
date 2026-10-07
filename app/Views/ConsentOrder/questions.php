<?php if($res_logged_in === true ) : ?>
    <?php $this->extend("Layouts/no_nav");?>
<?php else : ?>
    <?php $this->extend("Layouts/main_template");?>
<?php endif;?>


<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>

<style>
.well{
	border: 2px solid #16A184!important;
}
</style>

<div id="page-wrapper" class="notifications-page">

    <div class="row">
        <div class="col-8">
			<div class="panel panel-primary">
	            <div class="panel-heading">
	            	<h1>Consent Order Question(s)</h1>
	            </div>
				<div class="panel-body">
					<?php if(!is_array($all_questions) || count($all_questions) < 0) : ?>
						<p>No questions found.</p>
					<?php else : ?>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Question</th>
									<th>Answer</th>
								</tr>

								<tbody>
									<?php foreach($all_questions as $aq) : ?>
                                         <tr>
                                         	<td><?=mb_strimwidth($aq['note'], 0, 40, "...");?></td>
                                         	<td><a href="<?=base_url();?>consent-order/answer_question/<?=$aq['id'];?>">Answer</a></td>
                                         </tr>
									<?php endforeach;?>
								</tbody>
							</thead>
						</table>
					<?php endif;?>
        		</div>
    		</div>
 		</div>
 	</div>
</div>
<!-- /#page-wrapper -->

<?php $this->endSection() ;?>