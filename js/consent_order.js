(function(){
	$(document).ready(function(){

		var consentOrder = {

			init: function(){
				this.attachListeners();
				$(document).on("click",".remove_applicant_option,.remove_respondent_option,.remove_respondent_debt_option,.remove_respondent_debt_option",this.removeOption);
			},
			attachListeners: function(){

				$('#applicant_income').blur(this.formatFieldForMoney);
				$('#respondent_income').blur(this.formatFieldForMoney);

				$('.applicant_clone_select_options').click(this.cloneSelectOptionsApplicant);
				$('.respondent_clone_select_options').click(this.cloneSelectOptionsRespondent);

				$('.applicant_clone_debt_options').click(this.cloneDebtOptionsApplicant);
				$('.respondent_clone_debt_options').click(this.cloneDebtOptionsRespondent);

				$('#house_value_applicant,#mortgage_left_applicant').keyup(this.calcApplicantEquity);
				$('#house_value_respondent,#mortgage_left_respondent').keyup(this.calcRespondentEquity);

				$('.applicant_a_b_calclate').click(this.calclateApplicantAB);
				$('.respondent_a_b_calclate').click(this.calclateRespondentAB);

				$('.applicant_c_d_calclate').click(this.calculateApplicantCD);
				$('.respondent_c_d_calclate').click(this.calculateRespondentCD);

				$('.applicant_e_f_calclate').click(this.calculateApplicateEF);
				$('.respondent_e_f_calclate').click(this.calculateRespondentEF);
			},
			cloneSelectOptionsApplicant: function(){
				
				var selectElm = '<div class="form-group">';
					   selectElm += '<select name="other_options_respondent[]" class="form-control">';
							selectElm += '<option value="">--Select Option--</option>';
							selectElm += '<option value="none">None</option>';
							selectElm += '<option value="savings">Savings (account)</option>';
							selectElm += '<option value="isas">ISAS</option>';
							selectElm += '<option value="stock_and_shares">Stock and shares</option>';
							selectElm += '<option value="business_assets">Business assets</option>';
							selectElm += '<option value="current_account">Current account</option>';
						selectElm += '</select>';
						selectElm += ' &pound;<input style="width:80px;" type="text" name="applicant_option_value[]" id="" class="form-control applicant_option_value" placeholder="0.00">';
					selectElm += '</div>'
					selectElm += '<a href="#" class="remove_applicant_option" onclick="return false;">-</a>';
				
				$(''+selectElm+'').insertBefore("a#add_applicant_other_option");				
			},
			cloneSelectOptionsRespondent: function(){

				var selectElm = '<div class="form-group">';
					   selectElm += '<select name="debt_options_applicant[]" class="form-control">';
							selectElm += '<option value="">--Select Option--</option>';
							selectElm += '<option value="none">None</option>';
							selectElm += '<option value="savings">Savings (account)</option>';
							selectElm += '<option value="isas">ISAS</option>';
							selectElm += '<option value="stock_and_shares">Stock and shares</option>';
							selectElm += '<option value="business_assets">Business assets</option>';
							selectElm += '<option value="current_account">Current account</option>';
						selectElm += '</select>';
						selectElm += ' &pound;<input style="width:80px;" type="text" name="respondent_option_value[]" id="" class="form-control respondent_option_value" placeholder="0.00">';
					selectElm += '</div>'
					selectElm += '<a href="#" class="remove_respondent_option" onclick="return false;">-</a>';
		
				$(''+selectElm+'').insertBefore("a#respondent_clone_select_options");				
			},
			cloneDebtOptionsApplicant: function(){

				var selectElm = '<div class="form-group">';
					   selectElm += '<select name="other_options_respondent[]" class="form-control">';
							selectElm += '<option value="">--Select Option--</option>';
							selectElm += '<option value="none">None</option>';
							selectElm += '<option value="cedit_cards">Credit Cards</option>';
							selectElm += '<option value="loans">Loans</option>';
							selectElm += '<option value="overdraft">Overdraft</option>';
							selectElm += '<option value="other">other</option>';
						selectElm += '</select>';
						selectElm += ' &pound;<input style="width:80px;" type="text" name="applicant_option_debt_value[]" id="" class="form-control applicant_option_debt_value" placeholder="0.00">';
					selectElm += '</div>'
					selectElm += '<a href="#" class="remove_respondent_debt_option" onclick="return false;">-</a>';
		
				$(''+selectElm+'').insertBefore("a#applicant_clone_debt_options");				
			},
			cloneDebtOptionsRespondent: function(){

					var selectElm = '<div class="form-group">';
					   selectElm += '<select name="debt_options_respondent[]" class="form-control">';
							selectElm += '<option value="">--Select Option--</option>';
							selectElm += '<option value="none">None</option>';
							selectElm += '<option value="cedit_cards">Credit Cards</option>';
							selectElm += '<option value="loans">Loans</option>';
							selectElm += '<option value="overdraft">Overdraft</option>';
							selectElm += '<option value="other">other</option>';
						selectElm += '</select>';
						selectElm += ' &pound;<input style="width:80px;" type="text" name="respondent_option_debt_value[]" id="" class="form-control respondent_option_debt_value" placeholder="0.00">';
					selectElm += '</div>'
					selectElm += '<a href="#" class="remove_respondent_debt_option" onclick="return false;">-</a>';
		
				$(''+selectElm+'').insertBefore("a#respondent_clone_debt_options");				
			},
			removeOption: function(){
				var $this = $(this);
				$this.prev('.form-group').remove();
				$this.remove();
				
			},
			calcApplicantEquity: function(){

				var appliant_house_price = (typeof  $('#house_value_applicant') !== "undefined" )  ? $('#house_value_applicant').val().replace(/\D/g,'') : "",
				    applicant_mortgage_left = (typeof  $('#mortgage_left_applicant') !== "undefined" )  ? $('#mortgage_left_applicant').val().replace(/\D/g,'') : "",
				    applicant_total = $('#applicant_equity_total'),
				    total = "";

				 if(appliant_house_price != "" && applicant_mortgage_left != ""){
				 	//do the calulations
				 	total = parseInt(appliant_house_price) - parseInt(applicant_mortgage_left);
				 	applicant_total.val(consentOrder.addCommas(total) );
				 }else{
				 	applicant_total.val("0.00");
				 }		
			},
			calcRespondentEquity: function(){

				var respondent_house_price = (typeof  $('#house_value_respondent') !== "undefined" )  ? $('#house_value_respondent').val().replace(/\D/g,'') : "",
				    respondent_mortgage_left = (typeof  $('#mortgage_left_respondent') !== "undefined" )  ? $('#mortgage_left_respondent').val().replace(/\D/g,'') : "",
				    respondent_total = $('#respondent_equity_total'),
				    total = "";

				 if(respondent_house_price != "" && respondent_mortgage_left != ""){
				 	//do the calulations
				 	total = parseInt(respondent_house_price) - parseInt(respondent_mortgage_left);
				 	respondent_total.val(consentOrder.addCommas(total) );
				 }else{
				 	respondent_total.val("0.00");
				 }		
			},
			formatFieldForMoney: function(){

				var $this = $(this),
				    $elm = $this.attr("id"),
				    cVal = $('#'+$elm+'').val().replace(/\D/g,''),
				    moneyFormatted = consentOrder.addCommas(cVal);

				 if(cVal != ""){
				 	$('#'+$elm+'').val(moneyFormatted);
				 }
			},

			calclateApplicantAB: function(){

				var $totalElm = $('#applicant_a_b_total'),
					applicant_eq_total = $('#applicant_equity_total').val().replace(/\D/g,'');

					if(typeof applicant_eq_total !== "undefined" && applicant_eq_total != ""){
						
						//we now need to loop through the "other options"
						var option_total = 0;
						$("input.applicant_option_value").each(function(){
							if(this.value != 0 && this.value != ""){
								option_total =  parseInt(option_total) + parseInt(this.value.replace(/\D/g,'')) ;
							}
						});
						
						var applicant_final_total = ( option_total != 0 ) ? consentOrder.addCommas(parseInt(option_total) + parseInt(applicant_eq_total)) : consentOrder.addCommas(parseInt(applicant_eq_total));
						$totalElm.val(applicant_final_total);

					}else{
						alert("You must enter the applicant's total equity before we can calculate the total");
					}
			},
			calclateRespondentAB: function(){

				var $totalElm = $('#respondent_a_b_total'),
					respondent_eq_total = $('#respondent_equity_total').val().replace(/\D/g,'');

					if(typeof respondent_eq_total !== "undefined" && respondent_eq_total != ""){
						
						//we now need to loop through the "other options"
						var option_total = 0;
						$("input.respondent_option_value").each(function(){
							if(this.value != 0 && this.value != ""){
								option_total =  parseInt(option_total) + parseInt(this.value.replace(/\D/g,'')) ;
							}
						});
						
						var respondent_final_total = ( option_total != 0 ) ? consentOrder.addCommas(parseInt(option_total) + parseInt(respondent_eq_total)) : consentOrder.addCommas(parseInt(respondent_eq_total));
						$totalElm.val(respondent_final_total);

					}else{
						alert("You must enter the respondent's total equity before we can calculate the total");
					}
			},
			calculateApplicantCD: function(){

				var $totalElm = $('#applicant_c_d_total'),
					applicant_ab_total = $('#applicant_a_b_total').val().replace(/\D/g,'');

					if(typeof applicant_ab_total !== "undefined" && applicant_ab_total != ""){
						
						//we now need to loop through the "other options"
						var option_total = 0;
						$("input.applicant_option_debt_value").each(function(){
							if(this.value != 0 && this.value != ""){
								option_total =  parseInt(option_total) + parseInt(this.value.replace(/\D/g,'')) ;
							}
						});
						
						var applicant_final_total = ( option_total != 0 ) ? consentOrder.addCommas(parseInt(applicant_ab_total) - parseInt(option_total)) : consentOrder.addCommas(parseInt(applicant_ab_total));
						$totalElm.val(applicant_final_total);

					}else{
						alert("You must calculate the applicant's gross capital before continuing");
					}
			},
			calculateRespondentCD: function(){

				var $totalElm = $('#respondent_c_d_total'),
					respondent_ab_total = $('#respondent_a_b_total').val().replace(/\D/g,'');

					if(typeof respondent_ab_total !== "undefined" && respondent_ab_total != ""){
						
						//we now need to loop through the "other options"
						var option_total = 0;
						$("input.respondent_option_debt_value").each(function(){
							if(this.value != 0 && this.value != ""){
								option_total =  parseInt(option_total) + parseInt(this.value.replace(/\D/g,'')) ;
							}
						});
						
						var responden_final_total = ( option_total != 0 ) ? consentOrder.addCommas(parseInt(respondent_ab_total) - parseInt(option_total)) : consentOrder.addCommas(parseInt(respondent_ab_total));
						$totalElm.val(responden_final_total);

					}else{
						alert("You must calculate the respondent's gross capital before continuing");
					}
			},
			calculateApplicateEF: function(){

				var $totalElm = $('#applicant_e_f_total'),
					applicant_cd_total = $('#applicant_c_d_total').val().replace(/\D/g,''),
					applicant_pension_total = $('#applicant_pension_total').val().replace(/\D/g,'');

					if(typeof applicant_cd_total !== "undefined" && applicant_cd_total != ""){

						var respondent_final_total = consentOrder.addCommas(parseInt(applicant_cd_total) + parseInt(applicant_pension_total)) ;

						if(respondent_final_total != "NaN"){
							$totalElm.val(respondent_final_total);
						}else{
							$totalElm.val("0.00");
						}
					}else{
						alert("You must calculate the applicants's net capital before continuing");
					}
			},
			calculateRespondentEF: function(){
				var $totalElm = $('#respondent_e_f_total'),
					respondent_cd_total = $('#respondent_c_d_total').val().replace(/\D/g,''),
					respondent_pension_total = $('#respondent_pension_total').val().replace(/\D/g,'');

					if(typeof respondent_cd_total !== "undefined" && respondent_cd_total != ""){

						var respondent_final_total = consentOrder.addCommas(parseInt(respondent_cd_total) + parseInt(respondent_pension_total)) ;

						if(respondent_final_total != "NaN"){
							$totalElm.val(respondent_final_total);
						}else{
							$totalElm.val("0.00");
						}
					}else{
						alert("You must calculate the respondents's net capital before continuing");
					}
			},
			addCommas: function(str) {
			    var parts = (str + "").split("."),
			        main = parts[0],
			        len = main.length,
			        output = "",
			        first = main.charAt(0),
			        i;

			    if (first === '-') {
			        main = main.slice(1);
			        len = main.length;    
			    } else {
			        first = "";
			    }
			    i = len - 1;
			    while(i >= 0) {
			        output = main.charAt(i) + output;
			        if ((len - i) % 3 === 0 && i > 0) {
			            output = "," + output;
			        }
			        --i;
			    }
			    // put sign back
			    output = first + output;
			    // put decimal part back
			    if (parts.length > 1) {
			        output += "," + parts[1];
			    }
			    return output;
			},


			
		}

		consentOrder.init();
	});
})();