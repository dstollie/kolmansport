function objRef(id) {return document.getElementById(id)}
function styleRef(id) {return this.objRef(id).style}

var helptekst=new Array(10) 
helptekst[0]=''
helptekst[1]='<h2>Gebruikersnaam</h2>Vul hier de gebruikersnaam van uw account bij Oypo in.'
helptekst[2]='<h2>Hoogte</h2>In het veld \'hoogte\' bepaalt u de hoogte van het venster op uw webpagina waarin de OYPO-module wordt geladen. U kunt zowel relatieve als absolute hoogtes (in pixels) gebruiken.<br><br><b>Voorbeelden relatieve hoogtes</b><br><i>100%<br>80%<br>60%</i><br><br><b>Voorbeelden absolute hoogtes</b><br><i>800<br>750<br>600</i>'
helptekst[3]='<h2>ID van de fotomap</h2>In dit veld bepaalt u welke fotomap initieel in het venster op uw webpagina wordt weergegeven. Dit kan de hoofdmap van uw OYPO-account zijn of een willekeurige andere fotomap.<br>Het ID van de fotomap (mapid) vindt u in het tabblad \'basis\' van de betreffende fotomap op de pagina \'<a href="http://www.oypo.nl/content.asp?path=eanbaokb&f=2" target="_blank">beheer fotomappen</a>\' in uw account.<br><br><b>Voorbeelden van fotomap-ID\'s</b><br>0497CB4924EF23D4<br>8AF78E2CDE3F67D0'
helptekst[4]='<h2>Style sheet</h2>Met behulp van een eigen stylesheet kunt u de standaard kleurinstellingen geheel naar eigen wens aanpassen. Vul in dit veld de url van de locatie van het stylesheet-bestand op uw eigen webserver in.<br><br><b>Voorbeelden van url\'s</b><br>http://www.uwsite.nl/oypo.css<br>http://www.domeinnaam.nl/oypoblauw.css<br><br>Een voorbeeld van een stylesheet-bestand kunt u <a href="http://www.oypo.nl/pixxer/api/css/1207_default_compleet.css" target="_blank">hier downloaden</a>'
helptekst[5]='<h2>Mappennavigatie</h2>Met deze optie schakelt u de navigatie tussen fotomappen aan of uit. Als u de mappennavigatie uitschakelt kunnen bezoekers niet tussen de verschillende fotomappen navigeren. Het uitschakelen van de mappennavigatie is met name handig als u zelf een alternatieve navigatie voor fotomappen maakt.'
helptekst[6]=''
helptekst[7]='<h2>Fotomail</h2>Met behulp van Fotomail kunnen bezoekers van uw website bekenden informeren over foto\'s in de fotomap. Deze optie kunt u aan- en uitschakelen.'
helptekst[8]='<h2>White label verzending</h2>Als u uw fotowinkel in uw website integreert kan Oypo nabestellingen afhandelen onder uw (bedrijfs)naam en logo. Mailcorrespondientie, pakbonnen, afzender-etiketten en het statusscherm worden op maat gemaakt.<br>Lees hier meer over <a href="http://www.oypo.nl/content.asp?path=p3pl44xa" target="_blank">white label verzendingen</a>.<br><br><b>Let op:</b> Zet deze alleen optie op \'ja\' indien Oypo heeft bevestigd dat de white label instellingen gereed zijn.'
helptekst[9]='<h2>ID van het white label</h2>Vul in dit veld de naam van de white label-instelling in. Deze naam ontvangt u van Oypo zodra de persoonlijke instellingen gereed zijn.<br>In de meeste gevallen is deze naam gelijk aan uw gebruikersnaam bij Oypo.'
helptekst[10]='<h2>Layout</h2>U kunt kiezen uit de volgende navigatiemogelijheden:<br /><br /><b>Mappen-overzicht</b><br />De bezoekers van uw website starten met het overzicht van de laatst toegevoegde fotomappen en een kalender met de datums waarop foto\'s zijn gemaakt.<br /><br /><b>Specifieke fotomap</b><br />De bezoekers van uw website navigeren door middel van de mappennavigatie door de boomstructuur van uw fotomappen.<br /><br /><b>Inlogkaartjes</b><br />Bezoekers kunnen niet navigeren tussen fotomappen, maar gebruiken de code en het wachtwoord zoals op de inlogkaartjes staan. Deze optie wordt met name voor schoolfotografie gebruikt.<br><!--Lees meer over het <a href="#" title="Schoolfotografie - inlogkaartjes">gebruik van inlogkaartjes</a>-->'
helptekst[11]='<h2>Kleurenschema</h2>Kies voor het standaard kleurenschema van Oypo (met witte achtergrond), of kies uw eigen kleuren bij \'Zelf aanpassen\'. Als u volledige vrijheid wilt hebben, dan kunt u ook uw eigen stylesheet gebruiken door de url in te vullen van de locatie waar uw eigen stylesheet-bestand staat.'
helptekst[12]='<h2>Transparante achtergrond</h2>Indien aangevinkt wordt de achtergrond van de implementatie transparant. Hierdoor zal de de achtergrond van uw eigen website zichtbaar zijn.'
helptekst[13]='<h2>Wachtwoord</h2>Vul hier het wachtwoord van uw account bij Oypo in. Met het wachtwoord kunnen de fotomappen die u heeft bij Oypo, opgehaald worden.'
//$.ajaxSetup ({ cache: false });     // Disable caching of AJAX responses

var thishelp=0;

function showHelp(button, nr) {
	
	objRef('helptxt').innerHTML=helptekst[nr]
	//styleRef('code').display='none'
	if (styleRef('help').display=='block' && thishelp==nr) {
		styleRef('help').display='none'
	} else {
		styleRef('help').display='block'
		element = jQuery('#help');
		posX = jQuery(button).position().left;
		posY = jQuery(button).position().top;
		padX = 15;
		padY = -15;
		newposX = posX - element.width() - padX;
		newposY = posY - element.height() - padY;
		
		console.log(newposX);
		console.log(newposY);
		
		element.css({'left': newposX, 'top': newposY });
		
	}
	thishelp=nr
	
	
	
}

function check() {
	if (objRef('mapid').value.length<16 && objRef('rs1').checked) {
		if (objRef('mapid').value.length==0) {alert('Het ID van de fotomap is nog niet ingevuld.')} else {alert('Het ingevulde ID van de fotomap is onjuist.')}
		return false
	}
	if (objRef('userid').value.length==0 && objRef('rs2').checked) {
		alert('De gebruikersnaam is nog niet ingevuld.')
		return false
	}
	if (objRef('wlcheck1').checked && objRef('wlid').value.length<1){
		alert('Het ID voor White label verzending is niet ingevuld')
		return false
	}		
	if (objRef('rk3').checked) {
		if (objRef('css').value.length<13) {
			alert('Het adres van het stylesheet is niet volledig.\nVoer de volledige url in\n\nVoorbeeld:\nhttp://www.domeinnaam.nl/oypo.css')
			return false
		}
		if (objRef('css').value.substr(0,7)!='http://' && objRef('css').value.substr(0,7)!='HTTP://') {
			alert('Het adres van het stylesheet is niet volledig.\nVoer de volledige url inclusief \'http://\' in\n\nVoorbeeld:\nhttp://www.domeinnaam.nl/oypo.css')
			return false
		}
	}
	return true;
}

function rgb2hex(rgb) {
	if(rgb == undefined) return '';
	if (rgb.search("rgb") == -1 ) {
	  return rgb;
	} else {
		rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
		function hex(x) { return ("0" + parseInt(x).toString(16)).slice(-2); }
		return '#'+hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
	}
}

function generate_code(type){

	styleRef('help').display='none';
	
	// VARS
	htmlcode='<scr'+'ipt type="text/javascript">\r\n';
	urlcode='/pixxer/api/templates/1207-preview.asp';
	
	if (objRef('rs1').checked) {
		htmlcode+='var mode=\'map\';\r\n';	
		urlcode+='?mode=map';
		htmlcode+='var mapid=\''+objRef('mapid').value+'\';\r\n';	
		urlcode+='&mapid='+objRef('mapid').value;
		htmlcode+= (objRef('nonav0').checked)?'var nonav=0;\r\n':'nonav=1;\r\n';
		urlcode+= (objRef('nonav0').checked)?'&nonav=0':'&nonav=1';
	} else {
		if (objRef('rs2').checked) {
			htmlcode+='var mode=\'user\';\r\n';	
			urlcode+='?mode=user';
			htmlcode+='var userid=\''+objRef('userid').value+'\';\r\n';	
			urlcode+='&userid='+objRef('userid').value;
		} else {
			htmlcode+='var mode=\'school\';\r\n';	
			urlcode+='?mode=school';
		}
	}

	// Transparantie
	delete transparency;	// Delete the var if it is previous set
	if(objRef('t1').checked){ 
		htmlcode+='var transparency=1;'+'\r\n';
		urlcode+='&transparency=1';
	}

	// Whitelabel verzending
	if (objRef('wlcheck1').checked){
		htmlcode+='var wl=\''+objRef('wlid').value+'\';\r\n';
		urlcode+='&wl='+objRef('wlid').value;		
	}
			
	// Kleuren
	if (objRef('rk3').checked){
		htmlcode+='var css=\''+objRef('css').value+'\';\r\n';
		urlcode+='&css='+escape(objRef('css').value);
	}else{
		if(objRef('rk2').checked){
			for (i=1;i<7;i++){
				if (objRef('v'+i)) {
					htmlcode+='var kleur'+i+'=\''+rgb2hex(styleRef('v'+i).backgroundColor)+'\';\r\n';
					urlcode+='&kleur'+i+'='+escape(''+rgb2hex(styleRef('v'+i).backgroundColor));
				}
			}
		}
	}
		
	htmlcode+='</scr'+'ipt>\r\n';
	htmlcode+='<scr'+'ipt type="text/javascript" src="//www.oypo.nl/pixxer/api/templates/1207a.js"></scr'+'ipt>\r\n';
	htmlcode+='<div id="pixxer_iframe"></div>\r\n';
	
	if(type == 'html'){ 
		return htmlcode;
	}else{
		return urlcode;
	}
}

function show_code() {
	if(check()){
		htmlcode = generate_code('html');
		$('#code').show()
		$('#htmlcode2').val(htmlcode);
	}
}

function show_example(){

	if(check()){
		src = generate_code('url');	
		$('#voorbeeld').show().find('div:first').load(src);	
	}
}

function genall() {
	if(check()){
		var top = $('#wizardtop').offset().top;
		show_code()
		show_example()
		setTimeout(function() {$('html, body').animate({scrollTop:top}, 500);},500)
	}
}

jQuery(document).ready( function() {
	
	v1_color = rgb2hex(jQuery('#v1').css('backgroundColor'));
	v2_color = rgb2hex(jQuery('#v2').css('backgroundColor'));
	v3_color = rgb2hex(jQuery('#v3').css('backgroundColor'));
	v4_color = rgb2hex(jQuery('#v4').css('backgroundColor'));
	v5_color = rgb2hex(jQuery('#v5').css('backgroundColor'));
	v6_color = rgb2hex(jQuery('#v6').css('backgroundColor'));
		
	jQuery('#v1').ColorPicker({ color: v1_color, onShow: function (colpkr) { jQuery(colpkr).fadeIn(500); return false; }, onSubmit: function(hsb, hex, rgb, el) { jQuery('#v1').css('backgroundColor', '#' + hex); jQuery('#oypo_v1').val('#' + hex); }});
	jQuery('#v2').ColorPicker({ color: v2_color, onShow: function (colpkr) { jQuery(colpkr).fadeIn(500); return false; }, onSubmit: function(hsb, hex, rgb, el) { jQuery('#v2').css('backgroundColor', '#' + hex); jQuery('#oypo_v2').val('#' + hex); }});
	jQuery('#v3').ColorPicker({ color: v3_color, onShow: function (colpkr) { jQuery(colpkr).fadeIn(500); return false; }, onSubmit: function(hsb, hex, rgb, el) { jQuery('#v3').css('backgroundColor', '#' + hex); jQuery('#oypo_v3').val('#' + hex); }});
	jQuery('#v4').ColorPicker({ color: v4_color, onShow: function (colpkr) { jQuery(colpkr).fadeIn(500); return false; }, onSubmit: function(hsb, hex, rgb, el) { jQuery('#v4').css('backgroundColor', '#' + hex); jQuery('#oypo_v4').val('#' + hex); }});
	jQuery('#v5').ColorPicker({ color: v5_color, onShow: function (colpkr) { jQuery(colpkr).fadeIn(500); return false; }, onSubmit: function(hsb, hex, rgb, el) { jQuery('#v5').css('backgroundColor', '#' + hex); jQuery('#oypo_v5').val('#' + hex); }});
	jQuery('#v6').ColorPicker({ color: v6_color, onShow: function (colpkr) { jQuery(colpkr).fadeIn(500); return false; }, onSubmit: function(hsb, hex, rgb, el) { jQuery('#v6').css('backgroundColor', '#' + hex); jQuery('#oypo_v6').val('#' + hex); }});

});