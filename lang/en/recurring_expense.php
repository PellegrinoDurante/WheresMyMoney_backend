<?php

return [
    'name' => 'Name',
    'description' => 'Description',
    'trigger' => [
        'title' => 'Trigger',
        'description' => 'The trigger is used to determine if and when a new charge should be created.',
        'temporal' => [
            'title' => 'Date and time',
            'cron' => 'Cron expression',
        ],
        'email' => [
            'title' => 'Email received',
            'subject' => 'Email subject',
        ],
    ],
    'charge_data_provider' => [
        'title' => 'Data Reader',
        'description' => 'The Data Reader is used to read the amount and the charge ',
        'user_defined' => [
            'title' => 'Defined by the user',
            'amount' => 'Amount',
            'charged_at' => 'Charge date',
        ],
        'email_link_scraping' => [
            'title' => 'Read from a link in the email\'s body',
            'link_xpath' => 'Link element\'s XPath',
            'amount_xpath' => 'Amount element\'s XPath',
            'charged_at_xpath' => 'Charge date element\'s XPath',
            'charged_at_format' => 'Charge date\'s format',
            'date_locale' => 'Date\'s locale',
            'click_before_xpath' => 'Element\'s XPath to click before reading data',
        ],
        'email_attachment_pdf' => [
            'title' => 'Read from the email\'s attachment',
            'index' => 'Attachment\'s index',
            'page' => 'Page number',
            'amount_pos_x' => 'Amount\'s X coordinate',
            'amount_pos_y' => 'Amount\'s Y coordinate',
            'amount_width' => 'Amount\'s width',
            'amount_height' => 'Amount\'s height',
            'charged_at_pos_x' => 'Charge date\'s X coordinate',
            'charged_at_pos_y' => 'Charge date\'s Y coordinate',
            'charged_at_width' => 'Charge date\'s width',
            'charged_at_height' => 'Charge date\'s height',
            'charged_at_format' => 'Charge date\'s format',
            'date_locale' => 'Date\'s locale',
        ],
        'email_body_scraping' => [
            'title' => 'Read from the email\'s body',
            'amount_xpath' => 'Amount element\'s XPath',
            'charged_at_xpath' => 'Charge date element\'s XPath',
            'charged_at_format' => 'Charge date\'s format',
            'date_locale' => 'Date\'s locale',
        ],
    ],
    'created_at' => 'Creation date',
    'updated_at' => 'Update date',
];
