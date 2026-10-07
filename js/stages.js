

(function(){
	$(document).ready(function(){

		var stage1Obj = {

			init: function(){

				var children_by_spouse = $('#children_by_spouse'),
				    children_by_other = $('#children_by_other');

				    children_by_spouse.on('click',function(){

				    	if(children_by_spouse.is(":checked")){
				    		stage1Obj.showElements("spouse");
				    	}else{
				    		stage1Obj.hideElements("spouse");
				    	}
				    });

				    children_by_other.on('click',function(){

				    	if(children_by_other.is(":checked")){
				    		stage1Obj.showElements("other");
				    	}else{
				    		stage1Obj.hideElements("other");
				    	}
				    });

				    $('#children_by_other_name_unknow').on('click',this.hideOtherChildNum);

			},
			showElements: function(type){
				
				var num_children = $('#num_of_children_by_spouse'),
				    num_children_label = $('label[for=num_of_children_by_spouse]'),
				    num_other_children = $('#num_of_children_by_other'),
				    num_other_chidren_label = $('label[for=children_by_other_name_unknow]');

				if(type == "spouse"){
					num_children_label.removeClass("hidden");
					num_children.removeClass("hidden");
					
				}else if(type == "other"){
					num_other_chidren_label.removeClass("hidden");
					num_other_children.removeClass("hidden");
				}
			},
			hideElements: function(type){

				var num_children = $('#num_of_children_by_spouse'),
				    num_children_label = $('label[for=num_of_children_by_spouse]'),
				    num_other_children = $('#num_of_children_by_other'),
				    num_other_chidren_label = $('label[for=children_by_other_name_unknow]');

				
				if(type == "spouse"){
					num_children_label.addClass("hidden");
					num_children.addClass("hidden");
					num_children.val(0);

				}else if(type == "other"){
					num_other_chidren_label.addClass("hidden");
					num_other_children.addClass("hidden");
					num_other_children.val(0);
				}
			},
			hideOtherChildNum: function(){

				var num_of_other_children = $('#num_of_children_by_other');

				if($('#children_by_other_name_unknow').is(":checked")){
					num_of_other_children.addClass("hidden");
					num_of_other_children.val(0);
				}else{
					num_of_other_children.removeClass("hidden");
				}
			}
		}

		stage1Obj.init();
	});
})();