<?php

return [
    'title' => 'Spesa ricorrente',
    'name' => 'Nome',
    'description' => 'Descrizione',
    'trigger' => [
        'title' => 'Innesco',
        'description' => "L'innesco è usato per determinare se e quando un nuovo addebito dovrebbe essere creato.",
        'temporal' => [
            'title' => 'Data e ora',
            'cron' => 'Espressione cron',
        ],
        'email' => [
            'title' => 'Email ricevuta',
            'subject' => "Oggetto dell'email",
        ],
    ],
    'charge_data_provider' => [
        'title' => "Lettura dati dell'addebito",
        'description' => "Questa sezione è usata per indicare come e/o dove prelevare l'importo e la data dell'addebito.",
        'user_defined' => [
            'title' => "Definito dall'utente",
            'amount' => 'Importo',
            'charged_at' => 'Data di addebito',
        ],
        'email_link_scraping' => [
            'title' => "Preleva da una pagina web il cui link è contenuto nell'email",
            'link_xpath' => "XPath del link",
            'amount_xpath' => "XPath dell'importo",
            'charged_at_xpath' => 'XPath della data di addebito',
            'charged_at_format' => 'Formato della data di addebito',
            'date_locale' => 'Localizzazione della data di addebito',
            'click_before_xpath' => "XPath dell'elemento su cui fare click prima di prelevare i dati",
        ],
        'email_attachment_pdf' => [
            'title' => "Preleva da un allegato PDF dell'email",
            'index' => "Indice dell'allegato",
            'page' => 'Numero della pagina',
            'amount_pos_x' => "Coordinata X dell'importo",
            'amount_pos_y' => "Coordinata Y dell'importo",
            'amount_width' => "Larghezza dell'importo",
            'amount_height' => "Altezza dell'importo",
            'charged_at_pos_x' => "Coordinata X della data di addebito",
            'charged_at_pos_y' => "Coordinata Y della data di addebito",
            'charged_at_width' => "Larghezza della data di addebito",
            'charged_at_height' => "Altezza della data di addebito",
            'charged_at_format' => 'Formato della data di addebito',
            'date_locale' => 'Localizzazione della data di addebito',
        ],
        'email_body_scraping' => [
            'title' => "Preleva dal contenuto dell'email",
            'amount_xpath' => "XPath dell'importo",
            'charged_at_xpath' => "XPath dell'importo",
            'charged_at_format' => 'Formato della data di addebito',
            'date_locale' => 'Localizzazione della data di addebito',
        ],
    ],
    'created_at' => 'Data di creazione',
    'updated_at' => 'Data di aggiornamento',
];
