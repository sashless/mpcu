# mpcu : mobile presentation control unit

### Usage

- app/console doctrine:schema:update
- app/console server:run

Open localhost:8000/app_dev.php/

Register a UserSession via the Form and login with your credentials.

Choose Reciever.

Open the page on the same or another device again and login with your credentials.

Choose Sender.

Swipe from left to right or righ to left. The Actions are saved within your UserSession and the Recieving Site will display every action the Sender is doing in real time.

### Todo

- add build system like buildout or grunt etc
- add service like youtube to control, online presentation etc 
- write more tests
- TDD for the future dev
