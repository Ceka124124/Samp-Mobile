<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$message = $input['message'] ?? '';

if (empty($message)) {
  echo json_encode(["error" => "Mesaj boş"]);
  exit;
}

$apiKey = "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"; // kendi OpenAI api key

$data = [
  "model" => "gpt-3.5-turbo",
  "messages" => [
    ["role" => "user", "content" => $message]
  ]
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Content-Type: application/json",
  "Authorization: Bearer " . $apiKey
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if (curl_errno($ch)) {
  echo json_encode(["error" => curl_error($ch)]);
  exit;
}

curl_close($ch);

$responseData = json_decode($response, true);
$botReply = $responseData['choices'][0]['message']['content'] ?? null;

if ($botReply) {
  echo json_encode(["response" => $botReply]);
} else {
  echo json_encode(["error" => "Yanıt alınamadı"]);
}
?>
