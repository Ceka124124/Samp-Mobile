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
