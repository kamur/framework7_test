// Initialize your app
var app = new Framework7({
  root: '#app',
  id: 'io.framework7.testapp',
  name: 'Test Framework7',
  theme: 'auto',
  routes: routes
});

// Export selectors engine
var $$ = Dom7;

// Add view
var mainView = app.views.create('.view-main', {
  url: '/',
  // dynamicNavbar: true
});

// Login
function login(data){
  app.request.post(window.prefix_url+'/actions/login', data, function(msg, status, xhr){
    if(msg == "error")
      alert("I dati inseriti non sono corretti. Riprova.");
    else if(msg == "error2")
      alert("Si è verificato un errore durante il login. Riprova.");
    else{
      var link = msg.split("§");
      if(link[1] != "0000")
        document.cookie = "uid="+link[1];
      window.location = window.prefix_url+'/'+link[0];
    }
  });
}

$$('#login-button').on('click', function(){
  var email = $$('.login-screen-content [name="email"]').val();
  var password = $$('.login-screen-content [name="password"]').val();
  if(email == '') $$('.login-screen-content [name="email"]').trigger('blur')
  if(password == '') $$('.login-screen-content [name="password"]').trigger('blur')
  if(email != '' && password != ''){
    login({email: email, password: password});
  }
});
// End Login

// Register
function registerNewUser(data){
  app.request.post(window.prefix_url+'/actions/register', data, function(msg, status, xhr){
    if(msg.match(/OK$/)){
      app.loginScreen.close('#my-register-screen');
      app.dialog.alert("Registrazione avvenuta con successo. Ti è stata inviata una mail per confermare il tuo account.");
    }
    else
      alert(msg);
  });
}

$$('#register-button').on('click', function(){
  var email = $$('#my-register-screen [name="email"]').val();
  var password = $$('#my-register-screen [name="password"]').val();
  if(email == '') $$('#my-register-screen [name="email"]').trigger('blur')
  if(password == '') $$('#my-register-screen [name="password"]').trigger('blur')
  if(email != '' && password != ''){
    registerNewUser({email: email, password: password});
  }
});
// End Register

// Callbacks to run specific code for specific pages, for example for Match page:
$$(document).on('page:init', '.page[data-name="match"]', function (e) {
  var matchStarted = false;
  var match = []
  var players = [];
  var designed = false;
  var last_player_id = -1;

  function fundamentals(){
    var arr;
    var selected;
    this.arr = ["battuta", "attacco", "difesa", "ricezione", "muro"];
    this.selected = -1;
  }
  var fundament = new fundamentals();

  function vote(){
    var value;
    var selected;
    this.value = ["P", "++", "+", "-", "--", "E"];
    this.selected = -1;
  }
  var valuation = new vote();

  function player(name, number){
    var name;
    var chosen;
    var number;
    var array = new Array(fundament.arr.length);
    this.name = name;
    this.chosen = false;
    this.number = number;
    for(i=0; i<fundament.arr.length; i++)
      this[fundament.arr[i]] = [];
  }

  function addPlayer(){
    if(players.length == 7)
      alert("Non puoi aggiungere altri giocatori!");
    else {
      do {
        var check = false;
        var name = prompt("Inserisci il nome del giocatore: ");
        if(name != null){
          do {
            try {
              var number = prompt("Inserisci il numero di maglia del giocatore: ");
              if(isNaN(number)) throw(err)
            } catch(err){
              alert("Input is not a number");
            }
          } while(isNaN(number))
          for(i=0; i<players.length; i++){
            if(name == players[i].number){
              check = true;
              alert("Numero già esistente!");
            }
          }
        }
      } while(check)
      if(name && number){
        var playerToAdd = new player(name, number);
        last_player_id++;
        players.push(playerToAdd);
        var btn = document.createElement("BUTTON");
        btn.setAttribute('data-id', last_player_id);
        btn.setAttribute('onclick', 'selectedP(this)');
        var t = document.createTextNode(playerToAdd.number +" "+ playerToAdd.name);
        btn.appendChild(t);
        document.getElementById("players").appendChild(btn);
      }
    }
  }

  function startMatch(){
    if(!matchStarted){
      matchStarted = true;
      for(i=0 ; i<fundament.arr.length; i++){
        var myId = i;
        var li = document.createElement("LI");
        var a = document.createElement("A");
        var t = document.createTextNode(fundament.arr[i]);
        li.setAttribute('data-id', myId);
        a.setAttribute('class', fundament.arr[myId]);
        a.appendChild(t);
        li.setAttribute('onclick', 'selectedF(this)');
        document.getElementById("fund").appendChild(li);
        li.appendChild(a);
      }
      for(i=0 ; i<valuation.value.length; i++){
        var myId = i;
        var li = document.createElement("LI");
        var a = document.createElement("A");
        var t = document.createTextNode(valuation.value[i]);
        li.setAttribute('data-id', myId);
        a.setAttribute('class', valuation.value[myId]);
        a.appendChild(t);
        li.setAttribute('onclick', 'selectedV(this)');
        document.getElementById("value").appendChild(li);
        li.appendChild(a);
      }
    }
  }

  function selectedP(btn){
    var saved;
    player_id = btn.getAttribute('data-id');
    player = players[player_id];
    for(i=0; i< players.length; i++){
      players[i].chosen = false;
      if(player.name == players[i].name)
        saved = i;
      }
    players[saved].chosen = true;
    alert(players[saved].name+" selected.");
    designed = true;
  }

  function selectedF(btn){
    myId = btn.getAttribute('data-id');
    fundament.selected = myId;
    alert(fundament.arr[fundament.selected] + " selected!");
  }

  function selectedV(btn){
    myId = btn.getAttribute('data-id');
    valuation.selected = myId;
    for(i=0; i<players.length; i++){
      if(players[i].chosen == true){
        players[i][fundament.arr[fundament.selected]].push(valuation.value[valuation.selected]);
        alert("Ho aggiunto " + valuation.value[valuation.selected] + " a " +fundament.arr[fundament.selected] + " di " +players[i].name);

        fundament.selected = -1;
        valuation.selected = -1;
      }
    }
  }

  function showPoints(){
    if(players.length == 0){
      alert("No Player in array!");
    } else {
      if(match.length == 0){
        match = [players];
      }
      document.getElementById("player-rows").innerHTML = "";
      for(s=0; s<match.length; s++){
        var line = document.createElement("TR");
        var cell_set = document.createElement("TD");
        cell_set.appendChild(document.createTextNode("Set "+(s+1)));
        cell_set.setAttribute('colspan', '7');
        cell_set.setAttribute('class', 'set');
        line.appendChild(cell_set);
        document.getElementById("player-rows").appendChild(line);
        var setPlayers = match[s];
        for(i=0; i<setPlayers.length; i++){
          var player = setPlayers[i];
          var line = document.createElement("TR");
          var cell_number = document.createElement("TD");
          cell_number.appendChild(document.createTextNode(player.number));
          line.appendChild(cell_number);
          var cell_name = document.createElement("TD");
          cell_name.appendChild(document.createTextNode(player.name));
          line.appendChild(cell_name);
          for(f=0; f<fundament.arr.length; f++){
            cell = document.createElement("TD");
            cell.appendChild(document.createTextNode(player[fundament.arr[f]].join(', ')));
            line.appendChild(cell);
          }
          document.getElementById("player-rows").appendChild(line);
          document.getElementById("dialog-stats").setAttribute('style', 'display: block');
        }

      }
    }
  }

  function endSet(){
    var setPlayers = JSON.parse(JSON.stringify(players));
    match.push(setPlayers);
    players.map(function(player, idx){
      console.log('Reset Player '+player.name);
      for(i=0; i<fundament.arr.length; i++)
        players[idx][fundament.arr[i]] = [];
    });
  }

  function testTeam(){
    team = {'Pippo': 1, 'Pluto': 2, 'Topolino': 3, 'Minnie': 4, 'Paperina': 5, 'Paperino': 6, 'Zio Paperione': 7};
    for(var name in team){
      var number = team[name];
      var playerToAdd = new player(name, number);
      last_player_id++;
      players.push(playerToAdd);

      var li = document.createElement("LI");    //creazione tag per la lista
      var d1 = document.createElement("DIV");
      var d2 = document.createElement("DIV");
      var t = document.createTextNode(playerToAdd.name);

      li.setAttribute('data-id', last_player_id); //caricamento attributi
      li.setAttribute('class', "item-content");
      d1.setAttribute('class', "item-inner");
      d2.setAttribute('class', "item-title");
      d2.setAttribute('onclick', "selectedP(this)");

      document.getElementById("el-player").appendChild(li);
      li.appendChild(d1);
      d1.appendChild(d2);
      d2.appendChild(t);              //inserimento tag e creazione dinamica della lista (non sono sicuro dell'ordine in cui devo fare le append)
    };
  }

  function closeStats(){
    document.getElementById("dialog-stats").setAttribute('style', '');
  }

  document.addEventListener('DOMContentLoaded', testTeam, false);
  document.addEventListener('DOMContentLoaded', startMatch, false);
});