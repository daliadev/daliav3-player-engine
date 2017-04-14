<HTML>
  <HEAD>
    <META HTTP-EQUIV="Content-Language" CONTENT="fr">
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; CHARSET=iso-8859-1">
    <TITLE>Exemples de texte à trous</TITLE>
    <SCRIPT LANGUAGE="JavaScript">
      function erreurl(numliste) {
       laliste=eval("form1.liste"+numliste);
       return laliste[laliste.selectedIndex].value;
      }

      function message(erreurs) {
       if (erreurs==0) alert('Bravo, aucune erreur !');
       else if (erreurs==1) alert('Tu as fait une erreur !');
       else alert('Tu as fait '+erreurs+' erreurs !');
      }

      function verif1() {
       erreurs=0;
       for (i=1; i<=2; i++) {
        erreurs+=eval(erreurl(i));
       }
       message(erreurs);
      }

      function verif2() {
       erreurs=0;
       if (form2.rep1.value!="rectangle") erreurs++;
       if (form2.rep2.value!="180") erreurs++;
       message(erreurs);
      }
    </SCRIPT>
  </HEAD>

  <BODY>
    <H1>Exemples de textes à trous</H1>
    <P><BR></P>

    <H2>Compléter en choisissant un mot dans une liste</H2>
    <FORM NAME="form1">
      <BLOCKQUOTE>
        <OL>
          <LI>On appelle triangle
            <SELECT NAME="liste1" SIZE=1>
              <OPTION VALUE=1>équilatéral</OPTION>
              <OPTION VALUE=0>isocèle</OPTION>
              <OPTION VALUE=1>rectangle</OPTION>
              <OPTION VALUE=1>scalène</OPTION>
            </SELECT>
          un triangle qui a deux côtés de même longueur.<BR><BR>
          </LI>
<LI>
On appelle
<SELECT NAME="liste2" SIZE=1>
<OPTION VALUE=1>bissectrice</OPTION>
<OPTION VALUE=1>hauteur</OPTION>
<OPTION VALUE=0>médiane</OPTION>
<OPTION VALUE=1>médiatrice</OPTION>
</SELECT>
d'un triangle une droite qui passe par un sommet et le
milieu du côté opposé.
</LI>
</OL></BLOCKQUOTE>
<BR>
<DIV ALIGN=right>
<INPUT TYPE=button VALUE="Vérifier" ONCLICK="verif1()"></INPUT>
</DIV>
</FORM>
<P><BR></P>

<H2>Compléter en écrivant un mot</H2>
<FORM NAME="form2">
<BLOCKQUOTE><OL>
<LI>
On appelle triangle
<INPUT NAME="rep1" TYPE=text SIZE=12 MAXLENGTH=12></INPUT>
, un triangle qui a un angle droit.
</LI>
<LI>
La somme des 3 angles d'un triangle est égale à
<INPUT NAME="rep2" TYPE=text SIZE=5 MAXLENGTH=5></INPUT>
degrés.
</LI>
</OL></BLOCKQUOTE>
<BR>
<DIV ALIGN=right>
<INPUT TYPE=button VALUE="Vérifier" ONCLICK="verif2()"></INPUT>
</DIV>
</FORM>

</BODY>
</HTML>

<!-- Source : http://tdprog.free.fr/ateliers/formulaires/trous/ -->
