# Mini projet Symfony : Billetterie de cinéma

Vous êtes chargé de développer une application de billetterie de cinéma pour une salle de cinéma locale. Voici les différentes entités à créer :

## Movie
Cette entité représente un film avec les attributs suivants :
- `id`: identifiant unique du film
- `title`: titre du film
- `description`: description du film
- `duration`: durée du film en minutes
- `release_date`: date de sortie du film
- `image`: lien vers une image du film

## Room
Cette entité représente une salle de cinéma avec les attributs suivants :
- `id`: identifiant unique de la salle
- `name`: nom de la salle
- `capacity`: capacité de la salle en nombre de sièges

## Screening
Cette entité représente une projection d'un film dans une salle avec les attributs suivants :
- `id`: identifiant unique de la projection
- `movie`: le film projeté
- `room`: la salle où a lieu la projection
- `start_time`: heure de début de la projection
- `end_time`: heure de fin de la projection
- `price`: prix d'une place pour cette projection

## Ticket
Cette entité représente un ticket pour une projection avec les attributs suivants :
- `id`: identifiant unique du ticket
- `screening`: la projection pour laquelle le ticket a été acheté
- `seat`: numéro de siège où se trouve le spectateur

Les fonctionnalités à implémenter sont les suivantes :
- Réaliser le CRUD pour Movie, Room et Screening
- Sur la page d'accueil, on souhaite avoir la liste des films en fonction des scéances du jour.
- Sur chaque film on souhaite avoir le titre, la description, la date de sortie, la durée, la description et en dessous la liste des séances.
- Lorsqu'on clique sur la séance on arrive sur une page où l'on peut acheter des tickets.
- On affichera également le nombre de places disponibles. 
- Lorsque c'est validé on retourne à la page d'accueil avec un message flash de succès.
- Si l'on dépasse le nombre de places disponibles, alors on affiche un message flash d'erreur.

Bonus: traduire le titre de la homepage, en ajoutant un CTA pour choisir la locale (FR/EN).
