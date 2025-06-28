┌─────────────────────────────────────────────────────────────────────────────┐
│                            HÉRITAGE PRINCIPAL                               │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│    User (Single Table Inheritance)                                          │
│    ├── Politicien                                                           │
│    └── [autres types d'utilisateurs]                                        │
│                                                                             │
│    Delit (Single Table Inheritance)                                         │
│    ├── DelitFinancier                                                       │
│    ├── DelitFraude                                                          │
│    ├── DelitVol                                                             │
│    └── [autres types de délits]                                             │
│                                                                             │
│    Partenaire (Single Table Inheritance)                                    │
│    ├── PartenairePhysique                                                   │
│    └── PartenaireMoral                                                      │
│                                                                             │
│    Document (Single Table Inheritance)                                      │
│    ├── DocumentImage                                                        │
│    ├── DocumentVideo                                                        │
│    ├── DocumentAudio                                                        │
│    └── DocumentFichier                                                      │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                              USER                               │
├─────────────────────────────────────────────────────────────────┤
│ • id (PK)                                                       │
│ • email (UNIQUE)                                                │
│ • password                                                      │
│ • roles (JSON)                                                  │
│ • firstName                                                     │
│ • lastName                                                      │
│ • dateCreation                                                  │
│ • derniereConnexion                                             │
│ • estActif                                                      │
│ • discr (discriminator: 'user', 'politicien')                   │
│                                                                 │
│ 🆕 NOUVEAUX CHAMPS AJOUTÉS:                                     │
│ • telephone                                                     │
│ • adresse                                                       │
│ • dateNaissance                                                 │
│ • nationalite                                                   │
│ • profession                                                    │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                           POLITICIEN                            │
├─────────────────────────────────────────────────────────────────┤
│ • Hérite de tous les champs User                                │
│ • parti_id (FK → Parti)                                         │
│ • biographie (TEXT)                                             │
│ • photo                                                         │
│                                                                 │
│ 🆕 NOUVEAUX CHAMPS AJOUTÉS:                                     │
│ • fonction                                                      │
│ • dateEntreePolitique                                           │
│ • mandatActuel                                                  │
│ • circonscription                                               │
│ • salaireMensuel                                                │
│ • declarationPatrimoine (JSON)                                  │
│ • casierJudiciaire                                              │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                              DELIT                              │
├─────────────────────────────────────────────────────────────────┤
│ • id (PK)                                                       │
│ • type (SIMPLE_ARRAY)                                           │
│ • description (TEXT)                                            │
│ • date                                                          │
│ • lieu_id (FK → Lieu)                                           │
│ • discr (discriminator: 'delit', 'financier', 'fraude', 'vol')  │
│                                                                 │
│ 🆕 NOUVEAUX CHAMPS AJOUTÉS:                                     │
│ • statut (EN_COURS, JUGE, CLASSE, PRESCRIT)                     │
│ • gravite (MINEURE, MOYENNE, GRAVE, TRES_GRAVE)                 │
│ • dateDeclaration                                               │
│ • numeroAffaire                                                 │
│ • procureurResponsable                                          │
│ • temoinsPrincipaux (JSON)                                      │
│ • preuvesPrincipales (JSON)                                     │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         DELITFINANCIER                          │
├─────────────────────────────────────────────────────────────────┤
│ • Hérite de tous les champs Delit                               │
│                                                                 │
│ 🆕 CHAMPS SPÉCIFIQUES FINANCIERS:                               │
│ • montantEstime (BIGINT en centimes)                            │
│ • devise                                                        │
│ • methodePaiement                                               │
│ • compteBancaire                                                │
│ • paradissFiscal                                                │
│ • blanchimentSoupçonne (BOOLEAN)                                │
│ • institutionsImpliquees (JSON)                                 │
│ • circuitFinancier (TEXT)                                       │
│ • montantRecupere (BIGINT)                                      │
│ • argentRecupere (BOOLEAN)                                      │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                           DELITFRAUDE                           │
├─────────────────────────────────────────────────────────────────┤
│ • Hérite de tous les champs Delit                               │
│                                                                 │
│ 🆕 CHAMPS SPÉCIFIQUES FRAUDE:                                   │
│ • typeFraude (ELECTORALE, FISCALE, DOCUMENTAIRE, SOCIALE)       │
│ • documentsManipules (JSON)                                     │
│ • nombreVictimes                                                │
│ • prejudiceEstime (BIGINT)                                      │
│ • methodeFraude (TEXT)                                          │
│ • complicesIdentifies (JSON)                                    │
│ • systemeInformatique (BOOLEAN)                                 │
│ • fraudeOrganisee (BOOLEAN)                                     │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                            DELITVOL                             │
├─────────────────────────────────────────────────────────────────┤
│ • Hérite de tous les champs Delit                               │
│                                                                 │
│ 🆕 CHAMPS SPÉCIFIQUES VOL:                                      │
│ • typeVol (FONDS_PUBLICS, MATERIEL, INFORMATIONS, IDENTITE)     │
│ • biensDerobes (JSON)                                           │
│ • valeurEstimee (BIGINT)                                        │
│ • methodeDerriereVol (TEXT)                                     │
│ • lieuStockage                                                  │
│ • biensRecuperes (BOOLEAN)                                      │
│ • pourcentageRecupere                                           │
│ • receleurs (JSON)                                              │
│ • volPremedite (BOOLEAN)                                        │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                              PARTI                              │
├─────────────────────────────────────────────────────────────────┤
│ • id (PK)                                                       │
│ • nom (UNIQUE)                                                  │
│ • couleur                                                       │
│ • slogan                                                        │
│ • logo                                                          │
│ • dateCreation                                                  │
│ • description (TEXT)                                            │
│                                                                 │
│ 🆕 NOUVEAUX CHAMPS AJOUTÉS:                                     │
│ • siteWeb                                                       │
│ • adresseSiege                                                  │
│ • telephoneContact                                              │
│ • emailContact                                                  │
│ • presidentActuel                                               │
│ • orientationPolitique                                          │
│ • budgetAnnuel (BIGINT)                                         │
│ • nombreAdherents                                               │
│ • partiActif (BOOLEAN)                                          │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                           PARTENAIRE                            │
├─────────────────────────────────────────────────────────────────┤
│ • id (PK)                                                       │
│ • nom                                                           │
│ • email                                                         │
│ • telephone                                                     │
│ • adresse                                                       │
│ • siteWeb                                                       │
│ • notes (TEXT)                                                  │
│ • dateCreation                                                  │
│ • niveauRisque (FAIBLE, MOYEN, ELEVE)                          │
│ • discr (discriminator: 'physique', 'moral')                   │
│                                                                 │
│ 🆕 CHAMPS COMMUNS PARTENAIRES:                                  │
│ • ville                                                         │
│ • codePostal                                                    │
│ • pays                                                          │
│ • datePremiereCollaboration                                     │
│ • nombreDelitsImplique                                          │
│ • estActif (BOOLEAN)                                            │
│ • commentairesInternes (TEXT)                                   │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                       PARTENAIREPHYSIQUE                        │
├─────────────────────────────────────────────────────────────────┤
│ • Hérite de tous les champs Partenaire                          │
│                                                                 │
│ 🆕 CHAMPS SPÉCIFIQUES PERSONNE PHYSIQUE:                        │
│ • prenom                                                        │
│ • nomFamille                                                    │
│ • dateNaissance                                                 │
│ • lieuNaissance                                                 │
│ • nationalite                                                   │
│ • profession                                                    │
│ • numeroSecu                                                    │
│ • numeroCNI                                                     │
│ • adresseSecondaire                                             │
│ • telephoneSecondaire                                           │
│ • situationFamiliale                                            │
│ • personnesACharge                                              │
│ • niveauEtudes                                                  │
│ • casierJudiciaire (BOOLEAN)                                    │
│ • fortuneEstimee (BIGINT)                                       │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                        PARTENAIREMORAL                          │
├─────────────────────────────────────────────────────────────────┤
│ • Hérite de tous les champs Partenaire                          │
│                                                                 │
│ 🆕 CHAMPS SPÉCIFIQUES PERSONNE MORALE:                          │
│ • raisonSociale                                                 │
│ • formeJuridique (SA, SARL, SAS, ASSOCIATION, etc.)             │
│ • siret                                                         │
│ • numeroTVA                                                     │
│ • secteurActivite                                               │
│ • dirigeantPrincipal                                            │
│ • chiffreAffaires (BIGINT)                                      │
│ • nombreEmployes                                                │
│ • paysFiscal                                                    │
│ • dateCreationEntreprise                                        │
│ • capitalSocial (BIGINT)                                        │
│ • actionnairePrincipal                                          │
│ • coteeEnBourse (BOOLEAN)                                       │
│ • filiales (JSON)                                               │
│ • licences (JSON)                                               │
│ • certifications (JSON)                                         │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                            DOCUMENT                             │
├─────────────────────────────────────────────────────────────────┤
│ • id (PK)                                                       │
│ • nom                                                           │
│ • chemin                                                        │
│ • dateCreation                                                  │
│ • description (TEXT)                                            │
│ • delit_id (FK → Delit)                                         │
│ • auteur_id (FK → Politicien)                                   │
│ • tailleFichier                                                 │
│ • discr (discriminator: 'image', 'video', 'audio', 'fichier')   │
│                                                                 │
│ 🆕 CHAMPS COMMUNS DOCUMENTS:                                    │
│ • niveauConfidentialite (PUBLIC, CONFIDENTIEL, SECRET)          │
│ • dateDeclassification                                          │
│ • sourceInformation                                             │
│ • personnesAutorisees (JSON)                                    │
│ • nombreConsultations                                           │
│ • derniereConsultation                                          │
│ • documentParent_id (FK → Document)                             │
│ • estArchive (BOOLEAN)                                          │
│ • checksum                                                      │
│ • motsCles (JSON)                                               │
│ • langueDocument                                                │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         DOCUMENTIMAGE                           │
├─────────────────────────────────────────────────────────────────┤
│ • Hérite de tous les champs Document                            │
│                                                                 │
│ 🆕 CHAMPS SPÉCIFIQUES IMAGE:                                    │
│ • formatImage (JPEG, PNG, GIF, TIFF, etc.)                      │
│ • largeur                                                       │
│ • hauteur                                                       │
│ • resolution                                                    │
│ • datePhoto                                                     │
│ • lieuPhoto                                                     │
│ • appareilPhoto                                                 │
│ • coordonneesGPS                                                │
│ • estRetouchee (BOOLEAN)                                        │
│ • logicielRetouche                                              │
│ • metadonneesExif (JSON)                                        │
│ • personnesIdentifiees (JSON)                                   │
│ • qualiteImage (EXCELLENTE, BONNE, MOYENNE, FAIBLE)             │
│ • thumbnailPath                                                 │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         DOCUMENTVIDEO                           │
├─────────────────────────────────────────────────────────────────┤
│ • Hérite de tous les champs Document                            │
│                                                                 │
│ 🆕 CHAMPS SPÉCIFIQUES VIDEO:                                    │
│ • formatVideo (MP4, AVI, MOV, WMV, etc.)                        │
│ • duree (en secondes)                                           │
│ • resolution                                                    │
│ • frameRate                                                     │
│ • codec                                                         │
│ • qualiteVideo (4K, HD, SD)                                     │
│ • avecSon (BOOLEAN)                                             │
│ • sousTitres (BOOLEAN)                                          │
│ • langueAudio                                                   │
│ • dateEnregistrement                                            │
│ • lieuEnregistrement                                            │
│ • materielEnregistrement                                        │
│ • personnesFilmees (JSON)                                       │
│ • timestampsImportants (JSON)                                   │
│ • thumbnailPath                                                 │
│ • urlStreamingExterne                                           │
│ • plateformeHebergement                                         │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         DOCUMENTAUDIO                           │
├─────────────────────────────────────────────────────────────────┤
│ • Hérite de tous les champs Document                            │
│                                                                 │
│ 🆕 CHAMPS SPÉCIFIQUES AUDIO:                                    │
│ • formatAudio (MP3, WAV, FLAC, AAC, etc.)                       │
│ • duree (en secondes)                                           │
│ • bitrate                                                       │
│ • frequenceEchantillonnage                                      │
│ • nombreCanaux (MONO, STEREO)                                   │
│ • qualiteAudio (LOSSLESS, HIGH, MEDIUM, LOW)                    │
│ • dateEnregistrement                                            │
│ • lieuEnregistrement                                            │
│ • materielEnregistrement                                        │
│ • personnesEnregistrees (JSON)                                  │
│ • transcriptionTexte (TEXT)                                     │
│ • transcriptionValidee (BOOLEAN)                                │
│ • languePrincipale                                              │
│ • motsClesAudio (JSON)                                          │
│ • niveauSonore                                                  │
│ • filtresAppliques (JSON)                                       │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                        DOCUMENTFICHIER                          │
├─────────────────────────────────────────────────────────────────┤
│ • Hérite de tous les champs Document                            │
│                                                                 │
│ 🆕 CHAMPS SPÉCIFIQUES FICHIER:                                  │
│ • typeFichier (CONTRAT, FACTURE, EMAIL, RAPPORT, etc.)          │
│ • formatFichier (PDF, DOC, XLS, TXT, etc.)                      │
│ • nombrePages                                                   │
│ • estSigneNumeriquement (BOOLEAN)                               │
│ • signataires (JSON)                                            │
│ • dateSignature                                                 │
│ • autoriteSignature                                             │
│ • numeroDocument                                                │
│ • versionDocument                                               │
│ • documentOriginal (BOOLEAN)                                    │
│ • contenuExtrait (TEXT)                                         │
│ • indexeRecherche (BOOLEAN)                                     │
│ • motsClesDocument (JSON)                                       │
│ • clausesImportantes (JSON)                                     │
│ • montantsMentionnes (JSON)                                     │
│ • personnesMentionnees (JSON)                                   │
│ • dateValidite                                                  │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                              LIEU                               │
├─────────────────────────────────────────────────────────────────┤
│ • id (PK)                                                       │
│ • adresse                                                       │
│ • ville                                                         │
│ • pays                                                          │
│ • codePostal                                                    │
│                                                                 │
│ 🆕 NOUVEAUX CHAMPS AJOUTÉS:                                     │
│ • latitude (DECIMAL)                                            │
│ • longitude (DECIMAL)                                           │
│ • typeEtablissement                                             │
│ • estPublic (BOOLEAN)                                           │
│ • niveauSecurite                                                │
│ • capaciteAccueil                                               │
│ • horaireAcces                                                  │
│ • responsableSecurite                                           │
│ • videoSurveillance (BOOLEAN)                                   │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                           COMMENTAIRE                           │
├─────────────────────────────────────────────────────────────────┤
│ • id (PK)                                                       │
│ • contenu (TEXT)                                                │
│ • dateCreation                                                  │
│ • dateModification                                              │
│ • delit_id (FK → Delit)                                         │
│ • commentaireParent_id (FK → Commentaire)                       │
│ • auteur_id (FK → Politicien)                                   │
│                                                                 │
│ 🆕 NOUVEAUX CHAMPS AJOUTÉS:                                     │
│ • estModere (BOOLEAN)                                           │
│ • scoreCredibilite (1-100)                                      │
│ • typeCommentaire (STANDARD, EXPERT, OFFICIEL)                  │
│ • domaineExpertise                                              │
│ • estPublic (BOOLEAN)                                           │
│ • nombreLikes                                                   │
│ • nombreDislikes                                                │
│ • estSignale (BOOLEAN)                                          │
│ • raisonSignalement                                             │
└─────────────────────────────────────────────────────────────────┘

User 1----* Politicien (héritage)
Politicien *----1 Parti
Politicien *----* Delit
Politicien 1----* Commentaire
Politicien 1----* Document (auteur)

Delit 1----* DelitFinancier (héritage)
Delit 1----* DelitFraude (héritage)
Delit 1----* DelitVol (héritage)
Delit 1----* Commentaire
Delit 1----* Document
Delit *----1 Lieu
Delit *----* Partenaire

Partenaire 1----* PartenairePhysique (héritage)
Partenaire 1----* PartenaireMoral (héritage)

Document 1----* DocumentImage (héritage)
Document 1----* DocumentVideo (héritage)
Document 1----* DocumentAudio (héritage)
Document 1----* DocumentFichier (héritage)
Document *----1 Document (parent - pour versioning)