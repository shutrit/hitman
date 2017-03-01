# hitman
The hitman api  creates a new game in which a new word is selected from the word table. 
A new game object is created.the Game table has two columns id and game column which stored the word. 
There is also a relation of one to many between the wod table and the game table in a column word_id. 
the api has two resources:
1)  localhost:8000/games/  method post creates a new game.  
2) games/id method  put   lets user/machine  play the game 11 guesses are allowed. 
 upon a successful guess of all the letters in a word the user recieves a message and  the game is reset. 
 to pass the letter you guess you can use curl : curl -X PUT -d ot=e localhost:8000/games/4   
   the key : "ot" 
