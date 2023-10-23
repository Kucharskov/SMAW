# So Minimize thAt Width (SMAW) - [live demo](http://r.kucharskov.pl)
A script written in PHP as a link shortener. Main functions and capabilities:
  - Settings block at the very beginning of the script
  - Option to generate "nice" URLs (like YouTube) with selectable ID lengths
  - Built-in 4 languages (Polish, English, German, Italian) with the ability to easily add your own language
  - It uses the [Zurb Foundation](http://foundation.zurb.com/) framework version 5
  - That's all in ONE file

## Changelog
I can't guarantee that I typed everything I did here, because I might not have remembered ;)

###### Version 3.1 (still beta)
  - Fix XSS in title, found by [kozmer](https://github.com/kozmer)
  - Fix XSS in last links, found by [ToBeatElite](https://github.com/tobeatelite)
  - Update link in footer from my website to SMAW GitHub

###### Version 3.0 REFACTOR (beta)
  - Refactored whole PHP code, but funcionality stays similar!
  - CSS fixes (with framework classes)
  - Translation updates and fixes
  - New languages: German (by [r0BIT](https://twitter.com/0xr0BIT)), Italian (by [P0](https://twitter.com/Pzz02))
  - Now link ID's is no longer BASE64, now theres number-magic with [hash-int](https://github.com/dmhendricks/hash-int) library which makes URLs more YouTube-like
  - Additional golden primes calculated in hash-int which allows user to crate 13 chars URL ID's (and thats a lot)
  - Different way to get TITLE from source page
  - Silenced warning of getting TITLE 
  - New default configuration
  - Bump JavaScript libraries versions
  - Refactored README also ;)

###### Version 2.1 (beta)
  - Feature for fixing missing slash in URL
  - Fature added to BASE64 link ID's
  - Added "No recent shortened links" verification
  - Fixed not updating links counter
  - Fixes in output links
  - Translation updates and fixes
  - CSS fixes (with framework classes)
  - Bump JavaScript libraries versions

###### Version 2.0
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

###### Version 1.0
  - Initial version
  
## Important thing
I wrote this code while i was in technical school. Every now and then I look at it and try to improve it - with varying success. I have tested this piece of code as much as I can, especially with the boundary conditions, and I feel that it works. Especially since the live demo hasn't caused damage since 2015. But maybe it's just luck. Use on your own risk.
