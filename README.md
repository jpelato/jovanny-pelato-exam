# Exam by Jovanny Pelato

Start of exam July 6 9:00 AM

End of exam July 7 2:50 PM

## INSTRUCTION
The project folder should be cloned or pasted into the Apache htdoc folder.

The project must be run using the command while inside the Apache/htdoc/projectFolderName folder.

```bash
# To install node library
npm install
# To start the project on a local server
npm run dev
# Please enter the supplied IP address in your browser
```

## VERSION USE

```bash
# PHP
PHP 8.1.11

# NODE
v18.15.0

# Apache
Apache/2.4.54 (Win64)
```

## SETUP

```php
# In order to establish a connection, alter the connect parameter.
# location htdoc\jovanny-pelato-exam\controllers\db_connect.php
function connect(){
    $servername = "localhost";
    $username = "root";
    $password = "jpelato";
    $dbname = "youtube_db";
    $conn = new mysqli($servername, $username, $password,$dbname);
    if ($conn->connect_error) {
        echo $conn->connect_error;
    }
    return $conn;
}
```

```java
// htdocs\jovanny-pelato-exam\src\main.js

// "jovanny-pelato-exam" If you're planning to rename it, replace this with the folder name. 
axios.defaults.baseURL = 'http://localhost/jovanny-pelato-exam/controllers';
createApp(App)
.use(vuetify)
.use(VueAxios, axios)
.mount('#app')
```