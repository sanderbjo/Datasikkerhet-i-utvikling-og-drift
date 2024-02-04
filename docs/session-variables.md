# $_SESSION variabler
Variablene blir satt på login, og hentes ut med '$_SESSION["\<Variabelnavn\>"]'.

**!!!Disse variablene må ikke endres!!!**

Endres disse variablene risikerer man at en bruker får uautorisert tilgang til andre brukerkontoer eller funsjoner som
de ikke skal ha mulighet til å bruke.

## id (int)
ID for bruker.

## loggedIn (bool)
Brukes for å sjekke om en noen er logget inn.

- true = logget inn
- false = IKKE logget inn

## email (string)
Epost til bruker.

## name (string)
Navnet til bruker.

## role (?) TODO: Skal role være rolekey (1/2) eller "elev"/"professor"?
Rolle på bruker.
