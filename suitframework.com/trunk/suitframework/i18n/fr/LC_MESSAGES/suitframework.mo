��    7      �  I   �      �  �   �  2   �  �  �  7   h  ~   �  w  	    �
  w   �  �      4   �  0     3   D  4   x  4   �  3   �  0     2   G  3   z  4   �  0   �  3     0   H  2   y     �     �  
   �  %   �  0     "   ?     b     i     u     �     �     �     �     �     �     �     �  
   �  K   �     &     -     2     6  �   L  �   �  �   h  �   �  �   �          $     (  s  -  �   �  9   �  :   �  :   �  �   6  �  �  9  k  ~   �  �   $  F   �  B   D  E   �  F   �  F     E   [  B   �  D   �  E   )  F   o  B   �  E   �  B   ?  D   �     �  #   �  	      '      2   5   $   h      �      �      �      �      �      �      �      �      �      �   !   �   
   
!  N   !     d!     k!     p!     t!  �   �!  �   "  �   �"  �   H#  �   �#     �$     �$     �$                 7            ,       $                  '          	   (   +   *      -   1                 2   #                           !   3               
      "       )         /              &       4          6       0                    %       5   .    06/06/2010: SUIT 2.0.0 and Rulebox 1.0.0 released. These versions were the first to be developed using SVN. You can see all of the differences between it and its predecessor by reading revisions 25 through 142. 07/31/2010: SUIT 2.0.1 and Rulebox 1.1.0 released. 07/31/2010: SUIT 2.0.1 and Rulebox 1.1.0 released. Applied the 80 character rule to the PHP files to match the Python files. Fixed bug with <a href="[url controller="root" action="template" templatefile="docs" parameter1="try" ]"/>try</a> that caused the variable to be undefined initially if no exception was thrown. Made SUIT PHP's parse function cache correctly. Before, it was loading the cache, but not returning it. The static variable "delimiter" has been added to <a href="[url controller="root" action="template" templatefile="docs" parameter1="call" ]"/>call</a> and <a href="[url controller="root" action="template" templatefile="docs" parameter1="transform" ]"/>transform</a>. 1.1.0 - The static variable "delimiter" has been added. <a href="[url controller="root" action="template" templatefile="docs" parameter1="url" ]"/>Return back to the url article.</a> <a href="[url controller="root" action="template" templatefile="slacks" ]"/>SLACKS</a> takes a SUIT generated log, which contains information on how the execute function works, and formats it to show the user how it transformed the template. In order to permit SLACKS to access this log, instead of calling execute and printing the result, you must use the following snippet. <a href="[url controller="root" action="template" templatefile="tryit" ]"/>Try It</a> is one of the more interactive features of this site. It can be used to play around with some Rulesets live on this site. Select a Rulset, and write a template to be transformed with it. A <a href="[url controller="root" action="template" templatefile="docs" parameter1="comparison" ]"/>cleaner</a> syntax. A <a href="[url controller="root" action="template" templatefile="download" parameter1="rulebox" ]"/>Rulebox</a> which contains several sets of rules you can use to transform your template. A string used to label this rule. (Default: "align") A string used to label this rule. (Default: "b") A string used to label this rule. (Default: "code") A string used to label this rule. (Default: "color") A string used to label this rule. (Default: "email") A string used to label this rule. (Default: "font") A string used to label this rule. (Default: "i") A string used to label this rule. (Default: "img") A string used to label this rule. (Default: "list") A string used to label this rule. (Default: "quote") A string used to label this rule. (Default: "s") A string used to label this rule. (Default: "size") A string used to label this rule. (Default: "u") A string used to label this rule. (Default: "url") Applies to Rules? Applies to the function rules? Attributes Available Since:</em> Rulebox (1.0.0) Available Since:</em> Rulebox for Python (1.0.0) Available Since:</em> SUIT (2.0.0) BBCode Basic Usage Brandon Evans Chris Santiago (Faltzer) Item Logo N/A No PHP Parse Parse - Click to toggle Peter Behr Powered by <a target="_blank" href="http://www.suitframework.com/">SUIT</a> SLACKS SUIT SVN St&eacute;phane Lemay The <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" ]"/>rules</a> containing the strings to search for. The <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" ]"/>rules</a> to use to transform the string. The <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" ]"/>rules</a> used to break up the string. The <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" ]"/>rules</a> used to determine how to add the string. The <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" ]"/>rules</a> used to specify how to walk through the tree. Update Yes walk Project-Id-Version: SUIT
Report-Msgid-Bugs-To: admin@brandonevans.org
POT-Creation-Date: 2007-08-02 18:01-0700
PO-Revision-Date: 2010-09-14 19:02-0500
Last-Translator: Brandon Evans <admin@brandonevans.org>
Language-Team: 
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Generated-By: Babel 0.9dev-r215
X-Poedit-Language: French
 2010-06-06: SUIT 2.0.0 et Rulebox 1.0.0 sont disponibles. Ces versions ont été les premières à être développées en utilisant SVN. Vous pouvez consulter l'historique des différences en consultant les révisions 25 à 142. 2010-07-31: SUIT 2.0.1 et Rulebox 1.1.0 sont disponibles. 2010-07-31: SUIT 2.0.1 et Rulebox 1.1.0 sont disponibles.  1.1.0 - La variable statique "delimiter" a été ajoutée. <a href="[url controller="root" action="template" templatefile="docs" parameter1="url" ]"/>Retour à l'adresse de l'article.</a> <a href="[url controller="root" action="template" templatefile="slacks" ]"/>SLACKS</a> reçoit un journal généré par SUIT, qui contient de l'information sur le fonctionnement de la fonction execute, et le formatte pour montrer à l'utilisateur comment le modèle a été transformé. Afin de permettre à SLACKS d'accéder à ce journal, au lieu d'appeler execute et d'afficher le résultat, vous devez exécuter le segment suivant. <a href="[url controller="root" action="template" templatefile="tryit" ]"/>Essayez-Le</a> est une des options les plus interactives du site. Elle peut être utilisée pour tester certains ensembles de règles directement en ligne. Sélectionner une Règle, et écrivez un modèle devant être transformé par lui. Une syntaxe plus <a href="[url controller="root" action="template" templatefile="docs" parameter1="comparison" ]"/>claire</a>. Une <a href="[url controller="root" action="template" templatefile="download" parameter1="rulebox" ]"/>Règle</a> qui contient plusieurs ensemble de règles pour transformer votre modèle.À REVOIR: Règle vs Rulebox Une chaîne utilisée pour identifier cette règle. (Défaut: "align") Une chaîne utilisée pour identifier cette règle. (Défaut: "b") Une chaîne utilisée pour identifier cette règle. (Défaut: "code") Une chaîne utilisée pour identifier cette règle. (Défaut: "color") Une chaîne utilisée pour identifier cette règle. (Défaut: "email") Une chaîne utilisée pour identifier cette règle. (Défaut: "font") Une chaîne utilisée pour identifier cette règle. (Défaut: "i") Une chaîne utilisée pour identifier cette règle. (Défaut: "img") Une chaîne utilisée pour identifier cette règle. (Défaut: "list") Une chaîne utilisée pour identifier cette règle. (Défaut: "quote") Une chaîne utilisée pour identifier cette règle. (Défaut: "s") Une chaîne utilisée pour identifier cette règle. (Défaut: "size") Une chaîne utilisée pour identifier cette règle. (Défaut: "u") Une chaîne utilisée pour identifier cette règle. (Défaut: "url") S'applique aux Règles? S'applique aux règles de fonction? Attributs Disponible depuis:</em> Rulebox (1.0.0) Disponible depuis:</em> Rulebox for Python (1.0.0) Disponible depuis:</em> SUIT (2.0.0) BBCode Usage de base Brandon Evans Chris Santiago (Faltzer) Item Logo N/D Non PHP Analyser Analyser - Cliquez pour échanger Peter Behr Propulsé par <a target="_blank" href="http://www.suitframework.com/">SUIT</a> SLACKS SUIT SVN St&eacute;phane Lemay Les <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" ]"/>règles</a> contenant les chaînes recherchées. Les <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" ]"/>règles</a> à utiliser pour transformer les chaînes. Les <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" ]"/>règles</a> utilisées pour délimiter la chaîne. Les <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" ]"/>règles</a> utilisées pour déterminer comment ajouter la chaîne. Les <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" ]"/>règles</a> utilisées pour spécifier comment traverser l'arbre. Mise à jour Oui marche 