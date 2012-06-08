$(document).ready(function(){
	
	$(".grow").hide();
	$(".grow").slideDown("slow");
	
	
	$(".more").hide();
	$(".showmore").click(function(){
			$(".more").slideToggle("slow");
			$(".showmore .choices").toggle();
	});
	
});

/* Contact Form Validation */
function validate(form)
{
	if( form.cfName.value == "" || form.cfEmail.value == "" || form.cfComments.value == "" ) 
   { 
	  document.getElementById('message').innerHTML = 'Please fill out name, email and a message.';
	  document.getElementById('message').style.display = 'block';
	  return false; 
   }
}