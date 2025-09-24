# **Specifiche funzionali — “Dashboard GSC \+ Timeline Attività”**

## **1\) Obiettivo**

Un’unica dashboard che mostri l’andamento giornaliero di **Clicks** e **Impressions** da Google Search Console (GSC) e sovrapponga una **timeline di attività SEO** (punti/etichette per “Aree” e “Attività”) con filtri, personalizzazione e gestione completa (aggiunta, modifica, cancellazione) delle attività.

Riferimento base UI: campi per origine dati, comandi e filtri (URL/Upload, offset marker, etichette eventi, serie Impressions, PNG export; filtro data e multi-selezione Area).

---

## **2\) Ambito (Scope)**

* **Incluso**

  * Recupero **timeseries giornaliera** da GSC via API (Clicks, Impressions; opzionale CTR, Position).

  * **Overlay eventi** derivati dalle attività interne.

  * **Filtri** per intervallo data e Aree; **toggle etichette** e **toggle serie Impressions**; regolazione **offset** verticale dei marker.

  * **Gestione attività**: CRUD, con campi e convalide (vedi §5).

  * **Personalizzazione**: tema chiaro/scuro, dimensione font, colori serie/aree, preset; **import/export impostazioni**; persistenza per-utente. (La versione allegata mostra palette predefinite per serie/aree).

  * **Export grafico** in PNG.

* **Escluso**

  * Dettagli implementativi (librerie, stack, pattern).

  * Segmentazioni per query/pagina/paese (potranno essere estensioni future).

  * Multi-proprietà contemporanee (si gestisce **una proprietà per volta**).

---

## **3\) Tipi di dato (modello concettuale)**

### **3.1 GSC — serie giornaliera**

* `date`: data (timezone coerente con la proprietà GSC).

* `clicks`: numero intero ≥ 0\.

* `impressions`: numero intero ≥ 0\.

* `ctr`: numero \[0..1\] opzionale.

* `position`: numero reale opzionale.

La base HTML originale normalizza i CSV con queste colonne e filtra per data; la piattaforma proprietaria riceverà gli stessi **campi logici** dalla vostra integrazione API.

### **3.2 Attività (timeline)**

* `id`: ID univoco.

* `data_inizio`: data di inizio dell’evento (obbligatoria)  
* `data_fine`: (non obbligatoria)

* `area`: stringa (es. “SEO On-page”, “Magazine”…).

* `title`: titolo/oggetto attività (equivalente a “Attività”).

* Campi opzionali (se utili al business): `stato`, `note`.

* Metadati: `created_by`, `created_at`, `updated_at`, `updated_by`.

La versione allegata utilizza “Date | Area | Attività | Periodo | Stato | Note” e visualizza l’evento come **marker a diamante** con testo opzionale.

### **3.3 Preferenze utente (personalizzazione)**

* `theme`: `dark`/`light`.

* `font_size`: intero (p.es. 10–20).

* `series_colors`: mappa `{Clicks, Impressions}`.

* `area_colors`: mappa `{area → colore}`; inizializzare con default e arricchire dinamicamente quando si creano nuove Aree.

---

## **4\) Flussi utente**

### **4.1 Caricamento dati**

1. L’utente seleziona:

   * **Intervallo date** (da / a).

   * (Facoltativo) **Aree** da includere (multi-selezione).

2. La piattaforma interroga GSC API per ottenere la serie giornaliera (dimensione `date`, metriche `clicks`, `impressions`; coerente con i filtri).

3. La piattaforma interroga l’archivio **Attività** interno (vedi §5) filtrando su date/Aree selezionate.

4. La dashboard **renderizza**:

   * **Linea Clicks** (asse Y sinistro) e **linea Impressions** (asse Y destro, visibile solo se l’utente abilita “Serie Impressions”).

   * **Marker delle attività** sulle date corrispondenti; ogni marker appartiene a un’**Area** (colore associato) e ha **label testuale** opzionale (abilitabile/disabilitabile via toggle “Etichette eventi”).

### **4.2 Interazioni sul grafico**

* **Offset marker** (slider %): alza/abbassa la quota dei marker rispetto alla linea Clicks per evitare sovrapposizioni. Aggiornamento in tempo reale.

* **Etichette eventi**: mostra/nasconde i testi accanto ai marker.

* **Serie Impressions**: mostra/nasconde la linea delle impression.

* **Esporta PNG**: scarica l’immagine del grafico.

* **Responsività**: il grafico si adatta al contenitore (desktop e laptop); tooltips con data e valori.

### **4.3 Filtri**

* **Intervallo date**: limita sia serie GSC sia attività. Pulsante **Applica filtri** aggiorna il grafico.

* **Aree (multi)**: mostra solo i marker delle aree selezionate e popola il selettore con le Aree presenti a sistema.

### **4.4 Personalizzazione**

* **Tema chiaro/scuro** e **font size** influenzano cromie, griglie, testi del grafico.

* **Colori serie** (Clicks/Impressions) e **Colori Aree** personalizzabili; la palette di default è come da documento allegato.

* **Preset** (p.es. Default/Brand/High-Contrast/Stampa) e **Import/Export impostazioni** (JSON) come opzione per portabilità delle preferenze (per-utente).

* **Persistenza** preferenze (per-utente) — a livello piattaforma.

---

## **5\) Gestione Attività (CRUD)**

### **5.1 Requisiti funzionali**

* **Aggiunta**: form con campi obbligatori `date`, `area`, `title`; opzionali `periodo`, `stato`, `note`. Convalide:

  * `date` deve ricadere nell’intervallo calendario gestito dalla proprietà GSC.

  * `area` deve esistere o potersi creare al volo (nuove Aree diventano disponibili nei filtri e in colorazione).

* **Modifica**: aggiornamento dei campi; riflesso immediato in grafico.

* **Cancellazione**: hard delete (o soft-delete se preferite audit log).

* **Ricerca/Lista**: tabellare, con ordinamenti (data desc di default), filtro per Area, ricerca per testo nel titolo/note.

* **Selezione Aree**: repository delle Aree con gestione colore di default e **override** da preferenze utente.

* **Stato**: opzionale (es. “Da fare”, “In corso”, “Completato”…); non influisce sulla renderizzazione oltre eventuali badge nei tooltip.

* **Bulk import/export** delle attività in CSV compatibile con i campi di cui sopra (opzionale, utile per onboarding).

Il documento allegato già prevede questi campi nelle note sul formato e nel template di esempio.

### **5.2 Comportamento sul grafico**

* Ogni attività produce **un marker** (simbolo coerente per tutte; colore in base all’**Area**).

* Testo del marker \= `title` (mostrato solo se **Etichette eventi** è attivo).

* Tooltip dell’evento: data, **Area**, `title` \+ (se presenti) `periodo`, `stato`, `note`.

---

## **6\) Requisiti di integrazione dati**

### **6.1 GSC (API)**

* Endpoint/contract interno che ritorna **serie giornaliera** per:

  * **Proprietà** (site o domain property), **date\_start**, **date\_end**.

  * Metriche: `clicks`, `impressions` (obbligatorie), `ctr`, `position` (opzionali).

* **Granularità**: `day`.

* **Timezone**: definita a livello proprietà e coerente fra serie e attività.

* **Errori**: comunicare problemi rete/permessi/rate-limit; mostrare messaggi chiari all’utente (stato vuoto e retry).

Nella versione file, il caricamento avviene da CSV/Sheets e viene “applicato” con un `refresh()` che ridisegna il grafico dopo ogni modifica controlli. Replicate lo stesso **contratto funzionale** lato API, cioè: quando la serie GSC è presente, il grafico può essere costruito/aggiornato.

### **6.2 Archivio Attività**

* API interne per **list**, **create**, **update**, **delete**.

* Filtro per intervallo date e per Set di Aree.

* Restituzione delle Aree effettivamente presenti per popolazione del filtro e della mappa colori.

---

## **7\) Rendering del grafico (comportamento richiesto)**

* **Serie “Clicks”**: linea principale (asse Y sinistro). **Serie “Impressions”**: linea secondaria (asse Y destro) disattivabile dall’utente.

* **Marker Attività**:

  * Simbolo uniforme (diamante) con **colore per Area**; etichetta testuale opzionale.

  * **Offset marker** (slider % 2–25) sposta i punti verso l’alto rispetto al valore Clicks del giorno per non sovrapporsi alla linea.

* **Legenda**: Clicks/Impressions \+ Aree.

* **Tooltip**:

  * Punti delle linee: data formattata \+ valore;

  * Punti attività: data \+ Area \+ titolo (ed eventuali dettagli).

* **Responsive**: il grafico si adatta al contenitore e mantiene leggibilità delle etichette.

* **Export**: pulsante “Esporta PNG” salva l’immagine del grafico.

---

## **8\) Personalizzazione & Preset**

* **Tema**: dark/light (influenza carta, plot, griglia, testi).

* **Font size**: range intero (p.es. 10–20) applicato a testi del grafico.

* **Colori serie**: Clicks/Impressions (default: ciano/rosso come da file).

* **Colori aree**: mappa area→colore con default pre-popolati (“SEO Tool”, “Magazine”, “SEO On-page”, “SEO Off-page”, “Altro”).

* **Preset**: set di combinazioni tema+font+palette (almeno: Default, Brand, High-Contrast, Stampa).

* **Import/Export impostazioni** (JSON) per condivisione/backup preferenze.

* **Persistenza** a livello utente (profilo/DB o storage utente della piattaforma).

---

## **9\) UX di supporto**

* **Stati vuoti**: messaggi chiari se mancano dati GSC o Attività; suggerimenti su come collegare/creare dati.

* **Indicatori di caricamento** sui fetch.

* **Messaggi d’errore** non tecnici (permessi mancanti, range troppo ampio, ecc.).

* **Demo mode** (opzionale) per QA: carica una serie sintetica e 3-4 attività di esempio, come nel file allegato.

---

## **10\) Accessibilità & i18n**

* Etichette form e controlli **navigabili da tastiera**.

* Contrasto adeguato in entrambe le modalità (dark/light).

* Testi/UI in **italiano**, predisporre chiavi per traduzione futura.

---

## **11\) Prestazioni**

* Tempo di primo rendering con dataset tipico (\< 3–5 anni di dati) entro limiti UX accettabili.

* **Caching** lato server per richieste identiche (stesso property/date-range).

* Gestire **rate-limits** GSC con backoff lato server e comunicazioni utente.

---

## **12\) Sicurezza & permessi**

* Accesso a GSC tramite **account e permessi** definiti dall’organizzazione.

* **Scope minimo** necessario per lettura dati.

* Attività: permessi per **visualizzare** vs **modificare** vs **cancellare**; audit log consigliato.

---

## **13\) Criteri di accettazione (estratto)**

1. **Recupero dati GSC**

   * Dato un intervallo date valido, la dashboard mostra **Clicks** (sempre) e **Impressions** (se attiva l’opzione).

2. **Filtri**

   * Cambiando data/Aree e premendo **Applica filtri**, il grafico si aggiorna coerentemente.

3. **Attività**

   * Creazione/modifica/cancellazione riflettono nuovi marker; le **etichette** compaiono/scompaiono con l’apposito toggle.

4. **Offset**

   * Muovendo lo slider **Offset marker**, l’altezza dei marker cambia in percentuale rispetto alla serie Clicks del giorno.

5. **Personalizzazione**

   * Cambiando **tema** o **font size**, la cromia/scala testi del grafico si aggiornano.

   * Modificando i **colori** (serie/aree), i nuovi colori si riflettono nei tracciati e rimangono persistenti per l’utente.

6. **Export**

   * Il pulsante **Esporta PNG** genera un file immagine del grafico.

7. **Stati vuoti/errore**

   * Se l’API restituisce 0 righe o errore controllabile, la UI mostra uno stato vuoto o messaggio e resta utilizzabile.

