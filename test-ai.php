<?php
session_start();

if (!isset($_SESSION['chat'])) {
    $_SESSION['chat'] = [];
}

// AJAX request handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $prompt = $_POST['prompt'] ?? '';

    if ($prompt) {

        $_SESSION['chat'][] = [
            'role' => 'user',
            'text' => $prompt
        ];

        $data = [
            "prompt" => $prompt,
            "n_predict" => 300,
            "temperature" => 0.7
        ];

        $ch = curl_init("http://localhost:8080/completion");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json"
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        $result = json_decode($response, true);

        $aiText = $result['content'] ?? 'No response';

        $_SESSION['chat'][] = [
            'role' => 'ai',
            'text' => $aiText
        ];

        echo json_encode(["ai" => $aiText]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Local AI Chat</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #0b1220;
}

.chat-box {
    height: 75vh;
    overflow-y: auto;
    padding: 20px;
    background: #0f172a;
    border-radius: 12px;
}

.msg {
    padding: 12px 15px;
    border-radius: 12px;
    margin-bottom: 10px;
    max-width: 75%;
    white-space: pre-wrap;
}

.user {
    background: #2563eb;
    color: white;
    margin-left: auto;
}

.ai {
    background: #1f2937;
    color: #e5e7eb;
}

.input-box {
    background: #111827;
    padding: 15px;
    border-radius: 12px;
    margin-top: 10px;
}

.typing {
    display: inline-block;
    border-right: 2px solid #fff;
    animation: blink 0.8s infinite;
}

@keyframes blink {
    50% { border-color: transparent; }
}
</style>

</head>

<body>

<div class="container py-4">

    <h3 class="text-white mb-3">💬 Local AI Chat (Llama 3.2)</h3>

    <!-- Chat Window -->
    <div id="chat" class="chat-box">

        <?php foreach ($_SESSION['chat'] as $chat): ?>

            <div class="msg <?= $chat['role'] === 'user' ? 'user' : 'ai' ?>">
                <?= htmlspecialchars($chat['text']) ?>
            </div>

        <?php endforeach; ?>

    </div>

    <!-- Input -->
    <div class="input-box d-flex gap-2">

        <input 
            id="prompt"
            type="text" 
            class="form-control" 
            placeholder="Ask something about contracts, PDFs, vendors..."
        >

        <button class="btn btn-primary" onclick="sendMsg()">
            Send
        </button>

    </div>

</div>

<script>
function typeEffect(element, text, speed = 10) {
    let i = 0;
    element.innerHTML = "";

    let interval = setInterval(() => {
        element.innerHTML += text.charAt(i);
        i++;

        if (i >= text.length) {
            clearInterval(interval);
        }
    }, speed);
}

function sendMsg() {

    let input = document.getElementById("prompt");
    let text = input.value.trim();

    if (!text) return;

    let chat = document.getElementById("chat");

    // Add user message
    chat.innerHTML += `<div class="msg user">${text}</div>`;

    // AI placeholder
    let aiBox = document.createElement("div");
    aiBox.className = "msg ai";
    aiBox.innerHTML = `<span class="typing">thinking...</span>`;
    chat.appendChild(aiBox);

    input.value = "";
    chat.scrollTop = chat.scrollHeight;

    fetch("", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "prompt=" + encodeURIComponent(text)
    })
    .then(res => res.json())
    .then(data => {

        typeEffect(aiBox, data.ai);

        chat.scrollTop = chat.scrollHeight;
    });
}

// Enter key support
document.getElementById("prompt").addEventListener("keypress", function(e){
    if (e.key === "Enter") {
        sendMsg();
    }
});
</script>

</body>
</html>