$(document).on('focus', 'input[type="text"]:not(.editable_txt,.addr_editable_txt),textarea:not(.editable_txt)', function(event) {
  var TargetID = event.target.id;
    event.target.addEventListener('keyup', stayUpperCase);

    event.target.addEventListener('change', stayUpperCase);

  });

$(window).on('keyup', function(event){
    if(event.keyCode == '9'){

        var TargetID = event.target.id;

        event.target.addEventListener('keyup', stayUpperCase);

        event.target.addEventListener('change', stayUpperCase);


    }

});

  var stayUpperCase = function () {
   if(this.value != this.value.toUpperCase()){
     var caretPos = getCaretPosition(this);
     this.value = this.value.toUpperCase();
     setCaretPosition(this, caretPos);
   }
 };

 function getCaretPosition (elem) {
 // Initialize
 var caretPos = 0;

 // IE Support
 if (document.selection) {
   // Set focus on the element
   elem.focus ();
   // To get cursor position, get empty selection range
   var sel = document.selection.createRange ();
   // Move selection start to 0 position
   sel.moveStart ('character', -elem.value.length);
   // The caret position is selection length
   caretPos = sel.text.length;
 }

 // Firefox support
 else if (elem.selectionStart || elem.selectionStart == '0'){
   caretPos = elem.selectionStart;
 }

 // Return results
 return (caretPos);
}

function setCaretPosition(elem, caretPos) {
 if(elem != null) {
   if(elem.createTextRange) {
     var range = elem.createTextRange();
     range.move('character', caretPos);
     range.select();
   }
   else {
     if(elem.selectionStart) {
       elem.focus();
       elem.setSelectionRange(caretPos, caretPos);
     }
     else
       elem.focus();
   }
 }
}



function insertAtCssaret(areaId, text) {
    var txtarea = areaId;
    var scrollPos = txtarea.scrollTop;
    var caretPos = txtarea.selectionStart;

    var front = (txtarea.value).substring(0, caretPos);
    var back = (txtarea.value).substring(txtarea.selectionEnd, txtarea.value.length);
    txtarea.value = front + text + back;
    caretPos = caretPos + text.length;
    txtarea.selectionStart = caretPos;
    txtarea.selectionEnd = caretPos;
    txtarea.focus();
    txtarea.scrollTop = scrollPos;
}






$(document).bind("paste", function(event){
  event.stopPropagation();
  event.preventDefault();

  if (window.clipboardData && window.clipboardData.getData) { 
	// IE
		pastedText = window.clipboardData.getData('Text');
	}
	else if (event.originalEvent.clipboardData && event.originalEvent.clipboardData.getData) { 
		// other browsers
		pastedText = event.originalEvent.clipboardData.getData('text/plain');
	}
  var upperdata = pastedText.toUpperCase();
  if(upperdata != "" && upperdata != null){
    $(event.target).parent().addClass('is-dirty')
  } 

  if($(event.target).hasClass('editable_txt') || $(event.target).hasClass('addr_editable_txt')) {
  insertAtCssaret(event.target,pastedText);
    }else{
  insertAtCssaret(event.target,upperdata);  
  }
});