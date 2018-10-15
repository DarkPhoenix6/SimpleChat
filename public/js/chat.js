
  var user;
  var color;
  var hash;
  var maxId = 0;
  var messageBox = document.getElementById("messageLog");
  var userName = document.getElementById("userName");
  var userColor = document.getElementById("userColor");
 
  
  
  
  function sendMessage(){
	var message = document.getElementById("message").value;
	var ajax = new XMLHttpRequest();
	ajax.open("POST", "/api/send", true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send("message=" + message + "&user=" + user + "&color=" + color + "&hash=" + hash);
	document.getElementById("message").value = "";
  }
  
  
  function setUser(){
	user = document.getElementById("userName").value;
 color = document.getElementById("userColor").value;
	var ajax = new XMLHttpRequest();
	ajax.open("POST", "/setUser", true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send("user=" + user + "&color=" + color);
	
  }
  
  function getMessages(){
	var ajax = new XMLHttpRequest();
	ajax.open("GET", "/api/msgs/"+hash+"/"+maxId, true);
	ajax.onload = function(e) {
		if(ajax.readyState === 4 && ajax.status === 200){
			var json = this.responseText;
			var array = JSON.parse(json);
			for(var key in array){
				messageBox.innerHTML += array[key]
				if(parseInt(key) > maxId){
					maxId = parseInt(key);
				}
				messageBox.scrollTop = messageBox.scrollHeight;
			}
		}
	};
	ajax.send();
  }
  
  var sessionAjax = new XMLHttpRequest();
  sessionAjax.open("GET", "/getSessionData/", true);
  sessionAjax.onload = function(e){
  // console.log("test");
	  if (sessionAjax.readyState === 4 && sessionAjax.status === 200)
	  {
		var json = this.responseText;
		var array = JSON.parse(json, function(key, value)
		{
			if (key == "user")
			{
				user = value;
			}
			if (key == "color")
			{
				color = value;
			}
			if (key == "hash")
			{
				hash = value;
			}
		});
		userColor.value = color;
		userName.value = user;
		getMessages();
		setInterval(getMessages, 5000);
	  }
  }
  sessionAjax.send();

  document.getElementById("sendMessage").addEventListener("click", sendMessage);
  document.getElementById("setUser").addEventListener("click", setUser);
  document.getElementById("message").addEventListener("keyup", function(event) {
	if(event.keyCode === 13)
		document.getElementById("sendMessage").click();
  });
  document.getElementById("userName").addEventListener("keyup", function(event) {
	if(event.keyCode === 13)
		document.getElementById("setUser").click();
  });
  document.getElementById("userColor").addEventListener("keyup", function(event) {
	if(event.keyCode === 13)
		document.getElementById("setUser").click();
  });
