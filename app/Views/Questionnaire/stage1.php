<?php $this->extend("Layouts/main_template");?>
<?php $this->section("title");?> <?=$title;?> :: Divorce Track <?php $this->endSection();?>

<?php $this->section("content") ; ?>
<br /><br />
<div class="row">
	<div class="col-8">

        <div class="panel panel-primary">
		<div class="panel-heading"><h1>Questionnaire</h1></div>
    		<div class="panel-body">

                <strong>Stage 1 Progress</strong><br /><br />
                <div class="progress">
                  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<?=$progress_status;?>0%;">
                    <?=$progress_status;?>0%
                  </div>
                </div>
                <hr>
                
                <div class="panel panel-primary">
                    <div class="panel-heading"><h3 class="panel-title">Questionnaire<?=$section_wording;?></h3></div>
                    <div class="panel-body">

                        <!-- Main forms -->
                        <?php if($current_stage == 1) : ?>          
                            <?= view("Questionnaire/forms/section1"); ?>
                        <?php elseif($current_stage == 2) : ?>
                            <?=view("Questionnaire/forms/section2"); ?>
                        <?php elseif($current_stage == 3) : ?>
                            <?=view("Questionnaire/forms/section3"); ?>
                        <?php elseif($current_stage == 4) : ?>
                            <?=view("Questionnaire/forms/section4"); ?>
                        <?php elseif($current_stage == 5 && $segment3 != 1 && $segment3 != 2 && $segment3 != 3 && $segment3 != 4 && $segment3 != 5) : ?>
                            <?=view("Questionnaire/forms/section5"); ?>
                        <?php elseif($current_stage == 6 ) : ?>
                            <?=view("Questionnaire/forms/complete"); ?>
                        <?php endif ;?>
                        <!-- Main forms end -->

                        <!-- Adultery -->
                        <?php if($segment2 == 5 && $segment3 == 1 && !$segment4) : ?>
                            <?=view("Questionnaire/forms/section5_adultery"); ?>
                        <?php endif;?>
                        <!-- Adultery End -->
                        
                        <!-- Behaviour -->
                        <?php if($segment2 == 5 && $segment3 == 2 && !$segment4) : ?>
                            <?=view("Questionnaire/forms/section5_behaviour"); ?> 
                        <?php endif;?>
                        <!-- Behaviour End -->

                        <!-- Desertion -->
                        <?php if($segment2 == 5 && $segment3 == 5 && !$segment4) : ?>
                            <?=view("Questionnaire/forms/section5_desertion"); ?>
                        <?php endif;?>
                        <!-- Desertion End -->
                    
                        <!-- Court Proceedings -->
                        <?php if($segment2 == 5 && $segment4 == 2 || $segment2 == 5 && in_array($segment3,array(3,4))) : ?>
                            <?=view("Questionnaire/forms/section5_court_proceedings"); ?>
                        <?php endif;?>
                        <!-- Court Proceddings ENd -->

                    </div>
                </div>
            </div>
        </div>
	</div>
</div>


<input type="hidden" name="spouse_child_count" id="spouse_child_count" value="<?=$qdata['num_of_children_by_spouse'];?>">
<input type="hidden" name="other_child_count" id="other_child_count" value="<?=$qdata['num_of_children_by_other'];?>">


<script>

$(document).ready(function()
{


    $(document).on('click','#res_unknown',function(){
            
        var is_checked = $(this).prop("checked");
        
        if(is_checked == true){
             $('#respondent_address_line1,#respondent_address_line2,#respondent_address_line3,#respondent_town,#respondent_postcode,#respondent_county').val("unknown");
             $("#respondent_country").val(242);

             var package_id = parseInt('<?=$package_id;?>');
             if(package_id == 6 || package_id == 2 || package_id == 196 || package_id == 195)
             {
                alert("If you do not know the address of the Respondent, or you are in contact and the Respondent will not confirm an address then you will need to upgrade your service - please call 01793 211211 to discuss this further.\n\nHowever, if you are in contact with the Respondent but you do not know the address at this stage, you will not be able to continue until you have this.");

                $('#stage2_submit').attr("disabled", true);
             }


        }else{
             $('#stage2_submit').attr("disabled", false);
             $('#respondent_address_line1,#respondent_address_line2,#respondent_address_line3,#respondent_town,#respondent_postcode,#respondent_county').val("");
             $("#respondent_country").val($("#respondent_country option:first").val());
        }
    });



    //dynamic datepickers
    var spouse_child_count = parseInt($('#spouse_child_count').val()),
        spounse_date_picker = "",
        other_child_count = parseInt($('#other_child_count').val()),
        other_date_picker = "";

    for (i = 0; i < spouse_child_count; i++) { 
       spounse_date_picker += ',#spouse_datepicker_'+i;
    }

    for (i = 0; i < other_child_count; i++) { 
       other_date_picker += ',#other_datepicker_'+i;
    }

    var today = new Date();
    $("#petitoner_dob,#respondent_dob,#marriage_date_of_marriage,#marriage_date_of_separation,#adultery_when_discovered_date,#date_of_desertion"+spounse_date_picker+other_date_picker+"  ").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange: '1916:<?=date("Y");?>',
        dateFormat:'dd-mm-yy'
    });

});
</script>



<?php $this->endSection() ;?>