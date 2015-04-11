[PL] So Minimize thAt Width [SMAW](http://r.kucharskov.pl)
======
Skrypt napisany w języku PHP skracający linki. Główne funkcje i zalety:
  - Przyjemna konfiguracja na początku pliku
  - Mozliwość włączenia/wyłączenia generowania ładnych URLi
  - Możliwość przestawienia języka (polski / angielski) wspierająca możliwosć dodania własnych tłumaczeń
  - Korzysta z frameworka [Zurb Foundation](http://foundation.zurb.com/)
  - Wszystko w JEDNYM pliku

Lista zmian
----
######Wersja 2.0
  - Poprawki i aktualizacje tłumaczeń
  - Poprawki CSS (z klasami framework'a)
  - Poprawiony niezmienny kod języka "en" html
  - Przepisany generator linków rewritemod
  - Standardowo wyłączony rewritemod
  - Usunięty dziwny i nieużyteczny regex do walidacji adresów
  - Uproszczono wyświetlanie wiadomości
  - Zmiana czasu przekierowania na 3 sekundy
  - Dodana opcja do pokazywania ostatnich X skróconych adresów
  - Dodano licznik adresów z opcją włączenia
  - Dodano tytuł strony podczas przekierowywania
  - Pół wbudowany HTML w kod echo PHP
  - Troche zminimalizowano kod
  - Włączono output buffering
  
######Wersja 1.0
  - Początkowa wersja
---

[EN] So Minimize thAt Width [SMAW](http://r.kucharskov.pl)
======
Script written in PHP which shortens links. Main features and benefits:
  - A nice setup at the beginning of the file
  - Option to enable / disable the generation of the pretty URLs
  - Option to change language (polish / english),which supports adding your own translations
  - It uses the [Zurb Foundation](http://foundation.zurb.com/) framework
  - That's all in ONE file

Changelog
----
######Version 2.0
  - Translation updates and fixes
  - CSS fixes (with framework classes)
  - Fixed always "en" html lang code
  - Reworked rewritemod links generator
  - Default disabled rewritemod
  - Removed stange and useless regex to validate URL
  - Simplified showing messages
  - Changed redirection time to 3 sec
  - Added option to show last X shorted urls
  - Added links count with enable option
  - Added show title page when redirecting
  - Semi HTML build-in PHP echo code
  - Some minimized code
  - Turned output buffering on

######Version 1.0
  - Initial version