var ivotings = Array();
var ar_elm = Array();
var i_elm = 0;
var itemvotin = '';
var votingfiles = 'catalog/controller/voting/';
var advote = 0;

var getVotsElm = function () {
  var obj_div = document.getElementsByTagName('div');
  var nrobj_div = obj_div.length;
  for(var i=0; i<nrobj_div; i++) {
    if(obj_div[i].className && obj_div[i].id) {
	    var elm_id = obj_div[i].id;
      if((obj_div[i].className=='vot_plus' || obj_div[i].className=='vot_updown1' || obj_div[i].className=='vot_updown2') && elm_id.indexOf("vt_")==0) {
        ivotings[elm_id] = obj_div[i];
        ar_elm[i_elm] = elm_id;
        i_elm++;
      }
    }
  }
  if(ar_elm.length>0) votAjax(ar_elm, '');
};

function addVotData(elm_id, vote, nvotes, renot) {
  if(ivotings[elm_id]) {
    var clik_up = (renot == 0) ? ' onclick="addVote(this, 1)"' : ' onclick="alert(\'Вы уже голосовали за этот отзыв\')"';

      var nvup = (nvotes*1 + vote*1) /2;
      var nvdown = nvotes - nvup;
      var clik_down = (renot == 0) ? ' onclick="addVote(this, -1)"' : ' onclick="alert(\'Вы уже голосовали за этот отзыв\')"';

      ivotings[elm_id].innerHTML = '<span style="color: #666;">Отзыв полезен? <a id="yesvot" '+ clik_up+ '>Да</a> <b id="nvup">'+ nvup+ '</b> / <a id="novot" '+ clik_down+ '>Нет</a> <b id="nvdown">'+ nvdown+ '</b></span>';
  }
}

function addVote(ivot, vote) {
  if(advote == 0) {
    var elm = Array();
    elm[0] = ivot.parentNode.parentNode.id;

    votAjax(elm, vote);
  }
  else alert('Вы уже голосовали');
}

function get_XmlHttp() {
  var xmlHttp = null;
  if(window.XMLHttpRequest) xmlHttp = new XMLHttpRequest();
  else if(window.ActiveXObject) xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
  return xmlHttp;
}

function votAjax(elm, vote) {
  var cerere_http =  get_XmlHttp();

  var datasend = Array();
  for(var i=0; i<elm.length; i++) datasend[i] = 'elm[]='+elm[i];
  datasend = datasend.join('&')+'&vote='+vote;

  cerere_http.open("POST", votingfiles+'voting.php', true);

  cerere_http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  cerere_http.send(datasend);

  cerere_http.onreadystatechange = function() {
    if (cerere_http.readyState == 4) {
      eval("var jsonitems = "+ cerere_http.responseText);
      if (jsonitems) {
        for(var votitem in jsonitems) {
          var renot = jsonitems[votitem][2];
           if(renot == 3) {
            alert("Вы уже проголосовали 3 раза \n Вы можете продолжить завтра");
            window.location.reload(true);
          }
          else addVotData(votitem, jsonitems[votitem][0], jsonitems[votitem][1], renot);
        }
      }
      if(vote != '' && (renot == undefined || renot == 2)) advote = 1;
	  }
  }
}
function addLoadVote(func) {
  var oldonload = window.onload; 
  if (typeof window.onload != 'function') window.onload = func;
  else { 
    window.onload = function() { 
      if (oldonload) { oldonload(); } 
      func();
    } 
  } 
}
addLoadVote(getVotsElm);