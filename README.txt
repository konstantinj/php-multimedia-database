This was a task during my studies
---------------------------------


Hinweise zur Anwendung
======================

Als Laufzeitumgebung benutze ich php-fpm + nginx. Beides läuft zusammen mit mysql in einer Docker Umgebung. Zum einfachen Handling benutze ich Docker-Compose sowie ein Makefile. Auf dem ausführenden System muss daher mindestens Docker und Docker-Compose vorhanden sein. Wenn auch Make installiert ist, wird es noch etwas einfacher. Theoretisch müsste auch PHP Composer (https://getcomposer.org/) installiert sein, um die Abhängigkeiten local zu installieren. Ich liefere die Anwendung allerdings mit installierten Abhängigkeiten aus.

Diese Make Befehle werden benötigt:

make build 	- Bauen des Dockerfiles, d.h. Kompilierung von php und Zusatzmodulen
make run 	- Starten des gesamten docker-compose stack
make stop	- Stoppt die Docker Container
make clean	- Stoppt und löscht die Docker Container sowie die Datenbank

Der Code wird per Docker Volume eingebunden, d.h. die Docker Container sind nur die reine Laufzeitumgebung.

Ich habe das Phalcon Framework (https://phalcon.io/) benutzt, um mir die Arbeit mit PHP zu vereinfachen.

Im Verzeichnis app sind die php source Dateien. Phalcon baut auf dem MVC Prinzip auf. Die meiste Logik, die für die B-Aufgabe von Bedeutung ist, befindet ist im MediaController in app/app/controllers/MediaController.php.

Die Anwendung beinhaltet einen Login. Benutzer ist “admin”, Passwort ist “wbh”. Nur eingeloggte Benutzer sehen die Editier- und Löschfunktion.

Editieren, Löschen, Videos/Bilder ansehen und XML Ansicht sind über die Icons auf der rechten Seite der Tabelle erreichbar.

Wie geplant habe ich die “Schauspieler” in eine eigne Tabelle ausgelagert und eine m:n Beziehung hergestellt.

Um Videos/Bilder anzulegen, genügt es, eine Datei hochzuladen. Titel und Schauspieler sind Optional. Schauspieler können mit Komma getrennt eingegeben werden.

Der Titel wird, so fern nicht angegeben, aus dem hochgeladenen Video/Bild extrahiert. Ebenso der Typ, die Dauer (im Falle eines Videos) und die Dimensionen.


Einschränkungen
===============

Nativ lassen sich nur die Video Formate mp4, webm und ogg im Browser mit Hilfe des html5 video-Tags abspielen. Für alle anderen Formate werden Plugins (z.B. Flash (sollte nicht mehr benutzt werden) oder VLC) benötig oder die Videos müssen in ein geeignetes Format (heutzutage i.d.R. mp4) konvertiert werden. Für avi und flv habe ich das exemplarisch mit ffmpeg implementiert. Da es Teil der Aufgabe war ein avi- und ein flv-Video zu speichern, mache ich die Konvertierung zu mp4 erst, wenn das Video zum Abspielen angefordert wird. Gängige Internetplattformen konvertieren die Videos bereits direkt nach dem sie hochgeladen wurden. Das spart Speicher auf dem Server.

Ich habe außerdem versucht ein buffering zu implementieren. Allerdings nur sehr rudimentär, da das gesamte Video zum Abspielen zu erst aus der Datenbank in den Speicher geladen werden muss. Von dort kann es dann "Häppchenweise" zum Client übertragen werden (wenn der Browser das zulässt). Es wäre sicherlich auch möglich die Binärdaten bereits in Teilen aus der Datenbank zu laden. Ich denke aber, das war nicht Ziel dieser Aufgabe.

Im Fall von großen Dateien belastet dieses Vorgehen den Server allerdings sehr. Wenn Performanz wichtig ist, sollte man weiterhin Dateien im Dateisystem ablegen. Außerdem beinhalten Webserver bereits gut getestete Algorithmen, um Daten an ihre Clients zu streamen. Diese Funktionalität muss nicht neu implementiert werden. Backups der Datenbank dürften bei größeren Dateien ebenfalls zum Problem werden. Für kleinere Daten, bei denen die Sicherheit wichtiger ist als die Performanz, ist die Speicherung in der Datenbank sicherlich sinnvoller.

Tests
=====

Ich habe meine Anwendung nur mit Dateien kleiner als 1mb getestet. Die Test-Dateien habe ich von dieser Seite: http://techslides.com/sample-files-for-development

Der mysql dump inkl. Test-Daten liegt in mysql/docker-entrypoint-initdb.d/mmi.sql und wird beim Start der Anwendung automatisch importiert (sofern die Datenbank leer ist).

Getestet habe ich unter OSX 10.15.1 mit dem Chrome Browser.
