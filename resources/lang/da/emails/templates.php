<?php

// resources/lang/da/email_templates.php

return [
    'mailserver-test' => [
        'subject' => 'Test-mail fra {{ domain }}',
        'html_template' => '<p><strong>Testmail</strong></p><p>Dette er en testmail sendt fra {{ domain }}</p><p>SMTP-serveren er konfigureret korrekt!</p>',
        'text_template' => "Testmail\nThis is a test-mail sent from {{ domain }}\n\nThe SMTP-server is configured correctly!",
    ],
    'user-invitation' => [
        'subject' => 'Du er blevet inviteret til Datas',
        'html_template' => '<p>Hej {{user.first_name}},</p><p>Du er blevet inviteret til at oprette en konto på {{app.name}}.</p><p>For at acceptere invitationen og komme i gang, klik på linket nedenfor:</p><p><br></p><p>Har du spørgsmål? Kontakt os på {{email}}.</p><p>Vi glæder os til at byde dig velkommen! </p><p><br></p><p>Venlig hilsen,</p><p>{{app.name}}</p>',
        'text_template' => 'Du er blevet inviteret',
    ],
    'admin-invitation' => [
        'subject' => 'Du er blevet inviteret som administrator',
        'html_template' => '<p>Hej {{user.first_name}},</p><p>Du er blevet inviteret til at være administrator for {{app.name}}.</p><p>Som administrator får du adgang til at administrere indstillinger, brugere og indhold på platformen.</p><p>For at acceptere din invitation og komme i gang, klik på linket nedenfor.</p><p><br></p><p>Venlig hilsen,</p><p>{{app.name}}</p>',
        'text_template' => 'Du er blevet inviteret',
    ],
    'user-activation' => [
        'subject' => 'Aktivér din konto hos {{app.name}}',
        'html_template' => '<p>Hej {{user.first_name}}</p><p><br></p><p>Tak for din registrering på {{app.name}}! For at fuldføre oprettelsen af din konto, skal du aktivere den ved at klikke på linket nedenfor:</p><p><br></p><p>Hvis du ikke har oprettet en konto, kan du ignorere denne e-mail.</p><p>Har du spørgsmål? Kontakt os på {{email}}.</p><p><br></p><p>Venlig hilsen,</p><p>{{app.name}}</p>',
        'text_template' => 'Aktiver nu',
    ],
    'reset-password' => [
        'subject' => 'Anmodning om gendannelse af kodeord',
        'html_template' => '<p>Kære {{user.first_name}},</p><p>Vi har modtaget en anmodning om at nulstille dit kodeord hos {{app.name}}. </p><p>For at fortsætte med gendannelsen, klik venligst på linket nedenfor:</p><p><br></p><p>Hvis du ikke har anmodet om at nulstille dit kodeord, bedes du ignorere denne e-mail.</p><p><br></p><p>Hvis du har brug for yderligere assistance, er du velkommen til at kontakte os på {{email}}</p><p><br></p><p>Venlig hilsen,</p><p>{{app.name}}</p>',
        'text_template' => 'Gendan kodeord',
    ]
];
