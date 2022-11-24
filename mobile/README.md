# Jeu 2048.

<!-- TABLE OF CONTENTS -->
# Sommaire
  <ol>
    <li>
      <a href="#description-du-projet">Description du projet</a>
    </li>
    <li>
      <a href="#commencer">Commencer</a>
      <ul>
        <li><a href="##prérequis">Prérequis</a></li>
        <li><a href="##installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#utilisation">Utilisation</a></li>
  </ol>

# Description du projet

Le projet reprend les bases du jeu 2048. 
Notre jeu démarre sur une grille NxN (n est choisi par l'utilisateur). 
Le joueur commence avec deux cases remplies de deux 2.
Le joueur peut décider de déplacer les cases soit vers le haut, le bas, la gauche ou la droite. 
Si deux cases adjacentes sont déplacées dans le même sens et portent un nombre identiques, elles sont additionnées. 
Lors d'un déplacement, une case de la grille prise aléatoirement est remplie avec un chiffre 2 seulement si le déplacement modifie la grille.

Le jeu se termine en victoire si le joueur arrive à former une case avec la valeur 2 puissance 11+(n-4).
L'échec a lieu si l'ensemble des cases sont remplies sans possibilité d'en fusionner au moins deux lors d'un déplacement.

# Commencer

## Prérequis

- flutter (lien : https://docs.flutter.dev/get-started/install).

## Installation

1. Dans un terminal, placez-vous dans le dossier du projet et tapez la commande suivante :
```sh
flutter run
```

2. La commande va lancer l'application.
    
# Utilisation

  Pour jouer à notre jeu, le joueur doit réaliser des clics allant soit :
  - vers le haut
  - vers le bas
  - vers la gauche
  - vers la droite

## Crédits
   
   RIBAUT THOMAS : https://github.com/Dopey990.  
   DEREZE Alexandra : https://github.com/Alex221100
