# Sito immobiliare: ora è un modulo dello starter

Il modulo immobiliare (ultima versione di Di Guida Real Estate, con lo stile del tema) è **dentro lo starter** e si accende da
*Aspetto → Personalizza → Opzioni → Moduli (CPT) → Immobili*. Non serve più copiare file dal sito di origine.

Cosa porta, come funziona e cosa succede quando si spegne: vedi `GUIDA-TEMA.md`, sezione "Moduli (CPT)". Codice: `inc/modules.php`
(registro dei moduli) e `inc/modules/immobili/` (CPT, tassonomie, filtri, PDF con Dompdf, campi ACF, scheda, immobile di esempio).

## Cosa resta da fare per un nuovo sito immobiliare
1. Accendere il modulo Immobili; la pagina **Immobili** e il modulo "Richiedi informazioni" si creano da soli.
2. Aggiungere i termini della zona (Comune › quartiere), i consulenti (Immobili → Consulenti, con foto e contatti) e gli immobili.
3. Costruire la home con i blocchi **Hero home** e **Immobili in evidenza** (e i blocchi generici dello starter: `sezione-voci`, `recensioni`, `testo-affiancato`, `team`, `mappa`).
4. Per le campagne pubblicitarie (landing "iniziative") non c'è un modulo: si fa solo quando serve, personalizzata sul progetto.
5. Controllo finale: filtri combinati, paginazione, immobili senza prezzo/superficie, scheda su mobile, PDF, mail e Flamingo, `npm run build`.
