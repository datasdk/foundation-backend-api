<?php
return [
    'mailserver-test' => [
        'subject' => 'Test email from {{ domain }}',
        'html_template' => '<p><strong>Testmail</strong></p><p>This is a test mail sent from {{ domain }}</p><p>The SMTP server is configured correctly!</p>',
        'text_template' => 'Testmail\nThis is a test-mail sent from {{ domain }}\n\nThe SMTP-server is configured correctly!',
    ],

    'user-invitation' => [
        'subject' => 'You have been invited to Datas',
        'html_template' => '<p>Hi {{first_name}},</p><p>You have been invited to create an account at {{company}}.</p><p>To accept the invitation and get started, click the link below:</p><p><br></p><p>If you have any questions, please contact us at {{email}}.</p><p>We look forward to welcoming you!</p><p><br></p><p>Best regards,</p><p>{{company}}</p>',
        'text_template' => 'You have been invited',
    ],

    'admin-invitation' => [
        'subject' => 'You have been invited as an administrator',
        'html_template' => '<p>Hi {{first_name}},</p><p>You have been invited to be an administrator for {{company}}.</p><p>As an administrator, you will have access to manage settings, users, and content on the platform.</p><p>To accept your invitation and get started, click the link below.</p><p><br></p><p>Best regards,</p><p>{{company}}</p>',
        'text_template' => 'You have been invited',
    ],

    'user-activation' => [
        'subject' => 'Activate your account at {{company}}',
        'html_template' => '<p>Hi {{first_name}}</p><p><br></p><p>Thank you for registering at {{company}}! To complete the creation of your account, you need to activate it by clicking the link below:</p><p><br></p><p>If you did not create an account, you can ignore this email.</p><p>If you have any questions, please contact us at {{email}}.</p><p><br></p><p>Best regards,</p><p>{{company}}</p>',
        'text_template' => 'Activate now',
    ],

    'reset-password' => [
        'subject' => 'Request for password reset',
        'html_template' => '<p>Dear {{first_name}},</p><p>We have received a request to reset your password at {{company}}.</p><p>To continue with the reset process, please click the link below:</p><p><br></p><p>If you did not request a password reset, please ignore this email.</p><p><br></p><p>If you need further assistance, feel free to contact us at {{email}}</p><p><br></p><p>Best regards,</p><p>{{company}}</p>',
        'text_template' => 'Reset password',
    ]
];
