<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="/make_chat" method="POST">
        @csrf
        <input type="text" name="receiver" placeholder="receiver">
        <input type="text" name="contents" placeholder="contents">
        <!-- <input type="text" name="file" placeholder="file"> -->
        <input type="file" name="file_input" placeholder="file">
        <input type="text" name="chat_references" placeholder="references">
        <button type="submit">Submit</button>
    </form>
</body>
</html>