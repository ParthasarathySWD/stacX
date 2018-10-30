var ie = (typeof document.selection != "undefined" && document.selection.type != "Control") && true;
var w3 = (typeof window.getSelection != "undefined") && true;
var button_save = 0;
var style_theme = theme;
var OrderUID = $('#OrderUID').val();

$(document).ready(function() {
	is_tinyMCE_active = false;
	if (typeof(tinyMCE) != "undefined") {
		if(style_theme != '3'){
			tinymce.execCommand('mceRemoveEditor', true, 'editor');
		}
	}
});


(function($) {
	$.fn.focusToEnd = function() {
		return this.each(function() {
			var v = $(this).val();
			$(this).focus().val("").val(v);
		});
	};
})(jQuery);

$("#LegalDescription").focusToEnd();

function getCaretPosition(element) {
	//console.log(window.getSelection());
	var caretOffset = 0;
	if (w3 && window.getSelection().rangeCount) {
		var range = window.getSelection().getRangeAt(0);
		var preCaretRange = range.cloneRange();
		preCaretRange.selectNodeContents(element);
		preCaretRange.setEnd(range.endContainer, range.endOffset);
		caretOffset = preCaretRange.toString().length;
	} else if (ie) {
		var textRange = document.selection.createRange();
		var preCaretTextRange = document.body.createTextRange();
		preCaretTextRange.moveToElementText(element);
		preCaretTextRange.setEndPoint("EndToEnd", textRange);
		caretOffset = preCaretTextRange.text.length;
	}
	return caretOffset;
}

function showCaretDiv(ele) {
	var el = $(ele).get(0);
	return getCaretPosition(el);
}


function change_theme(sel) {
	var theme = sel.options[sel.selectedIndex].value;

	if (theme == '3') {
		triggerpage(base_url + 'Legal_Description/index/' + OrderUID + '/theme/3');
	} else if (theme == '2') {
		triggerpage(base_url + 'Legal_Description/index/' + OrderUID + '/theme/2');
	} else {
		triggerpage(base_url + 'Legal_Description/index/' + OrderUID);
	}
}

(function($, undefined) {
	$.fn.getCursorPosition = function() {
		var el = $(this).get(0);
		var pos = 0;
		if ('selectionStart' in el) {
			pos = el.selectionStart;
		} else if ('selection' in document) {
			el.focus();
			var Sel = document.selection.createRange();
			var SelLength = document.selection.createRange().text.length;
			Sel.moveStart('character', -el.value.length);
			pos = Sel.text.length - SelLength;
		}
		return pos;
	}
})(jQuery);

function insertAtCaret(areaId, text) {
	var txtarea = document.getElementById(areaId);
	if (!txtarea) {
		return;
	}

	var scrollPos = txtarea.scrollTop;
	var strPos = 0;
	var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
		"ff" : (document.selection ? "ie" : false));
	if (br == "ie") {
		txtarea.focus();
		var range = document.selection.createRange();
		range.moveStart('character', -txtarea.value.length);
		strPos = range.text.length;
	} else if (br == "ff") {
		strPos = txtarea.selectionStart;
	}

	var front = (txtarea.value).substring(0, strPos);
	var back = (txtarea.value).substring(strPos, txtarea.value.length);
	txtarea.value = front + text + back;
	strPos = strPos + text.length;
	if (br == "ie") {
		txtarea.focus();
		var ieRange = document.selection.createRange();
		ieRange.moveStart('character', -txtarea.value.length);
		ieRange.moveStart('character', strPos);
		ieRange.moveEnd('character', 0);
		ieRange.select();
	} else if (br == "ff") {
		txtarea.selectionStart = strPos;
		txtarea.selectionEnd = strPos;
		txtarea.focus();
	}

	txtarea.scrollTop = scrollPos;
}

/*Advanced and editor view*/
$('.addtolegal').on('click', function(event) {
	event.preventDefault();

	var caretPos = $('#LegalDescription').getCursorPosition();
	var content = $('#LegalDescription').val();;
	if (style_theme == '2') {
		addcontent = $('#results_box').val();
		newcontent = content.substr(0, caretPos) + addcontent + content.substr(caretPos);
		$('#LegalDescription').val(newcontent);
		$('#results_box').val('');

	} else if (style_theme == '3') {
		addcontent = tinyMCE.get('editor').getContent({
			format: 'text'
		});
		newcontent = content.substr(0, caretPos) + addcontent + content.substr(caretPos);
		$('#LegalDescription').val(newcontent);
		addcontent = tinyMCE.get('editor').setContent('');
	}else{
		var addcontent = $('#final_span').val();
		newcontent = content.substr(0, caretPos) + addcontent + content.substr(caretPos);
		$('#LegalDescription').val(newcontent);
		$('#final_span').val('');
	}

});

/*Default view*/
$(".edittext").on('click', function(event) {
	event.preventDefault();

	var caretPos = $('#LegalDescription').getCursorPosition();

	var content = $('.editmainlegal').val();

	var addcontent = $('#final_span').val();

	if ($(this).val() == '1') {
		newcontent = content.substr(0, caretPos) + addcontent + content.substr(caretPos);

		$('#LegalDescription').val(newcontent);

		$('#final_span').val('');
		button_save = 1;

	} else if ($(this).val() == '2') {

		insertAtCaret('final_span', '\n');

	} else if ($(this).val() == '3') {
		insertAtCaret('final_span', '\n\n');

	}
});

var create_email = false;
var interim_transcript = '';
var recognizing = false;
var ignore_onend;
var start_timestamp;
var final_transcript = $('#final_span').html();
var SpeechElement = $('.speechrecg');

if (style_theme == null || style_theme == '') {
	if (!('webkitSpeechRecognition' in window)) {
		//alert('speech to text not work in this browser')
	} else {
		var recognition = new webkitSpeechRecognition();
		recognition.continuous = true;
		recognition.interimResults = true;

		recognition.onstart = function() {
			recognizing = true;
			recognition.lang = "en-US";
			//info_speak_now');
		};

		recognition.onerror = function(event) {
			if (event.error == 'no-speech') {
				$(SpeechElement).html('<i class="fa fa-microphone" aria-hidden="true"></i>');

				$(SpeechElement).removeClass('btn-danger').addClass('btn-success');
				$('#interim_span').hide();
				//info_no_speech');
				ignore_onend = true;
			}
			if (event.error == 'audio-capture') {
				$(SpeechElement).html('<i class="fa fa-microphone" aria-hidden="true"></i>');

				$(SpeechElement).removeClass('btn-danger').addClass('btn-success');
				$('#interim_span').hide();
				//info_no_microphone');
				ignore_onend = true;
			}
			if (event.error == 'not-allowed') {
				$(SpeechElement).html('<i class="fa fa-microphone" aria-hidden="true"></i>');

				$(SpeechElement).removeClass('btn-danger').addClass('btn-success');
				$('#interim_span').hide();

				if (event.timeStamp - start_timestamp < 100) {
					//info_blocked');
				} else {
					//info_denied');
				}
				ignore_onend = true;
			}
		};

		recognition.onend = function() {
			recognizing = false;

			if (ignore_onend) {
				return;
			}
			$(SpeechElement).html('<i class="fa fa-microphone" aria-hidden="true"></i>');

			$(SpeechElement).removeClass('btn-danger').addClass('btn-success');
			$('#interim_span').hide();
			if (!final_transcript) {
				//info_start');
				return;
			}
			if (window.getSelection) {
				window.getSelection().removeAllRanges();
				var range = document.createRange();
				range.selectNode(document.getElementById('final_span'));
				window.getSelection().addRange(range);
			}

		};

		recognition.onresult = function(event) {

			if (button_save == 1) {

				final_transcript = '';
				button_save = 0;
			}
			var interim_transcript = '';
			if (typeof(event.results) == 'undefined') {
				recognition.onend = null;
				recognition.stop();
				$(SpeechElement).html('<i class="fa fa-microphone" aria-hidden="true"></i>');
				$(SpeechElement).removeClass('btn-danger').addClass('btn-success');
				$('#interim_span').hide();
				return;
			}

			var Speechlength = 0;
			var finalspeech = 0;
			for (var i = event.resultIndex; i < event.results.length; ++i) {
				if (event.results[i].isFinal) {
					Speechlength = i;
					finalspeech = 1;
				} else {
					interim_transcript += event.results[i][0].transcript;
				}
			}

			interim_span.innerHTML = linebreak(interim_transcript);
			if (finalspeech == 1) {

				final_transcript = capitalize(event.results[Speechlength][0].transcript);
				insertAtCaret('final_span', linebreak(final_transcript));
			}

			finalspeech = 0;
		};
	}

	var two_line = /\n\n/g;
	var one_line = /\n/g;

	function linebreak(s) {
		return s.replace(two_line, '<p></p>').replace(one_line, '<br>');
	}

	var first_char = /\S/;

	function capitalize(s) {
		return s.replace(first_char, function(m) {
			return m.toUpperCase();
		});
	}

	function startButton(event) {
		if (!('webkitSpeechRecognition' in window)) {
			$.notify({icon:"icon-bell-check",message:"Speech To Text is Not supported"},{type:"danger",delay:800 });
			return;
		}
		if (recognizing) {
			recognition.stop();
			$(SpeechElement).html('<i class="fa fa-microphone" aria-hidden="true"></i>');
			$(SpeechElement).removeClass('btn-danger').addClass('btn-success');
			$('#interim_span').hide();
			return;
		}
		$('#interim_span').show();
		$(SpeechElement).removeClass('btn-success').addClass('btn-danger');

		$(SpeechElement).html('<i class="fa fa-microphone-slash" aria-hidden="true"></i>');

		final_transcript = '';
		recognition.start();
		ignore_onend = false;
		//info_allow');
		start_timestamp = event.timeStamp;
	}

} else if (style_theme == "2") {

	/*---------------------------------------------For Advanced------------------------------------------------------------*/

	localStorage.setItem("interfaceLang", "en");
	var interfaceLang = "en";
	var NEW_LINE = "\n";
	var END_OF_SENTENCE_SYMBOLS = [".", ":", "?", "!", ":-)", ":-(", NEW_LINE, "\n"];
	var STRINGS = new generateStrings(interfaceLang);
	var sessionsList;
	var shownTitle = "";
	var maxChars = 25;
	var RTL_languages = ["he", "iw", "ar"];
	var COMMANDS = new generateTranscriptionCommands("en-US");
	var listActionCommands = COMMANDS.listActionCommands;
	var listBreakCommands = COMMANDS.listBreakCommands;
	var listToReplaceCommands = COMMANDS.listToReplaceCommands;
	var listToReplaceMarks = COMMANDS.listToReplaceMarks;
	var currentSessionIndex;
	var variables = null;
	var isToRestartImmediately;
	var lastScroll = 0;
	var isResultInBuffer = false;
	var resultInBuffer = "";
	var symbolToAppend = "";
	var variables_recognizing = false;
	variables_intentionalPause = null;

	runOnload();

	function runOnload() {

		$("#results_box").keydown(function(b) {
			if (b.keyCode == 13) {
				b.preventDefault();
				if (variables_recognizing && isResultInBuffer) {
					startButton();
					isToRestartImmediately = true
				} else {
					insertText(NEW_LINE)
				}
			}
		});
		$("#results_box").keypress(function(b) {
			if ((b.which >= 33) && (b.which <= 63) && variables_recognizing && isResultInBuffer) {
				b.preventDefault();
				insertSymbol(String.fromCharCode(b.which))
			}
		});

		//var a = initializeLanguages();
		$("#select_language option[value='" + localStorage.selected_language + "']").attr("selected", true);
		//updateLanguage();
		$("#save_checkbox").prop("checked", (localStorage.autoSave === "true"));
		zoom(parseFloat(localStorage.zoom));
		localStorage.zoom = Math.sqrt(parseFloat(localStorage.zoom));
		if (localStorage.getItem("toggleHelpPane") === "hide") {
			$("#application_pane").css({
				right: "0"
			});
			$("#help_pane").css({
				display: "none"
			})
		}
	}

	function zoom(c) {
		localStorage.zoom = parseFloat(localStorage.zoom) * c;
		var b = parseFloat($("#results_box").css("font-size"));
		var a = parseFloat($("#results_box").css("line-height"));
		b = c * b + "px";
		a = c * a + "px";
		$("#results_box").css({
			"font-size": b
		});
		$("#results_box").css({
			"line-height": a
		})
	}

	function trimSpaces(a) {
		while (a[0] === " ") {
			a = a.slice(1)
		}
		while (a[a.length - 1] === " ") {
			a = a.slice(0, a.length - 1)
		}
		return a
	}
	var first_char = /\S/;

	function capitalize(a) {
		return a.replace(first_char, function(b) {
			return b.toUpperCase()
		})
	}

	function pauseRecognition() {
		if (variables_recognizing) {
			variables_intentionalPause = true;
			recognition.stop();
			$('#start_button').html('<i class="fa fa-microphone" aria-hidden="true"></i>');
			$('#start_button').removeClass('btn-danger');
			$('#start_button').addClass('btn-success');

		}
	}

	function upgrade() {}

	function startButton(b) {

		$("#results_box").focus();
		variables_intentionalPause = false;
		if (variables_recognizing) {
			pauseRecognition();
			return
		}
		$('#start_button').html('<i class="fa fa-microphone-slash" aria-hidden="true"></i>');
		$('#start_button').addClass('btn-danger');
		$('#start_button').removeClass('btn-success');
		recognition.lang = "en-US";
		//recognition.lang = select_language.value;
		try {
			recognition.start()
		} catch (a) {}
	}
	if (!("webkitSpeechRecognition" in window)) {
		upgrade()
	} else {
		//start_button.style.display = "inline-block";
		var recognition = new webkitSpeechRecognition();
		recognition.continuous = true;
		recognition.interimResults = true;
		recognition.maxAlternatives = 1;
		recognition.lang = "en-US";
		//recognition.lang = select_language.value;
		recognition.onstart = function() {
			isResultInBuffer = false;
			resultInBuffer = "";
			symbolToAppend = "";
			variables_recognizing = true;
			$('#start_button').html('<i class="fa fa-microphone-slash" aria-hidden="true"></i>');
			$('#start_button').addClass('btn-danger');
			$('#start_button').removeClass('btn-success');
			console.log("Fired onstart")
		};
		recognition.onend = function() {
			console.log("Fired onend");

			$('#start_button').html('<i class="fa fa-microphone" aria-hidden="true"></i>');
			$('#start_button').removeClass('btn-danger');
			$('#start_button').addClass('btn-success');
			variables_recognizing = false;
			if (isResultInBuffer) {
				for (var a = 0; a < listToReplaceCommands.length; a++) {
					resultInBuffer = resultInBuffer.replace(listToReplaceCommands[a], listToReplaceMarks[a])
				}
				insertText(resultInBuffer);
				resultInBuffer = "";
				isResultInBuffer = false
			}
			insertText(symbolToAppend);
			symbolToAppend = "";
			$("#mirror").fadeOut("slow");
			if (variables_intentionalPause === false || isToRestartImmediately) {
				console.log("Ended unintentionally");
				recognition.start();
				return false
			} else {
				$('#start_button').html('<i class="fa fa-microphone" aria-hidden="true"></i>');
				$('#start_button').removeClass('btn-danger');
				$('#start_button').addClass('btn-success');
				variables_intentionalPause = true;
				return true
			}
		};
		recognition.onspeechstart = function() {};
		recognition.onspeechend = function() {};
		recognition.onnomatch = function(a) {
			console.log("Fired onnomatch")

		};
		recognition.onerror = function(a) {
			$('#start_button').html('<i class="fa fa-microphone" aria-hidden="true"></i>');
			$('#start_button').removeClass('btn-danger');
			$('#start_button').addClass('btn-success');


			console.log("Fired onerror")
		};
		recognition.onresult = function(c) {
			console.log("Fired onresult");
			if (typeof(c.results) === "undefined") {
				recognition.onend = null;
				variables_intentionalPause = true;
				recognition.stop();
				$('#start_button').html('<i class="fa fa-microphone" aria-hidden="true"></i>');
				$('#start_button').removeClass('btn-danger');
				$('#start_button').addClass('btn-success');

				upgrade();
				return
			}
			$("#mirror").fadeIn("fast");
			mirror.innerHTML = "";
			str = "";
			for (var b = c.resultIndex; b < c.results.length; ++b) {
				str = trimSpaces(c.results[b][0].transcript);
				if (!(str.substring(0, 2) === "I " && select_language.value.split("-")[0] === "en")) {
					str = str[0].toLowerCase() + str.substring(1, str.length)
				}
				if (c.results[b].isFinal) {
					isResultInBuffer = false;
					resultInBuffer = "";
					console.log(" --- Final result= " + str);
					switch (listActionCommands.indexOf(str)) {
						case 0:
							return;
						default:
							break
					}
					for (var a = 0; a < listToReplaceCommands.length; a++) {
						str = str.replace(listToReplaceCommands[a], listToReplaceMarks[a])
					}
					$("#mirror").fadeOut("slow");
					insertText(str)
				} else {
					mirror.innerHTML += c.results[b][0].transcript;
					isResultInBuffer = true;
					resultInBuffer = mirror.innerText;
					console.log(" ----- Interim result= " + c.results[b][0].transcript);
					str = " " + str;
					for (var a = 0; a < listBreakCommands.length; a++) {
						if (str.indexOf(listBreakCommands[a]) !== (-1)) {
							startButton(c);
							isToRestartImmediately = true;
							return
						}
					}
				}
			}
		}
	}

	function updateLanguage() {
		localStorage.setItem("selected_language", select_language.value);
		COMMANDS = new generateTranscriptionCommands(select_language.value);
		listActionCommands = COMMANDS.listActionCommands;
		listBreakCommands = COMMANDS.listBreakCommands;
		listToReplaceCommands = COMMANDS.listToReplaceCommands;
		listToReplaceMarks = COMMANDS.listToReplaceMarks;
		cell_period.innerHTML = COMMANDS.period;
		cell_comma.innerHTML = COMMANDS.comma;
		cell_question.innerHTML = COMMANDS.question;
		cell_colon.innerHTML = COMMANDS.colon;
		cell_semi.innerHTML = COMMANDS.semi;
		cell_exclamation.innerHTML = COMMANDS.exclamation;
		cell_dash.innerHTML = COMMANDS.dash;
		cell_line.innerHTML = COMMANDS.line;
		cell_paragraph.innerHTML = COMMANDS.paragraph;
		cell_open.innerHTML = COMMANDS.open;
		cell_close.innerHTML = COMMANDS.close;
		cell_smiley.innerHTML = COMMANDS.smiley;
		cell_sad.innerHTML = COMMANDS.sad;
		if (variables !== null) {
			if (variables_recognizing) {
				isToRestartImmediately = false;
				startButton()
			}
		}
	}

	function isLastSymbolAnyOf(d, c) {
		var b = "";
		for (var a = 0; a < c.length; a++) {
			b = c[a];
			if (d.slice(d.length - b.length) === b) {
				return true
			}
		}
		return false
	}

	function insertSymbol(a) {
		if (variables_recognizing) {
			symbolToAppend = a;
			insertText(a)
			//startButton();
			//isToRestartImmediately = true
		} else {
			insertText(a)
		}
	}

	function insertText(e) {
		$("#results_box").focus();
		if (e.length > 0) {
			var d = document.getElementById("results_box").selectionStart;
			var b = $("#results_box").val();
			var f = Math.max(0, d - 5);
			var a = b.substring(f, d);
			var g = (a.length > 0 && a[a.length - 1] === " ");
			a = trimSpaces(a);
			if (isLastSymbolAnyOf(a, END_OF_SENTENCE_SYMBOLS) || a == "" || d < 2 || a === "null") {
				e = capitalize(e)
			}
			if (a.length > 0 && !g && [NEW_LINE, "("].indexOf(a[a.length - 1]) === (-1) && [".", ",", ";", ":", "?", "!", ")"].indexOf(e[0]) === (-1)) {
				e = " " + e
			}
			$("#results_box").val(b.substring(0, d) + e + b.substring(d));
			if ((b.length - (d + e.length)) < 300) {
				var c = document.getElementById("results_box");
				c.scrollTop = c.scrollHeight
			}
			d += e.length;
			setCaretToPos(document.getElementById("results_box"), d);
			if (localStorage.getItem("autoSave") === "true") {
				localSave()
			}
			return e.length
		} else {
			return 0
		}
	}

	function rememberScroll() {
		lastScroll = $("#results_box").scrollTop()
	}

	function scrollToMemory() {
		document.getElementById("results_box").scrollTop = lastScroll
	}

	function backspace(c) {
		var b = document.getElementById("results_box").selectionStart;
		var a = jQuery("#results_box").val();
		jQuery("#results_box").val(a.substring(0, b - c) + a.substring(b))
	}

	function backspaceLastWord() {
		var c = document.getElementById("results_box").selectionStart;
		var a = jQuery("#results_box").val();
		var b = a.substring(0, c - 1).lastIndexOf(" ");
		jQuery("#results_box").val(a.substring(0, b) + " " + a.substring(c))
	}

	function backToLastWord() {
		var c = document.getElementById("results_box").selectionStart;
		var a = jQuery("#results_box").val();
		var b = a.substring(0, c - 1).lastIndexOf(" ");
		setCaretToPos(document.getElementById("results_box"), b)
	}

	function goToEnd() {
		setCaretToPos(document.getElementById("results_box"), $("#results_box").val().length)
	}

	function setSelectionRange(b, c, d) {
		if (b.setSelectionRange) {
			b.focus();
			b.setSelectionRange(c, d)
		} else {
			if (b.createTextRange) {
				var a = b.createTextRange();
				a.collapse(true);
				a.moveEnd("character", d);
				a.moveStart("character", c);
				a.select()
			}
		}
	}

	function setCaretToPos(a, b) {
		setSelectionRange(a, b, b)
	}

	function updateInterfaceLang(a) {
		localStorage.setItem("interfaceLang", a);
		switch (localStorage.getItem("interfaceLang")) {
			case ("de"):
			case ("es"):
			case ("fr"):
			case ("ja"):
			case ("ru"):
			case ("it"):
			case ("zh"):
			case ("cn"):
			case ("ar"):
			case ("pt"):
			case ("yue"):
				window.location.replace(window.location.href + localStorage.getItem("interfaceLang") + "/");
				break;
			default:
				window.location.replace(window.location.href);
				break
		}
	}

	function initializeLanguages() {
		var b = [
			["en-US", "US English"],
			["id-ID", "Bahasa, Indonesia"],
			["ms-MY", "Bahasa, Melayu"],
			["bg-BG", "Bulgarian"],
			["cs-CZ", "Czech"],
			["da-DK", "Dansk (Danish)"],
			["de-DE", "Deutsch"],
			["nl-NL", "Dutch, Netherlands"],
			["en-AU", "English, Australia"],
			["en-CA", "English, Canada"],
			["en-IN", "English, India"],
			["en-NZ", "English, New Zealand"],
			["en-ZA", "English, S. Africa"],
			["en-GB", "English, UK"],
			["en-US", "English, US"],
			["es-AR", "español, Argentina"],
			["es-BO", "español, Bolivia"],
			["es-CL", "español, Chile"],
			["es-CO", "español, Colombia"],
			["es-CR", "español, Costa Rica"],
			["es-EC", "español, Ecuador"],
			["es-SV", "español, El Salvador"],
			["es-ES", "español, España"],
			["es-US", "español, Estados Unidos"],
			["es-GT", "español, Guatemala"],
			["es-HN", "español, Honduras"],
			["es-MX", "español, México"],
			["es-NI", "español, Nicaragua"],
			["es-PA", "español, Panamá"],
			["es-PY", "español, Paraguay"],
			["es-PE", "español, Perú"],
			["es-PR", "español, Puerto Rico"],
			["es-DO", "español, R. Dominicana"],
			["es-UY", "español, Uruguay"],
			["es-VE", "español, Venezuela"],
			["fr-FR", "français"],
			["is-IS", "Icelandic"],
			["zu-ZA", "IsiZulu"],
			["it-IT", "italiano"],
			["it-CH", "italiano, Svizzera"],
			["ko-KR", "Korean"],
			["hi-IN", "Hindi हिन्दी"],
			["hu-HU", "Magyar"],
			["nb-NO", "Norwegian"],
			["pl-PL", "Polski"],
			["pt-BR", "Português, Brasil"],
			["pt-PT", "Português, Portugal"],
			["ro-RO", "română"],
			["ru-RU", "России"],
			["sr-RS", "Serbian"],
			["sk-SK", "Slovak"],
			["fi-FI", "Suomi"],
			["sv-SE", "Svenska"],
			["tr-TR", "Turkish"],
			["el-GR", "Ελληνικά"],
			["cmn-Hans-CN", "普通话 (中国大陆)"],
			["cmn-Hans-HK", "普通话 (香港)"],
			["cmn-Hant-TW", "中文 (台灣)"],
			["yue-Hant-HK", "粵語 (香港)"],
			["ja-JP", "日本の"],
			["he-IL", "עברית"],
			["ar-DZ", "العربية, Algeria"],
			["ar-EG", "العربية, Egypt"],
			["ar-IQ", "العربية, Iraq"],
			["ar-JO", "العربية, Jordan"],
			["ar-KW", "العربية, Kuwait"],
			["ar-LB", "العربية, Lebanon"],
			["ar-MA", "العربية, Morocco"],
			["ar-QA", "العربية, Qatar"],
			["ar-SA", "العربية, Saudi Arabia"],
			["ar-AE", "العربية, UAE"]
		];
		for (var a = 0; a < b.length; a++) {
			select_language.options[a] = new Option(b[a][1], b[a][0])
		}
		return b
	}

	function generateStrings(a) {
		switch (a) {
			default: this.title = "Speechnotes - Professional Speech Recognitizing Text Editor Made for Everyone. Voice & Key-Typing.";this.monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];this.TEXTAREA_PLACE_HOLDER = "Click the microphone to start dictating. Click here to start typing.";
			break
		}
		this.listPunctuationMarks = [":-)", ":-)", ":-(", ".", ".", ",", ",", "?", "?", ":", ":", ";", ";", ";", ";", ";", "!", "!", "!", "-", "-", NEW_LINE, NEW_LINE + NEW_LINE, NEW_LINE, NEW_LINE, "(", "(", ")", ")"];
		return
	}

	function generateTranscriptionCommands(g) {
		g = g.split("-")[0];
		var b;
		var e;
		var d;
		var f;
		var h;
		var a = [":-)", ":-)", ":-(", ".", ",", "?", ":", ";", "!", "-", NEW_LINE, "(", ")"];
		switch (g) {
			case ("de"):
				b = ["not_implemented"];
				e = [" punkt", " komma", " fragezeichen", " doppelpunkt", " semikolon", " semikolon", " semikolon", " ausrufezeichen", " ausrufezeichen", " neue zeile", " neuer absatz", " klammer öffnen", " klammer schließen", " bindestrich"];
				d = ["smiley", "trauriges gesicht", "Bindestrich "];
				f = [".", ",", "?", ":", ";", ";", ";", "!", "!", NEW_LINE, NEW_LINE + NEW_LINE, "(", ")", "-"];
				h = [":-)", ":-(", "- "];
				this.period = "Punkt";
				this.comma = "Komma";
				this.question = "Fragezeichen";
				this.colon = "Doppelpunkt";
				this.semi = "Semikolon";
				this.exclamation = "Ausrufezeichen";
				this.dash = "Bindestrich";
				this.line = "Neue Zeile";
				this.paragraph = "Neuer Absatz";
				this.open = "Klammer öffnen";
				this.close = "Klammer schließen";
				this.smiley = "Smiley";
				this.sad = "Trauriges Gesicht";
				break;
			case ("es"):
				b = ["not_implemented"];
				e = [" coma", " signo de interrogación", " dos puntos", " 2 puntos", " punto y coma", " punto y,", " punto y ,", ". y coma", ". y,", ". y ,", " punto", " signo de exclamación", " exclamación", " nueva línea", " nuevo apartado", " abrir paréntesis", " cerrar paréntesis", " guión"];
				d = ["cara sonriente", "cara triste", "guión "];
				f = [",", "?", ":", ":", ";", ";", ";", ";", ";", ";", ".", "!", "!", NEW_LINE, NEW_LINE + NEW_LINE, "(", ")", "-"];
				h = [":-)", ":-(", "- "];
				this.period = "Punto";
				this.comma = "Coma";
				this.question = "Signo de interrogación";
				this.colon = "Dos puntos";
				this.semi = "Punto y coma";
				this.exclamation = "Signo de exclamación, Exclamación";
				this.dash = "Guión";
				this.line = "Nueva línea";
				this.paragraph = "Nuevo apartado";
				this.open = "Abrir paréntesis";
				this.close = "Cerrar paréntesis";
				this.smiley = "Cara sonriente";
				this.sad = "Cara triste";
				break;
			case ("fr"):
				b = ["not_implemented"];
				e = [" virgule", " point d'interrogation", " deux-points", " deux points", " 2 points", " point-virgule", " point virgule", " point ,", " point,", " point d'exclamation", " point", " nouvelle ligne", " nouveau paragraphe", " ouvrir la parenthèse", " fermer la parenthèse", " tiret"];
				d = ["smiley", "visage triste", "tiret "];
				f = [",", "?", ":", ":", ":", ";", ";", ";", ";", "!", ".", NEW_LINE, NEW_LINE + NEW_LINE, "(", ")", "-"];
				h = [":-)", ":-(", "- "];
				this.period = "Point";
				this.comma = "Virgule";
				this.question = "Point d'interrogation";
				this.colon = "Deux-points";
				this.semi = "Point-virgule";
				this.exclamation = "Point d'exclamation";
				this.dash = "Tiret";
				this.line = "Nouvelle ligne";
				this.paragraph = "Nouveau paragraphe";
				this.open = "Ouvrir la parenthèse";
				this.close = "Fermer la parenthèse";
				this.smiley = "Smiley";
				this.sad = "Visage triste";
				break;
			case ("it"):
				b = ["not_implemented"];
				e = [" virgula", " punto interrogativo", " due punti", " 2 punti", " punto e virgola", " punto e,", " punto e ,", " esclamativo", " punto esclamativo", " punto", " nuova riga", " nuovo paragrafo", " apri parentesi", " chiudi parentesi", " trattino"];
				d = ["smiley", "faccina sorridente", "faccina triste", "trattino "];
				f = [",", "?", ":", ":", ";", ";", ";", "!", "!", ".", NEW_LINE, NEW_LINE + NEW_LINE, "(", ")", "-"];
				h = [":-)", ":-)", ":-(", "- "];
				this.period = "Punto";
				this.comma = "Virgola";
				this.question = "Punto interrogativo";
				this.colon = "Due punti";
				this.semi = "Punto e virgula";
				this.exclamation = "Esclamativo, Punto esclamativo";
				this.dash = "Trattino";
				this.line = "Nuova riga";
				this.paragraph = "Nuovo paragrafo";
				this.open = "Apri parentesi";
				this.close = "Chiudi parentesi";
				this.smiley = "Smiley, Faccina sorridente";
				this.sad = "Faccina triste";
				break;
			case ("ru"):
				b = ["not_implemented"];
				e = [" запятая", " вопросительный знак", " двоеточие", " точка с запятой", " точка с,", " точка с ,", " точка", " восклицательный символ", " восклицательный знак", " новая строка", " новый параграф", " открывающаяся скобка", " закрывающаяся скобка", " тире"];
				d = ["смайлик", "улыбочка", "грустное лицо", "тире "];
				f = [",", "?", ":", ";", ";", ";", ".", "!", "!", NEW_LINE, NEW_LINE + NEW_LINE, "(", ")", "-"];
				h = [":-)", ":-)", ":-(", "- "];
				this.period = "Точка";
				this.comma = "Запятая";
				this.question = "Вопросительный знак";
				this.colon = "Двоеточие";
				this.semi = "Точка с запятой";
				this.exclamation = "Восклицательный символ, Восклицательный знак";
				this.dash = "Тире";
				this.line = "Новая строка";
				this.paragraph = "Новый параграф";
				this.open = "Открывающаяся скобка";
				this.close = "Закрывающаяся скобка";
				this.smiley = "Смайлик, Улыбочка";
				this.sad = "Грустное лицо";
				break;
			case ("ja"):
				b = ["not_implemented"];
				e = [" ピリオド ", " コンマ ", " 疑問符 ", " コロン ", " セミコロン ", " 感嘆符 ", "感嘆符記号", " 改行 ", " 新しい段落 ", " 括弧開き ", " 括弧閉じ", " ダッシュ "];
				d = [" スマイリー ", " 悲しい顔 ", " ダッシュ "];
				f = [". ", ", ", "? ", ": ", "; ", "! ", "! ", NEW_LINE, NEW_LINE + NEW_LINE, " (", ") ", "-"];
				h = [" :-) ", " :-( ", "-"];
				this.period = "ピリオド";
				this.comma = "コンマ";
				this.question = "疑問符";
				this.colon = "コロン";
				this.semi = "セミコロン";
				this.exclamation = "感嘆符, 感嘆符記号";
				this.dash = "ダッシュ";
				this.line = "改行";
				this.paragraph = "新しい段落";
				this.open = "括弧開き";
				this.close = "括弧閉じ";
				this.smiley = "スマイリー";
				this.sad = "悲しい顔";
				break;
			case ("cmn"):
				b = ["not_implemented"];
				e = [" 句号 ", " 逗号 ", " 问号 ", " 冒号 ", " 分号 ", " 感叹号 ", " 换行 ", " 新段落 ", " 左圆括号 ", " 右圆括号", " 破折号 "];
				d = [" 笑脸 ", " 悲伤的脸 ", " 破折号 "];
				f = [". ", ", ", "? ", ": ", "; ", "! ", NEW_LINE, NEW_LINE + NEW_LINE, " (", ") ", "——"];
				h = [" :-) ", " :-( ", "——"];
				this.period = "句号";
				this.comma = "逗号";
				this.question = "问号";
				this.colon = "冒号";
				this.semi = "分号";
				this.exclamation = "感叹号";
				this.dash = "破折号";
				this.line = "换行";
				this.paragraph = "新段落";
				this.open = "左圆括号";
				this.close = "右圆括号";
				this.smiley = "笑脸";
				this.sad = "悲伤的脸";
				break;
			case ("ar"):
				b = ["not_implemented"];
				e = [" فترة ", " فاصلة مفاصلة", " علامة استفهام", " نقطتان", " نقوطة", " طة التعجب", " علامة تعجب، ", " خط جديد", " فقرة جديدة", " افتح القوسان", " أغلق القوسان", " الشرطة"];
				d = ["مبتسم", "وجه حزين", "الشرطة "];
				f = [".", ";", "?", ":", ",", "!", "!", NEW_LINE, NEW_LINE + NEW_LINE, "(", ")", "-"];
				h = [":-)", ":-(", "- "];
				this.period = "فترة";
				this.comma = "فاصلة";
				this.question = "علامة استفهام";
				this.colon = "نقطتان";
				this.semi = "فاصلة مفاصلة";
				this.exclamation = "علامة تعجب، نقطة التعجب";
				this.dash = "الشرطة";
				this.line = "خط جديد";
				this.paragraph = "فقرة جديدة";
				this.open = "افتح القوسان";
				this.close = "أغلق القوسان";
				this.smiley = "مبتسم، وجه مبتسم";
				this.sad = "وجه حزين";
				break;
			case ("pt"):
				b = ["not_implemented"];
				e = [" interrogação", " dois pontos", " 2 pontos", " ponto e vírgula", " ponto e,", " ponto e ,", " ponto", " vírgula", " exclamação", " nova linha", " parágrafo", " abre parêntese", " fecha parêntese", " hífen"];
				f = ["?", ":", ":", ";", ";", ";", ".", ",", "!", NEW_LINE, NEW_LINE + NEW_LINE, "(", ")", "-"];
				d = ["smiley", "rosto triste", "hífen "];
				h = [":-)", ":-(", "- "];
				this.period = "Ponto";
				this.comma = "Vírgula";
				this.question = "Interrogação";
				this.colon = "Dois pontos";
				this.semi = "Ponto e vírgula";
				this.exclamation = "Exclamação";
				this.dash = "Hífen";
				this.line = "Nova linha";
				this.paragraph = "Parágrafo";
				this.open = "Abre parêntese";
				this.close = "Fecha parêntese";
				this.smiley = "Smiley";
				this.sad = "Rosto triste";
				break;
			default:
				b = ["not_implemented"];
				e = [" period", " comma", " question mark", " colon", " semicolon", " semi colon", " semi:", " semi :", " exclamation mark", " exclamation point", " new line", " new paragraph", " open parentheses", " close parentheses", " hyphen"];
				d = ["smiley", "smiley face", "sad face", "dash "];
				f = [".", ",", "?", ":", ";", ";", ";", ";", "!", "!", NEW_LINE, NEW_LINE + NEW_LINE, "(", ")", "-"];
				h = [":-)", ":-)", ":-(", "- "];
				this.period = "Period";
				this.comma = "Comma";
				this.question = "Question mark";
				this.colon = "Colon";
				this.semi = "Semi Colon";
				this.exclamation = "Exclamation mark, Exclamation point";
				this.dash = "Dash, Hyphen";
				this.line = "New line";
				this.paragraph = "New paragraph";
				this.open = "Open parentheses";
				this.close = "Close parentheses";
				this.smiley = "Smiley, Smiley face";
				this.sad = "Sad face";
				break
		}
		this.listActionCommands = b;
		this.listBreakCommands = d.concat(e, a, b);
		this.listToReplaceCommands = e.concat(d);
		for (var c = 0; c < e.length; c++) {
			e[c] = trimSpaces(e[c])
		}
		this.listToReplaceCommands = this.listToReplaceCommands.concat(e);
		for (var c = 0; c < e.length; c++) {
			e[c] = capitalize(e[c])
		}
		for (var c = 0; c < d.length; c++) {
			d[c] = capitalize(d[c])
		}
		this.listToReplaceCommands = this.listToReplaceCommands.concat(e, d);
		this.listToReplaceMarks = f.concat(h, f)
	}

} else if (style_theme == "3") {

	var h = window.innerHeight - 200;
	tinymce.init({
		selector: '#editor',
		//content_style: ".mce-content-body {font-size: 12pt; font-family: Times New Roman,serif;}",
		height: 144,
		plugins: "print autosave wordcount table emoticons lists advlist insertdatetime paste textcolor charmap directionality",
		branding: false,
		autosave_ask_before_unload: false,
		table_default_styles: {
			width: '50%'
		},
		menubar: false,
		toolbar: ["fontselect | fontsizeselect | forecolor | bold italic underline | bullist numlist  |  ltr rtl | removeformat | restoredraft"],
	});

	var listening, speechrestart, speech, commands, commandslist = "";
	var comrepOn = true;

	function initialize() {

		speech = new webkitSpeechRecognition();
		speech.continuous = true;
		speech.maxAlternatives = 5;
		speech.interimResults = true;
		speech.lang = 'en-us';
		speech.onend = reset;
	}

	if (typeof(webkitSpeechRecognition) !== 'function') {
		//
	} else {

		if (typeof(localStorage["language"]) == 'undefined') {
			localStorage["language"] = "en-us";
			document.getElementById("lang").value = "en-us";
			localStorage.dirLR = "t"; /* true */
			commands = ["period", "comma", "exclamation mark", "question mark", "colon", "semicolon", "hyphen", "new line"];
		} else {
			//document.getElementById("lang").value = localStorage.language;
		}
		if (typeof(sessionStorage["transcript"]) == 'undefined') {
			sessionStorage["transcript"] = "";
		}

		function reset() {
			if (speechrestart) {
				speech.start();
			} else {
				listening = false;

				$('.editorspeech').html('<i class="fa fa-microphone" aria-hidden="true"></i>');
				$('.editorspeech').removeClass('btn-danger').addClass('btn-success');
				//document.getElementsByClassName("state")[0].style.display = "none";
				document.getElementById("input").style.display = "none";
				//document.getElementById("listen").innerHTML = "► Start";
			}
		}

		function startButton() {
			if (listening) {
				speech.stop();
				speechrestart = false;
				reset();
			} else {
				speech.start();
				listening = true;
				$('.editorspeech').html('<i class="fa fa-microphone-slash" aria-hidden="true"></i>');
				$('.editorspeech').addClass('btn-danger').removeClass('btn-success');

				//document.getElementsByClassName("state")[0].style.display = "inline-block";
				document.getElementById("input").style.display = "inline-block";
				// document.getElementById("listen").innerHTML = "■ Stop ";
				speechrestart = true;
			}
		}

		function comrep(z) {
			if (comrepOn === true) {
				for (var n = 0; n < commands.length; n++) {
					var re = new RegExp(commands[n], "ig");
					z = z.replace(re, replacement[n]).replace("\n\n", "<p>");
				}
			}
			return z;
		}

		var replacement = [".", ",", ":", ";", "!", "?", "<p>"];

		function command() {

			switch (localStorage["language"]) {
				case "af-za":
					commands = ["dubblepunt", "kommapunt", "punt", "komma", "uitroepteken", "vraagteken", "nuwe paragraaf"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "am-ET":
					commands = ["አራት ነጥብ", "ነጠላ ሰረዝ", "ነጠላ ሰረዝ", "ድርብ ሰረዝ", "አስረጂ ሰረዝ", "ሦስት ነጥብ", "አዲስ አንቀጽ"];
					replacement = ["።", "፥", "፣", "፤", "፦", "፧", "<p>"];
					break;
				case "ar-DZ":
				case "ar-BH":
				case "ar-EG":
				case "ar-IQ":
				case "ar-IL":
				case "ar-JO":
				case "ar-KW":
				case "ar-LB":
				case "ar-LY":
				case "ar-MA":
				case "ar-OM":
				case "ar-PS":
				case "ar-QA":
				case "ar-SA":
				case "ar-TN":
				case "ar-AE":
				case "ar-YE":
					commands = ["نقطتان",
						"فاصلة منقوطة",
						"نقطة",
						"فاصلة",
						"علامة التعجب",
						"علامة استفهام",
						"الفقرة الجديدة"
					];
					replacement = [":",
						"؛", /* Arabic semicolon */
						".",
						"،", /* Arabic comma */
						"!",
						"؟", /* Arabic question mark */
						"<p>"
					];
					break;
				case "hy-AM":
					commands = ["վերջակետ", "ստորակետ", "միջակետ", "բութ", "նոր կետը"];
					replacement = ["։", ",", "․", "՝", "<p>"];
					break;
				case "az-AZ":
					commands = ["iki nöqtə", "nöqtə", "nöqtəli vergül", "vergül", "nida işarəsi", "sual işarəsi", "yeni paraqraf"];
					replacement = [":", ".", ";", ",", "!", "?", "<p>"];
					break;
				case "bn-BD":
				case "bn-IN":
					commands = [];
					replacement = [];
					break;
				case "id-id":
				case "ms-my":
					commands = ["titik dua", "titik koma", "titik", "koma", "tanda seru", "tanda tanya", "paragraf baru"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "bg-bg":
					commands = ["двоеточие", "точка и запетая", "точка", "запетая", "удивителен знак", "въпросителен знак", "нов параграф"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "ca-es":
					commands = ["dos punts", "punt i coma", "punt", "coma", "signe d'exclamació", "signe d'interrogació", "nou paràgraf"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "cs-cz":
					commands = ["dvojtečka", "středník", "tečka", "čárka", "vykřičník", "otazník", "nový odstavec"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "da-DK":
					commands = ["semikolon", "kolon", "punktum", "komma", "udråbstegn", "spørgsmålstegn", "nyt afsnit"];
					replacement = [";", ":", ".", ",", "!", "?", "<p>"];
					break;
				case "de-de":
					commands = ["Doppelpunkt", "Strichpunkt", "Punkt", "Komma", "Ausrufezeichen", "Fragezeichen", "Klammer öffnen", "Klammer schließen", "neuer Absatz"];
					replacement = [":", ";", ".", ",", "!", "?", "(", ")", "<p>"];
					break;
				case "en-au":
				case "en-ca":
				case "en-GH":
				case "en-in":
				case "en-IE":
				case "en-KE":
				case "en-NG":
				case "en-nz":
				case "en-PH":
				case "en-za":
				case "en-TZ":
				case "en-gb":
				case "en-us":
					commands = ["semicolon", "colon", "period", "full stop", "comma", "exclamation point", "question mark", "open parentheses", "close parentheses", "open quote", "close quote", "hyphen", "slash", "new paragraph"];
					replacement = [";", ":", ".", ".", ",", "!", "?", "(", ")", "''", "' '", "-", "/", "<p>"];
					break;
				case "es-ar":
				case "es-bo":
				case "es-cl":
				case "es-co":
				case "es-cr":
				case "es-ec":
				case "es-sv":
				case "es-es":
				case "es-us":
				case "es-gt":
				case "es-hn":
				case "es-mx":
				case "es-ni":
				case "es-pa":
				case "es-py":
				case "es-pe":
				case "es-pr":
				case "es-do":
				case "es-uy":
				case "es-ve":
					commands = ["dos puntos", "punto y coma", "punto", "coma", "signo de exclamación", "signo de interrogación", "abrir paréntesis", "cerrar paréntesis", "abrir comillas", "cerrar comillas", "nuevo párrafo"];
					replacement = [":", ";", ".", ",", "!", "?", "(", ")", "''", "' '", "<p>"];
					break;
				case "el-gr":
					commands = ["Άνω τελεία", "ερωτηματικό", "τελεία", "κόμμα", "θαυμαστικό", "Νέα παράγραφος"];
					replacement = ["·", ";", ".", ",", "!", "<p>"]; /* "·" - greek semicolon";" - greek Question Mark */
					break;
				case "eu-es":
					commands = ["bi puntu", "puntu eta koma", "puntua", "koma", "harridura-marka", "galdera-marka", "paragrafo berria"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "fa-ir":
					commands = ["دونقطه",
						"نقطه‌ ویرگول",
						"نقطه",
						"ويرگول",
						"علامت تعجيب",
						"علامت سوال",
						"پاراگراف جدید"
					];
					replacement = [":",
						"؛",
						".",
						"،",
						"!",
						"؟",
						"<p>"
					];
					break;
				case "fil-PH":
					commands = ["tutuldok", "tuldok-kuwit", "tuldok", "kuwit", "tandang padamdam", "tandang pananong", "bagong talata"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "fr-CA":
				case "fr-fr":
					commands = ["deux-points", "point-virgule", "point d'exclamation", "point d'interrogation", "point", "virgule", "nouveau paragraphe"];
					replacement = [":", ";", "!", "?", ".", ",", "<p>"];
					break;
				case "gl-es":
					commands = ["dous puntos", "punto e coma", "punto", "coma", "signo de exclamación", "signo de interrogación", "novo parágrafo"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "ka-GE":
					commands = [];
					replacement = [];
					break;
				case "gu-IN":
					commands = [];
					replacement = [];
					break;
				case "hi-in":
					commands = ["विसर्ग", "अर्ध विराम", "पूर्ण विराम", "अल्प विराम", "विस्मयादिवाचक चिन्ह", "प्रशनवाचक चिन्ह", "नया अनुच्छेद"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "hr_HR":
					commands = ["dvotočje", "točka sa zarezom", "točka", "zarez", "uskličnik", "upitnik", "novi odlomak"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "he-il":
					commands = ["נקודתיים",
						"נקודה ופסיק",
						"נקודה",
						"פסיק",
						"סימן קריאה",
						"סימן שאלה",
						"סעיף חדש"
					];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "zu-za":
					commands = [];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "is-is":
					commands = ["samasemmerki", "semikomma", "punktur", "komma", "upphrópunarmerki", "spurningarmerki", "ný málsgrein"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "it-it":
				case "it-ch":
					commands = ["due punti", "punto e virgola", "punto esclamativo", "punto interrogativo", "punto", "virgola", "nuovo paragrafo"];
					replacement = [":", ";", "!", "?", ".", ",", "<p>"];
					break;
				case "jv-ID":
					commands = [];
					replacement = [];
					break;
				case "kn-IN":
					commands = [];
					replacement = [];
					break;
				case "km-KH":
					commands = [];
					replacement = [];
					break;
				case "lo-LA":
					commands = [];
					replacement = [];
					break;
				case "lv-LV":
					commands = [];
					replacement = [];
					break;
				case "lt-LT":
					commands = ["dvitaškis", "kabliataškis", "taškas", "kablelis", "šauktukas", "klaustukas", "nauja pastraipa"];
					replacement = [":", ";", ".", ",", "!", "?"];
					break;
				case "la":
					commands = ["duo puncta", "punctum et virgula", "punctum", "virgula", "signum exclamationis", "signum interrogationis", "novum caput"];
					replacement = [":", ";", ".", ",", "!", "?"];
					break;
				case "hu-hu":
					commands = ["kettőspont", "pontosvessző", "pont", "vessző", "felkiáltójel", "kérdőjel", "új bekezdés"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "ml-IN":
					commands = [];
					replacement = [];
					break;
				case "mr-IN":
					commands = [];
					replacement = [];
					break;
				case "nl-nl":
					commands = ["dubbelepunt", "puntkomma", "punt", "komma", "uitroepteken", "vraagteken", "nieuwe paragraaf"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "ne-NP":
					commands = [];
					replacement = [];
					break;
				case "nb-NO":
					commands = ["semikolon", "kolon", "punktum", "komma", "utropstegn", "spørsmålstegn", "nytt avsnitt"];
					replacement = [";", ":", ".", ",", "!", "?", "<p>"];
					break;
				case "pl-pl":
					commands = ["dwukropek", "średnik", "kropka", "przecinek", "znak wykrzyknienia", "znak zapytania", "nowy ustęp"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "pt-br":
				case "pt-pt":
					commands = ["dois pontos", "ponto e vírgula", "ponto de exclamação", "ponto de interrogação", "ponto", "vírgula", "novo paragrafo"];
					replacement = [":", ";", "!", "?", ".", ",", "<p>"];
					break;
				case "ro-ro":
					commands = ["două puncte", "punct și virgulă", "punct", "virgulă", "semnul exclamării", "semnul întrebării", "nou alineat"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "si-LK":
					commands = [];
					replacement = [];
					break;
				case "ru-ru":
					commands = ["двоеточие", "точка с запятой", "точка", "запятая", "восклицательный знак", "вопросительный знак", "новый абзац"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "sr-rs":
					commands = ["двотачка", "тачка-зарез", "тачка", "зарез", "узвичник", "упитник", "нови став"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "sk-sk":
					commands = ["dvojbodka", "bodkočiarka", "bodka", "čiarka", "výkričník", "otáznik", "nový odsek"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "sl-SI":
					commands = [];
					replacement = [];
					break;
				case "su-ID":
					commands = [];
					replacement = [];
					break;
				case "sw-TZ":
				case "sw-KE":
					commands = [];
					replacement = [];
					break;
				case "fi-fi":
					commands = ["kaksoispiste", "puolipiste", "piste", "pilkku", "huutomerkki", "kysymysmerkki", "uusi kappale"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "sv-se":
					commands = ["semikolon", "kolon", "ny punkt", "punkt", "kommatecken", "utropstecken", "frågetecken"];
					replacement = [";", ":", "<p>", ".", ",", "!", "?"];
					break;
				case "ta-IN":
				case "ta-SG":
				case "ta-LK":
				case "ta-MY":
					commands = [];
					replacement = [];
					break;
				case "te-IN":
					commands = [];
					replacement = [];
					break;
				case "vi-VN":
					commands = ["dấu hai chấm", "dấu chấm phẩy", "dấu chấm than", "dấu chấm hỏi", "dấu chấm", "dấu phẩy", "đoạn văn mới"];
					replacement = [":", ";", "!", "?", ".", ",", "<p>"];
					break;
				case "tr-tr":
					commands = ["iki nokta", "noktalı virgül", "nokta", "virgül", "ünlem işareti", "soru işareti", "yeni paragraf"];
					replacement = [":", ";", ".", ",", "!", "?", "<p>"];
					break;
				case "uk":
					commands = ["двокрапка", "крапка з комою", "крапка", "кома", "знак оклику", "знак питання", "відкрити дужки", "закрити дужки", "відкрити лапки", "закрити лапки", "новий параграф"];
					replacement = [":", ";", ".", ",", "!", "?", "(", ")", "''", "' '", "<p>"];
					break;
				case "ur-PK":
				case "ur-IN":
					commands = [];
					replacement = [];
					break;
				case "th-th":
					commands = [];
					replacement = [];
					break;
				case "ko-kr":
					commands = [];
					replacement = [];
					break;
				case "ja-jp":
					commands = ["中黒", "句点", "読点", "感嘆符"];
					replacement = ["・", "。", "、", "!"];
					break;
				case "cmn-hans-cn":
				case "cmn-hans-hk":
				case "cmn-hant-tw":
				case "yue-hant-hk":
					commands = ["句號", "頓號"];
					replacement = [" 。", "、"];
					break;
			}
			var commandslist = "";
			for (var n = 0; n < commands.length; n++) {
				commandslist += '<div class="clearrow"><div class="cell1">' + commands[n] + '</div><div class="cell2"><span>' + comrep(commands[n]) + '</span></div></div>';

			}
			document.getElementById('commands').innerHTML = commandslist;
		}

		function toggleComRep() {

			if (comrepOn === true) {
				comrepOn = false;
				document.getElementById("punctuation-button").innerHTML = 'Voice punctuation is OFF <i class="fa fa-commenting-o" aria-hidden="true"></i>';
			} else {
				comrepOn = true;
				command();
				document.getElementById("punctuation-button").innerHTML = 'Voice punctuation is ON <i class="fa fa-commenting-o" aria-hidden="true"></i>';
			}
		}

		function updateLang(sel) {
			speech.stop();
			var l = sel.options[sel.selectedIndex].value;
			speech.lang = l;
			localStorage.language = l;
			command();

			var drtl = ["ar-DZ", "ar-BH", "ar-EG", "ar-IQ", "ar-IL", "ar-JO", "ar-KW", "ar-LB", "ar-LY", "ar-MA", "ar-OM", "ar-PS", "ar-QA", "ar-SA", "ar-TN", "ar-AE", "ar-YE", , "fa-ir", , "he-il", "ur-PK", "ur-IN"]; /*   RTL scripts (Hebrew, Farsi and Arabic)*/

			if (drtl.indexOf(l) > -1) {
				if (localStorage.dirLR == 't') {
					tinyMCE.activeEditor.execCommand('mceDirectionRTL');
					localStorage.dirLR = 'f';
				}
			} else {
				if (localStorage.dirLR == 'f') {
					tinyMCE.activeEditor.execCommand('mceDirectionLTR');
					localStorage.dirLR = 't';
				}
			}
			if (listening) {
				setTimeout(function() {
					startlisten();
				}, 500);
			}
		}

		initialize();
		reset();
		command();

		speech.onerror = function(e) {
			var msg = e.error + " error";
			if (e.error === 'no-speech') {
				msg = "No speech was detected. Please try again.";
			} else if (e.error === 'audio-capture') {
				msg = "Please ensure that a microphone is connected to your computer.";
			} else if (e.error === 'not-allowed') {
				msg = "The app cannot access your microphone. Please go to 'Chrome settings' and allow microphone access for this website.";
			}
			document.getElementById("input").innerHTML = "<p style='font-size:14px; color:orange'>" + msg + "</p>";

		};

		function capitalize(s) {

			return s.replace(/( | |&NBSP;)/g, '').replace(/( )(\s)*/gi, ' ').replace(/\s\./g, '.').replace(/\s\,/g, ',').replace(/\s\?/g, '?').replace(/\s\!/g, '!').replace(/\s\;/g, ';').replace(/\s\:/g, ':').replace(/\( /g, '(').replace(/\s\)/g, ')').replace(/'' /g, '“').replace(/ ' '/g, '”').replace(/ - | -|- /g, '-').replace(/ \/ | \/|\/ /g, '/').replace(/(P>|p>|">|(\.|\?|\!))(\s| |&NBSP;)*([A-я])/g, function(txt) {
				return txt.toUpperCase();
			});
		} /* 1,2) removing spaces 3) finding .?!, html tags 4) capitalizing first chracter of new sentence*/

		speech.onresult = function(e) {
			var interim_transcript = '';
			if (typeof(e.results) == 'undefined') {
				reset();
				return;
			}
			for (var i = e.resultIndex; i < e.results.length; ++i) {
				var val = e.results[i][0].transcript;

				if (e.results[i].isFinal) {
					tinyMCE.activeEditor.insertContent(comrep(val));
					var bookmark = tinyMCE.activeEditor.selection.getBookmark(2, true);
					var ttt = capitalize(tinyMCE.activeEditor.getContent());
					tinyMCE.activeEditor.setContent(ttt);
					tinyMCE.activeEditor.selection.moveToBookmark(bookmark);

				} else {
					interim_transcript += " " + comrep(val);

					var scrolll = $('#input');
					var height = scrolll[0].scrollHeight;
					scrolll.scrollTop(height);
				}
			}
			document.getElementById("input").innerHTML = interim_transcript;
		};

	}

}

$("#punctuation-button").click(function() {
	$("#commands").slideToggle("slow", function() {});
});

