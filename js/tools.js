function showhide(id){
	obj = document.getElementById(id);
	obj_img = document.getElementById(id + 'img');
	obj_docs = document.getElementById(id + 'docs');
	obj_delete = document.getElementById(id + 'delete');
	tag = document.getElementById(id + 'toggle');
	
	if (obj != null) {
		test = obj;
	} else if (obj_img != null) {
		test = obj_img;
	} else if (obj_docs != null) {
		test = obj_docs;
	}
	
	if (test.style.display == "table"){
		tag.innerHTML = '+';
		if (obj != null) {
			obj.style.display = "none";
		}
		if (obj_img != null) {
			obj_img.style.display = "none";
		}
		if (obj_docs != null) {
			obj_docs.style.display = "none";
		}
		if (obj_delete != null) {
			obj_delete.style.display = "none";
		}
	} else {
		tag.innerHTML = '-';
		if (obj_img != null) {
			obj_img.style.display = "table";
		}
		if (obj_docs != null) {
			obj_docs.style.display = "table";
		}
		if (obj_delete != null) {
			obj_delete.style.display = "table";
		}
		if (obj != null) {
			obj.style.display = "table";
		} 
	}  
	
}

function checkAllKeywords(){

	var checkboxes = document.getElementsByName('selected_keywords[]');
	var button = document.getElementById('checkall');

	if(button.checked == true){
		for (var i in checkboxes){
			checkboxes[i].checked = 'FALSE';
		}
	}else{
		for (var i in checkboxes){
			checkboxes[i].checked = '';
		}
	}
}

function checkAllElements(id) {
	
	var checkboxes = document.getElementsByName(id + '[]');
	var button = document.getElementById('checkall_' + id);

	if(button.checked == true){
		for (var i in checkboxes){
			checkboxes[i].checked = 'FALSE';
		}
	}else{
		for (var i in checkboxes){
			checkboxes[i].checked = '';
		}
	}
}
