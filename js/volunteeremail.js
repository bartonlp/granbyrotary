// Volunteer Logic

var target = '';

function sendemail(f, id) {
  // f is the form this.
  // id is the name of the target tag to use in ajaxSucc()
  target = id;

  var ajaxRequest = new Ajax.Request('vMailer.ajax.php' , {
method: 'post',
parameters: f.serialize(),           
onSuccess: ajaxSucc, 
onFailure: function(trans) { alert("error: " +trans.responseText);}
  });

  return false;
}

function ajaxSucc(trans) {
  if(trans.responseText.match(/ok/)) {
    if(target) {
      d = $(target);
      d.innerHTML = "<p style='font-size: 20pt; color: red;'>Thank you for Volunteering!</p>";
    } else {
      alert('Thank you for Volunteering!');
    }
  } else {
    alert("Error:"+trans.responseText);
  }
  target = '';
  return false;
}

