<?php
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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.mailjet.com/v3.1/send');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_USERPWD, $this->api_key . ':' . $this->api_secret);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $response]);
        }
    }
}

// Example usage
$mail = new PHP_Email_Form();
$mail->to = 'recipient@example.com';
$mail->from_name = 'Your Name';
$mail->reply_to_email = $_POST['email'];
$mail->subject = 'Subject of the Email';
$mail->add_message('This is the content of the message.', 'Message');
echo $mail->send();
?>