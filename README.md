# sowrs
<h1>Doc</h1>
<p>
Bienvenue dans la doc de sowrs.com. Cette doc vous présentera l'essentiel
de la gestion qui est mise en place dans le projet. 
</p>
<h2>Technologies utilisées : </h2>
<ul>
<li>PHP / Symfony</li>
<li>Javascript / JQuery</li>
<li>Bootstrap</li>
<li>API externe (matching)</li>
<li>Serveur : Linux / Ubuntu 20.04</li>
<li>Base de données : MySQL / MariaDB</li>
</ul>
<p>
Le projet est un jobboard orienté sens au travail, il permet de mettre en relation
les candidats et les entreprises qui partagent des valeurs communes. Une API
de matching est implémenté pour gérer ce matching.
</p>
<h2>Le serveur</h2>
<p>
Concernant le serveur de production, il s'agit d'un VPS chez OVH avec une 
distribution Linux / Ubuntu 20.04. Les identifiants ont été restitués au chef de
projet.
</p>
<h3>Le répertoire du projet</h3>
<p>
Rendez-vous dans le répertoire du projet avec : cd /var/www/html/sowrs/<br />
Les droits d'accès y sont gérés en 770, le user propriétaire est "ubuntu", le groupe
est "www-data" et les autres n'ont aucun droit pour sécuriser le projet.
</p>
<h3>Le VirtualHost</h3>
<p>
Voici le chemin du virtual host : /etc/apache2/sites-available/sowrs.com.conf<br />
L'adresse de l'admin a été changée et devra probablement être modifiée par la suite.
La configuration est basique et pointe vers le répertoire "public" du projet. 
La réécriture d'URL est également paramétrée.
</p>
<h3>Le pare feu</h3>
<p>
Le pare feu utilisé est "ufw" et permet les connexions en HTTPS uniquement. Les 
connexions en HTTP étant redirigées par le SSL.
</p>
<h3>Le certificat SSL</h3>
<p>
Le certificat SSL est généré par "certbot", c'est un certificat Let's Encrypt 
gratuit et qui se régénère automatiquement en challengeant les domaines.
</p>
<h2>À savoir</h2>
<p>
Le projet sowrs.com a vu plusieurs stagiaires en développemment web travailler sur son 
développement et sa conception. Il est donc probable que certains développements 
semblent tiérs par les cheveux, ou pas du tout optimisés. Pour exemple, vous verrez 
que le CSS est dans différents fichiers, ce qui est bien, mais que ces fichiers sont 
tous appelés sur le template de base... De plus les propriétés sont complètements 
mélangées dans les différents fichiers. Un travail de réorganisation était prévu, mais 
pas prioritaire pour l'entreprise. 
</p>
<p>
L'i18n n'est pas faite (pas de priorité pour l'entreprise), il faut implémenter 
les méthodes de traduction dans tous les fichiers du projet.
</p>
<h3>Javascript</h3>
<p>
Le javascript est géré dans des fichiers séparés dans le répertoire /public/js/. 
Néanmoins, une grande partie est également géré directement dans les template. N'hésitez
pas à aller explorer également les vues donc.
</p>
<h3>L'API</h3>
<p>
L'API de matching a été développé par un prestataire externe, elle n'est pas géré 
directement dans le projet et les accès sont difficiles à avoir. De plus, en local les 
appels de l'API sont bugé, car votre BDD locale ne correspond pas avec celle de l'API, 
il faut être vigilant et ne pas corrompre la BDD de l'API par des appels hasardeux, 
sinon les appels en production seront bugés également. 
</p>
<p>
Les méthodes d'appel à l'API sont présents dans le répertoire src/Controller/Api/. 
</p>