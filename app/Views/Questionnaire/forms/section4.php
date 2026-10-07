<p>This section is for you to tell us about your children. Please specify, if any, how many children were born to you both.</p>
<p>And how many, if any children were born to either the petitioner or respondent outside of the marriage.</p>
<p>If no, please click continue</p>
<br />

<strong>Child Declaration</strong><br />

<?=form_open('');?>


    <?php
          if (session()->has('errors')) 
          {
             $errors = session('errors');
             if (is_array($errors)) {
                 echo '<div class="alert alert-danger">
                         <button class="close" data-dismiss="alert">&times;</button>';
                 foreach ($errors as $e) {
                     echo $e . '<br />';
                 }
                 echo '</div>';
             } else {
                 // Handle cases where errors might be a single string
                 echo '<div class="alert alert-danger">
                         <button class="close" data-dismiss="alert">&times;</button>';
                 echo $errors;
                 echo '</div>';
             }
         }
    ?>

    <div class="checkbox">
        <label><input type="checkbox" id="children_by_spouse" name="children_by_spouse" value="1"
        <?php if($qdata['children_by_spouse'] == 1){echo "checked";}?>>Any children by spouse?</label>
    </div>

    <div class="control-group form-group" id="spouse_children">
        <div class="controls" class="hidden">
        <label for="num_of_children_by_spouse">Please specify how many children you and your spouse have:</label>
        <input class="<?php if($qdata['num_of_children_by_spouse'] >= 1){echo "checked";}else{echo 'hidden';}?> form-control" id="num_of_children_by_spouse" name="num_of_children_by_spouse" class="form-control" type="text" value="<?php if(isset($qdata['num_of_children_by_spouse']) && !isset($_POST['num_of_children_by_spouse']) && $qdata['num_of_children_by_spouse'] != "") { echo $qdata['num_of_children_by_spouse']; }elseif(isset($_POST['num_of_children_by_spouse'])){ echo set_value('num_of_children_by_spouse');} ?>" style="width:42px;">
        </div>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" id="children_by_other" name="children_by_other" value="1"
        <?php if($qdata['children_by_other'] >= 1 && $qdata['num_of_children_by_other'] > 0 ){echo "checked";}?>>Have any children been born to either of you by another person during the marriage or are there any step children?</label>

        <label class="<?php if($qdata['children_by_other'] >= 1){echo '';}else{echo 'hidden';}?>" for="children_by_other_name_unknow" style="margin-left:10px;">
        <input type="checkbox" id="children_by_other_name_unknow" name="children_by_other_name_unknow" value="1"
        <?php if($qdata['children_by_other_name_unknow'] >= 1){echo "checked";}?> <?php if(@$_POST['children_by_other_name_unknow'] == 1){echo "checked";} ;?> >Please tick the box if you do NOT know the names of these children?</label>

        <input class="<?php if($qdata['num_of_children_by_other'] != 0){echo "checked";}else{echo 'hidden';}?> form-control" id="num_of_children_by_other" name="num_of_children_by_other" class="form-control" type="text" value="<?php if(isset($qdata['num_of_children_by_other']) && !isset($_POST['num_of_children_by_other']) && $qdata['num_of_children_by_other'] != "") { echo $qdata['num_of_children_by_other']; }elseif(isset($_POST['num_of_children_by_other'])){ echo set_value('num_of_children_by_other');} ?>" style="width:42px;">
    </div>


<div class="control-group form-group">
    <div class="controls">
    <a href="<?=base_url();?>questionnaire/stage1/3" class="btn btn-primary" role="button">Previous</a>
    <input id="stage4_submit" class="btn btn-primary" name="stage4_submit" class="form-control" type="submit" value="<?= ( $segment5 == 1 ) ? 'Update' : 'Continue'; ?>">
    </div>
</div>		
<?=form_close();?>

<?php if($segment5 == 1 || $qdata['num_of_children_by_spouse'] > 0 || $qdata['num_of_children_by_other'] > 0 ) : ?>

    <hr>

    <?=form_open('');?>


   <?php if($qdata['num_of_children_by_spouse'] != 0) :?>
       <p>Please provide the full names (including middle name(s)) and dates of birth of children born to the petitioner and respondent during the marriage</p>

       <?php if($qdata['num_of_children_by_spouse'] >20):?>
       <div class="alert alert-warning" role="alert">Please amend the number of children by spouse entered above as this exceeds are maximum allowance(20).</div>
   <?php endif;?>


   <?php

    if (session()->has("child_errrors") ){

       echo ' <div class="alert alert-danger">
                   <button class="close" data-dismiss="alert"></button>';
                   foreach(session("child_errrors") as $key => $e){
                       echo "Child details row {$key} contains errors, please ensure you have entered all required field.<br />";
                   }
       echo '</div>';                          
   }
   
   ?>

<div class="panel panel-default responsive-table">
   <div class="panel-heading"><strong>Children by spouse</strong></div>
   <table class="table">
      <thead>
         <tr>
            <th>Forename</th>
            <th>Middle name</th>
            <th>Surname</th>
            <th>Date of birth</th>
            <th>Gender</th>
            <th>Aged 16-18 in education,<br /> training or full time work</th>
         </tr>
      </thead>
      <tbody>
         <?php for($x = 0; $x <$qdata['num_of_children_by_spouse']; $x++ ):?>
         <tr>

            <?php  

               // Repopulate the forename
               $forename = "";
               if(isset($children_by_spouse[$x]) && $children_by_spouse[$x]['spouse_child_forename'] && !isset(old("spouse_child_details")[$x]['spouse_child_forename']) ){
                  $forename = $children_by_spouse[$x]['spouse_child_forename'];
               }elseif(isset(old("spouse_child_details")[$x]['spouse_child_forename'])){
                   $forename = old("spouse_child_details")[$x]['spouse_child_forename'];
               }


               // Repopulate the middle name
               $middlename = "";
               if(isset($children_by_spouse[$x]) && $children_by_spouse[$x]['spouse_child_middlename'] && !isset(old("spouse_child_details")[$x]['spouse_child_middlename']) ){
                  $middlename = $children_by_spouse[$x]['spouse_child_middlename'];
               }elseif(isset(old("spouse_child_details")[$x]['spouse_child_middlename'])){
                   $middlename = old("spouse_child_details")[$x]['spouse_child_middlename'];
               }


               // Repopulate the surname
               $surname = "";
               if(isset($children_by_spouse[$x]) && $children_by_spouse[$x]['spouse_child_surname'] && !isset(old("spouse_child_details")[$x]['spouse_child_surname']) ){
                  $surname = $children_by_spouse[$x]['spouse_child_surname'];
               }elseif(isset(old("spouse_child_details")[$x]['spouse_child_surname'])){
                  $surname = old("spouse_child_details")[$x]['spouse_child_surname'];
               }


               // Repopulate the DOB
               $dob = "";
               if(isset($children_by_spouse[$x]) && $children_by_spouse[$x]['child_dob'] && !isset(old("spouse_child_details")[$x]['dob']) ){
                  $dob = $children_by_spouse[$x]['child_dob'];
               }elseif(isset(old("spouse_child_details")[$x]['dob'])){
                  $dob = old("spouse_child_details")[$x]['dob'];
               }


               // Repopulate the gender
               $maleSelected = "";
                if(isset($children_by_spouse[$x]) && $children_by_spouse[$x]['child_gender'] && !isset(old("spouse_child_details")[$x]['gender']) && $children_by_spouse[$x]['child_gender'] == "male" ){
                  $maleSelected = "selected";
               }elseif(isset(old("spouse_child_details")[$x]['gender']) && old("spouse_child_details")[$x]['gender'] == "male"){
                  $maleSelected = "selected";
               }


               $femaleSelected = "";
               if(isset($children_by_spouse[$x]) && $children_by_spouse[$x]['child_gender'] && !isset(old("spouse_child_details")[$x]['gender']) && $children_by_spouse[$x]['child_gender'] == "female" ){
                  $femaleSelected = "selected";
               }elseif(isset(old("spouse_child_details")[$x]['gender']) && old("spouse_child_details")[$x]['gender'] == "female"){
                  $femaleSelected = "selected";
               }


               // Repopulate the training select
               $trainingSelectYes = "";
               if(isset($children_by_spouse[$x]) && !isset(old("spouse_child_details")[$x]['in_edc_train_ftwok']) && $children_by_spouse[$x]['in_educ_train_fwork'] == "1" ){
                  $trainingSelectYes = "selected";
               }elseif(isset(old("spouse_child_details")[$x]['in_edc_train_ftwok']) && old("spouse_child_details")[$x]['in_edc_train_ftwok'] == "1"){
                  $trainingSelectYes = "selected";
               }

               $trainingSelectNo = "";
               if(isset($children_by_spouse[$x]) && !isset(old("spouse_child_details")[$x]['in_edc_train_ftwok']) && $children_by_spouse[$x]['in_educ_train_fwork'] == "0" ){
                  $trainingSelectNo = "selected";
               }elseif(isset(old("spouse_child_details")[$x]['in_edc_train_ftwok']) && old("spouse_child_details")[$x]['in_edc_train_ftwok'] == "0"){
                  $trainingSelectNo = "selected";
               }



            ?>

            <td><input type="text" name="spouse_child_details[<?=$x;?>][spouse_child_forename]" id="" class="form-control" required value="<?=$forename;?>"></td>
            <td><input type="text" name="spouse_child_details[<?=$x;?>][spouse_child_middlename]" id="" class="form-control" value="<?=$middlename;?>"></td>
            <td><input type="text" name="spouse_child_details[<?=$x;?>][spouse_child_surname]" id="" class="form-control" required value="<?=$surname;?>"></td>
            <td><input type="text" name="spouse_child_details[<?=$x;?>][dob]" id="spouse_datepicker_<?=$x;?>" class="form-control" required value="<?=$dob;?>"></td>

            <td>
               <select name="spouse_child_details[<?=$x;?>][gender]" id="spouse_child_details[<?=$x;?>][gender]" class="form-control" required>
                  <option value="">--Please Select--</option>
                  <option value="female" <?=$femaleSelected;?>>Female</option>
                  <option value="male" <?=$maleSelected;?>>Male</option>
               </select>
            </td>
            <td>
               <select name="spouse_child_details[<?=$x;?>][in_edc_train_ftwok]" id="spouse_child_details[<?=$x;?>][in_edc_train_ftwok]" class="form-control" required>
                  <option value="">--Please Select--</option>
                  <option value="1" <?=$trainingSelectYes;?>>Yes</option>
                  <option value="0" <?=$trainingSelectNo;?>>No</option>
               </select>
            </td>
         </tr>
         <?php endfor;?>
      </tbody>
   </table>
</div>
<?php endif;?>

   
   <?php

    if (session()->has("child_errrors") ){

       echo ' <div class="alert alert-danger">
                   <button class="close" data-dismiss="alert"></button>';
                   foreach(session("child_errrors") as $key => $e){
                       echo "Child details row {$key} contains errors, please ensure you have entered all required field.<br />";
                   }
       echo '</div>';                          
   }
   
   ?>



    <?php if($qdata['num_of_children_by_other'] != 0) :?>
        <p>Please provide the fullnames (including middle name(s)) and dates of birth of children born to the petitioner and respondent prior to during the marriage, who lived with you both throughout the marriage (ie step children)</p>

        <?php if($qdata['num_of_children_by_other'] >20):?>
            <div class="alert alert-warning" role="alert">Please amend the number of children by other entered above as this exceeds are maximum allowance(20).</div>
        <?php endif;?>
    <div class="panel panel-default">
       <div class="panel-heading"><strong>Children by other</strong></div>
       <table class="table">
          <thead>
             <tr>
                <th>Forename</th>
                <th>Middle Name</th>
                <th>Surname</th>
                <th>Date Of Birth</th>
                <th>Gender</th>
                <th>Step Child To</th>
                <th>Name Known</th>
             </tr>
          </thead>
          <tbody>
             <?php for($x = 0; $x <$qdata['num_of_children_by_other']; $x++ ):?>

               <?php 
                  // Repopulate the forename
                  $forename = "";
                  if(isset($get_children_by_other[$x]) && $get_children_by_other[$x]['other_child_forename'] && !isset(old("other_child_details")[$x]['other_child_forename']) ){
                     $forename = $get_children_by_other[$x]['other_child_forename'];
                  }elseif(isset(old("other_child_details")[$x]['other_child_forename'])){
                      $forename = old("other_child_details")[$x]['other_child_forename'];
                  }

                  // Repopulate the middle name
                  $middlename = "";
                  if(isset($get_children_by_other[$x]) && $get_children_by_other[$x]['other_child_middlename'] && !isset(old("other_child_details")[$x]['other_child_middlename']) ){
                     $middlename = $get_children_by_other[$x]['other_child_middlename'];
                  }elseif(isset(old("other_child_details")[$x]['other_child_middlename'])){
                      $middlename = old("other_child_details")[$x]['other_child_middlename'];
                  }


                  // Repopulate the surname
                  $surname = "";
                  if(isset($get_children_by_other[$x]) && $get_children_by_other[$x]['other_child_surname'] && !isset(old("other_child_details")[$x]['other_child_surname']) ){
                     $surname = $get_children_by_other[$x]['other_child_surname'];
                  }elseif(isset(old("other_child_details")[$x]['other_child_surname'])){
                      $surname = old("other_child_details")[$x]['other_child_surname'];
                  }

                  // Repopulate the DOB
                  $dob = "";
                  if(isset($get_children_by_other[$x]) && $get_children_by_other[$x]['child_dob'] && !isset(old("other_child_details")[$x]['dob']) ){
                     $dob = $get_children_by_other[$x]['child_dob'];
                  }elseif(isset(old("other_child_details")[$x]['dob'])){
                      $dob = old("other_child_details")[$x]['dob'];
                  }


                  // Repopulate the gender
                  $maleSelected = "";
                   if(isset($get_children_by_other[$x]) && $get_children_by_other[$x]['child_gender'] && !isset(old("other_child_details")[$x]['gender']) && $get_children_by_other[$x]['child_gender'] == "male" ){
                     $maleSelected = "selected";
                  }elseif(isset(old("other_child_details")[$x]['gender']) && old("other_child_details")[$x]['gender'] == "male"){
                     $maleSelected = "selected";
                  }


                  $femaleSelected = "";
                  if(isset($get_children_by_other[$x]) && $get_children_by_other[$x]['child_gender'] && !isset(old("other_child_details")[$x]['gender']) && $get_children_by_other[$x]['child_gender'] == "female" ){
                     $femaleSelected = "selected";
                  }elseif(isset(old("other_child_details")[$x]['gender']) && old("other_child_details")[$x]['gender'] == "female"){
                     $femaleSelected = "selected";
                  }

                  $petSelected = "";
                  if(isset($get_children_by_other[$x]) && !isset(old("other_child_details")[$x]['born_or_adopted_by']) && $get_children_by_other[$x]['born_or_adopted_by'] == "petitioner" ){
                     $petSelected = "selected";
                  }elseif(isset(old("other_child_details")[$x]['born_or_adopted_by']) && old("other_child_details")[$x]['born_or_adopted_by'] == "petitioner"){
                     $petSelected = "selected";
                  }


                  $resSelected = "";
                  if(isset($get_children_by_other[$x]) && !isset(old("other_child_details")[$x]['born_or_adopted_by']) && $get_children_by_other[$x]['born_or_adopted_by'] == "respondant" ){
                     $resSelected = "selected";
                  }elseif(isset(old("other_child_details")[$x]['born_or_adopted_by']) && old("other_child_details")[$x]['born_or_adopted_by'] == "respondant"){
                     $resSelected = "selected";
                  }


                  $petSelected = "";
                  if(isset($get_children_by_other[$x]) && !isset(old("other_child_details")[$x]['born_or_adopted_by']) && $get_children_by_other[$x]['born_or_adopted_by'] == "petitioner" ){
                     $petSelected = "selected";
                  }elseif(isset(old("other_child_details")[$x]['born_or_adopted_by']) && old("other_child_details")[$x]['born_or_adopted_by'] == "petitioner"){
                     $petSelected = "selected";
                  }


                  $nameKnownYes = "";
                  if(isset($get_children_by_other[$x]) && !isset(old("other_child_details")[$x]['other_child_name_known']) && $get_children_by_other[$x]['other_child_name_known'] == "1" ){
                     $nameKnownYes = "selected";
                  }elseif(isset(old("other_child_details")[$x]['other_child_name_known']) && old("other_child_details")[$x]['other_child_name_known'] == "1"){
                     $nameKnownYes = "selected";
                  }

                  $nameKnownNo = "";
                  if(isset($get_children_by_other[$x]) && !isset(old("other_child_details")[$x]['other_child_name_known']) && $get_children_by_other[$x]['other_child_name_known'] == "0" ){
                     $nameKnownNo = "selected";
                  }elseif(isset(old("other_child_details")[$x]['other_child_name_known']) && old("other_child_details")[$x]['other_child_name_known'] == "0"){
                     $nameKnownNo = "selected";
                  }
               ?>
             <tr>
                <td><input type="text" name="other_child_details[<?=$x;?>][other_child_forename]" id="" class="form-control" value="<?=$forename;?>"></td>
                <td><input type="text" name="other_child_details[<?=$x;?>][other_child_middlename]" id="" class="form-control" value="<?=$middlename;?>"></td>
                <td><input type="text" name="other_child_details[<?=$x;?>][other_child_surname]" id="" class="form-control" value="<?=$surname;?>"></td>
                <td><input type="text" name="other_child_details[<?=$x;?>][dob]" id="other_datepicker_<?=$x;?>" class="form-control" value="<?=$dob;?>"></td>
                <td>
                   <?php $gender = @$_POST['other_child_details'][$x]['gender'];?>
                   <select name="other_child_details[<?=$x;?>][gender]" id="other_child_details[<?=$x;?>][gender]" class="form-control" >
                      <option value="">--Please Select--</option>
                      <option value="female" <?=$femaleSelected;?>>Female</option>
                      <option value="male" <?=$maleSelected;?>>Male</option>
                   </select>
                </td>
                <td>
                   <?php $rel = @$_POST['other_child_details'][$x]['born_or_adopted_by'];?>
                   <select name="other_child_details[<?=$x;?>][born_or_adopted_by]" id="other_child_details[<?=$x;?>][born_or_adopted_by]" class="form-control" required>
                      <option value="">--Please Select--</option>
                      <option value="petitioner" <?=$petSelected;?>>Petitioner</option>
                      <option value="respondant" <?=$resSelected;?>>Respondant</option>
                   </select>
                </td>
                <td>
                   <select name="other_child_details[<?=$x;?>][other_child_name_known]" id="other_child_details[<?=$x;?>][other_child_name_known]" class="form-control" required>
                      <option value="">--Please Select--</option>
                      <option value="1" <?=$nameKnownYes;?>>Yes</option>
                      <option value="0" <?=$nameKnownNo;?>>No</option>
                   </select>
                </td>
             </tr>
             <?php endfor;?>
          </tbody>
       </table>
    </div>
    <?php endif;?>
    <input type="submit" class="btn btn-primary" name="children_submit_stage_4_submit" value="Next">
<?=form_close();?>
<?php endif;?>