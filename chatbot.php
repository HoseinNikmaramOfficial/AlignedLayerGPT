<?php

$telegram_token = '';

$telegram_api_url = 'https://api.telegram.org/bot' . $telegram_token . '/';

$api_url = '';
$api_token = '';


$update = json_decode(file_get_contents('php://input'), true);
$message = $update['message'] ?? null;

if ($message) {
    $chat_id = $message['chat']['id'];
    $user_message = $message['text'];

    if (str_starts_with($user_message, '/start')) {
        $welcome_message = "Welcome! You can ask me any questions related to the AlignedLayer. Feel free to inquire!";
        sendMessage($chat_id, $welcome_message);
    } else {
       
      
$data = [
    [
        "role" => "assistant",
        "content" => "Answer based on the following rules:
        1) If the user says greetings , respond with greeting.
        2) If the user asks a question related to the following information, provide a short and concise answer based only on the information below. Do not provide unrelated details.
        3) If the user asks a question not covered by the information below, respond with: I can only answer questions related to the provided information.
        4) If the user asks a question in another language (e.g., Spanish, Arabic, Persian), respond in the same language if the question is related to the provided information. Use the information below to provide accurate answers.
        
        Information:
        
        1) What is this Project?
        AlignedLayer (or ALIGN/aligned layer) is a ZK verification layer currently in beta on Ethereum mainnet. It focuses on enhancing protocol security and supports the ecosystem through features like delegated staking and ZK app development.
        
        2) When is the TGE?
        The date is TBA. The Foundation will announce it. Keep an eye on our socials.
        
        3) Is Aligned on mainnet already?
        Yes, Aligned's ZK verification layer is in beta on Ethereum mainnet.
        
        4) When can I claim the tokens? Where can I claim them?
        Claims for tokens have not opened yet. Stay tuned for announcements about when you can claim from the address you registered during the Genesis Wave(s). Note that there is no claim website live yet. If you see claim websites right now, they are scams. Do not connect your wallet. The official Aligned and Aligned Foundation Twitter accounts will share official links when it is live.
        
        5) I already registered for the Genesis Drop. Do I need to do anything else?
        No. If you registered your wallet, you’re all set. Wait for official announcements.
        
        6) I missed registration. Can I still register?
        No. Registration is now closed.
        
        7) What will be done with unclaimed tokens? Will they be burned?
        Unclaimed tokens will be used to support the long-term growth of the ecosystem and community. The Foundation does not believe burning tokens is the best strategy for the protocol.
        
        8) What can I do to support the project now?
        - Read our blog posts to understand Aligned’s Proof Verification Layer.
        - Build ZK apps that utilize the protocol.
        - Create content, including memes, and discuss Aligned and ALIGN on Twitter.
        - Delegate (re)staked assets to EigenLayer operators running Aligned, enhancing protocol security. Check operators here: https://explorer.alignedlayer.com/operators
        
        9) What are the ALIGN tokenomics and utility?
        You can read about tokenomics here: https://blog.alignedlayer.com/aligned-align-tokenomics-and-roadmap"
    ],
    [
        "role" => "user",
        "content" => $user_message
    ]
];

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'one-api-token: ' . $api_token,
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        curl_close($ch); 
  
        $response_data = json_decode($response, true);
        $ai_answer =  $response_data['result'][0];
        sendMessage($chat_id, $ai_answer);
    }
}

function sendMessage($chat_id, $text)
{
    global $telegram_api_url;
    $send_message_url = $telegram_api_url . 'sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($text);
    file_get_contents($send_message_url);
}

?>
