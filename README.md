# hitman
The hitman api  creates a new game in which a new word is selected from the word table. 
A new game object is created.the Game table has two columns: id(int, ai,primary key) &  game(string) with the value of the word slected for this game.
There is  a relation of one to many between the word table and the game table in a column word_id.
the api has two resources:
1)  localhost:8000/games  method post creates a new game. the response returns the new game id.  
2) games/id                method put  to send a request with your letter of choice. 11 guesses are permitted. 
   upon a successful guess of all the letters in a word a response message confrims the victory and a game is finished.
   example request :  curl -X PUT -d ot=e localhost:8000/games/4   
  
