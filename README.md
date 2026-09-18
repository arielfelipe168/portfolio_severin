# Portfolio — Ossoungbemi Séverin Adewalé

Site portfolio dynamique développé en **PHP natif** (sans framework), intégrant un **espace d'administration sécurisé** permettant à l'utilisateur d'administrer l'intégralité de son contenu : profil, photo, expériences, formations, compétences, langues, outils, loisirs et engagements passés.

## 🌟 Fonctionnalités

- **Site Public :**
  - Design éditorial et moderne (Typographies Playfair Display & Inter).
  - Animations fluides au défilement et interface responsive (compatible mobile et ordinateur).
  - Affichage structuré du parcours professionnel, des compétences et des réalisations.

- **Espace d'Administration :**
  - Gestion CRUD complète (Création, Lecture, Mise à jour, Suppression) pour chaque section du CV.
  - Gestion du profil et téléversement de la photo de profil avec validation des fichiers.
  - Authentification sécurisée et protection contre les failles CSRF sur l'ensemble des formulaires.

- **Architecture Technique :**
  - Base de données **SQLite** autonome (aucun serveur MySQL nécessaire).
  - Aucune dépendance externe à installer (aucun besoin de Composer ou de build JS).

---

## 🛠️ Prérequis

- **PHP 8.0** ou supérieur.
- Extension PHP **`pdo_sqlite`** activée (activée par défaut sur la majorité des environnements PHP).

---

## 🚀 Démarrage rapide (en local)

1. Clonez ce dépôt sur votre machine :
   ```bash
   git clone [https://github.com/arielfelipe168](https://github.com/arielfelipe168)
   cd portfolio