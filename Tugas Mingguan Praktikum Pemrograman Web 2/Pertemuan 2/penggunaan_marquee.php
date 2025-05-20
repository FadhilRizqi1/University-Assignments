<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penggunaan Marquee</title>
    <style>
    body {
        background-color: rgb(200, 250, 255);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 20px;
    }

    .identitas {
        text-align: center;
        width: 300px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }

    marquee {
        background-color: #4CAF50;
        color: white;
        font-size: 20px;
        padding: 10px;
        border-radius: 10px;
    }
    </style>

</head>

<body>
    <div class="identitas">
        <h3>Nama : Ahmad Fadhil Rizqi</h3>
        <h3>NIM : 09031282328058</h3>
    </div>

    <h1 style="text-align:center">PENGGUNAAN HEADING</h1>

    <h1>Ini adalah heading 1</h1>
    <h2>Ini adalah heading 2</h2>
    <h3>Ini adalah heading 3</h3>
    <h4>Ini adalah heading 4</h4>
    <h5>Ini adalah heading 5</h5>
    <h6>Ini adalah heading 6</h6>

    <p>Ini adalah paragraf</p>

    <h1 style="text-align:center">PENGGUNAAN MARQUEE</h1>

    <marquee behavior="scroll" direction="left">Penggunaan Marque ke kiri</marquee>
    <marquee behavior="scroll" direction="right">Penggunaan Marque ke kanan</marquee>
    <marquee behavior="scroll" direction="up" style="text-align:center">Penggunaan Marque ke atas</marquee>
    <marquee behavior="scroll" direction="down" style="text-align:center">Penggunaan Marque ke bawah</marquee>

    <marquee behavior="scroll" direction="left" style="display:block; margin: 0 auto; width:50%">Penggunaan Marque
        dari tengah ke kiri</marquee>
    <marquee behavior="scroll" direction="Right" style="display:block; margin: 0 auto; width:50%">Penggunaan Marque
        dari tengah ke kanan</marquee>

    <marquee behavior="alternate" direction="right" style="display:block; margin: 0 auto; width:50%">Penggunaan Marque
        dari tengah ke kanan</marquee>


</body>

</html>