/////////////////////// index.html
<html>
<head>
<meta charset="utf-8"/>
<style>
 
</style>
<script>
// GET index.php?imie=Maciej&nazwisko=Lolek&wzrost=176
 
document.addEventListener("DOMContentLoaded", function(event) {
        ajax={
                send:function(f){
                        var imie=document.getElementById("imie").value;
                        var nazwisko=document.getElementById("nazwisko").value;
                        xhttp = new XMLHttpRequest();
                        xhttp.open("POST", "ajax.php", true);
                        
                        xhttp.onreadystatechange = function() {
                                console.log(this.readyState); // można wykorzystać do Ajaxloader'ów
                        if (this.readyState == 4 && this.status == 200) {
                        if(f==1){
                                document.getElementById("imie").value="";
                                                document.getElementById("nazwisko").value="";
                        
                                //document.getElementById("output").innerHTML = this.responseText;
                                var dane = JSON.parse(this.responseText) // JSON.stringify
                                //console.log(dane, dane.imie, dane['nazwisko'], dane.nazwisko);
                                for(var i=0; i< dane.length; i++){
                                        document.getElementById("output").innerHTML+=dane[i].imie+" "+dane[i].nazwisko+"<br/>";
                                }
                         }}
                };
                        
                        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xhttp.send("imie="+imie+"&nazwisko="+encodeURIComponent(nazwisko)+"&f="+f); 
                        // encodeURIComponent(" śćą")
                }
        }
});
</script>
</head>
<body>
<button onclick="ajax.send(1)">WYŚLIJ</button>
<div id="output"></div>
imie: <input id="imie">
nazwisko: <input id="nazwisko">
</body>
</html>
 
 
 
//////////////// ajax.php
<?php
//echo "lol";
// php.net
//print_r($_POST); //$_POST , $_GET <- to są tablice
//echo $_POST['imie']." ".$_POST['nazwisko'];
//echo json_encode($_POST); // json_decode
 
$dsn = 'mysql:dbname=ajax;host=localhost';
$user = 'root';
$password = '';
// CRUD - Create, Retrive, Update, Delete
try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->exec("set names utf8");
    //print_r($dbh);
    if(isset($_POST['f']) && $_POST['f']==1){ // insert
                $sql = 'insert into dane values("",:imie,:nazwisko)';
                $sth = $dbh->prepare($sql);
                $sth->bindValue(':imie', $_POST['imie'], PDO::PARAM_STR);
                $sth->bindValue(':nazwisko', $_POST['nazwisko'], PDO::PARAM_STR);
                $sth->execute();
                ////
        $sql = 'select * from dane';
                $sth = $dbh->prepare($sql);
                $sth->execute();
                $dane = $sth->fetchAll();
                //print_r($dane);
        echo json_encode($dane);
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
 
 
 
//////////// dane.sql
-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 19 Gru 2017, 08:30
-- Wersja serwera: 10.1.26-MariaDB
-- Wersja PHP: 7.1.9
 
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
 
 
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
 
--
-- Baza danych: `ajax`
--
 
-- --------------------------------------------------------
 
--
-- Struktura tabeli dla tabeli `dane`
--
 
CREATE TABLE `dane` (
  `id` int(11) NOT NULL,
  `imie` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `nazwisko` varchar(100) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
 
--
-- Zrzut danych tabeli `dane`
--
 
INSERT INTO `dane` (`id`, `imie`, `nazwisko`) VALUES
(1, 'Maciej', 'Kołodziej'),
(2, 'Adam', 'Adamski'),
(3, 'Adam', 'Bednarski'),
(4, 'Olo', 'Ryszardzki'),
(5, 'AAA', 'BBB'),
(6, 'ĄŚĆ', 'ÓŁ'),
(7, 'dsdsd', 'śłó');
 
--
-- Indeksy dla zrzutów tabel
--
 
--
-- Indexes for table `dane`
--
ALTER TABLE `dane`
  ADD PRIMARY KEY (`id`);
 
--
-- AUTO_INCREMENT for dumped tables
--
 
--
-- AUTO_INCREMENT dla tabeli `dane`
--
ALTER TABLE `dane`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;
 
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;