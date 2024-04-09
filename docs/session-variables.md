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

## role (int)

Rolle på bruker.

- 1 = Foreleser
- 2 = Elev

## csrf-* (string)

Auth tokens som må valideres for at en form skal være gyldig.

- csrf-changePassword
- csrf-login
- csrf-logout
- csrf-signup

## anon-subject (int[])

En liste med IDer på emner hvor en bruker som ikke er logget inn skal ha tilgang til
