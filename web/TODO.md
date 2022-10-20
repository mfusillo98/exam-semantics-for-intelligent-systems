# todo list

## Realizzazione Sito web pubblico. Prevede:
[x] Realizzazione file di view da template + rotte/controller con file separati per head, header, menu e footer 
[x] Controller per registrazione e login utenti, conferma via OTP email, recupero password con OTP via email (S)
[ ] View di visualizzazione dati personali utente, con modifica dati e richiesta cancellazione (invio email a floriano ,alert email) (S)
[ ] Ricerca di corsi di formazione nella piattaforma con possibilità di iscrizione (S)
- Pagina riepilogativa del corso stile edmodo/teams con materiale caricato dai docenti, calendario lezioni, e informazioni vari (S)
- Pagina del corso in cui è possibile vedere i “compiti” assegnati dai docenti con possibilità di caricamento di un file di consegna (S)

## Realizzazione “Pannello di controllo amministrazione”. Prevede:
[x] Realizzazione file di view da template + rotte/controller con file separati per head, header, menu e footer (M)
[x] Gestione CRUD degli admin (M)
[x] Gestione dei permessi di accesso alle varie pagine disponibili (M)
[ ] Watable con utenti iscritti ed esportazione in CSV (S)
[ ] CRUD docenti con generazione credenziali iniziali (S)
[x] CRUD dei corsi (descrizione, programma del corso, immagini, video) con range di date per la pre-iscrizione al corso ed eventuale costo di iscrizione (M)
[ ] Assegnazione corsi ai docenti (M)
- Possibilità di creare un calendario di lezioni programmate visibile ai corsisti (M)
- Pagina di report dei pagamenti ricevuti
- CRUD categorie dei contenuti del sito (M)
- CRUD contenuti del sito web (pagine/articoli) divisi in categorie (M)
- CRUD delle voci del menù (e sotto-menù) con possibilità di collegamento delle pagine create (rotte dinamiche) (M)

## Realizzazione “Pannello di controllo docente”: Prevede:
- Realizzazione file di view da template + rotte/controller con file separati per head, header, menu e footer
- Pagina login, cambio pw con controller e rotte
- Programmazione delle lezioni private con studenti (con sistema di invito via email)
- Tasto per creare la video-chat-room della piattaforma con Jitsi meet o con link Zoom
- Watable dei corsi assegnati 
- Pagina informativa dei corsi (durata delle lezioni, materiale caricato personalmente e quello di altri docenti, elenco dei corsisti, compiti assegnati e consegne)
- CRUD per ogni corso per task/compiti con data di scadenza e valutazione del materiale consegnato dallo studente 
- Possibilità di caricare file multimediali per ogni corso