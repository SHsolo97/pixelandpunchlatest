<?php
require 'vendor/autoload.php'; // Ensure Mailjet SDK is included

use \Mailjet\Resources;

class PHP_Email_Form {
    public $to;
    public $from_name;
    public $from_email = 'suhailharoon500@gmail.com'; // Fixed from email address for sending
    public $reply_to_email; // Email collected from the form
    public $subject;
    public $messages = array();
    public $ajax = false;

    private $api_key = '1296d749749093ae2f1cc3f29525389c';
    private $api_secret = 'c3eb9aea18e8201ed7704caed8dfc38c';

    public function add_message($content, $label, $priority = 0) {
        $this->messages[] = array(
            'content' => $content,
            'label' => $label,
            'priority' => $priority
        );
    }

    public function send() {
        $email_content = "";
        foreach ($this->messages as $message) {
            $email_content .= $message['label'] . ": " . $message['content'] . "\n";
        }
        
        $mj = new \Mailjet\Client($this->api_key, $this->api_secret, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->from_email,
                        'Name' => $this->from_name
                    ],
                    'To' => [
                        [
                            'Email' => $this->to,
                            'Name' => 'Recipient Name'
                        ]
                    ],
                    'Subject' => $this->subject,
                    'TextPart' => $email_content,
                    'ReplyTo' => [
                        'Email' => $this->reply_to_email,
                        'Name' => $this->from_name
                    ]
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        if ($response->success()) {
            return 'Message sent successfully!';
        } else {
            return 'Failed to send message: ' . $response->getReasonPhrase();
        }
    }
}
?>